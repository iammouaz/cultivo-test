<?php

namespace App\Jobs;

use App\Models\Event;
use App\Services\EventService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

//job for ending the event in the new end date
//$job = (new CloseEvent($id,$event->end_date))->delay($event->end_date);
//$job->delay($event->end_date);
//inside the job set the handle code to check if the given date is in the past or in the future
//todo check failed jobs table for the failed jobs and handle them in a chron job
class CloseEvent implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $event_id;
    protected $dateTime;
    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($event_id)
    {
        $this->event_id = $event_id;
    }


    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $event = Event::find($this->event_id);
        if($event->bid_status == 'open' && Carbon::parse($event->end_date) <= now()){
            /** @var EventService $eventService */
            $eventService = app('eventService');
            $eventService->closeEvent($event);
            if (config('app.env')!='production') {
                Log::info('Event '.$event->id.' closed');
            }
        }
        else
        {
            if (config('app.env')!='production') {
                Log::info('Event '.$event->id.' not closed because it is'.$event->bid_status.' and end date is '.$event->end_date);
            }
        }
    }
}
