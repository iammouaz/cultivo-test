<?php

namespace App\Http\Controllers;

use App\Jobs\SendCustomEmail;
use App\Mail\CheckoutMail;
use App\Models\Category;
use App\Models\Country;
use App\Models\DefaultRegions;
use App\Models\Event;
use App\Models\Fee;
use App\Models\GeneralSetting;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Price;
use App\Models\Product;
use App\Models\SampleSet;
use App\Models\ShippingRanges;
use App\Models\ShippingRegion;
use App\Models\ShippingRegionCountry;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Sentry\Laravel\Integration;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;

class SampleSetOrderController extends Controller
{
    const PENDENIN = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';


    public function addToCartAjax(Request $request)
    {
        $validated = $request->validate([
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|numeric|min:1',

        ]);
        $user = auth()->user();
        // check if user has already orders for this event
        $eventId = $validated['event_id'];
        $orders = $user->orders()
            ->whereHas('sampleSets', function ($query) use ($eventId) {
                $query->whereHas('event', function ($subQuery) use ($eventId) {
                    $subQuery->where('id', $eventId);
                });
            })
            ->with('sampleSets')
            ->get()->toArray();
        $quantity = 0;
        foreach ($orders as $order) {
            foreach ($order['sample_sets'] as $sampleSet) {
                $quantity += $sampleSet['pivot']['quantity'];
            }
        }
        // end get quantity from orders

        $event = Event::findOrFail($validated['event_id']);
        $sample_set_limit_per_account = $event->sample_set_limit_per_account;
        $cart =json_decode($user->sample_set_cart ??null, true) ;
        if (isset($cart[$validated['event_id']])) {
            if($cart[$validated['event_id']]['quantity'] + $validated['quantity'] +$quantity > $sample_set_limit_per_account){
                $message = ('You can not add more than '.$sample_set_limit_per_account.' sample sets for this event');
                return response()->json(['error' => $message], 422);
            }
            $cart[$validated['event_id']]['quantity'] += $validated['quantity'];
        } else {
            if($validated['quantity'] +$quantity > $sample_set_limit_per_account){
                $message = ('You can not add more than '.$sample_set_limit_per_account.' sample sets for this event');
                return response()->json(['error' => $message], 422);
            }
            $cart[$validated['event_id']] = [ //properties that remains in cart
                "quantity" => $validated['quantity'],
                "event_id" => $validated['event_id'],
            ];
        }

        $detailedCart = [];
        list($cart, $detailedCart) = $this->validateCartAndGetDetails($cart, $detailedCart);
        $user = auth()->user();
        $user->sample_set_cart = json_encode($cart);
        $user->save();
        return $this->getCartView();
    }

    public function updateCartAjax(Request $request)
    {
        if ($request->carts == 0) {
            $this->emptyCart($request->event_id);
        } else {
            $validated = $request->validate([
                'carts' => 'required|array',
                'carts.*.event_id' => 'required|exists:events,id',
                'carts.*.quantity' => 'required|numeric|min:1',
                'event_id' => 'required|exists:events,id'

            ]);
            $user = auth()->user();
            // check if user has already orders for this event
            $eventId = $validated['event_id'];
            $orders = $user->orders()
                ->whereHas('sampleSets', function ($query) use ($eventId) {
                    $query->whereHas('event', function ($subQuery) use ($eventId) {
                        $subQuery->where('id', $eventId);
                    });
                })
                ->with('sampleSets')
                ->get()->toArray();
            $quantity = 0;
            foreach ($orders as $order) {
                foreach ($order['sample_sets'] as $sampleSet) {
                    $quantity += $sampleSet['pivot']['quantity'];
                }
            }
            // end get quantity from orders
            $event = Event::findOrFail($validated['event_id']);
            $sample_set_limit_per_account = $event->sample_set_limit_per_account;

            // delete cart in db and add new items
            $cart = [];
            foreach ($validated['carts'] as $item) {
                $cart[$item['event_id']] = [ //properties that remains in cart
                    "quantity" => $item['quantity'],
                    "event_id" => $item['event_id'],
                ];
                if($item['quantity'] + $quantity > $sample_set_limit_per_account){
                    $message = ('You can not add more than '.$sample_set_limit_per_account.' sample sets for this event');
                    return response()->json(['error' =>$message], 422);
                }
            }

            $detailedCart = [];
            list($cart, $detailedCart) = $this->validateCartAndGetDetails($cart, $detailedCart); //
            $currentCartExceptEvent = $this->getCartArrayExceptEvent($validated['event_id']);
            foreach ($currentCartExceptEvent as $key => $item) {
                $cart[$key] = $item;
            }


            $user->sample_set_cart = json_encode($cart);
            $user->save();
                //                Log::info('cart updated in auth (cart update function)', ['cart' => $cart]);

        }
        return $this->getCartView();
    }
    public function emptyCart($event_id = null)
    {
        if ($event_id) {
            $cart = $this->getCartArrayExceptEvent($event_id);
            if (auth()->check()) {
                $user = auth()->user();
                $user->sample_set_cart = json_encode($cart);
                $user->save();
            }
        } else {

            if (auth()->check()) {
                $user = auth()->user();
                $user->sample_set_cart = null;
                $user->save();
            }
        }
    }

