<?php
namespace App\Http\PusherEvents;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class RequestPush implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $event_id;
    public $user_id;
    public $data;


    public function __construct($event,$request_id,$user_id)
    {
        $this->event_id = $event->id;
        $this->user_id = $user_id;
        $this->data = ['request_id'=>$request_id,'event_id'=> $event->id,'event_name'=>$event->name,'event_url'=>route('event.details', [$event->id, slug($event->name)])];
        $codes=get_extention_keys('pusher');
        set_pusher_config($codes);
    }

    public function broadcastOn()
    {
        return ['request.'.$this->event_id.'.user.'.$this->user_id];
    }

    public function broadcastAs()
    {
        return 'request.'.$this->event_id.'.user.'.$this->user_id;
    }
}
