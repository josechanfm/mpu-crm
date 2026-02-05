<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\StaffNotice;
use Illuminate\Support\Facades\Mail;
use App\Mail\RelativeAgeReminderEmail;
use App\Models\Staff;
use App\Models\Scheduler;

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
        // Step 1: Fetch remote staff records
        Staff::fetchRemoteStaffRecords('Schedule');
        // Step 2: Create notices
        Staff::createNotices('Schedule');
        // Step 3: Fetch notices
        // try {
        //     $notices = StaffNotice::with(['relative:id,name_zh,name_pt'])
        //         ->where('date', date('Y-m-d'))
        //         ->where('status', 'N')
        //         ->get();

        //     if ($notices->isEmpty()) {
        //         $this->logScheduler('fetchNotices', 'No notices found for today');
        //     }

        //     // Step 4: Send emails
        //     foreach ($notices as $notice) {
        //         try {
        //             Mail::to($notice->email)->send(new RelativeAgeReminderEmail($notice));
        //             $this->logScheduler('sendEmailTo:' . $notice->email, 'Success');
        //         } catch (\Exception $e) {
        //             $this->logScheduler('sendEmailTo:' . $notice->email, 'Failed: ' . $e->getMessage());
        //         }
        //     }
        // } catch (\Exception $e) {
        //     $this->logScheduler('fetchNotices', 'Failed: ' . $e->getMessage());
        // }

        return Command::SUCCESS;
    }

    /**
     * Log action to the Scheduler model.
     *
     * @param string $action
     * @param string $result
     */
    protected function logScheduler($action, $result)
    {
        Scheduler::create([
            'action' => $action,
            'result' => $result,
        ]);
    }
}