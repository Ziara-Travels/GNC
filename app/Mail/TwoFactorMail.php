<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class TwoFactorMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $code;
    public $action;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $code, $action = 'verification')
    {
        $this->user = $user;
        $this->code = $code;
        $this->action = $action;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->action === 'verification' ? '2FA Verification Code' : '2FA Setup Code';
        
        return new Envelope(
            subject: $subject . ' - Ziara Travel',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.two-factor',
            with: [
                'user' => $this->user,
                'code' => $this->code,
                'action' => $this->action,
                'expiresAt' => now()->addMinutes(10)->format('Y-m-d H:i:s')
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