    private function validateCartAndGetDetails($cart, array $detailedCart): array
    {
        foreach ($cart as $key => $item) {
            $sample_set = SampleSet::with("event")->where('event_id', $item['event_id'])
                ->orderby('created_at','desc')->first();
            if (is_null($sample_set)) {
                unset($cart[$key]);
            } else {
                $cart[$key]['quantity'] = $item['quantity'];
                $cart[$key]['event_id'] = $item['event_id'];

                $detailedCart[$key]['sample_set_id'] = $sample_set->id;
                $detailedCart[$key]['name'] = $sample_set->event->name.' sample set';
                $detailedCart[$key]['event_name'] = $sample_set->event->name;
                $detailedCart[$key]['quantity'] = $item['quantity'];
                $detailedCart[$key]['event_id'] = $item['event_id'];
                $detailedCart[$key]['price'] = $sample_set->price;
                $detailedCart[$key]['total_package_weight_Lb'] = $sample_set->total_package_weight_Lb;
                $detailedCart[$key]['number_of_samples_per_box'] = $sample_set->number_of_samples_per_box;
                $detailedCart[$key]['weight_per_sample_grams'] = $sample_set->weight_per_sample_grams;
                $detailedCart[$key]['image'] = getImage( imagePath()['product']['path']. '/' . $sample_set->image, imagePath()['product']['size'], false, 'sm');

            }
        }
        //        dd($detailedCart);
        return array($cart, $detailedCart);
    }

    public function getCartArray($event_id = null)
    {
        $detailedCart = [];

            $user = auth()->user();
            $cart = json_decode($user->sample_set_cart ?? null, true);
            if (!is_null($cart)) {
                list($cart, $detailedCart) = $this->validateCartAndGetDetails($cart, $detailedCart);
                $user->sample_set_cart = json_encode($cart);
                $user->save();
            }

        if ($event_id) {
            $detailedCart = collect($detailedCart)->where('event_id', $event_id)->toArray();
        }
        return $detailedCart;
    }
    public function getCartArrayExceptEvent($event_id)
    {
        $cart = $this->getCartArray();
        $cart = collect($cart)->where('event_id', '!=', $event_id)->toArray();
        return $cart;
    }

    public function getFullCartItemsGroupedByEvent()
    {
        $cart = $this->getCartArray();
        $groupedItems = collect($cart)->groupBy('event_id');
        $events = [];
        foreach ($groupedItems as $eventId => $items) {
            $event = Event::findOrFail($eventId);
            $eventName = $event->name;
            $eventCategory = $event->category->name;
            $events[$eventId]['event_id'] = $eventId;
            $events[$eventId]['event_name'] = $eventName;
            $events[$eventId]['event_category'] = $eventCategory;
            $events[$eventId]['event_type'] = 'auction_sample_set';

            $events[$eventId]['items'] = $items;
        }
        return $events;
    }

    public function getCartView()
    {
        $cartItems = $this->getFullCartItemsGroupedByEvent();
        return response()->json(['itemCount' => count($cartItems), 'cartItems' => $cartItems]);
    }
    public function getCartItems($event_id)
    {
        $cart = $this->getCartArray($event_id);
        return response()->json(['cart' => $cart]);
    }

    #region checkout //todo refactor

