<?php

namespace App\Http\Controllers;

//use App\Jobs\SendCustomEmail;
use App\Models\Country;
use App\Models\DefaultRegions;
use App\Models\Event;
use App\Models\Fee;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Product;
use App\Models\ShippingRanges;
use App\Models\ShippingRegion;
use App\Models\Winner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Stripe\Charge;
use Stripe\PaymentIntent;
use Stripe\Stripe;
use Stripe\Token;

class CheckOutController extends Controller
{
    const PENDENIN = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';


    public function index($event_id)
    {

        $event_product = Event::find($event_id)->products->pluck('id');

        $pageTitle = __('Check Out');
        $emptyMessage = __('No winning history found');
        $winningHistories = Winner::where('user_id', auth()->id())->where('status', CheckOutController::PENDENIN)
            ->where('order_id', null)
            ->whereIn("product_id", $event_product)
            ->with('user', 'product', 'bid')->latest()->paginate(500);

        if (count($winningHistories->toArray()['data']) > 0) {
            $price_arr = $this->calucate_price($winningHistories->toArray()['data']);
            $total_shipping_price = 0;
            $total_price = 0;
            $sub_total = $price_arr['sub_total'];
            $product_ids = $price_arr['product_ids'];
            $event_ids = $price_arr['event_ids'];

        } else {
            $pageTitle = __('No winning history found');
            $emptyMessage = __('No winning history found');
            $total_shipping_price = 0;
            $total_price = 0;
            $sub_total = 0;
            $product_ids = [];
            $event_ids = [];
        }
        $countries = Country::all();

        $user = Auth::user();

        //TODO: get currency
        return view(activeTemplate() . 'user.checkout.cart', compact('pageTitle',
            'emptyMessage', 'winningHistories', 'sub_total',
            'total_shipping_price', 'total_price', 'user', 'countries', 'product_ids', 'event_ids'));
    }
//
//
//    public function order($payment = null)
//    {
//
//        $winningHistories = Winner::where('user_id', auth()->id())->where('status', CheckOutController::PENDENIN)
//            ->with('user', 'product', 'bid')->latest()->paginate(500);
//        //dd($winningHistories->toArray());
//
//        $price_arr = $this->calucate_price($winningHistories->toArray()['data']);
//        $total_shipping_price = $price_arr['total_shipping_price'];
//        $total_price = $price_arr['total_price'];
//
//
//    }

