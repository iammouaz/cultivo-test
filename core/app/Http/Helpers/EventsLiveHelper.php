<?php

namespace App\Http\Helpers;

use App\Http\PusherEvents\EventPush;
use App\Http\PusherEvents\ProductPush;
use App\Models\Bid;
use App\Models\Event;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class EventsLiveHelper
{

    public static function getEventData($event_id,$user_id = null)
    {
        if(is_null($user_id)){
            if (Auth::check())
                $user_id = Auth::id();
        }

        $event = Event::findOrFail($event_id);

        $time = showDateTime($event->end_date, 'm/d/Y H:i:s');
        $is_the_time_ended = $event->end_date < now();
        $is_event_ended = $event->start_status == 'ended';

        if ($is_the_time_ended && $event->bid_status == 'open') {
            $event->bid_status = 'closed';
            $event->save();
        }
        $highest_bid = 0;
        $products = $event->products;
        foreach ($products as $product) {

            $highest_bid = $highest_bid < $product->final_price ? $product->final_price : $highest_bid;
        }
        $start_counter = $event->start_counter;

        $bid_status = $event->bid_status;

        $event_start_type = $event->start_status;
        $event_view = '';
        if ($event_start_type == 'started') {
            //render time_countdown with base product object and set to $event_view variable
            $event_view = view('templates.basic.product.time_countdown',
                compact('event'))->render();
        }

//
//        foreach ($products as $product) {
//            event(new ProductPush($product->id));
//        }

        $allProducts = $event->products()->get();
        $products_info=[];
        foreach ($allProducts as $product) {
            $pro=[];
            $pro['id']=$product->id;
            $pro['highest_bidder_id']=$product->highest_bidder_id()??-1;
            array_push($products_info,$pro);
        }
        $products = $event->products()->whereHas('bids')->get();
        $total_bid_count = 0;
        $total_auction_value = 0;
        $total_weight = 0;

        foreach ($products as $product) {
            $total_bid_count += $product->total_bid;
            $total_auction_value += $product->final_total_price;
            $total_weight += $product->weight;

            // $highest_bid = $highest_bid < $product->final_price ? $product->final_price : $highest_bid;
        }
       // $product_count = $products->count() > 0 ? $products->count() : 1;
        //$auction_weight_avg = $total_weight / $product_count;
        $total_weight = $total_weight > 0 ? $total_weight : 1;
        $auction_weight_avg = $total_auction_value / $total_weight;
        $win_lot_count = $event->user_winning_products_count;
        $user_win_total_auction_value = 0;
        $user_win_total_weight = 0;
        $total_user_bid_in_this_event = 0;
        if($user_id) {
            foreach ($products as $product) {
                if (!is_null($product->max_bid()) && $product->max_bid()->user->id == $user_id) {
                    $user_win_total_auction_value += $product->final_total_price;
                    $user_win_total_weight += $product->weight;
                }

                if ($product->bids()->where('user_id', $user_id)->first()) {
                    $total_user_bid_in_this_event +=
                        $product->bids()->where('user_id', $user_id)->first()->User_Bids_Count;
                }
            }
        }

        $arr = ['event_start_type' => $event_start_type, 'bid_status' => $bid_status,
            'event_view' => $event_view, 'start_counter' => $start_counter,
            'is_the_time_ended' => $is_the_time_ended,
            'is_event_ended' => $is_event_ended,
            'total_bid_count' => $total_bid_count,'total_auction_value' => $total_auction_value
            ,'auction_weight_avg' => $auction_weight_avg ,'highest_bid' => $highest_bid
            ,'user_win_total_auction_value' => $user_win_total_auction_value
            ,'user_win_total_weight' => $user_win_total_weight
            ,'total_user_bid_in_this_event' => $total_user_bid_in_this_event
        ,'win_lot_count'=>$win_lot_count,'products_info'=>$products_info];
        return $arr;

    }


    public static function getProductData($product_id)
    {

        $product = Product::findOrFail($product_id);
        if (is_null($product)) {
            return ['message' => 'not found'];
        }

        $event = $product->event;
        $time = showDateTime($event->end_date, 'm/d/Y H:i:s');
        $is_the_time_ended = $event->end_date < now();

        if ($is_the_time_ended && $event->bid_status == 'open') {
            $event->bid_status = 'closed';
            $event->save();
        }

        $is_event_ended = $event->start_status == 'ended';

        $bids = $product->bids;
        $max_bid = $product->max_bid();
        $bid_count = $product->total_bid;
        $event_start_type = $event->start_status;
        $event_view = '';
        $start_counter = $event->start_counter;


        $amount = ($max_bid) ? $max_bid->amount : $product->price;
        $new_bidding_value = floatval($amount) + floatval($product->less_bidding_value);
        $new_bidding_value = round($new_bidding_value, 2);

        $bid_view = view('templates.basic.event.bid_area',
            compact('event', 'product', 'is_event_ended','new_bidding_value'))->render();

        $bid_view_product = view('templates.basic.product.bid_area',
            compact('event', 'product', 'is_event_ended','new_bidding_value'))->render();


        $bid_status = $event->bid_status;
        $weight = 0;
        if ($product->product_specification) {
            foreach ($product->product_specification as $spec) {
                if (strtoupper($spec->spec_key) == 'WEIGHT') {
                    $weight = $spec->Value;
                }
            }
        }
        $max_price = is_null($max_bid) ? $product->price : $max_bid->amount;
        $total_price = floatval($weight) * $max_price;
        $total_price = showAmount($total_price);

        if ($event_start_type == 'started') {
            //render time_countdown with base product object and set to $event_view variable
            $event_view = view('templates.basic.product.time_countdown',
                compact('event'))->render();
        }

        $last_bid_userid = 0;
        if(!is_null($max_bid)){
            $last_bid_userid = $max_bid->user_id;
        }
        // $count_bids_last1 = $product->count_bids_last1();
        $new_bidding_value = $amount + $product->less_bidding_value;
        $new_bidding_value = round($new_bidding_value, 2);

        if(!is_null($max_bid)){
            $max_bid = true;
        }
        else{
            $max_bid = false;
        }

        $amount = showAmount($amount);

        $arr = ['time' => $time, 'bid_count' => $bid_count, 'product_id' => $product_id,
            'max_bid' => $max_bid, 'event_start_type' => $event_start_type, 'bid_view' => $bid_view, 'bid_status' => $bid_status,
            'event_view' => $event_view, 'start_counter' => $start_counter, 'bid_view_product' => $bid_view_product,
            'is_event_ended' => $is_event_ended,'is_the_time_ended' => $is_the_time_ended,'amount' => $amount,
            'total_price' => $total_price,'last_bid_userid' => $last_bid_userid,//'count_bids_last1' => $count_bids_last1,
            'new_bidding_value' =>$new_bidding_value
        ];

        return $arr;
    }
}
