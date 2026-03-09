<?php


namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\CustomTrip;

class CustomTripRequestMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public $customTrip;
    public $recipientType;

    /**
     * Create a new message instance.
     */
    public function __construct(CustomTrip $customTrip, string $recipientType = 'customer')
    {
        $this->customTrip = $customTrip;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->recipientType === 'admin' 
            ? 'New Custom Trip Request: ' . $this->customTrip->name
            : 'Your Custom Trip Request Has Been Received';
            
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        $view = $this->recipientType === 'admin' 
            ? 'emails.custom-trip-admin'
            : 'emails.custom-trip-customer';
            
        return new Content(
            view: $view,
            with: [
                'customTrip' => $this->customTrip,
            ],
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