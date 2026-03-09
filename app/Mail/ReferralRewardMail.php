<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use App\Models\UserReferral;

class ReferralRewardMail extends Mailable
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
            subject: 'Congratulations! Your Referral Reward is Ready',
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.referral-reward',
            with: [
                'referral' => $this->referral,
                'user' => $this->referral->user,
                'rewardAmount' => $this->referral->reward_amount,
                'status' => $this->referral->status
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
