<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\OfferSheet;
use App\Models\ShippingRegion;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class CartController extends Controller
{
    public static function getEvents(){
        $winningHistories = Winner::where('user_id', auth()->id())->where('status', CheckOutController::PENDENIN)->where('order_id', null)
            ->with('user', 'product', 'bid')->latest()->get();
        $events_ids = $winningHistories->pluck('product.event_id')->unique();

        $events = Event::whereIn('id', $events_ids)->get();

        return $events;
    }
    public static function getEventsArr(){

        if (auth()->id() !=null) {
        $winningHistories = Winner::where('user_id', auth()->id())->where('status', CheckOutController::PENDENIN)->where('order_id', null)
            ->with('user', 'product', 'bid')->latest()->get();
        $eventWinners= $winningHistories->groupBy('product.event_id');

        $eventsArr = [];
        foreach ($eventWinners as $event_id=> $winner){
            $event = Event::findOrFail($event_id);
            $eventsArr[] = [
                'event_id'=>$event->id,
                'event_name' => $event->name,
                'event_category' => $event->category->name,
                'product_count' => $winner->count(),
            ];
        }
            return $eventsArr;
        }else{
            $eventsArr = [];
            return $eventsArr;
        }
    }
public static function getFullEventsArr(){

        if (auth()->id() !=null) {
        $winningHistories = Winner::where('user_id', auth()->id())->where('status', CheckOutController::PENDENIN)->where('order_id', null)
            ->with('user', 'product', 'bid')->latest()->get();
        $eventWinners= $winningHistories->groupBy('product.event_id');
//        dd($eventWinners);
        $eventsArr = [];
        foreach ($eventWinners as $event_id=> $winner){
            $event = Event::findOrFail($event_id);
            $arr = [
                'event_id'=>$event->id,
                'event_name' => $event->name,
                'event_category' => $event->category->name,
                'event_type' => 'auction',
                'product_count' => $winner->count(),
                'items' => [],
            ];
            foreach ($winner as $win){
                $arr['items'][] = [
                    'quantity' => 1,
                    'price' => $win->product->final_total_price,
                    'price_lb' => $win->product->final_price,
                    'size_weight' => $win->product->weight,
                    'origin'=> $win->product->origin,
                    'grade'=> $win->product->grade,
                ];
            }
            $eventsArr[] = $arr;
        }
            return $eventsArr;
        }else{
            $eventsArr = [];
            return $eventsArr;
        }
    }

    public static function getOfferSheets(){
        return (new OrderController())->getCartItemsGroupedByEvent();
    }
    public static function getCarts(){
        $carts= (new OrderController())->getFullCartItemsGroupedByEvent();
        $carts = array_merge($carts,  (new SampleSetOrderController())->getFullCartItemsGroupedByEvent());
        $carts = array_merge($carts, self::getFullEventsArr());
        return $carts;

    }
    public function getCartIconView(){
        return response()->json(['cart_icon'=>view('templates.basic.partials.cart_icon')->render()]);
    }

    public function cart_summary($event_id){
        $cart = (new OrderController())->getCartArray($event_id);
        $shippingRegions = ShippingRegion::query()->get();
        $pageTitle='CHECKOUT';

        // Step 1: Group the cart items by event ID
        $groupedItems = collect($cart);
        //event name
        $event = OfferSheet::findOrFail($event_id);
        $eventName = $event->name;
        $eventCategory = $event->category->name;
        //products count
        $productCount =$groupedItems->count();

        return view('templates.basic.cart_summary',compact('groupedItems','eventName','productCount','pageTitle','cart','shippingRegions','eventCategory','event_id'));

    }
}
