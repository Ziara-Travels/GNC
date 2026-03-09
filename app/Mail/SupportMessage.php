<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $supportData;

    public function __construct($supportData)
    {
        $this->supportData = $supportData;
    }

    public function build()
    {
        return $this->subject('Support Request Received - ' . $this->supportData['subject'])
                    ->view('emails.support-confirmation');
    }
}