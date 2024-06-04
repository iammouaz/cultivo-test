<?php

namespace App\Http\Controllers;

use App\Exceptions\BidValidationException;
use App\Models\AutoBidSetting;
use App\Models\Bid;
use App\Models\Event;
use App\Models\Product;
use App\Models\User;
use App\Services\BidService;
use Illuminate\Http\Request;

class AutoBidSettingController extends Controller
{
    /**
     * @var BidService
     */
    protected $bidService;
    public function __construct()
    {
        $this->bidService = app('bidService');
    }
    public function addAutoBidToAllUser($product_id)//todo check the need for this function (its route is not being called in front)
    {
        clear_all_cache();

        ini_set('memory_limit', 5120000000);
        ini_set('max_execution_time', 0);

        $users = User::all();

        $event = Event::find($product_id);
        if(is_null($event)){
            echo 'event not found';
        }
        $products = Product::where('event_id', $event->id)->get();
        foreach ($products as $product){
            foreach($users as $user){
                $this->addAutoBidToUser($user, $product->id);
            }
        }

        echo "done";
    }

    public function addAutoBidToUser($user, $product_id)//todo check the need for this function
    {
        clear_all_cache();

        $product = Product::live()->with('merchant', 'admin')->findOrFail($product_id);
        $setting_exist = AutoBidSetting::where('product_id', $product_id)->where('user_id', $user->id)->exists();

        $max_amount = Bid::where('product_id',$product_id)->max('amount');

        $step = rand(1, $product->event->max_bidding_value);
        $max_value = rand($max_amount,1000000);

        if ($setting_exist) {
            $setting = AutoBidSetting::where('product_id', $product_id)->where('user_id', $user->id)->first();
            $setting->update([
                'step'=>$step,
                'max_value'=> $max_value,
                'status'=>'active',
            ]);
        } else {
            $newsetting = new AutoBidSetting();
            $newsetting->product_id = $product->id;
            $newsetting->user_id = $user->id;
            $newsetting->step = $step;
            $newsetting->max_value=$max_value;
            $newsetting->save();
        }

        return  0;
    }

    public function store(Request $request)//todo check the need for this function
    {
        return $this->bidService->placeAutoBid($request);
    }


    protected function validation($request)//todo check the need for this function
    {
        clear_all_cache();

        $request->validate([
            'max_value' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            'step' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
        ]);
    }


    public function disable($id)
    {
        try {
            $this->bidService->disableAutoBid($id, auth()->user()->id);
        }
        catch (BidValidationException $ex){
            $notify= ['error'=> $ex->getMessage()];
            return $notify ;
        }
        $notify= ['success'=> __('Current Auto Bid has been disabled')];
        return back()->withNotify($notify);
    }


    public function show(Request $request){
        $autosettings = $this->bidService->getAutoBidSettings($request->productid, auth()->user()->id);
        if($autosettings){
            return response()->json(["max_value"=>$autosettings->max_value,"step"=>$autosettings->step,'status'=>$autosettings->status]);
        }
        //todo add missing validation if autoBidSettings not set
    }
}
