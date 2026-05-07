<?php

namespace App\Jobs;

use App\Mail\SouvenirNotificationMail;
use App\Models\SouvenirUser;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class SendSouvenirNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public SouvenirUser $user;
    public string $registrationUrl;

    public function __construct(SouvenirUser $user, string $registrationUrl)
    {
        $this->user = $user;
        $this->registrationUrl = $registrationUrl;
    }

    public function handle()
    {
        $recipient = $this->user->notify_email ?: $this->user->email;

        Mail::to($recipient)
            ->send(new SouvenirNotificationMail($this->user, $this->registrationUrl));

        $freshUser = SouvenirUser::find($this->user->id);
        if ($freshUser) {
            $freshUser->increment('notify_sent');
        }
    }
}