    public function submitCart($event_id, Request $request)
    {
        //checkout index

        $validatedData = $request->validate([
            'use_for_billing_info' => 'required',
            'shipping_first_name' => 'required',
            'shipping_last_name' => 'required',
            'shipping_address_1' => 'required',
            'shipping_address_2' => 'nullable',
            'shipping_city' => 'required',
            'shipping_state' => 'required',
            'shipping_postcode' => 'required',
            'shipping_country' => 'required',
            'shipping_phone' => 'required',

            //required_if:use_for_billing_info,==,0
            'billing_first_name' => 'required_if:use_for_billing_info,==,0',
            'billing_last_name' => 'required_if:use_for_billing_info,==,0',
            'billing_address_1' => 'required_if:use_for_billing_info,==,0',
            'billing_address_2' => 'nullable',
            'billing_city' => 'required_if:use_for_billing_info,==,0',
            'billing_state' => 'required_if:use_for_billing_info,==,0',
            'billing_postcode' => 'required_if:use_for_billing_info,==,0',
            'billing_country' => 'required_if:use_for_billing_info,==,0',
            'billing_phone' => 'required_if:use_for_billing_info,==,0',


            'payment_method' => 'required',
            'shipping_method' => 'required',

        ]);

        $user = Auth::user();
        $user->shipping_first_name = $validatedData['shipping_first_name'];
        $user->shipping_last_name = $validatedData['shipping_last_name'];
        $user->shipping_address_1 = $validatedData['shipping_address_1'];
        $user->shipping_address_2 = $validatedData['shipping_address_2'];
        $user->shipping_city = $validatedData['shipping_city'];
        $user->shipping_state = $validatedData['shipping_state'];
        $user->shipping_postcode = $validatedData['shipping_postcode'];
        $user->shipping_country = $validatedData['shipping_country'];
        $user->shipping_phone = $validatedData['shipping_phone'];

        if ($validatedData['use_for_billing_info'] == 0) {
            $user->billing_first_name = $validatedData['billing_first_name'];
            $user->billing_last_name = $validatedData['billing_last_name'];
            $user->billing_address_1 = $validatedData['billing_address_1'];
            $user->billing_address_2 = $validatedData['billing_address_2'];
            $user->billing_city = $validatedData['billing_city'];
            $user->billing_state = $validatedData['billing_state'];
            $user->billing_postcode = $validatedData['billing_postcode'];
            $user->billing_country = $validatedData['billing_country'];
            $user->billing_phone = $validatedData['billing_phone'];
        } else {
            $user->billing_first_name = $user->shipping_first_name;
            $user->billing_last_name = $user->shipping_last_name;
            $user->billing_address_1 = $user->shipping_address_1;
            $user->billing_address_2 = $user->shipping_address_2;
            $user->billing_city = $user->shipping_city;
            $user->billing_state = $user->shipping_state;
            $user->billing_postcode = $user->shipping_postcode;
            $user->billing_country = $user->shipping_country;
            $user->billing_phone = $user->shipping_phone;
        }

        $user->describes_business = $request->describes_business;//

        $user->pounds_green_coffee = $request->pounds_green_coffee;//
        $user->hosting_cupping = $request->hosting_cupping;//
        $user->billing_company_name = $request->billing_company_name;//
        $user->ein_number = $request->ein_number;//
        $user->delivery = $request->delivery;//
        $user->preferred_receiving_day = json_encode($request->input('preferred_receiving_day'));//
        $user->preferred_receiving_times = json_encode($request->input('preferred_receiving_times'));//
        $user->other_special_delivery = $request->other_special_delivery;
        $user->exclusive_offers = $request->exclusive_offers;
        $user->save();

        return redirect()->route('user.checkout.payment',
            ['shipping_method' => $request->input('shipping_method'), 'payment_method' => $request->input('payment_method'), 'event_id' => $event_id]);

    }


    public function donePage()
    {
        $pageTitle = __('Order received');
        return view(activeTemplate() . 'user.checkout.done', compact('pageTitle'));
    }

