<?php


namespace App\Services;

use App\Models\BookingAuditLog;
use App\Models\Booking;
use Illuminate\Support\Facades\Auth;

class BookingAuditService
{
    public function logAction(Booking $booking, string $action, array $oldValues = [], array $newValues = [], string $description = '')
    {
        BookingAuditLog::create([
            'booking_id' => $booking->id,
            'user_id' => Auth::id(),
            'action' => $action,
            'old_values' => $oldValues,
            'new_values' => $newValues,
            'description' => $description,
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
            'performed_at' => now()
        ]);
    }

    public function logBookingCreated(Booking $booking)
    {
        $this->logAction($booking, 'booking_created', [], $booking->toArray(), 'Booking was created');
    }

    public function logPaymentStatusChange(Booking $booking, string $oldStatus, string $newStatus)
    {
        $this->logAction(
            $booking,
            'payment_status_changed',
            ['payment_status' => $oldStatus],
            ['payment_status' => $newStatus],
            "Payment status changed from {$oldStatus} to {$newStatus}"
        );
    }

    public function logBookingCancelled(Booking $booking, string $reason = '')
    {
        $this->logAction(
            $booking,
            'booking_cancelled',
            ['status' => $booking->getOriginal('status')],
            ['status' => 'cancelled'],
            "Booking cancelled. Reason: {$reason}"
        );
    }

    public function logSeatUpdate(Booking $booking, array $oldSeats, array $newSeats)
    {
        $this->logAction(
            $booking,
            'seat_updated',
            ['seats' => $oldSeats],
            ['seats' => $newSeats],
            'Seat selection updated'
        );
    }

    public function logAmadeusApiCall(Booking $booking, string $endpoint, array $request, array $response)
    {
        $this->logAction(
            $booking,
            'amadeus_api_call',
            ['request' => $request],
            ['response' => $response],
            "Amadeus API call to {$endpoint}"
        );
    }

    public function getBookingTimeline(Booking $booking)
    {
        return BookingAuditLog::where('booking_id', $booking->id)
            ->with('performer')
            ->orderBy('performed_at', 'desc')
            ->get()
            ->map(function ($log) {
                return [
                    'id' => $log->id,
                    'action' => $log->action,
                    'description' => $log->description,
                    'performer' => $log->performer ? $log->performer->name : 'System',
                    'performed_at' => $log->performed_at->format('M j, Y g:i A'),
                    'old_values' => $log->old_values,
                    'new_values' => $log->new_values,
                    'icon' => $this->getActionIcon($log->action),
                    'color' => $this->getActionColor($log->action)
                ];
            });
    }

    private function getActionIcon(string $action)
    {
        return match($action) {
            'booking_created' => 'plus-circle',
            'payment_status_changed' => 'credit-card',
            'booking_cancelled' => 'x-circle',
            'seat_updated' => 'user',
            'amadeus_api_call' => 'globe',
            default => 'info'
        };
    }

    private function getActionColor(string $action)
    {
        return match($action) {
            'booking_created' => 'green',
            'payment_status_changed' => 'blue',
            'booking_cancelled' => 'red',
            'seat_updated' => 'purple',
            'amadeus_api_call' => 'gray',
            default => 'gray'
        };
    }
}