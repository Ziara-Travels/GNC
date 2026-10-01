<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;

class PreTripReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $reminderType;

    /**
     * Create a new message instance.
     *
     * @return void
     */
      public function __construct(Booking $booking, $reminderType = '24_hours')
    {
        $this->booking = $booking;
        $this->reminderType = $reminderType;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
   public function build()
    {
        $subject = match($this->reminderType) {
            '24_hours' => 'Flight Reminder - Departure Tomorrow',
            '2_hours' => 'Flight Alert - Departure in 2 Hours',
            'check_in' => 'Check-in Now Available',
            default => 'Flight Reminder'
        };

        return $this->subject($subject)
                    ->view('emails.pre-trip-reminder')
                    ->with([
                        'booking' => $this->booking,
                        'flight' => $this->booking->flight_details,
                        'reminderType' => $this->reminderType,
                        'checkInUrl' => route('booking.success', $this->booking->booking_reference)
                    ]);
    }
}
