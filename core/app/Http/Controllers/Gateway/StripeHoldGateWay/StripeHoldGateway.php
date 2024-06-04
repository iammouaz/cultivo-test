<?php

namespace App\Http\Controllers\Gateway\StripeHoldGateWay;

class StripeHoldGateway
{

    public function hold($token,$amount,$currency,$description,$event_id = null)
    {

// Set your secret key. Remember to switch to your live secret key in production.
// See your keys here: https://dashboard.stripe.com/apikeys
        \Stripe\Stripe::setApiKey('sk_test_51LCt9aLJZvxSrFWXxAFov9XnZjYKdiX9Q2j8Txsbe7IZAo928f1sAwQQAf0Aef516OTMXEV6uYBQ04UvoHvL9PcR009uxJ7PxF');

// Token is created using Checkout or Elements!
// Get the payment token ID submitted by the form:
        //$token = $_POST['stripeToken'];

        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,
            'source' => $token,
            'capture' => false,
            'metadata' => [
                'event_id' => $event_id
            ]
        ]);



        $payment = new Payment();
        $payment->user_id = auth()->user()->id;
        $payment->payment_type = 'hold';
        $payment->charge_id = $charge->id;
        $payment->amount = $charge->amount;
        $payment->currency = $charge->currency;
        $payment->description = $description;
        $payment->event_id = $event_id;
        $payment->save();

        dd($payment);

        return $payment;
    }


    public function  check_card_validation($token)
    {
        \Stripe\Stripe::setApiKey('sk_test_51LCt9aLJZvxSrFWXxAFov9XnZjYKdiX9Q2j8Txsbe7IZAo928f1sAwQQAf0Aef516OTMXEV6uYBQ04UvoHvL9PcR009uxJ7PxF');
        $charge = \Stripe\Charge::create([
            'amount' => 1,
            'currency' => 'usd',
            'description' => 'Test charge',
            'source' => $token,
        ]);
        return $charge;
    }

    public static function pay($token,$amount,$currency,$description,$event_id = null){
        \Stripe\Stripe::setApiKey('sk_test_51LCt9aLJZvxSrFWXxAFov9XnZjYKdiX9Q2j8Txsbe7IZAo928f1sAwQQAf0Aef516OTMXEV6uYBQ04UvoHvL9PcR009uxJ7PxF');


        $charge = \Stripe\Charge::create([
            'amount' => $amount,
            'currency' => $currency,
            'description' => $description,
            'source' => $token,
            'capture' => true,
            'metadata' => [
                'event_id' => $event_id
            ]
        ]);


        return $charge;



    }

    public function capture($id,$new_amount =null){
        $stripe = new \Stripe\StripeClient('sk_test_51LCt9aLJZvxSrFWXxAFov9XnZjYKdiX9Q2j8Txsbe7IZAo928f1sAwQQAf0Aef516OTMXEV6uYBQ04UvoHvL9PcR009uxJ7PxF');

        if(is_null($new_amount)){
            $stripe->charges->capture($id, []);
        }
        else {
            $stripe->charges->capture($id, ['amount' => $new_amount]);
        }

    }
    public function release($id)
    {

    }
}
