<?php
namespace App\Http\PusherEvents;
use App\Http\Helpers\EventsLiveHelper;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;

class ProductPush implements ShouldBroadcast//todo ,ShouldQueue
{
    use Dispatchable, InteractsWithSockets, SerializesModels;//todo,Queueable;

    public $product_id;
    public $data;


    public function __construct($product_id)
    {
        $this->product_id = $product_id;
        $this->data = json_encode(EventsLiveHelper::getProductData($product_id));
        $codes=get_extention_keys('pusher');
        set_pusher_config($codes);
    }

    public function broadcastOn()
    {
        return ['product.'.$this->product_id];
    }

    public function broadcastAs()
    {
        return 'product.'.$this->product_id;
    }
}
