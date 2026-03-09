<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\User;

class WishlistNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $itemName;
    public $itemDetails;

    /**
     * Create a new message instance.
     */
    public function __construct(User $user, $itemName, $itemDetails = [])
    {
        $this->user = $user;
        $this->itemName = $itemName;
        $this->itemDetails = $itemDetails;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'New Wishlist Item Added - Admin Notification',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.wishlist-notification',
            with: [
                'user' => $this->user,
                'itemName' => $this->itemName,
                'itemDetails' => $this->itemDetails,
                'timestamp' => now()->format('Y-m-d H:i:s')
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
