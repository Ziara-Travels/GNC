<?php


namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Mail\BookingCancelledMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class CancelUnpaidBookingsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        $unpaidBookings = Booking::where('payment_status', 'pending')
            ->where('status', 'pending')
            ->where('created_at', '<=', now()->subMinutes(30))
            ->get();

        foreach ($unpaidBookings as $booking) {
            // Cancel the booking
            $booking->update([
                'status' => 'cancelled',
                'payment_status' => 'failed'
            ]);

            // Send cancellation email
            if ($booking->user) {
                Mail::to($booking->user->email)->send(new BookingCancelledMail($booking));
            }

            // Log the cancellation
            Log::info('Auto-cancelled unpaid booking', [
                'booking_id' => $booking->id,
                'booking_reference' => $booking->booking_reference
            ]);
        }

        Log::info('Processed unpaid bookings', ['count' => $unpaidBookings->count()]);
    }
}