    public function paymentIndex($event_id, $shipping_method, $payment_method)
    {
        $event_product = Event::find($event_id)->products->pluck('id');

        $pageTitle = 'Payment';

        $user = Auth::user();

        $winningHistories = Winner::where('user_id', auth()->id())->where('status', CheckOutController::PENDENIN)
            ->whereIn("product_id", $event_product)
            ->where('order_id', null)
            ->with('user', 'product', 'bid')
            ->latest()->get();
        //dd($winningHistories->toArray());

        $region = ShippingRegion::find($shipping_method);

        if (count($winningHistories->toArray()) > 0) {
            $price_arr = $this->calucate_price($winningHistories->toArray(), $shipping_method,$user->shipping_country, $payment_method);
            $total_shipping_price = $price_arr['total_shipping_price'];
            $total_price = $price_arr['total_price_float'];
            $sub_total = $price_arr['sub_total'];
            $product_ids = $price_arr['product_ids'];
            $event_ids = $price_arr['event_ids'];
            $event_id = $price_arr['event_id'];

        } else {
            $pageTitle = __('No winning history found');
            $emptyMessage = __('No winning history found');
            $total_shipping_price = 0;
            $total_price = 0;
            $sub_total = 0;
            $product_ids = [];
            $event_ids = [];
            $event_id = null;
        }
        $event_deposit_percentage = 0;
        if (!is_null($event_id)) {
            $event = Event::find($event_id);
            $event_deposit_percentage = $event->deposit;
        }

        $hold_amount = null;

        if ($payment_method == 'Wise') {
            $hold_amount = $this->hold_amount_calculate($event_deposit_percentage, $total_price);
        }

        if ($shipping_method == -1) {
            $region = new ShippingRegion();
            $region->shipping_method = __('I will manage my own shipping');
        }

        if ($shipping_method == -2) {
            $region = new ShippingRegion();
            $region->shipping_method = __('I am part of a bidding group');
        }

        if ($shipping_method == -3) {
            $region = new ShippingRegion();
            $region->shipping_method = __('Air Freight (Request Quote)');
        }

        $event_deposit_percentage = 0;
        if (!is_null($event_id)) {
            $event = Event::find($event_id);
            $event_deposit_percentage = $event->deposit;

        }

        if ($payment_method == 'bank' || $payment_method == 'Wise') {
            return redirect()->route('user.checkout.payment.process_without_pay', ['event_id' => $event_id,
                'shipping_method' => $shipping_method, 'payment_method' => $payment_method]);
        }
        $intent = $this->create_payment_intent($total_price, 'usd');

        $total_price = showAmount($total_price, 2);
        if (!is_null($hold_amount)) {
            $hold_amount = showAmount($hold_amount, 2);
        }

        return view(activeTemplate() . 'user.checkout.payment', compact('intent', 'sub_total', 'payment_method', 'hold_amount', 'event_id',
            'winningHistories', 'event_deposit_percentage', 'pageTitle', 'shipping_method', 'user', 'product_ids', 'total_price', 'total_shipping_price', 'region'));
    }


    public function pay($event_id, $shipping_method, $payment_method, Request $request)
    {
//        $validatedData = $request->validate([
//            '_token' => 'required',
//        ]);
//
//
//        $order = Order::find($order_id);
//
//        $data = StripeHoldGateway::pay($validatedData['_token'], $order->total_price, 'usd', $order->id);
//
//        dd($data);
//        return "Done";

        $user = Auth::user();

        $event_product = Event::find($event_id)->products->pluck('id');

        $winningHistories = Winner::where('user_id', auth()->id())
            ->whereIn("product_id", $event_product)
            ->where('status', CheckOutController::PENDENIN)->where('order_id', null)
            ->with('user', 'product', 'bid')->latest()->get();
        //dd($winningHistories->toArray());

        $price_arr = $this->calucate_price($winningHistories, $shipping_method,$user->shipping_country, $payment_method);
        $total_shipping_price = $price_arr['total_shipping_price'];
        $total_price = $price_arr['total_price_float'];
        $product_ids = $price_arr['product_ids'];
        $event_id = $price_arr['event_id'];

        $products_names = Product::whereIn('id', $product_ids)->pluck('name')->toArray();
        $products_names = implode(', ', $products_names);


        $this->validate($request, [
            'strip_token' => 'required',
        ]);

        $strip_token = $request->strip_token;

        $cnts = $total_price;

        // $stripeAcc = json_decode($deposit->gatewayCurrency()->gateway_parameter);


        Stripe::setApiKey(config('app.STRIPE_API_KEY'));

        Stripe::setApiVersion("2020-03-02");

        $event_deposit_percentage = 0;
        if (!is_null($event_id)) {
            $event = Event::find($event_id);
            $event_deposit_percentage = $event->deposit;
        }

        $capture = true;
        if ($payment_method == 'Wise') {
            $cnts = $this->hold_amount_calculate($event_deposit_percentage, $cnts);
            $capture = false;
        }

        $total_payed = $cnts;
        $cnts = round($cnts, 2) * 100;;
//dd($cnts);
        //Checkout    Discription in stripe record should be User name, email, product name (s) (currenlty it is user id and proudct id)    Modify
        try {
            try {
                $charge = Charge::create(array(
                    'card' => $strip_token,
                    'currency' => 'usd',
                    'amount' => $cnts,
                    'description' => 'user name:' . $user->username . ' ,user email:' . $user->email . ' ,product name:' . $products_names,
                    'capture' => $capture,
                ));

                if ($charge['status'] == 'succeeded') {
//                    //PaymentController::userDataUpdate($deposit->trx);
//                    $notify[] = ['success', 'Payment captured successfully.'];
//                    return redirect()->route(gatewayRedirectUrl(true))->withNotify($notify);
                    $payment = new Payment();
                    $payment->user_id = auth()->id();
                    $payment->payment_type = $payment_method == "Wise" ? 'hold' : 'payment';
                    $payment->amount = $total_payed;
                    $payment->currency = 'usd';
                    $payment->description = ' receipt_url: ' . $charge['receipt_url'];
                    $payment->charge_id = $charge['id'];
                    $payment->save();


                    $order = new Order();
                    $order->user_id = auth()->id();
                    $order->product_ids = serialize($product_ids);
                    $order->payment_method = $payment_method;
                    $order->payment_status = $payment_method == "Wise" ? 'pending' : 'paid';
                    $order->payment_processed = true;
                    $order->paid_amount= $total_payed;
                    $order->shipping_price = $total_shipping_price;
                    $order->total_price = $total_price;
                    $order->shipping_method = $shipping_method;
                    $order->currency = 'usd';
                    $order->status = 'pending';
                    $order->save();
//                    setMailConfig();
//                    $this->sendEmails($order, $user);
                    $order_id = $order->id;
                    foreach ($winningHistories as $winningHistory) {
                        $winningHistory->order_id = $order_id;
                        $winningHistory->save();
                    }


                    return redirect()->route('user.checkout.done');

                }
            } catch (\Exception $e) {
                $notify[] = ['error', $e->getMessage()];
            }
        } catch (\Exception $e) {
            $notify[] = ['error', $e->getMessage()];
        }

        return redirect()->back()->withNotify($notify);
    }

