<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\UserReferral;

class ReferralCodeMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referral;

    /**
     * Create a new message instance.
     */
    public function __construct(UserReferral $referral)
    {
        $this->referral = $referral;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your Referral Code - Ziara Travel',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.referral-code',
            with: [
                'referral' => $this->referral,
                'user' => $this->referral->user,
                'code' => $this->referral->code
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
