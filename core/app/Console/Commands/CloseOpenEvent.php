<?php

namespace App\Console\Commands;

use App\Http\PusherEvents\EventPush;
use App\Http\PusherEvents\ProductPush;
use App\Models\Event;
use App\Services\EventService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Route;

class CloseOpenEvent extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'mcultivo:close_event';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Close open event if end time out';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        //todo either make a non-overlapping job or use this command in cron job but add a while loop with a sleep 5 seconds for 12 times
        $events = Event::where('bid_status', 'open')->where('end_date', '<', now())->get();//todo must be indexed
        if ($events->count() == 0){
            $this->info('No open event found');
            return Command::SUCCESS;
        }
        /** @var EventService $eventService */
        $eventService = app('eventService');
        foreach ($events as $event) {
            $eventService->closeEvent($event);
            $this->info('The Event ' . $event->id . ' is closed and pushed the notification');
        }
        $this->info('Done');
        return Command::SUCCESS;
    }
}
