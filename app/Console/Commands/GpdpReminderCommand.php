<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Gpdp;
use Illuminate\Support\Facades\Mail;

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
    protected $description = 'Command description';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $gpdps=Gpdp::where('is_valid',true)->where('date_remind',date('Y-m-d'))->get()->toArray();
        foreach($gpdps as $gpdp){
            Mail::send('emails.gpdpReminder',$gpdp, function($message) use($gpdp){
                $message->to($gpdp['email'])
                        ->from('josec@mpu.edu.mo','Personnel Department')
                        ->subject('GPDP remider');
            });
        }
        return Command::SUCCESS;
    }
}
