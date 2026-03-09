<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;


class LoginAlert extends Mailable
{
    use Queueable, SerializesModels;

       public $user;
    public $ipAddress;
    public $userAgent;

    public function __construct(User $user, $ipAddress, $userAgent)
    {
        $this->user = $user;
        $this->ipAddress = $ipAddress;
        $this->userAgent = $userAgent;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this->subject('New Login Alert - ' . config('app.name'))
                    ->view('emails.login-alert');
    }
}