    public function index($event_id)
    {
        $pageTitle = "Order";
        if (auth()->user()) {
            $user = auth()->user();
        } else {
            $user = null;
        }
        // $event_id= null;
        $sampleSetCarts = $this->getCartArray($event_id);
        $carts=null;



        $countries = Country::query()->get();
        return view('templates.basic.order', compact('pageTitle', 'user', 'carts', 'countries', 'event_id','sampleSetCarts'));
    }
    public function store(Request $request)
    {
        $validated = $this->validationOrder($request);
        if (auth()->user()) {
            $user = auth()->user();
            // update user details
            $user->firstname = $validated['customer_first_name'];
            $user->lastname = $validated['customer_last_name'];
            $user->email = $validated['customer_email'];
            $user->mobile = $validated['customer_phone'];
            $user->company_name = $validated['customer_company_name'];
            $user->company_website = $validated['customer_company_website'];
            $user->shipping_first_name = $validated['shipping_first_name'];
            $user->shipping_last_name = $validated['shipping_last_name'];
            $user->shipping_address_1 = $validated['shipping_address1'];
            $user->shipping_address_2 = $validated['shipping_address2'];
            $user->shipping_city = $validated['shipping_city'];
            $user->shipping_state = $validated['shipping_state'];
            $user->shipping_postcode = $validated['shipping_zip'];
            $user->shipping_country = $validated['shipping_country'];
            $user->shipping_phone = $validated['shipping_phone'];
            $user->ein_number = $validated['shipping_EIN_number'];
            $user->billing_first_name = $validated['billing_first_name'];
            $user->billing_last_name = $validated['billing_last_name'];
            $user->billing_address_1 = $validated['billing_address1'];
            $user->billing_address_2 = $validated['billing_address2'];
            $user->billing_city = $validated['billing_city'];
            $user->billing_state = $validated['billing_state'];
            $user->billing_postcode = $validated['billing_zip'];
            $user->billing_country = $validated['billing_country'];
            $user->billing_phone = $validated['billing_phone'];
            //            $user->shipping_method = $validated['shipping_method'];
            //            $user->payment_method = $validated['payment_method'];
            $user->save();
        } else {
            $user = null;
        }

        $sampleSets = $validated['sample_sets'];
        //        $order = $this->orderCreate($validated, $user,$productPrices);//todo remove order make here


        $shipping_method = $validated['shipping_method'];
        $payment_method = $validated['payment_method'];
        $shipping_country_id = $validated['shipping_country'];
        //        return redirect()->route('order.paymentIndex',
        //            compact('shipping_method', 'payment_method','productPrices', 'shipping_country_id'));

        $region = ShippingRegion::find($shipping_method);

        $price_arr = $this->calucate_price($sampleSets, $shipping_method, $shipping_country_id, $payment_method);
        $total_shipping_price = $price_arr['total_shipping_price'];
        $total_price = $price_arr['total_price_float'];
        $sub_total = $price_arr['sub_total'];
        $product_ids = $price_arr['product_ids'];
        $event_id = $price_arr['event_id'];
        $shipping_price_sample = $price_arr['total_shipping_price_sample'];
        $shipping_price_product = $price_arr['total_shipping_price_product'];
        $total_price_sample = $price_arr['total_price_sample_float'];
        $total_price_product = $price_arr['total_price_product_float'];



        //        $event_deposit_percentage = 0;
        //        if (!is_null($event_id)) {
        //            $event = Event::find($event_id);
        //            $event_deposit_percentage = $event->deposit;
        //        }

        $hold_amount = null; //not used anywhere
        //
        //        if ($payment_method == 'Wise') {
        //            $hold_amount = $this->hold_amount_calculate($event_deposit_percentage, $total_price);
        //        }

        if ($shipping_method == -1) {
            $region = new ShippingRegion();
            $region->shipping_method = 'I will manage my own shipping';
        }

        if ($shipping_method == -2) {
            $region = new ShippingRegion();
            $region->shipping_method = 'I am part of a bidding group';
        }

        if ($shipping_method == -3) {
            $region = new ShippingRegion();
            $region->shipping_method = 'Air Freight (Request Quote)';
        }

        //        $event_deposit_percentage = 0;
        //        if (!is_null($event_id)) {
        //            $event = Event::find($event_id);
        //            $event_deposit_percentage = $event->deposit;
        //
        //        }
        //        dd($total_price);
        $order = $this->orderCreate($validated, $user, $sampleSets, $total_shipping_price, $total_price,
            $shipping_price_sample,$shipping_price_product,$total_price_sample,$total_price_product,
            'pending', 'pending', 'usd');
        $order_id = $order->id;

        if ($payment_method == 'bank' || $payment_method == 'Wise') { //will be modified to paid by admin
            $this->sendEmails($order); //todo fix mail
            $this->emptyCart($event_id);
            return response()->json(['redirectUrl' => route('sample_set_order.paymentDone', compact('order_id'))]);
        }

        $allow_product_payment = config('app.allow_product_payment');
        $event = Event::find($event_id);
        $email_order = $event->sample_set_cart_config=='orders_by_email';

        if ($email_order){
            return response()->json(['redirectUrl' => route('sample_set_order.pay', compact('order_id'))]);
        }
        if($allow_product_payment){
            if($total_price > 0 ){
                return response()->json(['redirectUrl' => route('sample_set_order.paymentIndex', compact('order_id'))]);
            }else{
                return response()->json(['redirectUrl' => route('sample_set_order.pay', compact('order_id'))]);
            }
        }else{
            if($total_price_sample > 0){
                return response()->json(['redirectUrl' => route('sample_set_order.paymentIndex', compact('order_id'))]);
            }else{
                return response()->json(['redirectUrl' => route('sample_set_order.pay', compact('order_id'))]);
            }
        }


        // return response()->json(['redirectUrl' => route(
        //     'order.paymentIndex',
        //     compact('order_id')
        // )]);
    }
    public function paymentIndex($order_id)
    {

        $order = Order::query()->findOrFail($order_id);
        if ($order->payment_status == 'paid' || $order->payment_processed) {
            $pageTitle = 'Payment Done';
            return redirect()->route('sample_set_order.paymentDone', compact('order_id', 'pageTitle'));
        }
        $intent = $this->create_payment_intent($order->total_price, $order->currency);

        $total_price = showAmount($order->total_price, 2);

        $allow_product_payment = config('app.allow_product_payment');

        $pageTitle = 'Payment';
        return view(activeTemplate() . 'user.checkout.payment_commerce', compact(
            'intent',
            'pageTitle',
            'total_price',
            'order',
            'allow_product_payment'
        ));
    }
    public function paymentDone($order_id)
    {

        $order = Order::query()->findOrFail($order_id);

        $pageTitle = 'Payment Done';
        return view(activeTemplate() . 'user.checkout.done_commerce', compact(
            'pageTitle',
            'order'
        ));
    }
    public function pay(Request $request)
    {

        $validatedData = $request->validate([

            'order_id' => 'required',
        ]);

        $order_id = $validatedData['order_id'];
        $order = Order::query()->find($order_id);
        if ($order == null) {
            //todo log failed payment
            throw new \Exception('Order not found.');
        }
        if ($order->payment_status == 'paid' || $order->payment_processed) {
            $pageTitle = 'Payment Done';
            return redirect()->route('sample_set_order.paymentDone', compact('order_id', 'pageTitle'));
        }

        $event = $order->sampleSets()->first()->event??null;
        $cnts = $order->total_price;
        $email_order = $event->sample_set_cart_config=='orders_by_email';
        $allow_product_payment = config('app.allow_product_payment');
        $total_price_sample = $order->total_price_sample;
        $total_price_product = 0;


        if (!$allow_product_payment) {
            $cnts = $total_price_sample;
        }
        if($email_order){
            $cnts=0;
        }
        if ($cnts <= 0) {
            $pageTitle = $email_order?'Order Sent':'Payment Done';
            $order->payment_status = $email_order?'pending':'paid';
            $order->payment_processed = true;
            $order->paid_amount = $cnts;
            $order->status = 'pending';
            $order->order_type = $email_order?'email_only_order':( $allow_product_payment?"full_order":"sample_payment");
            $order->save();
            if ($event)
                $this->emptyCart($event->id);
            else {
                $this->emptyCart();
                Log::error('cannot empty cart: event not found');
                Integration::captureUnhandledException(new \Exception('warning: cannot empty cart after order placement: event not found'));
            }
            $this->sendEmails($order);
            return view(activeTemplate() . 'user.checkout.done_commerce',
                compact(
                    'order',
                    'pageTitle',
                    'total_price_sample',
                    'total_price_product',
                    'allow_product_payment'));
        }
        $validatedData = $request->validate([
            'strip_token' => 'required',
        ]);
        $strip_token = $validatedData['strip_token'];


        Stripe::setApiKey(config('app.STRIPE_API_KEY'));

        Stripe::setApiVersion("2020-03-02");

        $event_deposit_percentage = 0;
        //        if (!is_null($event_id)) { not used
        //            $event = Event::find($event_id);
        //            $event_deposit_percentage = $event->deposit;
        //        }

        $capture = true;
        //        if ($payment_method == 'Wise') { not used
        //            $cnts = $this->hold_amount_calculate($event_deposit_percentage, $cnts);
        //            $capture = false;
        //        }

        $total_payed = $cnts;
        $cnts = round($cnts, 2) * 100;
        $products_names = $order->sampleSets->map(function ($item) {
            return $item->event->name .' Sample Set';
        });
        $user = $order->user;
        //Checkout    Discription in stripe record should be User name, email, product name (s) (currenlty it is user id and proudct id)    Modify
        try {
            $charge = Charge::create(array(
                'card' => $strip_token,
                'currency' => $order->currency,
                'amount' => $cnts,
                'description' => 'user name:' . ($user->username ?? $order->fullName) . ' ,user email:' . ($user->email ?? $order->email) . ' ,product name:' . $products_names->implode(','),
                'capture' => $capture,
            ));

            if ($charge['status'] == 'succeeded') {
                $payment = new Payment();
                $payment->user_id = $user->id ?? null;
                $payment->payment_type = 'payment';
                $payment->amount = $total_payed;
                $payment->currency = $order->currency;
                $payment->description = ' receipt_url: ' . $charge['receipt_url'];
                $payment->charge_id = $charge['id'];
                $payment->order_id = $order->id;
                $payment->save();


                $order->payment_status = 'paid';
                $order->payment_processed = true;
                $order->status = 'pending';
                $order->paid_amount = $total_payed;
                $order->order_type = "sample_payment";
                $order->save();
                if ($event)
                    $this->emptyCart($event->id);
                else {
                    $this->emptyCart();
                    Log::error('cannot empty cart: event not found');
                    Integration::captureUnhandledException(new \Exception('warning: cannot empty cart after order placement: event not found'));
                }
                $this->sendEmails($order);
                $pageTitle = 'Payment Done';
                return view(activeTemplate() . 'user.checkout.done_commerce',
                    compact(
                        'order',
                        'pageTitle',
                        'total_price_sample',
                        'total_price_product',
                        'allow_product_payment'));
            }
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Integration::captureUnhandledException($e);
            $notify[] = ['error', $e->getMessage()];
        }

        return redirect()->back()->withNotify($notify);
    }

