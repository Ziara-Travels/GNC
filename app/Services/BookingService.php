<?php

namespace App\Services;

use App\Models\Booking;
use App\Models\Payment;
use App\Models\User;
use App\Models\Notification;
use App\Mail\BookingConfirmation;
use App\Mail\PendingPaymentNotification;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class BookingService
{
    /**
     * Create a booking for user, admin, or bulk users
     */
    public function createBooking($bookingData, $bookedBy, $bookFor = null)
    {
        return DB::transaction(function () use ($bookingData, $bookedBy, $bookFor) {
            $booking = new Booking();
            $booking->fill($bookingData);
            $booking->booking_reference = $this->generateBookingReference();
            $booking->booked_by = $bookedBy->id;
            
            // If booking for someone else (admin/superadmin booking for others)
            $booking->user_id = $bookFor ? $bookFor->id : $bookedBy->id;
            $booking->payment_status = 'pending';
            // 'pending' is the valid enum value on bookings.status; 'pending_payment'
            // is not and would fail the insert.
            $booking->status = 'pending';
            
            $booking->save();

            // Create payment record
            $payment = $this->createPaymentRecord($booking, $bookedBy, $bookFor);

            // Notifications are a best-effort side effect (admin alert, pending-payment
            // email) — a failure here (e.g. a mail/notification misconfiguration) must
            // never roll back an otherwise-successful booking.
            try {
                $this->sendBookingNotifications($booking, $bookedBy, $bookFor);
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Booking notification failed', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return [
                'booking' => $booking,
                'payment' => $payment
            ];
        });
    }

    /**
     * Create booking for bulk users
     */
    public function createBulkBookings($bookingData, $bookedBy, $userIds)
    {
        $bookings = [];
        $payments = [];

        DB::transaction(function () use ($bookingData, $bookedBy, $userIds, &$bookings, &$payments) {
            foreach ($userIds as $userId) {
                $user = User::find($userId);
                if ($user) {
                    $result = $this->createBooking($bookingData, $bookedBy, $user);
                    $bookings[] = $result['booking'];
                    $payments[] = $result['payment'];
                }
            }
        });

        return [
            'bookings' => $bookings,
            'payments' => $payments
        ];
    }

    /**
     * Process payment for booking
     */
    public function processPayment($booking, $paymentData, $paidBy)
    {
        return DB::transaction(function () use ($booking, $paymentData, $paidBy) {
            $payment = Payment::where('booking_id', $booking->id)->first();
            
            if (!$payment) {
                throw new \Exception('Payment record not found for booking');
            }

            $payment->update([
                'payment_method' => $paymentData['payment_method'],
                'provider_transaction_id' => $paymentData['transaction_id'] ?? null,
                'provider_response' => $paymentData['provider_response'] ?? null,
                'status' => 'completed',
                'processed_by' => $paidBy->id,
                'processed_at' => now(),
                'verified_at' => now(),
                'payment_details' => $paymentData
            ]);

            $booking->update([
                'payment_status' => 'paid',
                'status' => 'confirmed',
                'paid_amount' => $payment->amount,
                'transaction_id' => $paymentData['transaction_id'] ?? null
            ]);

            // Confirmation emails are a best-effort side effect — a broken template or
            // mail-server hiccup must never roll back a payment that already succeeded.
            try {
                Mail::to($booking->user->email)->send(new BookingConfirmation($booking));

                // Notify admin who booked if different from user
                if ($booking->booked_by && $booking->booked_by !== $booking->user_id) {
                    $bookedBy = User::find($booking->booked_by);
                    if ($bookedBy) {
                        Mail::to($bookedBy->email)->send(new BookingConfirmation($booking));
                    }
                }
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::error('Booking confirmation email failed', [
                    'booking_id' => $booking->id,
                    'error' => $e->getMessage(),
                ]);
            }

            return $payment;
        });
    }

    /**
     * Generate unique booking reference
     */
    private function generateBookingReference()
    {
        do {
            $reference = 'ZT' . strtoupper(Str::random(8));
        } while (Booking::where('booking_reference', $reference)->exists());
        
        return $reference;
    }

    /**
     * Create payment record for booking
     */
    private function createPaymentRecord($booking, $bookedBy, $bookFor)
    {
        $payment = Payment::create([
            'user_id' => $booking->user_id,
            'booking_id' => $booking->id,
            'payment_reference' => 'PAY' . strtoupper(Str::random(8)),
            'amount' => $booking->total_amount,
            'currency' => $booking->currency,
            'status' => 'pending',
            'payment_method' => null,
            'processed_by' => null
        ]);

        return $payment;
    }

    /**
     * Send booking notifications
     */
    private function sendBookingNotifications($booking, $bookedBy, $bookFor)
    {
        // If admin/superadmin booked for someone else
        if ($bookFor && $bookFor->id !== $bookedBy->id) {
            // Notify the user about pending payment
            $bookFor->notifications()->create([
                'type' => 'pending_payment',
                'data' => [
                    'booking_id' => $booking->id,
                    'booking_reference' => $booking->booking_reference,
                    'amount' => $booking->total_amount,
                    'currency' => $booking->currency,
                    'booked_by' => $bookedBy->name,
                    'booking_type' => $booking->booking_type,
                    'message' => "A {$booking->booking_type} booking has been made for you by {$bookedBy->name}. Please complete the payment to confirm your booking."
                ]
            ]);

            // Send email notification
            Mail::to($bookFor->email)->send(new PendingPaymentNotification($booking, $bookedBy));
        }

        // Notify admins/superadmins about new booking. This project uses a
        // plain 'role' string column on users (like the rest of the
        // codebase), not the unused spatie/laravel-permission roles()
        // relationship, whose 'user_roles' pivot table doesn't even exist.
        $admins = User::whereIn('role', ['admin', 'superadmin'])->get();

        foreach ($admins as $admin) {
            if ($admin->id !== $bookedBy->id) {
                $admin->notifications()->create([
                    'type' => 'new_booking',
                    'data' => [
                        'booking_id' => $booking->id,
                        'booking_reference' => $booking->booking_reference,
                        'user_name' => $booking->user->name,
                        'booked_by' => $bookedBy->name,
                        'booking_type' => $booking->booking_type,
                        'amount' => $booking->total_amount
                    ]
                ]);
            }
        }
    }

    /**
     * Get user's pending payments
     */
    public function getUserPendingPayments($userId)
    {
        return Booking::with('payment')
            ->where('user_id', $userId)
            ->where('payment_status', 'pending')
            ->orderBy('created_at', 'desc')
            ->get();
    }

    /**
     * Get bookings made by admin/superadmin
     */
    public function getBookingsMadeBy($userId)
    {
        return Booking::with(['user', 'payment'])
            ->where('booked_by', $userId)
            ->orderBy('created_at', 'desc')
            ->get();
    }
}
