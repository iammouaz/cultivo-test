<?php

namespace App\Jobs;

use App\Console\Commands\CloseOpenEvent;
use App\Models\Event;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class CloseOpenEventsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {

    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $count = 0;
        while ($count < 59) {
            $startTime =  Carbon::now();

            $command=new CloseOpenEvent();
            $command->handle();

            $endTime = Carbon::now();
            $totalDuration = $endTime->diffInSeconds($startTime);
            if($totalDuration > 0) {
                $count +=  $totalDuration;
            }
            else {
                $count++;
            }
            sleep(1);
        }
    }
}