    public function validationOrder(Request $request)
    {

        return  $request->validate([
            'sample_sets' => 'required|array',
            'customer_first_name' => 'required|string|max:255',
            'customer_last_name' => 'required|string|max:255',
            'customer_email' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'customer_company_name' => 'required|string|max:255',
            'customer_company_website' => 'required|string|max:255',
            'shipping_is_business' => 'required|boolean',
            'shipping_first_name' => 'required|string|max:255',
            'shipping_last_name' => 'required|string|max:255',
            'shipping_address1' => 'required|string|max:255',
            'shipping_address2' => 'nullable|string|max:255',
            'shipping_city' => 'required|string|max:255',
            'shipping_state' => 'required|string|max:255',
            'shipping_zip' => 'required|string|max:20',
            'shipping_country' => 'required|string|max:255',
            'shipping_phone' => 'required|string|max:20',
            'shipping_EIN_number' => 'max:255',
            'is_lift_gate_delivery' => 'boolean',
            'is_inside_delivery' => 'boolean',
            'is_appointment_request' => 'boolean',
            'is_notify_request' => 'boolean',
            'delivery_date' => 'json',
            'special_delivery_instruction' => 'max:255',
            'billing_first_name' => 'required|string|max:255',
            'billing_last_name' => 'required|string|max:255',
            'billing_address1' => 'required|string|max:255',
            'billing_address2' => 'nullable|string|max:255',
            'billing_city' => 'required|string|max:255',
            'billing_state' => 'required|string|max:255',
            'billing_zip' => 'required|string|max:20',
            'billing_country' => 'required|string|max:255',
            'billing_phone' => 'nullable|string|max:20',
            'payment_method' => 'required|string|max:255',
            'shipping_region_id' => 'required',
            'shipping_method' => 'required',

        ]);
    }


