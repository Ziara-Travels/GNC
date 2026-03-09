<?php

namespace App\Mail\dashboard;


use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class AdminPassChangeNotification extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $admin;

    public function __construct(User $user, User $admin)
    {
        $this->user = $user;
        $this->admin = $admin;
    }

    public function build()
    {
        return $this->subject('User Password Change Notification')
                    ->view('emails.dashboard.admin-pass-change')
                    ->with([
                        'user' => $this->user,
                        'admin' => $this->admin,
                        'changeTime' => now()->format('M d, Y \a\t H:i A'),
                    ]);
    }
}
