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
        //$schedule->command('gpdp:reminder')->everyMinute();
        //$schedule->command('gpdp:reminder')->dailyAt('09:26')->timezone(env('APP_TIMEZONE','Asia/Macau'));
        //php artisan schedule:run
        $schedule->command('gpdp:reminder')->dailyAt('08:00')->timezone(env('APP_TIMEZONE','Asia/Macau'));
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