    public function process_without_pay($event_id, $shipping_method, $payment_method, Request $request)
    {

        $user = Auth::user();

        $event_product = Event::find($event_id)->products->pluck('id');

        $winningHistories = Winner::where('user_id', auth()->id())
            ->whereIn("product_id", $event_product)
            ->where('status', CheckOutController::PENDENIN)->where('order_id', null)
            ->with('user', 'product', 'bid')->latest()->get();
        //dd($winningHistories->toArray());

        $price_arr = $this->calucate_price($winningHistories, $shipping_method);
        $total_shipping_price = $price_arr['total_shipping_price'];
        $total_price = $price_arr['total_price_float'];
        $product_ids = $price_arr['product_ids'];
        $event_id = $price_arr['event_id'];

        $products_names = Product::whereIn('id', $product_ids)->pluck('name')->toArray();
        $products_names = implode(', ', $products_names);


        $order = new Order();
        $order->user_id = auth()->id();
        $order->product_ids = serialize($product_ids);
        $order->payment_method = $payment_method;
        $order->payment_status = 'pending';
        $order->payment_processed = true;
        $order->shipping_price = $total_shipping_price;
        $order->total_price = $total_price;
        $order->shipping_method = $shipping_method;
        $order->currency = 'usd';
        $order->status = 'pending';
        $order->save();
//        setMailConfig();
//        $this->sendEmails($order, $user);

        $order_id = $order->id;
        foreach ($winningHistories as $winningHistory) {
            $winningHistory->order_id = $order_id;
            $winningHistory->save();
        }


        return redirect()->route('user.checkout.done');
    }


    public function hold_amount_calculate($event_deposit_percentage, $total_price)
    {
        $hold_amount = 0;
        if ($event_deposit_percentage > 0) {
            $hold_amount = ($event_deposit_percentage / 100) * $total_price;
        }
        return round($hold_amount, 2);
    }


    public function confirmation()
    {
        $pageTitle = __('Request Confirmation');

        $user = Auth::user();

        return view(activeTemplate() . 'user.checkout.conf', compact('pageTitle', 'user'));
    }

