<?php
namespace App\Http\PusherEvents;
use App\Http\Helpers\EventsLiveHelper;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class EventPush implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event_id;
    public $data;


    public function __construct($event_id)
    {
        $this->event_id = $event_id;
        $this->data = json_encode(EventsLiveHelper::getEventData($event_id));
        $codes=get_extention_keys('pusher');
        set_pusher_config($codes);
    }

    public function broadcastOn()
    {
        return ['event.'.$this->event_id];
    }

    public function broadcastAs()
    {
        return 'event.'.$this->event_id;
    }
}
