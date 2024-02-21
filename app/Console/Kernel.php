<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        // $autoMailPeriod=env('GPDP_AUTO_EMAIL',null);
        // if(!empty($autoMail)){
        //     $schedule->command('gpdp:reminder')->{$autoMailPeriod}();
        // }

        
        $schedule->command('gpdp:reminder')->dailyAt('1:00')->timezone(env('APP_TIMEZONE','Asia/Macau'));
        // $schedule->call(function(){
        //     info('called evey minute');
        // })->everyMinute();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
