<?php
namespace App\Http\PusherEvents;
use App\Http\Helpers\EventsLiveHelper;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductAutoBidEndPush implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $product_id;
    public $data;


    public function __construct($product_id)
    {
        $this->product_id = $product_id;
        $this->data = 'auto_bid_end';
        $codes=get_extention_keys('pusher');
        set_pusher_config($codes);
    }

    public function broadcastOn()
    {
        return ['autobidend.'.$this->product_id];
    }

    public function broadcastAs()
    {
        return 'autobidend.'.$this->product_id;
    }
}
