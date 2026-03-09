<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\Booking;
use App\Models\User;

class PendingPaymentNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $booking;
    public $bookedBy;

    /**
     * Create a new message instance.
     */
    public function __construct(Booking $booking, User $bookedBy)
    {
        $this->booking = $booking;
        $this->bookedBy = $bookedBy;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Payment Required - Booking Confirmation Pending',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.pending-payment-notification',
            with: [
                'booking' => $this->booking,
                'bookedBy' => $this->bookedBy,
                'user' => $this->booking->user
            ]
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        return [];
    }
}
