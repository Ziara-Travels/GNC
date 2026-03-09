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
        $failedPayments = Booking::where('payment_status', 'failed')
            ->where('status', 'pending')
            ->whereNull('reminder_sent')
            ->orWhere('reminder_sent', false)
            ->get();

        foreach ($failedPayments as $booking) {
            if ($booking->user) {
                Mail::to($booking->user->email)->send(new PaymentFailedReminderMail($booking));
                
                $booking->update(['reminder_sent' => true]);
            }
        }

        Log::info('Sent payment failure reminders', ['count' => $failedPayments->count()]);
    }
}