<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\BookingDraft;


class AbandonedBookingReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public $draft;
    public $flightDetails;
    public $hotelDetails;
    public $discountCode;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(BookingDraft $draft)
    {
        $this->draft = $draft;
        $this->flightDetails = $draft->flight_details;
        $this->hotelDetails = $draft->hotel_details;
        $this->discountCode = $this->generateDiscountCode();
    }

    public function build()
    {
        return $this->subject('Complete Your Booking - Special Offer Inside!')
                    ->view('emails.abandoned-booking-reminder')
                    ->with([
                        'draft' => $this->draft,
                        'flightDetails' => $this->flightDetails,
                        'hotelDetails' => $this->hotelDetails,
                        'discountCode' => $this->discountCode,
                        'discountAmount' => '10%',
                        'expiryTime' => now()->addHours(24)->format('M j, Y \a\t g:i A'),
                        'resumeUrl' => route('booking.resume', $this->draft->session_id)
                    ]);
    }

    private function generateDiscountCode()
    {
        return 'SAVE10' . strtoupper(substr($this->draft->session_id, 0, 4));
    }
}
