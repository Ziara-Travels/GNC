<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HelpConfirmation extends Mailable
{
    use Queueable, SerializesModels;

    public $helpData;

    public function __construct($helpData)
    {
        $this->helpData = $helpData;
    }

    public function build()
    {
        return $this->subject('Help Request Received - ' . $this->helpData['subject'])
                    ->view('emails.help-confirmation');
    }
}