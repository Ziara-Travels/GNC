<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;

class GDPRDataExportMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $exportPath;

    /**
     * Create a new message instance.
     */
    public function __construct($user, $exportPath = null)
    {
        $this->user = $user;
        $this->exportPath = $exportPath;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Your GDPR Data Export - Ziara Travel',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.gdpr-data-export',
            with: [
                'user' => $this->user,
                'exportPath' => $this->exportPath,
                'exportDate' => now()->format('Y-m-d H:i:s')
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