    public function calucate_price($all_data, $region_id = null, $shipping_country_id = null, $payment_method = null)
    {
        $total_shipping_weight = 0;

        $event_ids = [];

        $product_ids = [];
        $total_price = 0;
        foreach ($all_data as $data) {
            if (isset($data['product'])) {
                $w = 1;
                $event_ids[$data['product']['event_id']] = $data['product']['event_id'];
                $product_ids[] = $data['product']['id'];
                if (isset($data['product']['specification'])) {
                    foreach ($data['product']['specification'] as $s) {
                        if ($s['name'] == "Weight") {
                            $w = $s['value'];
                        }
                    }
                }

                $total_shipping_weight += $w;
                $total_price += $data['bid']['amount'] * $w;

            }
        }

        $total_shipping_price = 0;

        $ship_price = 0;
        $ranges = [];
        if (count($event_ids) == 1) {

            $event_id = null;
            foreach ($event_ids as $key => $value) {
                $event_id = $key;
            }
            //$event = Event::find($event_id);

            if (!is_null($region_id)) {
                $region = ShippingRegion::find($region_id);
            } else if ($region_id == -1 || $region_id == -2) {
                $region = null;
            } else {
                $region = ShippingRegion::where('event_id', $event_id)->where('event_type', Event::class)->first();
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

        }
        $total_shipping_price = $ship_price * $total_shipping_weight;
        $total_price += $total_shipping_price;

        if ($shipping_country_id && $payment_method) {
//            $event_deposit = Event::where('id', $event_id)->get(['deposit'])->first()->deposit; not used todo check why

            $payment_handel_fees = $this->get_payment_handel_fees($total_price, $event_id,
                $shipping_country_id, $payment_method);

            $total_shipping_price = $total_shipping_price + $payment_handel_fees;
            $total_price = $total_price + $payment_handel_fees;
        }

        if ($total_shipping_price == 0 && ($region_id != -1 && $region_id != -2 && $region_id != -3)) {
            $total_shipping_price_mess = 'Weight is not supported';
            return [
                'total_price' => showAmount($total_price, 2),
                'total_price_float' => $total_price,
                'total_shipping_price' => $total_shipping_price_mess,
                'sub_total' => round($total_price - $total_shipping_price, 2),
                'product_ids' => $product_ids,
                'event_ids' => $event_ids,
                'event_id' => $event_id
            ];
        }

        return [
            'total_price' => showAmount($total_price, 2),
            'total_price_float' => $total_price,
            'total_shipping_price' => round($total_shipping_price, 2),
            'sub_total' => round($total_price - $total_shipping_price, 2),
            'product_ids' => $product_ids,
            'event_ids' => $event_ids,
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
                'integration_check' => 'accept_a_payment',
            ],
        ]);

        return $payment_intent;
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
        $shipping_regions = ShippingRegion::where('event_id', $event_id)->where(function ($query) use ($region) {
            $query->where('region_name', $region->name)
                ->orWhere('region_name', 'Rest of the World');
        })->where('event_type', Event::class)->get(['id', 'shipping_method']);
        if (count($shipping_regions) == 0) {
            $region = DefaultRegions::where('default', 1)->first();

            $shipping_regions = ShippingRegion::where('event_id', $event_id)->where('region_name', $region->name)->where('event_type', Event::class)->get(['id', 'shipping_method']);
            if (count($shipping_regions) != 0) {
                return response()->json(['methods' => $shipping_regions]);
            }
            return response()->json(['error' => __('The Area Is Not supported')], 404);
        }


