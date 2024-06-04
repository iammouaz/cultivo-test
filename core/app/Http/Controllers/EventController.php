<?php

namespace App\Http\Controllers;

use App\Http\Helpers\EventsLiveHelper;
use App\Models\AutoBidSetting;
use App\Models\Bid;
use App\Models\Budget;
use App\Models\Event;
use App\Models\ExchangeRate;
use App\Models\Product;
use App\Models\UserProductFav;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function events()
    {
        $pageTitle = request()->search_key ? __('Search Products') : __('The World’s Premier Specialty Coffee Auctions');
        $emptyMessage = __('No product found');
        //dd("fgfg");

        $eventid = array();
        $eventid = get_allowed_events(Auth::id());

        $events = Event::live()->paginate(getPaginate(18));
        //$events = Event::live()->where('end_date', '>', now())->paginate(getPaginate(18));
        $policy_id=get_policy_id();

        return view($this->activeTemplate . 'event.list', compact('pageTitle', 'emptyMessage', 'events', 'eventid','policy_id'));
    }

    public function eventDetails($id){

        $event = Event::findOrFail($id);

        return redirect()->route('event.preview',[$event->event_url]);
    }


    public function eventPreview($url)
    {
        $pageTitle = 'Event Details';

        $event = Event::with('products')->where('event_url',$url)->firstOrFail();
//        $eventid = get_allowed_events(Auth::id());

        $is_event_ended = $event->start_status == 'ended';
        if (!$is_event_ended) {
//            if (Auth::check()) {
////                $eventid = get_allowed_events(Auth::id());
////                if (in_array($event->id, $eventid)) {
////                    $productid = get_allowed_products(Auth::id());
////                    $relatedProducts = Product::live()->where('event_id', $id)->whereIn('id', $productid)->get();
////                } else {
////                    //return all product
////                    $relatedProducts = Product::live()->where('event_id', $id)->get();
////                }
//
//                $relatedProducts = Product::live()->where('event_id', $id)->get();
//
//            } else {
//                $relatedProducts = Product::live()->where('event_id', $id)->get();
//            }
            $relatedProducts = Product::live()->where('event_id', $event->id)->get();

        } else {
            $relatedProducts = Product::where('event_id', $event->id)->get();
        }
        $eventid = array();
        $eventid = get_allowed_events(Auth::id());
        $pending_event_ids = get_pending_events(Auth::id());
        $imageData = imagePath()['event'];
        $products = $event->products;
        $highest_bid = 0;
        foreach ($products as $product) {

            $highest_bid = $highest_bid < $product->final_price ? $product->final_price : $highest_bid;
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
//        $product_count = $products->count() > 0 ? $products->count() : 1;
//        $auction_weight_avg = $total_auction_value / $product_count;

        $total_weight = $total_weight > 0 ? $total_weight : 1;
        $auction_weight_avg = $total_auction_value / $total_weight;
        $policy_id=get_policy_id();
        return view($this->activeTemplate . 'event.details', compact('pageTitle', 'is_event_ended',
            'event', 'relatedProducts', 'eventid', 'total_bid_count', 'total_auction_value', 'auction_weight_avg', 'highest_bid',
            'pending_event_ids','policy_id'));
    }

    public function getRefreshTime(Request $request)
    {
        if ($request->id) {

            $event = Event::findOrFail($request->id);
            $time = showDateTime($event->display_end_date, 'm/d/Y H:i:s');
//            $max_bid = $event->max_bid;
//            $bid_count = $event->bid_count;

            return $time;
        }
    }

    public function productAjax(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'product_id' => 'required',
        ]);

        $event = Event::findOrFail($request->event_id);
        $time = showDateTime($event->display_end_date, 'm/d/Y H:i:s');
        $is_the_time_ended = $event->display_end_date < now();
        if ($is_the_time_ended && $event->bid_status == 'open') {
            $event->bid_status = 'closed';
            $event->save();
        }

        $product = Product::findOrFail($request->product_id);
        $bids = $product->bids;
        $max_bid = $product->max_bid();
        $bid_count = $product->total_bid;
        $event_start_type = $event->start_status;
        $event_view = '';
        $start_counter = $event->start_counter;
        $bid_view = view($this->activeTemplate . 'product.bid_area',
            compact('event', 'product'))->render();
        $bid_status = $event->bid_status;
        $is_event_ended = $event->start_status == 'ended';


        if ($event_start_type == 'started') {
            //render time_countdown with base product object and set to $event_view variable
            $event_view = view($this->activeTemplate . 'product.time_countdown',
                compact('event'))->render();
        }
        return response()->json(['time' => $time, 'bids' => $bids, 'bid_count' => $bid_count,
            'max_bid' => $max_bid, 'event_start_type' => $event_start_type, 'bid_view' => $bid_view, 'bid_status' => $bid_status,
            'event_view' => $event_view, 'start_counter' => $start_counter, 'is_event_ended' => $is_event_ended]);
    }

    public function eventAjax(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
        ]);

        $arr = EventsLiveHelper::getEventData($request->event_id);

        return response()->json($arr);
    }

    public function eventAgreement($id)
    {
        $event = Event::findOrFail($id);
        $agreement = $event->agreement;
        $pageTitle = 'Auction Agreement for ' . $event->name;
        return view($this->activeTemplate . 'event.agreement', compact('pageTitle', 'agreement'));
    }

    public function getAuctionView(Request $request)
    {
        $id = $request->event_id;
        $event = Event::findOrFail($request->event_id);
        $is_event_ended = $event->start_status == 'ended';
        if (!$is_event_ended) {
            if (Auth::check()) {
//                $eventid = get_allowed_events(Auth::id());
//                if (in_array($event->id, $eventid)) {
//                    $productid = get_allowed_products(Auth::id());
//                    $products = Product::live()->where('event_id', $id)->whereIn('id', $productid)->get();
//                } else {
//                    //return all product
//                    $products = Product::live()->where('event_id', $id)->get();
//                }
                $products = Product::live()->where('event_id', $id)->get();


            } else {
                $products = Product::live()->where('event_id', $id)->get();
            }
        } else {
            $products = Product::where('event_id', $id)->get();
        }

        $auction_view = view($this->activeTemplate . 'event.auction_view',
            compact('event', 'products', 'is_event_ended'))->render();


        return response()->json(['auction_view' => $auction_view]);
    }

    public function getOverview(Request $request)
    {
        $id = $request->event_id;
        $event = Event::findOrFail($request->event_id);
        $is_event_ended = $event->start_status == 'ended';
        if (!$is_event_ended) {
            if (Auth::check()) {
//                $eventid = get_allowed_events(Auth::id());
//                if (in_array($event->id, $eventid)) {
//                    $productid = get_allowed_products(Auth::id());
//                    $products = Product::live()->where('event_id', $id)->whereIn('id', $productid)->get();
//                } else {
//                    //return all product
//                    $products = Product::live()->where('event_id', $id)->get();
//                }
                $products = Product::live()->where('event_id', $id)->get();


            } else {
                $products = Product::live()->where('event_id', $id)->get();
            }
        } else {
            $products = Product::where('event_id', $id)->get();
        }

        $relatedProducts = $products;

        $overview_view = view($this->activeTemplate . 'event.overview',
            compact('event', 'products', 'is_event_ended', 'relatedProducts'))->render();


        return response()->json(['overview_view' => $overview_view]);
    }

    public function getDashboardView(Request $request)
    {
        $id = $request->event_id;
        $event = Event::findOrFail($request->event_id);

        $products = [];
        $is_event_ended = $event->start_status == 'ended';
        if (!$is_event_ended) {
            if (Auth::check()) {
                $productid = get_allowed_products(Auth::id());
                $products = Product::live()->where('event_id', $id)->whereIn('id', $productid)->get();
            } else {
                $products = Product::live()->where('event_id', $id)->get();
            }
        } else {
            $products = Product::where('event_id', $id)->get();
        }

        $user_win_total_auction_value = 0;
        $user_win_total_weight = 0;
        $total_user_bid_in_this_event = 0;
        foreach ($products as $product) {
            if (!is_null($product->max_bid()) && $product->max_bid()->user->id == Auth::id()) {
                $user_win_total_auction_value += $product->final_total_price;
                $user_win_total_weight += $product->weight;
            }

            if ($product->bids()->where('user_id', Auth::id())->first()) {
                $total_user_bid_in_this_event +=
                    $product->bids()->where('user_id', Auth::id())->first()->User_Bids_Count;
            }
        }
        $avg_price = 0;
        if ($user_win_total_weight > 0) {
            $avg_price = $user_win_total_auction_value / $user_win_total_weight;
        }
        $win_lot_count = $event->user_winning_products_count;


        $auto_bid_settings = AutoBidSetting::where('user_id', Auth::id())->where('status', 'active')->whereIn('product_id', $products->pluck('id'))->with('product')->get();

        $user_event_budget = Budget::where('user_id', Auth::id())->where('event_id', $event->id)->first();
        if ($user_event_budget) {
            $user_event_budget = $user_event_budget->budget;
        } else {
            $user_event_budget = 0;
        }


        //get products in favorite list or has bid by this user in this event
        $product_favorite =
            UserProductFav::where('user_id', Auth::id())->pluck('product_id')->toArray();

        $product_bid = Bid::where('user_id', Auth::id())->pluck('product_id')->toArray();


        $products_has_bid_by_user =
            Product::live()
            ->where('event_id', $id)->whereIn('id',  $product_bid)->whereNotIn('id', $product_favorite)
                ->get();

        $products_in_fav_user =
            Product::live()
            ->where('event_id', $id)->whereIn('id',  $product_favorite)
                ->get();


        $dashboard_view = view($this->activeTemplate . 'event.dashboard_view',
            compact('event', 'user_win_total_auction_value', 'user_win_total_weight',
                'avg_price', 'products', 'total_user_bid_in_this_event',
                'auto_bid_settings', 'win_lot_count', 'is_event_ended','products_in_fav_user',
                'products_has_bid_by_user','user_event_budget'))->render();

        return response()->json(['dashboard_view' => $dashboard_view]);
    }

    public function getProducts($event_id)
    {

        $event = Event::findOrFail($event_id);
        $is_event_ended = $event->start_status == 'ended';
        if (!$is_event_ended) {
            if (Auth::check()) {
                $productid = get_allowed_products(Auth::id());
                $products = Product::live()->where('event_id', $event_id)->whereIn('id', $productid)->get();
            } else {
                $products = Product::live()->where('event_id', $event_id)->get();
            }
        } else {
            $products = Product::where('event_id', $event_id)->get();
        }

        return response()->json(['products' => $products]);

    }

    public function get_exchange_rate(Request $request)
    {
        $currency = ExchangeRate::where('Currency_Code', $request->currency_code)->first();
        if ($currency) {
            $exchange_rate = $currency->Exchange_Rate;
        } else {
            $exchange_rate = 0;
        }

        return response()->json([

            'exchange_rate' => $exchange_rate
        ]);
    }

}
