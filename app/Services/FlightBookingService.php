<?php

namespace App\Services;

use App\Events\BookingStatusUpdated;
use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

/**
 * Persistence and payment-state transitions for flight bookings.
 *
 * FlightBookingController was calling createBooking(), createBulkBookings(),
 * processPayment(), getUserPendingPayments() and getBookingsMadeBy() on
 * App\Services\BookingService. That class is the Booking.com API client (it
 * only implements search/details/URL helpers), so none of those five methods
 * existed and every one of those controller actions was a guaranteed fatal
 * error. The controller now injects this class instead.
 */
class FlightBookingService
{
    /**
     * Create a pending flight booking.
     *
     * @param  array  $bookingData  Output of FlightBookingController::prepareFlightBookingData()
     * @param  User   $actor        The authenticated user making the request.
     * @param  User|null $bookFor   When an admin books on behalf of someone else.
     * @return array{booking: Booking}
     */
    public function createBooking(array $bookingData, User $actor, ?User $bookFor = null): array
    {
        $owner = $bookFor ?? $actor;

        $booking = DB::transaction(function () use ($bookingData, $actor, $owner, $bookFor) {
            $attributes = $this->normaliseAttributes($bookingData);

            $attributes['user_id'] = $owner->id;

            // Only record booked_by when someone else is the passenger, so
            // "bookings made by this staff member" stays a meaningful list.
            if ($bookFor) {
                $attributes['booked_by'] = $actor->id;
                $attributes['role'] = $actor->role;
            }

            return Booking::create($attributes);
        });

        Log::info('Flight booking created', [
            'booking_id'        => $booking->id,
            'booking_reference' => $booking->booking_reference,
            'user_id'           => $owner->id,
            'booked_by'         => $bookFor ? $actor->id : null,
        ]);

        return ['booking' => $booking];
    }

    /**
     * Create one pending flight booking per target user.
     *
     * @return array{bookings: \Illuminate\Support\Collection<int,Booking>}
     */
    public function createBulkBookings(array $bookingData, User $actor, array $userIds): array
    {
        $bookings = collect();
        $failures = collect();

        foreach (array_unique($userIds) as $userId) {
            $target = User::find($userId);

            if (! $target) {
                $failures->push(['user_id' => $userId, 'reason' => 'User not found']);
                continue;
            }

            try {
                $result = $this->createBooking($bookingData, $actor, $target);
                $bookings->push($result['booking']);
            } catch (\Throwable $e) {
                // Keep going: one bad user must not abort the whole batch.
                Log::warning('Bulk flight booking failed for user', [
                    'user_id' => $userId,
                    'error'   => $e->getMessage(),
                ]);

                $failures->push(['user_id' => $userId, 'reason' => $e->getMessage()]);
            }
        }

        return ['bookings' => $bookings, 'failures' => $failures];
    }

    /**
     * Settle a payment against a booking.
     *
     * Records the Payment row and, on success, promotes the booking to
     * confirmed/completed. Wrapped in a transaction so a booking is never
     * marked paid without a matching payment record.
     *
     * @param  array $paymentData  payment_method, transaction_id, provider_response
     */
    public function processPayment(Booking $booking, array $paymentData, User $actor): Payment
    {
        $method = $paymentData['payment_method'] ?? 'bank';

        if (! in_array($method, ['mpesa', 'mpesa_stk', 'card', 'bank', 'bank_transfer', 'equitel', 'equity', 'mobile_money'], true)) {
            throw new \InvalidArgumentException("Unsupported payment method [{$method}].");
        }

        if ($booking->payment_status === 'completed' || $booking->payment_status === 'paid') {
            throw new \RuntimeException('This booking has already been paid.');
        }

        $payment = DB::transaction(function () use ($booking, $paymentData, $method, $actor) {
            $payment = Payment::create([
                'user_id'                  => $booking->user_id,
                'booking_id'               => $booking->id,
                'payment_reference'        => $this->generatePaymentReference(),
                'amount'                   => $booking->total_amount,
                'currency'                 => $booking->currency,
                'payment_method'           => $method,
                'status'                   => 'completed',
                'payment_details'          => $paymentData['payment_details'] ?? null,
                'provider_transaction_id'  => $paymentData['transaction_id'] ?? null,
                'provider_response'        => $paymentData['provider_response'] ?? null,
                'processed_by'             => $actor->id,
                'processed_at'             => now(),
                'verified_at'              => now(),
            ]);

            $booking->update([
                'status'            => 'confirmed',
                'booking_status'    => 'confirmed',
                'payment_status'    => 'completed',
                'paid_amount'       => $booking->total_amount,
                'payment_method'    => $method,
                'transaction_id'    => $paymentData['transaction_id'] ?? null,
                'payment_reference' => $payment->payment_reference,
            ]);

            return $payment;
        });

// Advisory only: BookingStatusUpdated is ShouldBroadcast, so with a real
// broadcast driver an exception here would surface as "Payment failed" even
// though the transaction already committed.
try {
    event(new BookingStatusUpdated($booking->fresh()));
} catch (\Throwable $e) {
    Log::warning('BookingStatusUpdated broadcast failed', [
        'booking_id' => $booking->id,
        'error'      => $e->getMessage(),
    ]);
}

return $payment;
    }

