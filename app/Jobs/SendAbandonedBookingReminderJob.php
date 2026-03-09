<?php


namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\AbandonedBookingReminderMail;
use App\Models\BookingDraft;

class SendAbandonedBookingReminderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $draft;

    public function __construct(BookingDraft $draft)
    {
        $this->draft = $draft;
    }

    public function handle()
    {
        // Check if booking is still incomplete and hasn't received reminder
        if ($this->draft->status === 'abandoned' && !$this->draft->reminder_sent) {
            $this->draft->generateDiscountCode();
            
            Mail::to($this->draft->email)->send(new AbandonedBookingReminderMail($this->draft));
            
            $this->draft->update(['reminder_sent' => true]);
        }
    }
}