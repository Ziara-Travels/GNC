<?php


namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\User;
use App\Models\ReferralCode;

class AdminReferralAlert extends Notification implements ShouldQueue
{
    use Queueable;

    protected $referrer;
    protected $newUser;
    protected $referralCode;

    public function __construct(User $referrer, User $newUser, ReferralCode $referralCode)
    {
        $this->referrer = $referrer;
        $this->newUser = $newUser;
        $this->referralCode = $referralCode;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('New Referral Registration')
            ->greeting('Hello Admin,')
            ->line('A new user has registered using a referral code.')
            ->line('**Referrer:** ' . $this->referrer->name . ' (' . $this->referrer->email . ')')
            ->line('**New User:** ' . $this->newUser->name . ' (' . $this->newUser->email . ')')
            ->line('**Referral Code:** ' . $this->referralCode->code)
            ->line('**Reward Percentage:** ' . $this->referralCode->reward_percentage . '%')
            ->action('View Referral Details', url('/admin/referrals/' . $this->referralCode->id))
            ->line('Please review and process this referral as needed.');
    }

    public function toArray($notifiable)
    {
        return [
            'title' => 'New Referral Registration',
            'message' => $this->newUser->name . ' registered using ' . $this->referrer->name . '\'s referral code',
            'referrer_id' => $this->referrer->id,
            'new_user_id' => $this->newUser->id,
            'referral_code' => $this->referralCode->code,
            'reward_percentage' => $this->referralCode->reward_percentage
        ];
    }
}