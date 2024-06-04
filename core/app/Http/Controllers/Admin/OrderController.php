<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Country;
use App\Models\Event;
use App\Models\Order;
use App\Models\ShippingRanges;
use App\Models\ShippingRegion;
use App\Models\Winner;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;

    // protected $search;


    public function index()
    {
        $orders = Order::query()->where('payment_processed',true)->orWhereNull('payment_processed')->latest()->paginate(getPaginate());
        $pageTitle = __("All Orders");
        $emptyMessage = __("No Order Found");
        return view('admin.order.index', compact('pageTitle', 'emptyMessage', 'orders'));
    }

    public function edit($id)
    {
        $pageTitle = __('Show Order');
        $emptyMessage = __("No Products Found");
        $order = Order::findOrFail($id);
        $user = $order->user;
        $data['firstname']=$user==null ? $order->customer_first_name:$user->firstname;
        $data['lastname']=$user==null ? $order->customer_last_name:$user->lastname;
        $data['email']=$user==null ? $order->customer_email:$user->email;
        $data['billing_phone']=$user==null ? $order->billing_phone:$user->billing_phone;
        $data['company_name']=$user==null ? $order->customer_company_name:$user->company_name;
        $data['company_website']=$user==null ? $order->customer_company_website:$user->company_website;
        $data['shipping_firstname']=$user==null ? $order->shipping_first_name:$user->shipping_first_name;
        $data['shipping_lastname']=$user==null ? $order->shipping_last_name:$user->shipping_last_name;
        $data['shipping_address_1']=$user==null ? $order->shipping_address1:$user->shipping_address_1;
        $data['shipping_address_2']=$user==null ? $order->shipping_address2:$user->shipping_address_2;
        $data['shipping_city']=$user==null ? $order->shipping_city:$user->shipping_city;
        $data['shipping_state']=$user==null ? $order->shipping_state:$user->shipping_state;
        $data['shipping_postcode']=$user==null ? $order->shipping_zip:$user->shipping_postcode;
        $data['shipping_country']=$user==null ? $order->shipping_country:$user->shipping_country;
        $data['billing_firstname']=$user==null ? $order->billing_first_name:$user->billing_first_name;
        $data['billing_lastname']=$user==null ? $order->billing_last_name:$user->billing_last_name;
        $data['billing_address_1']=$user==null ? $order->billing_address1:$user->billing_address_1;
        $data['billing_address_2']=$user==null ? $order->billing_address2:$user->billing_address_2;
        $data['billing_city']=$user==null ? $order->billing_city:$user->billing_city;
        $data['billing_state']=$user==null ? $order->billing_state:$user->billing_state;
        $data['billing_postcode']=$user==null ? $order->billing_zip:$user->billing_postcode;
        $data['billing_country']=$user==null ? $order->billing_country:$user->billing_country;
        $data['describes_business']=$user==null ? null:$user->describes_business;
        $data['pounds_green_coffee']=$user==null ? null:$user->pounds_green_coffee;
        $data['hosting_cupping']=$user==null ? null:$user->hosting_cupping;
        $data['delivery']=$user==null ? null:$user->delivery;
        $data['is_lift_gate_delivery']=$user==null ? $order->is_lift_gate_delivery:null;
        $data['is_inside_delivery']=$user==null ? $order->is_inside_delivery:null;
        $data['is_appointment_request']=$user==null ? $order->is_appointment_request:null;
        $data['is_notify_request']=$user==null ? $order->is_notify_request:null;
        $data['preferred_receiving_day']=$user==null ? $order->delivery_date:$user->preferred_receiving_day;
        $data['preferred_receiving_times']=$user==null ? $order->delivery_date:$user->preferred_receiving_times;
        $data['ein_number']=$user==null ? $order->shipping_EIN_number:$user->ein_number;
        $countries = Country::query()->get();
        $products = $order->products;
        $shipping_method_name = '';
        if($order->shipping_method >0){
            $shipping_method = ShippingRegion::find($order->shipping_method);
            if(!is_null($shipping_method)){
                $shipping_method_name = $shipping_method->shipping_method;
            }
        }
        elseif($order->shipping_method==-1){
            $shipping_method_name = __('I will take care of the shipping');
        }
        elseif($order->shipping_method==-2){
            $shipping_method_name = __('I am part of a bidding group');
        }
        elseif($order->shipping_method==-3){
            $shipping_method_name = __('Air Freight (Request Quote)');
        }
        return view('admin.order.edit', compact('emptyMessage','pageTitle','shipping_method_name', 'order', 'user', 'products','data','countries'));
    }

    public function update($id, Request $request)
    {
        $order = Order::findOrFail($id);
        if($request->has('status')){
            $order->status =$request->status;
        }
        if ($request->has('payment_status')) {
            $order->payment_status = $request->payment_status;
        }
        $order->save();

        return redirect()->route('admin.order.index')->with('success', __('Order Updated Successfully'));
    }


}
