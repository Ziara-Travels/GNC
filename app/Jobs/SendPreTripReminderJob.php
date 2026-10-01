<?php

namespace App\Jobs;

use App\Mail\PreTripReminderMail;
use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Emails confirmed, departing-soon bookings their pre-trip reminder.
 *
 * Replaces the phantom `bookings:send-reminders` schedule entry, which pointed
 * at a command that was never implemented. The class was referenced from three
 * controllers and did not exist, so every dispatch was a fatal error.
 *
 * Dispatched with no constructor arguments by the scheduler.
 */
class SendPreTripReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * How far ahead to look, and the windows that trigger a reminder.
     */
    private const WINDOW_DAYS = 3;

    public function handle(): void
    {
        $now = now();
        $from = $now->copy();
        $to = $now->copy()->addDays(self::WINDOW_DAYS);

        $bookings = Booking::query()
            ->where('booking_type', 'flight')
            ->where('booking_status', 'confirmed')
            ->whereNotNull('departure_time')
            ->whereBetween('departure_time', [$from, $to])
            ->with('user')
            ->get();

        $sent = 0;

        foreach ($bookings as $booking) {
            if (! $booking->user) {
                continue;
            }

            $reminderType = $this->reminderTypeFor($booking->departure_time);

            if ($reminderType === null) {
                continue;
            }

            try {
                Mail::to($booking->user->email)->send(
                    new PreTripReminderMail($booking, $reminderType)
                );
                $sent++;
            } catch (\Throwable $e) {
                // One bad address must not abort the whole batch.
                Log::warning('Pre-trip reminder failed', [
                    'booking_id' => $booking->id,
                    'error'      => $e->getMessage(),
                ]);
            }
        }

        Log::info('Sent pre-trip reminders', [
            'candidates' => $bookings->count(),
            'sent'       => $sent,
        ]);
    }

    /**
     * Pick the reminder bucket for a departure time, or null to skip.
     */
    private function reminderTypeFor(\Illuminate\Support\Carbon $departure): ?string
    {
        $hoursAway = now()->diffInHours($departure, false);

        if ($hoursAway <= 2) {
            return '2_hours';
        }

        if ($hoursAway <= 24) {
            return '24_hours';
        }

        return null;
    }
}