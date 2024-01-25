<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gpdp;
use Illuminate\Support\Facades\Mail;
use App\Mail\GpdpReminderEmail;

class GpdpReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'gpdp:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily auto send GPDP reminder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gpdps=Gpdp::where('is_valid',true)->where('date_remind',date('Y-m-d'))->get();
        foreach($gpdps as $gpdp){
            Mail::to($gpdp->email)->send(new GpdpReminderEmail($gpdp));
            // Mail::send('emails.gpdpReminder',$gpdp, function($message) use($gpdp){
            //     $message->to($gpdp['email'])
            //             ->cc('personnel@mpu.edu.mo')
            //             ->from('personnel@mpu.edu.mo','Personnel Office')
            //             ->subject('提交財產及利益申報書/ Apresentação da Declaração de Bens Patrimoniais e Interesses/ Submission of the Declaration of Assets and Interests');
            // });
        }
        return Command::SUCCESS;
    }
}
