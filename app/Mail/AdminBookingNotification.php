<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;


class AdminBookingNotification extends Mailable
{
    use Queueable, SerializesModels;

      public $booking;


    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Booking $booking)
    {
         $this->booking = $booking;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->view('emails.admin-booking-notification')
                    ->subject('New Booking Created - ' . $this->booking->booking_reference)
                    ->with([
                        'booking' => $this->booking,
                        'user' => $this->booking->user,
                    ]);
    }
}
