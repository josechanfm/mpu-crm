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
        return $this->subject('【畢業快樂】澳理大小熊上線喇!｜【Happy Graduation】MPU Bear is now available!')
                    ->view('emails.souvenir_notification');
    }
}
