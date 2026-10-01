<?php

namespace App\Mail;

use App\Models\Booking;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

/**
 * Sent by SendFailedPaymentRemindersJob (scheduled hourly) when a booking has
 * a failed payment and has not been reminded yet.
 */
class PaymentFailedReminderMail extends Mailable
{
    use Queueable, SerializesModels;

    public Booking $booking;

    public function __construct(Booking $booking)
    {
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Action needed: payment failed for booking '.($this->booking->booking_reference ?? ''))
            ->view('emails.payment-failed-reminder')
            ->with([
                'booking'          => $this->booking,
                'bookingReference' => $this->booking->booking_reference,
                'amountDue'        => number_format(
                    max(0, (float) $this->booking->total_amount - (float) $this->booking->paid_amount),
                    2
                ),
                'currency'         => $this->booking->currency,
                'payUrl'           => route('bookings.show', $this->booking->id),
            ]);
    }
}