    public function get_supported_shipping_method(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required',
            'country_id' => 'required',
        ]);
        $event_id = $validatedData['event_id'];
        $country_id = $validatedData['country_id'];
        $country = Country::find($country_id);
        if (is_null($country)) {
            return response()->json(['error' => 'faild 1']);
        }
        $region = DefaultRegions::find($country->Default_Region_ID);
        if (is_null($region)) {
            return response()->json(['error' => 'faild 2']);
        }
        $shipping_regions = ShippingRegion::where('event_id', $event_id)->where('event_type', Event::class)->where('region_name', $region->name)->get(['id', 'shipping_method']);
        if (count($shipping_regions) == 0) {
            $region = DefaultRegions::where('default', 1)->first();

            $shipping_regions = ShippingRegion::where('event_id', $event_id)->where('event_type', Event::class)->where('region_name', $region->name)->get(['id', 'shipping_method']);
            if (count($shipping_regions) != 0) {
                return response()->json(['methods' => $shipping_regions]);
            }
            return response()->json(['error' => 'The Area Is Not supported'], 404);
        }


        return response()->json(['methods' => $shipping_regions]);
    }

    public function get_shipping_price(Request $request)
    {
        $validatedData = $request->validate([
            'sample_sets' => 'required|array', //[price_id, quantity]
            'shipping_country_id' => 'nullable',
            'payment_method' => 'nullable',
            'shipping_region_id' => 'nullable',

        ]);
        $productPrices = $validatedData['sample_sets'];

        if (count($productPrices) > 0) {
            $price_arr = $this->calucate_price(
                $productPrices,
                $validatedData['shipping_region_id'] ?? null,
                $validatedData['shipping_country_id'] ?? null,
                $validatedData['payment_method'] ?? null
            );

            //            dd($total_shipping_price = $price_arr['total_shipping_price']);
            $total_shipping_price = $price_arr['total_shipping_price'];
            $total_price = $price_arr['total_price'];
            $sub_total = $price_arr['sub_total'];
        } else {
            $total_shipping_price = 0;
            $total_price = 0;
            $sub_total = 0;
        }

        return response()->json(['total_shipping_price' => $total_shipping_price, 'total_price' => $total_price, 'sub_total' => $sub_total]);
    }

    public function calucate_price($productPrices, $region_id = null, $shipping_country_id = null, $payment_method = null)
    {
        $total_shipping_weight = 0;

        $event = '';
        $product_ids = [];
        $total_price = 0;
        //       ==============================
        $total_price_sample = 0; // price of all samples without shipping
        $total_price_product = 0; // price of all products without shipping
        $shipping_weight_sample = 0;
        $shipping_weight_product = 0;
        foreach ($productPrices as $productPrice) {
            $sampleSet = SampleSet::find($productPrice['id']) ?? null;

            $event = $sampleSet->event;
            // Log::info('offer', ['price' => $Price]);

            // todo subtract quantity from offer

            // calculate total price
//            if ($Price->size->is_sample) {
                $total_price_sample += (($sampleSet->price) * $productPrice['quantity']);
                $shipping_weight_sample += ($sampleSet->total_package_weight_Lb * $productPrice['quantity']);
//            } else {
//                $total_price_product +=  (($Price->product_total_price) * $productPrice['quantity']);
//                $shipping_weight_product += ($size->weight_LB * $productPrice['quantity']);
//            }

            // $total_price += (($Price->product_total_price) * $productPrice['quantity']);
            // $total_shipping_weight += $size->weight_LB * $productPrice['quantity'];

        }
        //        ====================

        $total_shipping_price = 0;
        $total_shipping_price_product = 0;
        $total_shipping_price_sample = 0;
        // $total_price = $total_price_sample + $total_price_product;


        $ranges = [];
        $event_id = $event->id;
        $event_type = get_class($event);

        // $total_shipping_price = $this->get_total_shipping_price($region_id, $event_id, $event_type, $total_shipping_weight);
        // total shipping price
//        $total_shipping_price_product = $this->get_total_shipping_price($region_id, $event_id, $event_type, $shipping_weight_product);
            $total_shipping_price_sample = $this->get_total_shipping_price($region_id, $event_id, $event_type, $shipping_weight_sample);
        // price with sum shipping price
        $total_price_sample += $total_shipping_price_sample;
//        $total_price_product += $total_shipping_price_product;



        if ($shipping_country_id && $payment_method) {
            //            $event_deposit = Event::where('id', $event_id)->get(['deposit'])->first()->deposit;

            // $payment_handel_fees = $this->get_payment_handel_fees($total_price, $event,
            //     $shipping_country_id, $payment_method);

            // $total_shipping_price = $total_shipping_price + $payment_handel_fees;
            // $total_price = $total_price + $payment_handel_fees;
            // payment_handel_fees for product and sample
//            $payment_handel_fees_product = $this->get_payment_handel_fees(
//                $total_price_product,
//                $event,
//                $shipping_country_id,
//                $payment_method
//            );
            $ppayment_handel_fees_sample = $this->get_payment_handel_fees(
                $total_price_sample,
                $event,
                $shipping_country_id,
                $payment_method
            );
            // sum payment_handel_fees with total_shipping_price
//            $total_shipping_price_product += $payment_handel_fees_product;
            $total_shipping_price_sample += $ppayment_handel_fees_sample;
            // sum total price with payment_handel_fees
//            $total_price_product += $payment_handel_fees_product;
            $total_price_sample += $ppayment_handel_fees_sample;
        }
        // sum shipping price
        $total_shipping_price = $total_shipping_price_sample;
        // sum total price with shipping price
        $total_price = $total_price_sample ;
        if ($total_shipping_price === null && ($region_id != -1 && $region_id != -2 && $region_id != -3)) {//free shipping mods
            $total_shipping_price_mess = 'Weight is not supported';
            return [
                'total_price' => showAmount($total_price, 2),
                'total_price_float' => $total_price,
                'total_price_sample' => showAmount($total_price_sample, 2),
                'total_price_product' => 0,
                'total_shipping_price' => $total_shipping_price_mess,
                'total_shipping_price_sample' => $total_shipping_price_sample,
                'total_shipping_price_product' => 0,
                'sub_total' => round($total_price - 0, 2),//free shipping mods
                'product_ids' => $product_ids,
                'event_id' => $event_id
            ];
        }

        return [
            'total_price' => showAmount($total_price, 2),
            'total_price_float' => $total_price,
            'total_price_sample' => showAmount($total_price_sample, 2),
            'total_price_product' => showAmount($total_price_product, 2),
            'total_price_sample_float' => $total_price_sample,
            'total_price_product_float' => $total_price_product,
            'total_shipping_price_sample' => round($total_shipping_price_sample, 2),
            'total_shipping_price_product' => round($total_shipping_price_product, 2),
            'total_shipping_price' => round($total_shipping_price, 2),
            'sub_total' => round($total_price - $total_shipping_price, 2),
            'product_ids' => $product_ids,
            'event_id' => $event_id

        ];
    }

    private function create_payment_intent($total_price, $currency)
    {
        $total_price = round($total_price, 2) * 100;;
        Stripe::setApiKey(config('app.STRIPE_API_KEY'));
        $payment_intent = PaymentIntent::create([
            'amount' => $total_price,
            'currency' => $currency,
            'metadata' => [
                'integration_check' => 'accept_a_   payment',
            ],
        ]);

        return $payment_intent;
    }
    public function hold_amount_calculate($event_deposit_percentage, $total_price)
    {
        $hold_amount = 0;
        if ($event_deposit_percentage > 0) {
            $hold_amount = ($event_deposit_percentage / 100) * $total_price;
        }
        return round($hold_amount, 2);
    }
    public function get_payment_handel_fees($price, $event, $shipping_country_id, $payment_method)
    {
        if ($payment_method == 'bank') {
            return 0;
        }

        if ($payment_method == 'Wise') {
            $fees_price = (($price * 100) / (100 - 1)) - $price;
            return round($fees_price, 2);
        }
        $event_id = $event->id;
        $event_type = get_class($event);

        $fees = Fee::where('event_id', $event_id)->where('event_type', $event_type)->where('country_id', $shipping_country_id)->first();
        if (is_null($fees)) {
            $fees = Fee::where('event_id', $event_id)->where('event_type', $event_type)->where('country_id', 999)->first();
        }

        if ($fees) {
            $fees_price = (($price * 100) / (100 - $fees->fee_value)) - $price;
            return round($fees_price, 2);
        }

        return 0;
    }

    /**
     * @param array $validated
     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
     * @param Request $request
     * @param $productPrices
     * @return mixed
     */
    public function orderCreate(array $validated, ?\Illuminate\Contracts\Auth\Authenticatable $user, $productPrices, $total_shipping_price, $total_price,
                                      $shipping_price_sample,$shipping_price_product,$total_price_sample,$total_price_product,
                                      $status, $payment_status, $currency)
    {
        $order = Order::make($validated);
        if ($user)
            $order->user_id = $user->id;

        //        dd($request->shipping_region_id);
        $order->total_price = $total_price;
        $order->shipping_price = $total_shipping_price;
        $order->total_price_sample = $total_price_sample;
        $order->total_price_product = $total_price_product;
        $order->shipping_price_sample = $shipping_price_sample;
        $order->shipping_price_product = $shipping_price_product;
        //        $order->shipping_country_id = $shipping_country_id;//todo add to order in db
        $order->status = $status;
        $order->payment_status = $payment_status;
        $order->currency = $currency;
        $order->save();
        foreach ($productPrices as $productPrice) {
            $order->sampleSets()->attach($productPrice['id'], ['quantity' => $productPrice['quantity']]);
        }
        return $order;
    }

    public function sendEmails($order): void
    {
        //client email
        setMailConfig();
        // Log::info($order->products);
        SendCustomEmail::dispatch($order->customer_email, new CheckoutMail($order, true))->onConnection(config('app.email_job_queue_connection'));
        foreach($order->sampleSets as $sampleSet){
            $shortcodes=[
                'order_number'=>$order->id,
                'event_name'=>$sampleSet->event->name,
                'event_type'=>'Auction',
                'order_type'=>$sampleSet->event->sample_set_cart_config =="payment_process" ? "cart with payment processing for samples":"Cart with order Emails for all products",
                'order_date'=>$order->created_at,
                'product'=>$sampleSet->event->name .' Sample Set',
                'weight'=>round($sampleSet->total_package_weight_Lb,2),
                'quantity'=>$sampleSet->pivot->quantity,
                'price'=>round($sampleSet->price,2),
                'currency'=>$order->currency,
                'customer_name'=>$order->full_name,
                'company_name'=>$order->customer_company_name,
                'payment_method'=>$order->payment_method == "bank"  ? "Bank Transfer" : ($order->payment_method == "Stripe" ? "Credit Card" : "Email Order"),
                'payment_status'=>$order->payment_status == "paid"  ? "Paid":"Pending Payment",
                'grand_total'=>round($order->total_price,2),
                'subtotal'=>round($order->total_price-$order->shipping_price,2),//:round($order->paid_amount-$order->shipping_price,2),
                'shipping_value'=>round($order->shipping_price,2),
                'shipping_details'=>"Name: ".$order->shipping_first_name." ".$order->shipping_last_name."<br>Country,City: ".$order->country_shipping->Name.",".$order->shipping_city."<br>Address: ".$order->shipping_address1."<br>Zip Code: ".$order->shipping_zip."<br>Phone: ".$order->shipping_phone
//                    ."<br>Preferred receiving day and time:<br>".
//            view('templates.basic.partials.delivery_time_ranges',['delivery_time_ranges'=>$order->delivery_date])->render() //todo enable these and fix saving to exclude the non-checked items
                ,
                'billing_details'=>"Name: ".$order->billing_first_name." ".$order->billing_last_name."<br>Country,City: ".$order->country_billing->Name.",".$order->billing_city."<br>Address: ".$order->billing_address1."<br>Zip Code: ".$order->billing_zip."<br>Phone: ".$order->billing_phone,
            ];
            $user = $order->user;
            if ($user) {
                sendEmail($user,'Auction_Order_Confirmation',$shortcodes);
            }
            else {
                sendEmail_v2($order->customer_email, 'Auction_Order_Confirmation', $shortcodes);
            }
        }
        //seller emails
        $event = $order->sampleSets()->first()->event ?? null;
        if ($event) {
            $emails = explode(',', $event->emails);
            foreach ($emails as $email) {
                SendCustomEmail::dispatch($email, new CheckoutMail($order, false))->onConnection(config('app.email_job_queue_connection'));
            }
        } else {
            Log::error("cannot send email: event of order no:$order->id not found");
            Integration::captureUnhandledException(new \Exception("warning: cannot send email to sellers after order with no:$order->id  placement: event not found"));
        }
    }
    /**
     * @param $region_id
     * @param $event_id
     * @param string $event_type
     * @param $total_shipping_weight
     * @return float|int
     */
    public function get_total_shipping_price($region_id, $event_id, $event_type, $total_shipping_weight)
    {
        $ship_price = null;//free shipping mods
        if (!is_null($region_id)) {
            $region = ShippingRegion::find($region_id);
        } else if ($region_id == -1 || $region_id == -2) {
            $region = null;
            $ship_price=0;//free shipping mods
        } else {
            $region = ShippingRegion::where('event_id', $event_id)->where('event_type', $event_type)->first();
        }

        if (!is_null($region)) {
            $ranges = ShippingRanges::where('region_id', $region->id)->get();
            foreach ($ranges as $range) {

                if ($range->from <= $total_shipping_weight && $range->up_to >= $total_shipping_weight) {
                    $ship_price = $range->cost;
                    break;
                }
            }
        }

        $total_shipping_price = $ship_price?$ship_price * $total_shipping_weight:null;//free shipping mods
        return $total_shipping_price;
    }

    public function getShippingAndHandlingFees()
    {
        $request = request();
        $user = auth()->user();
        $validatedData = $request->validate([
            'event_id' => 'required|exists:events,id',
            'total_weight' => 'nullable|numeric|min:0',
            'shipping_method' => 'nullable',
            'payment_method' => 'nullable',
            'sub_total' => 'required|numeric|min:0',

        ]);
        $event_id = $validatedData['event_id'];
        $total_shipping_weight = $validatedData['total_weight'] ?? 0;
        $shipping_method = $validatedData['shipping_method'] ?? null;
        $payment_method = $validatedData['payment_method'] ?? null;
        $sub_total = $validatedData['sub_total'];
        $event = Event::find($event_id);
        $shipping_country_id = $user->country->id ?? null;
        if ((!$shipping_method || $shipping_method < 0) && !$shipping_country_id) {
            $shipping_regions = $event->shippingRegions()->select('id')->get();
            $shipping_region = null;
            foreach ($shipping_regions as $region) {
                if (ShippingRegionCountry::where('region_id', $region->id)->where('country', 999)->exists()) {
                    $shipping_region = $region;
                    break;
                }
            }
            if (!$shipping_region) {
                return response()->json(['message' => __('Cannot detect your location, please fill the region information in the checkout page below.'), 'shipping_and_handling_fees' => 0, 'total_price' => $sub_total]);
            }
            $shipping_method = $shipping_region->id;
            $shipping_country_id = 999;
        }
        $total_shipping_price = $this->get_total_shipping_price($shipping_method, $event_id, Event::class, $total_shipping_weight);
        $total_price = $sub_total + ($total_shipping_price??0);//free shipping mods
        $payment_handel_fees = $this->get_payment_handel_fees($total_price, $event, $shipping_country_id, $payment_method);
        $total_price = $total_price + $payment_handel_fees;
        return response()->json(['shipping_and_handling_fees' => ($total_shipping_price??0) + $payment_handel_fees, 'total_price' => $total_price]);//free shipping mods
    }

    #endregion
}
