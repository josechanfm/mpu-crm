<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StaffNotice;
use Illuminate\Support\Facades\Mail;
use App\Mail\RelativeAgeReminderEmail;

class RelativeAgeReminderCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'relativeAge:reminder';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Daily auto send Relative age (18,22,24) reminder';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $notices=StaffNotice::with(['relative:id,name_zh,name_pt'])->where('date',date('Y-m-d'))->where('status','N')->get();
        //$notices = StaffNotice::where('date', date('Y-m-d'))->get();
        foreach($notices as $notice){
            Mail::to($notice->email)->send(new RelativeAgeReminderEmail($notice));
         }
        return Command::SUCCESS;
    }
}
