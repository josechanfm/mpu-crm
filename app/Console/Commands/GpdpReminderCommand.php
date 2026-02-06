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
        }
        if ($gpdps->count()>0) {
            $this->logScheduler('gpdp', 'SUCCESS', $gpdps->count().' Gpdp records');
        }else{
            $this->logScheduler('gpdp', 'No records found for today');
        }
        return Command::SUCCESS;
    }

    protected function logScheduler($action, $result, $remark)
    {
        Scheduler::create([
            'action' => $action,
            'result' => $result,
            'remarks'=> $remark
        ]);
    }
}
