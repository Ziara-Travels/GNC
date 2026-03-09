<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;


class LoyaltyPointsUpdateMail extends Mailable
{
    use Queueable, SerializesModels;


     public $user;
    public $pointsEarned;
    public $totalPoints;
    public $booking;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, $pointsEarned, $totalPoints, $booking = null)
    {
        $this->user = $user;
        $this->pointsEarned = $pointsEarned;
        $this->totalPoints = $totalPoints;
        $this->booking = $booking;
    }

    public function build()
    {
        return $this->subject('Loyalty Points Update - ' . $this->pointsEarned . ' Points Earned!')
                    ->view('emails.loyalty-points-update')
                    ->with([
                        'user' => $this->user,
                        'pointsEarned' => $this->pointsEarned,
                        'totalPoints' => $this->totalPoints,
                        'booking' => $this->booking,
                        'rewardsUrl' => route('user.rewards')
                    ]);
    }
}
