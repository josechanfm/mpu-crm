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
        $schedule->command('gpdp:reminder')->everyMinute();
<<<<<<< HEAD
        //$schedule->command('gpdp:reminder')->daily();
        // $schedule->call(function(){
        //     info('called evey minute');
        // })->everyMinute();
=======
>>>>>>> bc1bacfcc2fe0074f63d96920ebd33aef3ecad9a
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
