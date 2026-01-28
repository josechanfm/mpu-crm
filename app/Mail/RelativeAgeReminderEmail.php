<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Illuminate\Mail\Mailables\Address;

class RelativeAgeReminderEmail extends Mailable
{
    use Queueable, SerializesModels;
    public $notice;
    public $subject;
    public $sender;
    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($notice)
    {
        $this->notice=$notice;
        $this->subject='Relative age';
        $this->sender=['josechan@mpu.edu.mo','Personnel Office'];
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
        // $email=[
        //     'admin_user_id'=>auth()->user()?auth()->user()->id:null,
        //     'sender'=>json_encode($this->sender),
        //     'receiver'=>$this->notice->email,
        //     'subject'=>$this->subject,
        //     'content'=>view('emails.relativeAgeReminder',$this->notice)->render()
        // ];
            // $this->notice->update([
            //     'title'=>$this->subject,
            //     'body'=>'email send',
            //     'sent_at'=>now(),
            //     'status'=>'S'
            // ]);
        //$emailOuput = view('emails.relativeAge18Reminder', ['data' => $this->notice->toArray()])->render();
        // dd($emailOuput);

        switch ($this->notice->age) {
            case 18:
                $this->notice->update([
                    'title'=>$this->subject,
                    'body'=>view('emails.relativeAge18Reminder', $this->notice->toArray())->render(),
                    'sent_at'=>now(),
                    'status'=>'S'
                ]);
                return $this->view('emails.relativeAge18Reminder',$this->notice->toArray());
                break;
            case 22:
                $this->notice->update([
                    'title'=>$this->subject,
                    'body'=>view('emails.relativeAge22Reminder', $this->notice->toArray())->render(),
                    'sent_at'=>now(),
                    'status'=>'S'
                ]);
                return $this->view('emails.relativeAge22Reminder',$this->notice->toArray());
                break;
            case 24:
                $this->notice->update([
                    'title'=>$this->subject,
                    'body'=>view('emails.relativeAge24Reminder', $this->notice->toArray())->render(),
                    'sent_at'=>now(),
                    'status'=>'S'
                ]);
                return $this->view('emails.relativeAge24Reminder',$this->notice->toArray());
                break;
            default:
                // Code to be executed if no case matches
        }
        return false;
        //return $this->view('emails.relativeAgeReminder',$this->notice->toArray());
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
