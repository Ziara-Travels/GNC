<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\UserReferral;

class ReferralSuccessMail extends Mailable
{
    use Queueable, SerializesModels;

    public $referral;
    public $referredUser;

    /**
     * Create a new message instance.
     */
    public function __construct(UserReferral $referral, $referredUser)
    {
        $this->referral = $referral;
        $this->referredUser = $referredUser;
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Referral Success - You\'ve Earned Rewards!',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.referral-success',
            with: [
                'referral' => $this->referral,
                'referrer' => $this->referral->user,
                'referredUser' => $this->referredUser,
                'rewardAmount' => $this->referral->reward_amount
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
