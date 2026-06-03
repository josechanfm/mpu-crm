<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvitationEventMail;
use App\Models\InvitationGuest;
use App\Models\InvitationEvent;

class SendInvitationEventEmail implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $guest;
    protected $event;

    public function __construct(InvitationGuest $guest, InvitationEvent $event)
    {
        $this->guest = $guest;
        $this->event = $event;
    }

    public function handle()
    {
        // Prepare placeholders and send email (same as before)
        $placeholders = [
            '{{guest_name}}' => $this->guest->name ?? 'Valued Guest',
            '{{guest_organization}}' => $this->guest->organization ?? '',
            '{{event_name}}' => $this->event->name,
            '{{event_date}}' => $this->event->start_date ? $this->event->start_date->format('F j, Y, g:i a') : 'TBD',
            '{{event_location}}' => trim(($this->event->venue ?? '') . ', ' . ($this->event->location ?? '')),
            '{{rsvp_link}}' => route('grp.invitation-rsvp', ['uuid' => $this->guest->uuid]),
            '{{qrcode_image}}' => $this->guest->getQrCodeBase64()
        ];

        $plainText = $this->event->email_template ?? '';
        $htmlContent = nl2br(e($plainText));

        $finalHtml = str_replace(array_keys($placeholders), array_values($placeholders), $htmlContent);

        Mail::to($this->guest->email)->send(new InvitationEventMail($finalHtml, $this->event->name));

        // Update sent timestamp
        $this->guest->update(['invitation_sent_at' => now()]);
    }
}