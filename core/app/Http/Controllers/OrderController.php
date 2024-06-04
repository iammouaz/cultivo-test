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
use App\Models\Offer;
use App\Models\OfferSheet;
use App\Models\Order;
use App\Models\Payment;
use App\Models\Price;
use App\Models\Product;
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

class OrderController extends Controller
{
    const PENDENIN = 'pending';
    const APPROVED = 'approved';
    const REJECTED = 'rejected';
    public function index($event_id)
    {
        $pageTitle = "Order";
        if (auth()->user()) {
            $user = auth()->user();
        } else {
            $user = null;
        }
        // $event_id= null;
        $carts = $this->getCartArray($event_id);



        $countries = Country::query()->get();
        return view('templates.basic.order', compact('pageTitle', 'user', 'carts', 'countries', 'event_id'));
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

        $productPrices = $validated['product_prices'];
        //        $order = $this->orderCreate($validated, $user,$productPrices);//todo remove order make here


        $shipping_method = $validated['shipping_method'];
        $payment_method = $validated['payment_method'];
        $shipping_country_id = $validated['shipping_country'];
        //        return redirect()->route('order.paymentIndex',
        //            compact('shipping_method', 'payment_method','productPrices', 'shipping_country_id'));

        $region = ShippingRegion::find($shipping_method);

        $price_arr = $this->calucate_price($productPrices, $shipping_method, $shipping_country_id, $payment_method);
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
        $order = $this->orderCreate($validated, $user, $productPrices, $total_shipping_price, $total_price,
        $shipping_price_sample,$shipping_price_product,$total_price_sample,$total_price_product,
         'pending', 'pending', 'usd');
        $order_id = $order->id;

        if ($payment_method == 'bank' || $payment_method == 'Wise') { //will be modified to paid by admin
            $this->sendEmails($order); //todo fix mail
            $this->emptyCart($event_id);
            return response()->json(['redirectUrl' => route('order.paymentDone', compact('order_id'))]);
        }
        $allow_product_payment = config('app.allow_product_payment');
        if($allow_product_payment){
            if($total_price > 0){
                return response()->json(['redirectUrl' => route('order.paymentIndex', compact('order_id'))]);
            }else{
                return response()->json(['redirectUrl' => route('order.pay', compact('order_id'))]);
            }
        }else{
                if($total_price_sample > 0){
                    return response()->json(['redirectUrl' => route('order.paymentIndex', compact('order_id'))]);
                }else{
                    return response()->json(['redirectUrl' => route('order.pay', compact('order_id'))]);
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
            return redirect()->route('order.paymentDone', compact('order_id', 'pageTitle'));
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
            return redirect()->route('order.paymentDone', compact('order_id', 'pageTitle'));
        }
        $event = $order->prices()->first()->offer->offerSheet ?? null;
        $cnts = $order->total_price;
        $allow_product_payment = config('app.allow_product_payment');
        $total_price_sample = $order->total_price_sample;
        $total_price_product = $order->total_price_product;

        if (!$allow_product_payment) {
            $cnts = $total_price_sample;
        }
        if ($cnts <= 0) {
            $pageTitle = 'Payment Done';
            $order->payment_status = 'paid';
            $order->payment_processed = true;
            $order->paid_amount = $cnts;
                $order->status = 'pending';
                $order->order_type = $allow_product_payment?"full_order":"sample_payment";
                $order->save();
                if ($event)
                    $this->emptyCart($event->id);
                else {
                    $this->emptyCart();
                    Log::error('cannot empty cart: offer sheet not found');
                    Integration::captureUnhandledException(new \Exception('warning: cannot empty cart after order placement: offer sheet not found'));
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
        $products_names = $order->prices->map(function ($item) {
            return $item->offer->name;
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
                $order->order_type = $allow_product_payment?"full_order":"sample_payment";
                $order->save();
                if ($event)
                    $this->emptyCart($event->id);
                else {
                    $this->emptyCart();
                    Log::error('cannot empty cart: offer sheet not found');
                    Integration::captureUnhandledException(new \Exception('warning: cannot empty cart after order placement: offer sheet not found'));
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

    public function emptyCart($offer_sheet_id = null)
    {
        if ($offer_sheet_id) {
            $cart = $this->getCartArrayExceptEvent($offer_sheet_id);
            session()->put('cart', $cart);
            if (auth()->check()) {
                $user = auth()->user();
                $user->cart = json_encode($cart);
                $user->save();
            }
        } else {

            if (auth()->check()) {
                $user = auth()->user();
                $user->cart = null;
                $user->save();
            }
            session()->forget('cart');
        }
    }
    public function validationOrder(Request $request)
    {

        return  $request->validate([
            'product_prices' => 'required|array',
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
    public function addToCartAjax(Request $request)
    {
        $validated = $request->validate([
            'product_price_id' => 'required|exists:prices,id',
            'quantity' => 'required|numeric|min:1',
            'is_sample' => 'nullable|boolean',
        ]);
        $price = Price::with(['offer', 'size'])->findOrFail($validated['product_price_id']);

        $cart = session()->get('cart');
        if (isset($cart[$validated['product_price_id']])) {
            $cart[$validated['product_price_id']]['quantity'] += $validated['quantity'];
        } else {
            $cart[$validated['product_price_id']] = [ //properties that remains in cart
                "quantity" => $validated['quantity'],
                "is_sample" => $validated['is_sample'],
                "product_price_id" => $validated['product_price_id'],
            ];
        }
        $detailedCart = [];
        list($cart, $detailedCart) = $this->validateCartAndGetDetails($cart, $detailedCart);
        session()->put('cart', $cart);
        if (auth()->check()) {
            $user = auth()->user();
            $user->cart = json_encode($cart);
            $user->save();
        }
        //        return response()->json(['cart' => $detailedCart]);
        return $this->getCartView();
    }

    public function updateCartAjax(Request $request)
    {
        if ($request->carts == 0) {
            $this->emptyCart($request->offer_sheet_id);
        } else {
            $validated = $request->validate([
                'carts' => 'required|array',
                'carts.*.product_price_id' => 'required|exists:prices,id',
                'carts.*.quantity' => 'required|numeric|min:1',
                'carts.*.is_sample' => 'nullable|boolean',
                'offer_sheet_id' => 'required|exists:offer_sheets,id',
            ]);

            // delete cart in db with session and add new items
            $cart = [];
            foreach ($validated['carts'] as $item) {
                $cart[$item['product_price_id']] = [ //properties that remains in cart
                    "quantity" => $item['quantity'],
                    "is_sample" => $item['is_sample'],
                    "product_price_id" => $item['product_price_id'],
                ];
            }

            $detailedCart = [];
            list($cart, $detailedCart) = $this->validateCartAndGetDetails($cart, $detailedCart); //
            $currentCartExceptEvent = $this->getCartArrayExceptEvent($validated['offer_sheet_id']);
            foreach ($currentCartExceptEvent as $key => $item) {
                $cart[$key] = $item;
            }
            //            Log::info('cart updated', ['cart' => $cart]);
            //            dd($cart);
            session()->put('cart', $cart); //save in session
            //            Log::info('cart updated in session (cart update function)', ['cart' => $cart]);
            if (auth()->check()) {
                $user = auth()->user();
                $user->cart = json_encode($cart);
                $user->save();
                //                Log::info('cart updated in auth (cart update function)', ['cart' => $cart]);
            }
        }
        return $this->getCartView();
    }

    /**
     * @param $price
     * @param $cart
     * @param $key
     */
    private function setItemDetails($price, &$cart, $key)
    {
        $cart[$key]['name'] = $price->offer->name ?? null;
        $cart[$key]['size_name'] = $price->size->size ?? null;
        $cart[$key]['price_id'] = $price->id;
        $cart[$key]['size_weight'] = $price->size->weight_LB;
        $cart[$key]['price'] = $price->product_total_price;
        $cart[$key]['price_lb'] = $price->price;
        $cart[$key]['image'] = getImage(imagePath()['product']['path'] . '/' . $price->offer->photo, imagePath()['product']['size'], false, 'sm');
        $cart[$key]['product_id'] = $price->offer->id;
        $cart[$key]['event_id'] = $price->offer->offer_sheet_id;
        $cart[$key]['other_prices'] = $price->offer->prices()->has('size')->get()->each(function ($item) {
            return [
                'id' => $item->id,
                'size_id' => $item->size_id,
                'size_name' => $item->size->size,
                'size_weight' => $item->size->weight_LB,
                'size_additional_cost' => $item->size->additional_cost,
                'price' => $item->price,
            ];
        })->toArray();
        $specs = $this->getSpecs($price->offer);
        $cart[$key]['region'] =  $specs['region'];
        $cart[$key]['origin'] =  $specs['origin'];
        $cart[$key]['grade'] =   $specs['grade'];
        $cart[$key]['total_units_available'] =   $specs['units_available'];
    }
    public function getSpecs($offer)
    {
        foreach ($offer->offer_specification as $spec) {
            if (strtoupper($spec->spec_key) == 'ORIGIN') { //done
                $origin = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'ALTITUDE') { //done
                $altitude = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'DRYING') { //done
                $drying = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'GRADE') { //done
                $grade = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'HARVEST') { //done
                $harvest = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'LOCATION') { //done
                $location = $spec->Value;
            }

            if (strtoupper($spec->spec_key) == 'PROCESSING METHOD') { //done
                $process = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'PRODUCER') { //done
                $producer = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'REGION') { //done
                $region = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'SCORE') {
                $score = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'SCREEN') { //done
                $screen = $spec->Value;
            }
            //            if (strtoupper($spec->spec_key) == 'UNIT SIZE') {
            //                $unitSize = $spec->Value;
            //            }
            if (strtoupper($spec->spec_key) == 'TASTING NOTES') { //done
                $taste = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'VARIETY') { //done
                $variety = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'UNITS AVAILABLE') { //done
                $unitsAvailable = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'Diff / 100 Lb') {
                $diff = $spec->Value;
            }
            if (strtoupper($spec->spec_key) == 'NY KCH24 @ 185') {
                $kch24 = $spec->Value;
            }
        }
        return [
            'origin' => $origin ?? null,
            'altitude' => $altitude ?? null,
            'drying' => $drying ?? null,
            'grade' => $grade ?? null,
            'harvest' => $harvest ?? null,
            'location' => $location ?? null,
            'process' => $process ?? null,
            'producer' => $producer ?? null,
            'region' => $region ?? null,
            'score' => $score ?? null,
            'screen' => $screen ?? null,
            //            'unit_size' => $unitSize ?? null,
            'taste' => $taste ?? null,
            'variety' => $variety ?? null,
            'units_available' => $unitsAvailable ?? null,
            'diff' => $diff ?? null,
            'kch24' => $kch24 ?? null

        ];
    }
    /**
     * @param $cart
     * @param array $detailedCart
     * @return array
     */
    private function validateCartAndGetDetails($cart, array $detailedCart): array
    {
        foreach ($cart as $key => $item) {
            $price = Price::with(['offer', 'size'])->firstWhere('id', $key);
            if (is_null($price) || is_null($price->offer) || is_null($price->size)) {
                unset($cart[$key]);
            } else {
                $detailedCart[$key]['quantity'] = $item['quantity'];
                $detailedCart[$key]['is_sample'] = $item['is_sample'];
                $detailedCart[$key]['product_price_id'] = $item['product_price_id'];
                $this->setItemDetails($price, $detailedCart, $key);
            }
        }
        //        dd($detailedCart);
        return array($cart, $detailedCart);
    }

    /**
     * @return array|mixed
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getCartArray($event_id = null)
    {

        $detailedCart = [];
        if (auth()->check()) {
            $user = auth()->user();
            $cart = json_decode($user->cart, true);
            if (!is_null($cart)) {
                list($cart, $detailedCart) = $this->validateCartAndGetDetails($cart, $detailedCart);
                $user->cart = json_encode($cart);
                $user->save();
                session()->put('cart', $cart);
                //                Log::info('cart updated in auth', ['cart' => $cart]);
                //                dd($cart);
            } else {
                $cart = session()->get('cart');
                if (!is_null($cart)) {
                    list($cart, $detailedCart) = $this->validateCartAndGetDetails($cart, $detailedCart);
                    $user->cart = json_encode($cart);
                    $user->save();
                    session()->put('cart', $cart);
                    //                    Log::info('cart updated in session and user', ['cart' => $cart]);
                    //                    dd($cart);
                }
            }
        } // add behaviour for anonymous user
        else {
            $cart = session()->get('cart');
            if (!is_null($cart)) {
                list($cart, $detailedCart) = $this->validateCartAndGetDetails($cart, $detailedCart);
                session()->put('cart', $cart);
                //                Log::info('cart updated in session', ['cart' => $cart]);
                //                dd($cart);
            }
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
    public function getCartItemsGroupedByEvent()
    {

        $cart = $this->getCartArray();
        $groupedItems = collect($cart)->groupBy('event_id');
        $events = [];
        foreach ($groupedItems as $eventId => $items) {
            $event = OfferSheet::findOrFail($eventId);
            $eventName = $event->name;
            $eventCategory = $event->category->name;
            $productCount = $items->count();
            $events[$eventId] = [
                'event_id' => $eventId,
                'event_name' => $eventName,
                'event_category' => $eventCategory,
                'product_count' => $productCount
            ];
        }
        return $events;
    }
    public function getFullCartItemsGroupedByEvent()
    {
        $cart = $this->getCartArray();
        $groupedItems = collect($cart)->groupBy('event_id');
        $events = [];
        foreach ($groupedItems as $eventId => $items) {
            $event = OfferSheet::findOrFail($eventId);
            $eventName = $event->name;
            $eventCategory = $event->category->name;
            $productCount = $items->count();
            $events[$eventId]['event_id'] = $eventId;
            $events[$eventId]['event_name'] = $eventName;
            $events[$eventId]['event_category'] = $eventCategory;
            $events[$eventId]['event_type'] = "offer_sheet";
            $events[$eventId]['product_count'] = $productCount;
            $events[$eventId]['items'] = $items;
        }
        return $events;
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
        $shipping_regions = ShippingRegion::where('event_id', $event_id)->where('event_type', OfferSheet::class)->where('region_name', $region->name)->get(['id', 'shipping_method']);
        if (count($shipping_regions) == 0) {
            $region = DefaultRegions::where('default', 1)->first();

            $shipping_regions = ShippingRegion::where('event_id', $event_id)->where('event_type', OfferSheet::class)->where('region_name', $region->name)->get(['id', 'shipping_method']);
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
            'product_prices' => 'required|array', //[price_id, quantity]
            'shipping_country_id' => 'nullable',
            'payment_method' => 'nullable',
            'shipping_region_id' => 'nullable',

        ]);
        $productPrices = $validatedData['product_prices'];

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
            $Price = Price::find($productPrice['id']) ?? null;

            $offer = $Price->offer;
            $event = $offer->offerSheet;
            // Log::info('offer', ['price' => $Price]);
            $size = $Price->size;
            if ($Price == null) {
                return redirect()->route('order.index',['event_id'=>$event->id])->with('error', 'Offer Price Not Found');
            }
            // todo subtract quantity from offer

            // calculate total price
            if ($Price->size->is_sample) {
                $total_price_sample +=  (($Price->product_total_price) * $productPrice['quantity']);
                $shipping_weight_sample += ($size->weight_LB * $productPrice['quantity']);
            } else {
                $total_price_product +=  (($Price->product_total_price) * $productPrice['quantity']);
                $shipping_weight_product += ($size->weight_LB * $productPrice['quantity']);
            }

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
        $total_shipping_price_product = $this->get_total_shipping_price($region_id, $event_id, $event_type, $shipping_weight_product);
        $total_shipping_price_sample = $this->get_total_shipping_price($region_id, $event_id, $event_type, $shipping_weight_sample);
        // price with sum shipping price
        $total_price_sample += $total_shipping_price_sample;
        $total_price_product += $total_shipping_price_product;



        if ($shipping_country_id && $payment_method) {
            //            $event_deposit = Event::where('id', $event_id)->get(['deposit'])->first()->deposit;

            // $payment_handel_fees = $this->get_payment_handel_fees($total_price, $event,
            //     $shipping_country_id, $payment_method);

            // $total_shipping_price = $total_shipping_price + $payment_handel_fees;
            // $total_price = $total_price + $payment_handel_fees;
            // payment_handel_fees for product and sample
            $payment_handel_fees_product = $this->get_payment_handel_fees(
                $total_price_product,
                $event,
                $shipping_country_id,
                $payment_method
            );
            $ppayment_handel_fees_sample = $this->get_payment_handel_fees(
                $total_price_sample,
                $event,
                $shipping_country_id,
                $payment_method
            );
            // sum payment_handel_fees with total_shipping_price
            $total_shipping_price_product += $payment_handel_fees_product;
            $total_shipping_price_sample += $ppayment_handel_fees_sample;
            // sum total price with payment_handel_fees
            $total_price_product += $payment_handel_fees_product;
            $total_price_sample += $ppayment_handel_fees_sample;
        }
        // sum shipping price
        $total_shipping_price = $total_shipping_price_product + $total_shipping_price_sample;
        // sum total price with shipping price
        $total_price = $total_price_sample + $total_price_product;
        if ($total_shipping_price == 0 && ($region_id != -1 && $region_id != -2 && $region_id != -3)) {
            $total_shipping_price_mess = 'Weight is not supported';
            return [
                'total_price' => showAmount($total_price, 2),
                'total_price_float' => $total_price,
                'total_price_sample' => showAmount($total_price_sample, 2),
                'total_price_product' => showAmount($total_price_product, 2),
                'total_shipping_price' => $total_shipping_price_mess,
                'total_shipping_price_sample' => $total_shipping_price_sample,
                'total_shipping_price_product' => $total_shipping_price_product,
                'sub_total' => round($total_price - $total_shipping_price, 2),
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
        $order->payment_processed = false;
        $order->currency = $currency;
        $order->save();
        foreach ($productPrices as $productPrice) {
            $order->prices()->attach($productPrice['id'], ['quantity' => $productPrice['quantity']]);
        }
        return $order;
    }

    /**
     * @param  $shipping_country
     * @return null
     */
    public function getCountryId($shipping_country) //to delete no need this function
    {
        $country = Country::where('Name', 'like', $shipping_country)->first();
        if ($country)
            $shipping_country_id = $country->id;
        else
            $shipping_country_id = null;
        return $shipping_country_id;
    }

    /**
     * @throws \Psr\Container\ContainerExceptionInterface
     * @throws \Psr\Container\NotFoundExceptionInterface
     */
    public function getCartItemsGroupedByEventId()
    {
        $cart = $this->getCartArray();
        $groupedItems = collect($cart)->groupBy('event_id')->toArray();
        return $groupedItems;
    }

    /**
     * @param $order
     * @return void
     */
    public function sendEmails($order): void
    {
        //client email
        setMailConfig();
        // Log::info($order->products);
        SendCustomEmail::dispatch($order->customer_email, new CheckoutMail($order, true))->onConnection(config('app.email_job_queue_connection'));
        foreach($order->products as $product){
            $shortcodes=[
                'order_number'=>$order->id,
                'event_name'=>$product->offer->offerSheet->name,
                'event_type'=>$product->offer->offerSheet->category->name == "Fixed Price" ? " Fixed Price Offer":"Open Offer",
                'order_type'=>"normal",
                'order_date'=>$order->created_at,
                'product'=>$product->offer->name,
                'weight'=>round($product->size->weight_LB,2),
                'quantity'=>$product->pivot->quantity,
                'price'=>round($product->price,2),
                'currency'=>$order->currency,
                'customer_name'=>$order->full_name,
                'company_name'=>$order->customer_company_name,
                'payment_method'=>$order->payment_method == "bank"  ? "Bank Transfer" : ($order->payment_method == "Stripe" ? "Credit Card" : "Email Order"),
                'payment_status'=>$order->payment_status == "paid"  ? "Paid":"Pending Payment",
                'grand_total'=>$order->paid_amount ?? round($order->total_price,2),
                'subtotal'=>isset($order->paid_amount) ? round($order->total_price-$order->shipping_price,2):round($order->paid_amount-$order->shipping_price,2),
                'shipping_value'=>round($order->shipping_price,2),
                'shipping_details'=>"Name: ".$order->shipping_first_name." ".$order->shipping_last_name."<br>Country,City: ".$order->country_shipping->Name.",".$order->shipping_city."<br>Address: ".$order->shipping_address1."<br>Zip Code: ".$order->shipping_zip."<br>Phone: ".$order->shipping_phone
//                    ."<br>Preferred receiving day and time:<br>".$order->delivery_date //todo enable these and fix saving to exclude the non-checked items
                ,
                'billing_details'=>"Name: ".$order->billing_first_name." ".$order->billing_last_name."<br>Country,City: ".$order->country_billing->Name.",".$order->billing_city."<br>Address: ".$order->billing_address1."<br>Zip Code: ".$order->billing_zip."<br>Phone: ".$order->billing_phone,
            ];
            $user = $order->user;
            if ($user) {
                sendEmail($user, 'Offer_Order_Confirmation', $shortcodes);
            }
            else {
                sendEmail_v2($order->customer_email, 'Offer_Order_Confirmation', $shortcodes);
            }
        }
        //seller emails
        $event = $order->prices()->first()->offer->offerSheet ?? null;
        if ($event) {
            $emails = explode(',', $event->emails);
            foreach ($emails as $email) {
                SendCustomEmail::dispatch($email, new CheckoutMail($order, false))->onConnection(config('app.email_job_queue_connection'));
            }
        } else {
            Log::error("cannot send email: offer sheet of order no:$order->id not found");
            Integration::captureUnhandledException(new \Exception("warning: cannot send email to sellers after order with no:$order->id  placement: offer sheet not found"));
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
        $ship_price = 0;
        if (!is_null($region_id)) {
            $region = ShippingRegion::find($region_id);
        } else if ($region_id == -1 || $region_id == -2) {
            $region = null;
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

        $total_shipping_price = $ship_price * $total_shipping_weight;
        return $total_shipping_price;
    }

    public function getShippingAndHandlingFees()
    {
        $request = request();
        $user = auth()->user();
        $validatedData = $request->validate([
            'offer_sheet_id' => 'required|exists:offer_sheets,id',
            'total_weight' => 'nullable|numeric|min:0',
            'shipping_method' => 'nullable',
            'payment_method' => 'nullable',
            'sub_total' => 'required|numeric|min:0',

        ]);
        $event_id = $validatedData['offer_sheet_id'];
        $total_shipping_weight = $validatedData['total_weight'] ?? 0;
        $shipping_method = $validatedData['shipping_method'] ?? null;
        $payment_method = $validatedData['payment_method'] ?? null;
        $sub_total = $validatedData['sub_total'];
        $event = OfferSheet::find($event_id);
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
        $total_shipping_price = $this->get_total_shipping_price($shipping_method, $event_id, OfferSheet::class, $total_shipping_weight);
        $total_price = $sub_total + $total_shipping_price;
        $payment_handel_fees = $this->get_payment_handel_fees($total_price, $event, $shipping_country_id, $payment_method);
        $total_price = $total_price + $payment_handel_fees;
        return response()->json(['shipping_and_handling_fees' => $total_shipping_price + $payment_handel_fees, 'total_price' => $total_price]);
    }
}
