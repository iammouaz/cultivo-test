<?php
namespace App\Http\PusherEvents;
use App\Http\Helpers\EventsLiveHelper;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductAutoBidPush implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product_id;
    public $data;


    public function __construct($product_id)
    {
        $this->product_id = $product_id;
        $this->data = 'auto_bid_start';
        $codes=get_extention_keys('pusher');
        set_pusher_config($codes);
    }

    public function broadcastOn()
    {
        return ['autobidstart.'.$this->product_id];
    }

    public function broadcastAs()
    {
        return 'autobidstart.'.$this->product_id;
    }
}
