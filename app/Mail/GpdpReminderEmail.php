<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class GpdpReminderEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $gpdp;
    public $subject;
    public $sender;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($gpdp)
    {
        $this->gpdp=$gpdp;
        $this->subject='提交財產及利益申報書/ Apresentação da Declaração de Bens Patrimoniais e Interesses/ Submission of the Declaration of Assets and Interests';
        $this->sender=['personnel@mpu.edu.mo','Personnel Office'];
    }

    /**
     * Get the message envelope.
     *
     * @return \Illuminate\Mail\Mailables\Envelope
     */
    public function envelope()
    {
        return new Envelope(
            from: new Address($this->sender[0],$this->sender[1]),
            subject: $this->subject,
        );
    }

    /**
     * Get the message content definition.
     *
     * @return \Illuminate\Mail\Mailables\Content
     */
    public function build()
    {
        $email=[
            'admin_user_id'=>auth()->user()?auth()->user()->id:null,
            'sender'=>json_encode($this->sender),
            'receiver'=>$this->gpdp->email,
            'subject'=>$this->subject,
            'content'=>view('emails.gpdpReminder',$this->gpdp)->render()
        ];
        $this->gpdp->emails()->create($email);
        return $this->view('emails.gpdpReminder',$this->gpdp->toArray());
        // return new Content(
        //     view: 'view.name',
        // );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array
     */
    public function attachments()
    {
        return [];
    }
}
