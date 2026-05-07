<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SouvenirNotificationMail extends Mailable
{
    use Queueable, SerializesModels;

    public $user;
    public $registrationUrl;

    public function __construct($user, $registrationUrl)
    {
        $this->user = $user;
        $this->registrationUrl = $registrationUrl;
    }

    public function build()
    {
        return $this->subject('Souvenir Notification / Souvenir 通知')
                    ->view('emails.souvenir_notification');
    }
}
