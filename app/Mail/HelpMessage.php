<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class HelpMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $helpData;

    public function __construct($helpData)
    {
        $this->helpData = $helpData;
    }

    public function build()
    {
        return $this->subject('Help Request - ' . $this->helpData['subject'])
                    ->view('emails.help-message');
    }
}