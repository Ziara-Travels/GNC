<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SupportMessage extends Mailable
{
    use Queueable, SerializesModels;

    public $supportData;

    public function __construct($supportData)
    {
        $this->supportData = $supportData;
    }

    public function build()
    {
        $type = ucfirst(str_replace('_', ' ', $this->supportData['type'] ?? 'support request'));

        return $this->subject($this->supportData['subject'] ?? 'New ' . $type)
                    ->view('emails.support-message');
    }
}
