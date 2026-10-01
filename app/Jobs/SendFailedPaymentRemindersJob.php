<?php


namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Mail\PaymentFailedReminderMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class SendFailedPaymentRemindersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle()
    {
        // The previous query chained `where(...)->where(...)->whereNull('reminder_sent')
        // ->orWhere('reminder_sent', false)`. Because SQL OR has lower precedence
        // than AND that evaluated as
        //     (failed AND pending AND reminder_sent IS NULL) OR (reminder_sent = 0)
        // so it matched EVERY booking that had never been reminded - including
        // successfully paid ones - and emailed them again on every hourly run.
        //
        // reminder_sent is `boolean NOT NULL DEFAULT 0`, so it can never be NULL
        // anyway. Remind only failed, still-pending bookings.
        $failedPayments = Booking::where('payment_status', 'failed')
            ->where('status', 'pending')
            ->where('reminder_sent', false)
            ->get();

        foreach ($failedPayments as $booking) {
            if ($booking->user) {
                Mail::to($booking->user->email)->send(new PaymentFailedReminderMail($booking));

                // Mark as reminded so the next hourly run skips it.
                $booking->update(['reminder_sent' => true]);
            }
        }

        Log::info('Sent payment failure reminders', ['count' => $failedPayments->count()]);
    }
}