    /**
     * Bookings belonging to a user that still need paying.
     */
    public function getUserPendingPayments(int $userId)
    {
        return Booking::where('user_id', $userId)
            ->whereIn('booking_status', ['pending'])
            ->whereIn('payment_status', ['pending', 'failed'])
            ->with(['user', 'payment'])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Flight bookings an admin/superadmin created on behalf of other users.
     */
    public function getBookingsMadeBy(int $actorId)
    {
        return Booking::where('booked_by', $actorId)
            ->with(['user', 'bookedBy'])
            ->orderByDesc('created_at')
            ->get();
    }

    /**
     * Map the controller's loosely shaped payload onto real columns.
     *
     * Two fixes matter here:
     *  - bookings.status is an enum limited to
     *    pending|confirmed|completed|cancelled|processing. The controller sent
     *    "pending_payment", which fails the check constraint on insert.
     *  - currency and amount are NOT NULL with no default, so they always have
     *    to be provided.
     */
    private function normaliseAttributes(array $data): array
    {
        $totalAmount = $data['total_amount'] ?? $data['total_price'] ?? $data['amount'] ?? 0;

        $attributes = [
            'booking_type'       => $data['booking_type'] ?? 'flight',
            'type'               => $data['type'] ?? $data['booking_type'] ?? 'flight',
            'title'              => $data['title'] ?? null,
            'amadeus_offer_id'   => $data['amadeus_offer_id'] ?? null,
            'amadeus_data'       => $data['amadeus_data'] ?? null,
            'flight_number'      => $data['flight_number'] ?? null,

            'departure_airport'  => $this->upper($data['departure_airport'] ?? $data['origin_code'] ?? null),
            'arrival_airport'    => $this->upper($data['arrival_airport'] ?? $data['destination_code'] ?? null),
            'origin_code'        => $this->upper($data['origin_code'] ?? $data['departure_airport'] ?? null),
            'destination_code'   => $this->upper($data['destination_code'] ?? $data['arrival_airport'] ?? null),
            'origin_city'        => $data['origin_city'] ?? null,
            'destination_city'   => $data['destination_city'] ?? null,
            'departure_time'     => $data['departure_time'] ?? null,
            'arrival_time'       => $data['arrival_time'] ?? null,
            'departure_date'     => $data['departure_date'] ?? null,
            'return_date'        => $data['return_date'] ?? null,

            'adults'             => (int) ($data['adults'] ?? 1),
            'children'           => (int) ($data['children'] ?? 0),
            'infants'            => (int) ($data['infants'] ?? 0),
            'traveler_count'     => (int) ($data['traveler_count'] ?? ((int) ($data['adults'] ?? 1) + (int) ($data['children'] ?? 0) + (int) ($data['infants'] ?? 0))),
            'travel_class'       => $data['travel_class'] ?? 'economy',
            'cabin_class'        => $data['cabin_class'] ?? 'economy',
            'stops'              => (int) ($data['stops'] ?? 0),

            'amount'             => $totalAmount,
            'total_amount'       => $totalAmount,
            'total_price'        => $totalAmount,
            'currency'           => $data['currency'] ?? 'KES',

            'contact_email'      => $data['contact_email'] ?? null,
            'contact_phone'      => $data['contact_phone'] ?? null,
            'passenger_details'  => $data['passenger_details'] ?? ($data['traveler_details'] ?? null),
            'traveler_details'   => $data['traveler_details'] ?? null,
            'contact_details'    => $data['contact_details'] ?? null,
            'flight_details'     => $data['flight_details'] ?? null,

            // "pending_payment" is not a member of the status enum.
            'status'             => $this->normaliseStatus($data['status'] ?? 'pending'),
            'booking_status'     => $this->normaliseBookingStatus($data['booking_status'] ?? 'pending'),
            'payment_status'     => $data['payment_status'] ?? 'pending',
            'payment_method'     => $data['payment_method'] ?? null,
            'booked_at'          => $data['booked_at'] ?? now(),
        ];

        // passenger_count must agree with the traveler breakdown.
        $attributes['passenger_count'] = (int) ($data['passenger_count'] ?? $attributes['traveler_count']);

        return array_filter($attributes, fn ($value) => $value !== null);
    }

    /**
     * Map any caller-supplied status onto the bookings.status enum.
     */
    private function normaliseStatus(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'confirmed', 'paid', 'success' => 'confirmed',
            'completed'                    => 'completed',
            'cancelled', 'canceled'        => 'cancelled',
            'processing', 'pending_payment' => 'processing',
            default                        => 'pending',
        };
    }

    /**
     * Map onto the bookings.booking_status enum.
     */
    private function normaliseBookingStatus(?string $status): string
    {
        return match (strtolower((string) $status)) {
            'confirmed', 'paid', 'completed' => 'confirmed',
            'cancelled', 'canceled'          => 'cancelled',
            default                          => 'pending',
        };
    }

    private function generatePaymentReference(): string
    {
        do {
            $reference = 'PAY'.strtoupper(Str::random(12));
        } while (Payment::withTrashed()->where('payment_reference', $reference)->exists());

        return $reference;
    }

    private function upper(?string $code): ?string
    {
        return $code === null ? null : strtoupper(trim($code));
    }
}