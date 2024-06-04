<?php

namespace App\Console;

use App\Console\Commands\ExchangeRates;
use App\Jobs\CloseOpenEventsJob;
use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        ExchangeRates::class
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('ExchangeRate')->daily();
        if (config('app.enable_laravel_event_close_job')) {
            $queueConnection=strtolower(config('app.event_close_queue_connection')??'');
            if($queueConnection!='') {//the job will not run in sync mode
                $schedule->job(new CloseOpenEventsJob,null,$queueConnection)->everyMinute();
            }
        }
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
