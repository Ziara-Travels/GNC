<?php

namespace App\Mail\dashboard;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class PassChangedNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }

    public function build()
    {
        return $this->subject('Password Successfully Changed')
                    ->view('emails.dashboard.pass-changed')
                    ->with([
                        'user' => $this->user,
                        'changeTime' => now()->format('M d, Y \a\t H:i A'),
                    ]);
    }
}
