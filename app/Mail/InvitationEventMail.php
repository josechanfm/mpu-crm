<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationEventMail extends Mailable
{
    use Queueable, SerializesModels;

    public $htmlContent;
    public $eventName;

    public function __construct($htmlContent, $eventName)
    {
        $this->htmlContent = $htmlContent;
        $this->eventName = $eventName;
    }

    public function build()
    {
        return $this->subject('Invitation: ' . $this->eventName)
                    ->html($this->htmlContent);
    }
}