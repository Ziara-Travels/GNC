<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Sent by CancelUnpaidBookingsJob when a booking that was never paid for is
 * auto-cancelled. That job is scheduled every ten minutes, so before this
 * class existed every scheduled run died with "Class not found".
 */
class BookingCancelledMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Your booking '.($this->booking->booking_reference ?? '').' has been cancelled')
            ->view('emails.booking-cancelled')
            ->with([
                'booking'          => $this->booking,
                'bookingReference' => $this->booking->booking_reference,
                'amount'           => number_format((float) $this->booking->total_amount, 2),
                'currency'         => $this->booking->currency,
                'supportUrl'       => route('help'),
            ]);
    }
}