        return response()->json(['methods' => $shipping_regions]);
    }

    public function get_shipping_price(Request $request)
    {
        $validatedData = $request->validate([
            'event_id' => 'required',
            'shipping_region_id' => 'nullable',
            'shipping_country_id' => 'nullable',
            'payment_method' => 'nullable',
        ]);

        $event_product = Event::find($validatedData['event_id'])->products->pluck('id');


        $winningHistories = Winner::where('user_id', auth()->id())->where('status', CheckOutController::PENDENIN)
            ->where('order_id', null)
            ->whereIn("product_id", $event_product)
            ->with('user', 'product', 'bid')->latest()->paginate(500);

        if (count($winningHistories->toArray()['data']) > 0) {
            $price_arr = $this->calucate_price($winningHistories->toArray()['data'], $validatedData['shipping_region_id'] ?? null
                , $validatedData['shipping_country_id'] ?? null, $validatedData['payment_method'] ?? null);
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

    /**
     * @param $price
     * @param $event_id
     * @param $shipping_country_id
     * @param $payment_method
     * @param $deposit_percentage
     * @return float|int
     */
    public function get_payment_handel_fees($price, $event_id, $shipping_country_id, $payment_method)
    {
        if ($payment_method == 'bank') {
            return 0;
        }

        if ($payment_method == 'Wise') {
            $fees_price = (($price * 100) / (100 - 1)) - $price;
            return round($fees_price, 2);
        }


        $fees = Fee::where('event_id', $event_id)->where('event_type',Event::class)->where('country_id', $shipping_country_id)->first();
        if (is_null($fees)) {
            $fees = Fee::where('event_id', $event_id)->where('event_type',Event::class)->where('country_id', 999)->first();
        }

        if ($fees) {
            $fees_price = (($price * 100) / (100 - $fees->fee_value)) - $price;
            return round($fees_price, 2);
        }

        return 0;
    }
//
//    /**
//     * @param Order $order
//     * @param \Illuminate\Contracts\Auth\Authenticatable|null $user
//     * @return void
//     */
//    public function sendEmails(Order $order, ?\Illuminate\Contracts\Auth\Authenticatable $user): void
//    {
//        foreach ($order->products as $product) {
//            $shortcodes = [
//                'order_number' => $order->id,
//                'event_name' => $product->event->name,
//                'event_type' => "Auction",
//                'order_type' => "normal",//$product->event->sample_set_cart_config =="payment_process" ? "cart with payment processing for samples":"Cart with order Emails for all products",
//                'order_date' => $order->created_at,
//                'product' => $product->name,
//                'weight' => round($product->weight, 2),
//                'quantity' => 1,
//                'price' => round($product->price, 2),
//                'currency' => $order->currency,
//                'customer_name' => $user->full_name,
//                'company_name' => $user->company_name,
//                'payment_method' => $order->payment_method == "bank" ? "Bank Transfer" : ($order->payment_method == "Stripe" ? "Credit Card" : "Email Order"),
//                'payment_status' => $order->payment_status == "paid" ? "Paid" : "Pending Payment",
//                'grand_total' => round($order->total_price, 2),
//                'subtotal' => round($order->total_price - $order->shipping_price, 2),//:round($order->paid_amount-$order->shipping_price,2),
//                'shipping_value' => round($order->shipping_price, 2),
//                'shipping_details' => "Name: " . $user->shipping_first_name . " " . $user->shipping_last_name . "<br>Country,City: " . $user->country_shipping->Name . "," . $user->shipping_city . "<br>Address: " . $user->shipping_address_1 . "<br>Zip Code: " . $user->shipping_postcode . "<br>Phone: " . $user->shipping_phone . "<br>Preferred receiving day and time:<br>" . $user->preferred_receiving_day . "<br>" . $user->preferred_receiving_times,
//                'billing_details' => "Name: " . $user->billing_first_name . " " . $user->billing_last_name . "<br>Country,City: " . $user->country->Name . "," . $user->billing_city . "<br>Address: " . $user->billing_address_1 . "<br>Zip Code: " . $user->billing_postcode . "<br>Phone: " . $user->billing_phone,
//
//            ];
//            sendEmail($user, 'Auction_Order_Confirmation', $shortcodes);
//        }
//    }
}
