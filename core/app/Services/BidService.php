<?php

namespace App\Services;


use App\Exceptions\BidValidationException;
use App\Http\PusherEvents\EventPush;
use App\Http\PusherEvents\ProductPush;
use App\Jobs\AutoBidForProductId;
use App\Models\AdminNotification;
use App\Models\AutoBidSetting;
use App\Models\Bid;
use App\Models\Event;
use App\Models\Group;
use App\Models\Product;
use App\Models\User;
use DateTime;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Sentry\Laravel\Integration;

class BidService extends BaseService
{

    /**
     * @param $request
     * @return int
     */
    public function placeAutoBid($request)//todo refactor, optimize
    {
        clear_all_cache();

        $user = auth()->user();
        $product = Product::live()->with('merchant', 'admin')->findOrFail($request->product_id);
//        if (!$product->is_in_allow_list){
//            return 5;
//        }
        $setting_exist = $this->autoBidSettingRepository->query()->where('product_id', $request->product_id)->where('user_id', $user->id)->exists();
        $max_amount = $this->bidRepository->query()->where('product_id', $request->product_id)->max('amount');
        if($product->less_bidding_value >$request->step){
            return  1;
        }
        if($product->event->max_bidding_value < $request->step){
            return  2;
        }
        if($max_amount){
            if($max_amount+$product->less_bidding_value >$request->max_value){
                return  3;
            }
        }else{
            if($product->price+$product->less_bidding_value >$request->max_value){
                return  4;
            }
        }

        if ($setting_exist) {
            $setting = $this->autoBidSettingRepository->query()->where('product_id', $request->product_id)->where('user_id', $user->id)->first();
            $setting->update([
                'step'=>$request->step,
                'max_value'=>$request->max_value,
                'status'=>'active',
            ]);
        } else {
            $newsetting = new AutoBidSetting();
            $newsetting->product_id = $product->id;
            $newsetting->user_id = $user->id;
            $newsetting->step = $request->step;
            $newsetting->max_value=$request->max_value;
            $newsetting->save();
        }

        return  0;
    }

    public function disableAutoBid($product_id, $user_id)
    {
        $setting = $this->autoBidSettingRepository->query()->where('product_id', $product_id)->where('user_id',$user_id)->first();
        throw_if(is_null($setting), BidValidationException::class, 'Current Auto Bid not found');
        throw_if($setting->status=='disable', BidValidationException::class, 'Current Auto Bid already disabled');
        $setting->status='disable';
        $setting->save();
        clear_all_cache();
    }
    public function getAutoBidSettings($product_id,$user_id)
    {

        $autosettings = $this->autoBidSettingRepository->query()->where('product_id', $product_id)->where('user_id',$user_id)->first();
        return $autosettings;
    }
    public function bid($product_id, $amount, $user, $is_auto_bid = false, $is_last_auto_bid = false,$bid_count=null,$user_bid_count=null)//todo refactor, optimize,use repository
    {
        clear_all_cache();

        $product = Product::live()->with('merchant', 'admin')->findOrFail($product_id);

//        if (!$product->is_in_allow_list){
//            $notify = ['error' => 'You are not allowed to bid on this product.'];
//            return response()->json($notify, 400);
//        }

        $last_bid = Bid::where('product_id', $product_id)->orderby('amount', 'desc')->first();

        //check if the bid type is auto bid
        if (!$is_auto_bid) {
            if ($last_bid) {
                if ($last_bid->user_id == $user->id) {
                    throw new BidValidationException( 'You can\'t outbid yourself');
                }
            }
        }

        $event = $this->eventRepository->find($product->event_id);


        if($event->bid_status == 'closed'){
            throw new BidValidationException('The bid is paused.');
        }

        $product->event->start_counter = $event->start_counter + 1;
        $product->event->save();

        $bid_exist = Bid::where('product_id', $product_id)->where('user_id', $user->id)->exists();
        $bid_exist_different_user = Bid::where('product_id', $product_id)->exists();
        if ($bid_exist_different_user) {

            $max_amount = Bid::where('product_id', $product_id)->max('amount');
            if (round($max_amount-$amount,getRoundPrecision()) >= 0) {
                throw new BidValidationException("Bid amount must be higher than the current bid");
            }


            if ((round($amount - $max_amount, 2) < $product->less_bidding_value) && !$is_last_auto_bid) {
                throw new BidValidationException('The minimum required bid increment is ' . $product->less_bidding_value);
            }

            $Highest_possible_bid = $product->event->max_bidding_value + $max_amount;
            if (!$is_auto_bid && round($amount - $max_amount, 2) > $product->event->max_bidding_value) {
                throw new BidValidationException('Your max bid is too high. Please place a bid less than US$ ' . $Highest_possible_bid);
            }
        } else {
            if (round($amount-$product->price,getRoundPrecision()) < 0) {
                throw new BidValidationException("Bid amount must be equal or higher than the product price");
            }

            $Highest_possible_bid = $product->event->max_bidding_value + $product->price;
            if (round($amount - $product->price, 2) > $product->event->max_bidding_value) {
                throw new BidValidationException('Your max bid is too high. Please place a bid less than US$ ' . $Highest_possible_bid);
            }
        }


        if ($bid_exist) {
            $bid = Bid::where('product_id', $product_id)->where('user_id', $user->id)->first();
            $user_updated = $bid->user_updated;

        } else {
            $bid = new Bid();
            $date = new DateTime();
            $user_updated[] = $date->format('Y-m-d H:i:s');

        }


        $bid->product_id = $product_id;
        $bid->user_id = $user->id;
        $bid->amount = $amount;

        $bid->prev_amount = $is_auto_bid ? 0 : 1;
        //$bid->prev_amount = 1;
        $bid->user_updated = $user_updated;
        if(!is_null($user_bid_count)){
            $bid->User_Bids_Count = $bid->User_Bids_Count + $user_bid_count;
        }
        $bid->save();


        if($is_auto_bid){
            $product->total_bid += $bid_count;
            $product->save();
        }
        else{
            $product->total_bid += 1;
            $product->save();
        }



        //notfication for admin
        $adminNotification = new AdminNotification();
        $adminNotification->user_id = $user->id;
        $adminNotification->title = 'A user has bid on your product';
        $adminNotification->click_url = urlPath('admin.product.bids', $product->id);
        $adminNotification->save();

        if (!$product->FirstBidDone) {
            $product->FirstBidDone = 1;
            $product->save();
        }

        if ($product->event->EventClockStartOn == 1 && $product->event->start_status == 'started') { //Manually
            $new_end_date = now()->addMinutes($product->event->less_bidding_time);
            if (isset($product->event->max_end_date)) {
                if ($new_end_date >= $product->event->max_end_date) {
                    $product->event->update([
                        'end_date' => $product->event->max_end_date,

                    ]);
                } else {
                    $product->event->update([
                        'end_date' => $new_end_date,

                    ]);
                }
            } else {
                $product->event->update([
                    'end_date' => $new_end_date,

                ]);
            }
        }

        if ($product->event->EventClockStartOn == 0) { //All product have a bid

            $allbid = true;
            foreach ($product->event->products as $product) {
                if (!$product->FirstBidDone) {
                    $allbid = false;
                    break;
                }
            }

            if ($allbid) {
                $new_end_date = now()->addMinutes($product->event->less_bidding_time);
                if (isset($product->event->max_end_date)) {
                    if ($new_end_date >= $product->event->max_end_date) {
                        $product->event->update([
                            'end_date' => $product->event->max_end_date,
                            'start_status' => 'started',
                        ]);
                    } else {
                        $product->event->update([
                            'end_date' => $new_end_date,
                            'start_status' => 'started',

                        ]);
                    }
                } else {
                    $product->event->update([
                        'end_date' => $new_end_date,
                        'start_status' => 'started',

                    ]);
                }
            }
        }


        if (!$is_auto_bid) {
            try {
                clear_all_cache();

                event(new EventPush($product->event_id));

                event(new ProductPush($product->id));
            } catch (\Exception $e) {
                Log::error($e);
                Integration::captureUnhandledException($e);
            }
        }

    }
    public function autoBidByProductId($product_id)//todo refactor, optimize ,use repository
    {
//        return DB::transaction(function () use ($product_id) {

        Log::info('Auto bid for product id: ' . $product_id . ' has been started');
        clear_all_cache();

        ini_set('memory_limit', 5120000000);
        ini_set('max_execution_time', -1);


        $settings = AutoBidSetting::where('product_id', $product_id)->where('status', 'active')->orderBy('updated_at', 'DESC')->get();
        $settings_count = count($settings);

        if ($settings_count == 0) {
            Log::info('Auto bid for product id: ' . $product_id . ' has no active settings');
            return false;
        }

        $product = Product::live()->with('merchant', 'admin')->findOrFail($product_id);

        $max_amount = Bid::where('product_id', $product_id)->max('amount');
        $max_amount = $max_amount != 0 && !is_null($max_amount) ? $max_amount : $product->price;

        $is_last_auto_bid = false;

        $last_settings = null;
        $bid_count = 0;
        $is_started_auto_bid_loop = false;
        $bids_log = [];

        $users_last_bid = array();

        $users_bid_count = array();
        $new_settings =  null;
        while ($settings_count > 1) {


            $is_last_auto_bid = false;
            reset($settings);

            foreach ($settings as $key => $setting) {
                $previous_bid = $max_amount;
                if ($setting->status == 'disable') {
                    continue;
                }


                if(round($max_amount - $setting->max_value,getRoundPrecision())>=0){
                    $setting->status = 'disable';
                    $setting->save();

                    $settings_count--;
                    unset($settings[$key]);



                    continue;
                }


                if(!is_null($last_settings) && $last_settings->user_id == $setting->user_id){
                    continue;
                }

                if(!isset($users_last_bid[$setting->user_id])){

                    $user_last_bid = Bid::where('product_id', $product_id)->where('user_id', $setting->user_id)->orderby('amount', 'desc')->first();
                    $users_last_bid[$setting->user_id] = $user_last_bid ? $user_last_bid->amount : 0;
                }
                $users_bid_count[$setting->user_id] = isset($users_bid_count[$setting->user_id]) ? $users_bid_count[$setting->user_id] + 1 : 1;

                $max_amount = $max_amount + $setting->step;

                if (round($max_amount-$setting->max_value,getRoundPrecision()) >= 0) {//if max value reached or bypassed disable the setting and set it to max setting value
                    $max_amount = $setting->max_value;
                    $setting->status = 'disable';
                    $setting->save();

                    $settings_count--;
                    unset($settings[$key]);


                    $is_last_auto_bid = true;
                }

                $last_settings = $setting;
                $bid_count++;

                $bids_log[] = array(
                    'product_id' => $product_id,
                    'new_bid' => $max_amount,
                    'user_id' => $setting->user_id,
                    'previous_bid' => $previous_bid,
                    'user_previous_bid' =>  $users_last_bid[$setting->user_id],
                );

                $users_last_bid[$setting->user_id] = $max_amount;
            }

            $is_started_auto_bid_loop = true;
        }

        if ($settings_count == 1) {//todo: check the need for this if
            $setting = AutoBidSetting::where('product_id', $product_id)->where('status', 'active')->orderby('updated_at', 'ASC')->first();//redundant call

            if($is_started_auto_bid_loop){
                if ($last_settings->user_id != $setting->user_id) {


                    if(round($max_amount - $setting->max_value,getRoundPrecision())>=0){
                        $setting->status = 'disable';
                        $setting->save();

                    }else{
                        if(!isset($users_last_bid[$setting->user_id])){
                            $user_last_bid = Bid::where('product_id', $product_id)->where('user_id', $setting->user_id)->orderby('amount', 'desc')->first();
                            $users_last_bid[$setting->user_id] = $user_last_bid ? $user_last_bid->amount : 0;
                        }

                        $bid_count++;

                        $users_bid_count[$setting->user_id] = isset($users_bid_count[$setting->user_id]) ? $users_bid_count[$setting->user_id] + 1 : 1;

                        $previous_bid = $max_amount;

                        $last_settings = $setting;

                        $max_amount = $max_amount + $setting->step;

                        if (round($max_amount - $setting->max_value,getRoundPrecision())>=0) {
                            $max_amount = $setting->max_value;
                            $setting->status = 'disable';
                            $setting->save();
                            $settings_count--;
                            $is_last_auto_bid = true;
                        }
                        $bids_log[] = array(
                            'product_id' => $product_id,
                            'new_bid' => $max_amount,
                            'user_id' => $setting->user_id,
                            'previous_bid' => $previous_bid,
                            'user_previous_bid' => $users_last_bid[$setting->user_id],
                        );

                        $users_last_bid[$setting->user_id] = $max_amount;

                    }

                }
            }
            else{
                $last_bid = Bid::where('product_id', $product_id)->orderby('amount', 'desc')->first();

                if (is_null($last_bid) || $last_bid->user_id != $setting->user_id) {

                    if(!isset($users_last_bid[$setting->user_id])){
                        $user_last_bid = Bid::where('product_id', $product_id)->where('user_id', $setting->user_id)->orderby('amount', 'desc')->first();
                        $users_last_bid[$setting->user_id] = $user_last_bid ? $user_last_bid->amount : 0;
                    }
                    $users_bid_count[$setting->user_id] = isset($users_bid_count[$setting->user_id]) ? $users_bid_count[$setting->user_id] + 1 : 1;

                    $bid_count++;
                    $last_settings = $setting;
                    $previous_bid = $max_amount;

                    $max_amount = $max_amount + $setting->step;

                    if (round($max_amount - $setting->max_value,getRoundPrecision())>=0) {
                        $max_amount = $setting->max_value;
                        $setting->status = 'disable';
                        $setting->save();
                        $settings_count--;
                        $is_last_auto_bid = true;
                    }
                    $bids_log[] = array(
                        'product_id' => $product_id,
                        'new_bid' => $max_amount,
                        'user_id' => $setting->user_id,
                        'previous_bid' => $previous_bid,
                        'user_previous_bid' => $users_last_bid[$setting->user_id],
                    );

                    $users_last_bid[$setting->user_id] = $max_amount;

                }
            }

        }

        if (!is_null($last_settings)) {

            //update or insert bid records for not wining users in auto bid
            foreach ($users_last_bid as $user_id => $amount) {
                if($amount != $max_amount){
                    $bid = Bid::where('product_id', $product_id)->where('user_id', $user_id)->first();
                    if(!is_null($bid)){
                        $bid->amount = $amount;
                        $bid->User_Bids_Count = $bid->User_Bids_Count + $users_bid_count[$user_id];
                        $bid->prev_amount =0;

                        $bid->save();
                    }
                    else{
                        $bid = new Bid();
                        $bid->product_id = $product_id;
                        $bid->user_id = $user_id;
                        $bid->amount = $amount;
                        $bid->User_Bids_Count = $users_bid_count[$user_id];
                        $bid->prev_amount =0;
                        $bid->save();
                    }
                }
            }


            $user = User::find($last_settings->user_id);
            if (!is_null($user)) {
                $user_bid_count = $users_bid_count[$user->id];
                try {
                    $this->bid($product_id, $max_amount, $user, true, $is_last_auto_bid, $bid_count, $user_bid_count);
                }catch (BidValidationException $e){
                    //todo check why the old code version simply ignores validation in the bid function
                }

            }
        }
        // dd($bids_log, $bid_count,$last_settings);

        try {
            Log::info('Auto bid for product id: ' . $product_id . ' has been ended');
            //event(new ProductAutoBidEndPush($product->id));
            clear_all_cache();
            event(new EventPush($product->event_id));
            event(new ProductPush($product->id));
        } catch (\Exception $e) {
            Log::error($e->getMessage());
            Integration::captureUnhandledException($e);
        }

        $bids_log = collect($bids_log);

        foreach ($bids_log->chunk(500) as $chunk)
        {
            \DB::table('bids_history')->insert($chunk->toArray());
        }


        //dd($bids_log);
        return true;
//        });
    }
    public function checkIfHasAutoBid($product_id)
    {
      return $this->autoBidSettingRepository->query()->where('product_id', $product_id)->where('status', 'active')->exists();
    }
    public function storeAutoBidSettings($request)//todo refactor, optimize
    {
        clear_all_cache();


//        return DB::transaction(function () use ($request) {

        $product = Product::live()->with('merchant', 'admin')->findOrFail($request->product_id);
//        if (!$product->is_in_allow_list) {
//            return response()->json(['error' => 'The product is not in the allow list.'], 400);
//        }

        if ($product->event->bid_status == 'closed') {
            throw new BidValidationException('The bid is paused.');
        }




        if (!is_null($product->max_auto_bid_price) && $request->max_value > $product->max_auto_bid_price) {
            throw new BidValidationException('The Max price in auto bid Settings can\'t be larger than ' . showAmount($product->max_auto_bid_price));
        }

        if (!is_null($product->max_auto_bid_steps)) {
            $max_bid = $product->max_bid();
            $amount = ($max_bid) ? $max_bid->amount : $product->price;
            $max_value = $request->max_value - $amount;

            $steps_count = $max_value / $request->step;
            if ($steps_count > $product->max_auto_bid_steps) {
               throw new BidValidationException('The maximum allowed steps\' count is ' . $product->max_auto_bid_steps . '. Auto bid settings can\'t be saved.');
            }
        }


        $result = $this->placeAutoBid($request);

        $max_amount = Bid::where('product_id', $request->product_id)->max('amount');
        $notify = null;
        switch ($result) {
            case 1:
               throw new BidValidationException('Your Step must be greater than ' . $product->less_bidding_value);
            case 2:
               throw new BidValidationException('Your Step must be less than ' . $product->event->max_bidding_value);
            case 3:
               throw new BidValidationException('Your Max Value must be greater than ' . ($max_amount + $product->less_bidding_value));
            case 4:
                throw new BidValidationException('Your Max Value must be greater than ' . ($product->price + $product->less_bidding_value));
//                case 5:
//                $notify = ['error' => 'The product is not in the allow list.'];
//break;

        }

        //event(new ProductAutoBidPush($product->id));

        AutoBidForProductId::dispatch($request->product_id);
//        });

    }
    #region unused functions
    public function autobid($product_id, $uid)//todo check the need for this function
    {

        $settings = AutoBidSetting::where('product_id', $product_id)->where('status', 'active')->orderby('updated_at', 'ASC')->get();
        $product = Product::live()->with('merchant', 'admin')->findOrFail($product_id);
        $event = Event::find($product->event_id);

        $settings_count = $settings->count();
        if ($settings_count == 1) {                          //if settings_count == 1
            // foreach ($settings as $setting) {
            $setting = AutoBidSetting::where('product_id', $product_id)->where('status', 'active')->first();
            if ($setting->user_id != $uid) {
                $group_id = user_in_group($event, $setting->user_id);
                if ($group_id > 0) {
                    $group = Group::find($group_id);
                    if (!$this->user_leader($group, $setting->user_id)) {
                        $last_bid = Bid::where('product_id', $product_id)->orderby('amount', 'desc')->first();
                        if ($last_bid) {
                            if ($this->user_leader($group, $last_bid->user_id)) {
                                return 0;
                            }
                        }
                    }
                }
                $bid_exist = Bid::where('product_id', $product->id)->where('user_id', $setting->user_id)->exists();
                $max_amount = Bid::where('product_id', $product->id)->max('amount');
                $new_bid = $max_amount + $setting->step;
                if (round($new_bid - $setting->max_value,getRoundPrecision())>=0) {
                    $setting->status = 'disable';
                    $setting->save();
                    if(round($max_amount - $setting->max_value,getRoundPrecision())>=0){
                        return 0;
                    }

                    // $new_bid = $setting->max_value;
                    // $settings_count=$settings_count-1;
                    if ($bid_exist) {
                        $bid = Bid::where('product_id', $product->id)->where('user_id', $setting->user_id)->first();
                        // if (round($setting->max_value - $max_amount,2) >= $product->less_bidding_value) {

                        $user_updated = $bid->user_updated;
                        $new_bid = $setting->max_value;
                        $bid->amount = $new_bid;
                        $bid->prev_amount = 1;
                        $date = new DateTime();
                        $user_updated[] = $date->format('Y-m-d H:i:s');
                        $bid->user_updated = $user_updated;
                        $bid->save();
                        $product->total_bid += 1;
                        $product->save();
                        // }
                    }
                    return 0;
                }

                // $bid_exist_different_user = Bid::where('product_id', $product->id)->exists();
                if ($bid_exist) {
                    $bid = Bid::where('product_id', $product->id)->where('user_id', $setting->user_id)->first();
                    $user_updated = $bid->user_updated;
                    $bid->amount = $new_bid;
                    $bid->prev_amount = 1;
                    $date = new DateTime();
                    $user_updated[] = $date->format('Y-m-d H:i:s');
                    $bid->user_updated = $user_updated;
                    $bid->save();
                }
                // elseif ($bid_exist_different_user) {
                //     $bid = new Bid();
                //     $bid->product_id = $product->id;
                //     $bid->user_id = $setting->user_id;
                //     $bid->amount = $new_bid;
                //     $bid->save();
                // }
                $product->total_bid += 1;
                $product->save();
                // $setting->user->balance -= $new_bid;
                // $setting->user->save();

                // $general = GeneralSetting::first();

                // $trx = getTrx();

                // $transaction = new Transaction();
                // $transaction->user_id = $setting->user->id;
                // $transaction->amount = $new_bid;
                // $transaction->post_balance = $setting->user->balance;
                // $transaction->trx_type = '-';
                // $transaction->details = 'Subtracted for a new bid';
                // $transaction->trx = $trx;
                // $transaction->save();

                if ($product->admin) {
                    $adminNotification = new AdminNotification();
                    $adminNotification->user_id = $setting->user->id;
                    $adminNotification->title = 'A user bid on your product';
                    $adminNotification->click_url = urlPath('admin.product.bids', $product->id);
                    $adminNotification->save();
                }
                if ($product->merchant) {

                    // $product->merchant->balance += $new_bid;
                    // $product->merchant->save();

                    // $transaction = new Transaction();
                    // $transaction->merchant_id = $product->merchant_id;
                    // $transaction->amount = $new_bid;
                    // $transaction->post_balance = $product->merchant->balance;
                    // $transaction->trx_type = '+';
                    // $transaction->details = showAmount($new_bid) . ' ' . $general->cur_text . ' Added for Bid';
                    // $transaction->trx =  $trx;
                    // $transaction->save();

                    // notify($product->merchant, 'BID_COMPLETE', [
                    //     'trx' => $trx,
                    //     'amount' => showAmount($new_bid),
                    //     'currency' => $general->cur_text,
                    //     'product' => $product->name,
                    //     'product_price' => showAmount($product->price),
                    //     'post_balance' => showAmount($product->merchant->balance),
                    // ], 'merchant');
                }
            }
            // }


            try {
                event(new EventPush($product->event_id));
                event(new ProductPush($product->id));
            }
            catch (\Exception $e) {
                Log::error($e);
                Integration::captureUnhandledException($e);
            }
        }
        if ($settings_count > 1) {                          //if settings_count  > 1
            while ($settings_count > 1) {
                foreach ($settings as $setting) {
                    // if ($setting->user_id != $uid) {
                    $group_id = user_in_group($event, $setting->user_id);
                    if ($group_id > 0) {
                        $group = Group::find($group_id);
                        if (!$this->user_leader($group, $setting->user_id)) {
                            $last_bid = Bid::where('product_id', $product_id)->orderby('amount', 'desc')->first();
                            if ($last_bid) {
                                if ($this->user_leader($group, $last_bid->user_id)) {
                                    break;
                                }
                            }
                        }
                    }
                    $bid_exist = Bid::where('product_id', $product->id)->where('user_id', $setting->user_id)->exists();
                    $max_amount = Bid::where('product_id', $product->id)->max('amount');
                    $new_bid = $max_amount + $setting->step;
                    if (round($new_bid - $setting->max_value,getRoundPrecision())>=0) {
                        $settings_count = $settings_count - 1;
                        $setting->status = 'disable';
                        $setting->save();
                        // $new_bid = $setting->max_value;
                        if ($bid_exist) {
                            $bid = Bid::where('product_id', $product->id)->where('user_id', $setting->user_id)->first();
                            // if (round($setting->max_value - $max_amount,2) >= $product->less_bidding_value) {
                            $user_updated = $bid->user_updated;
                            $new_bid = $setting->max_value;
                            $bid->amount = $new_bid;
                            $bid->prev_amount = 1;
                            $date = new DateTime();
                            $user_updated[] = $date->format('Y-m-d H:i:s');
                            $bid->user_updated = $user_updated;
                            $bid->save();
                            $product->total_bid += 1;
                            $product->save();
                            $activesetting = AutoBidSetting::where('product_id', $product_id)->where('status', 'active')->first();
                            if ($activesetting) {
                                $max_amount = Bid::where('product_id', $product->id)->max('amount');
                                $new_bid = $max_amount +   $activesetting->step;
                                if($new_bid>= $activesetting->max_value){
                                    $activesetting->status='disable';
                                    $activesetting->save();
                                    $bid = Bid::where('product_id', $product->id)->where('user_id',  $activesetting->user_id)->first();
                                    // if (round($activesetting->max_value - $max_amount,2) >= $product->less_bidding_value){
                                    $user_updated = $bid->user_updated;
                                    $new_bid = $activesetting->max_value;
                                    $bid->amount = $new_bid;
                                    $bid->prev_amount = 1;
                                    $date = new DateTime();
                                    $user_updated[] = $date->format('Y-m-d H:i:s');
                                    $bid->user_updated = $user_updated;
                                    $bid->save();
                                    $product->total_bid += 1;
                                    $product->save();
                                    // }
                                    break;
                                }
                                $bid = Bid::where('product_id', $product->id)->where('user_id',  $activesetting->user_id)->first();
                                $user_updated = $bid->user_updated;
                                $bid->amount = $new_bid;
                                $bid->prev_amount = 1;
                                $date = new DateTime();
                                $user_updated[] = $date->format('Y-m-d H:i:s');
                                $bid->user_updated = $user_updated;
                                $bid->save();
                                $product->total_bid += 1;
                                $product->save();
                            }
                            // }
                            // continue;
                            break;
                        }
                    }

                    // $bid_exist_different_user = Bid::where('product_id', $product->id)->exists();
                    if ($bid_exist) {
                        $bid = Bid::where('product_id', $product->id)->where('user_id', $setting->user_id)->first();
                        $user_updated = $bid->user_updated;
                        $bid->amount = $new_bid;
                        $bid->prev_amount = 1;
                        $date = new DateTime();
                        $user_updated[] = $date->format('Y-m-d H:i:s');
                        $bid->user_updated = $user_updated;
                        $bid->save();
                    }
                    // elseif ($bid_exist_different_user) {
                    //     $bid = new Bid();
                    //     $bid->product_id = $product->id;
                    //     $bid->user_id = $setting->user_id;
                    //     $bid->amount = $new_bid;
                    //     $bid->save();
                    // }
                    $product->total_bid += 1;
                    $product->save();
                    // $setting->user->balance -= $new_bid;
                    // $setting->user->save();

                    // $general = GeneralSetting::first();

                    // $trx = getTrx();

                    // $transaction = new Transaction();
                    // $transaction->user_id = $setting->user->id;
                    // $transaction->amount = $new_bid;
                    // $transaction->post_balance = $setting->user->balance;
                    // $transaction->trx_type = '-';
                    // $transaction->details = 'Subtracted for a new bid';
                    // $transaction->trx = $trx;
                    // $transaction->save();

                    if ($product->admin) {
                        $adminNotification = new AdminNotification();
                        $adminNotification->user_id = $setting->user->id;
                        $adminNotification->title = 'A user bid on your product';
                        $adminNotification->click_url = urlPath('admin.product.bids', $product->id);
                        $adminNotification->save();
                    }
                    if ($product->merchant) {

                        // $product->merchant->balance += $new_bid;
                        // $product->merchant->save();

                        // $transaction = new Transaction();
                        // $transaction->merchant_id = $product->merchant_id;
                        // $transaction->amount = $new_bid;
                        // $transaction->post_balance = $product->merchant->balance;
                        // $transaction->trx_type = '+';
                        // $transaction->details = showAmount($new_bid) . ' ' . $general->cur_text . ' Added for Bid';
                        // $transaction->trx =  $trx;
                        // $transaction->save();

                        // notify($product->merchant, 'BID_COMPLETE', [
                        //     'trx' => $trx,
                        //     'amount' => showAmount($new_bid),
                        //     'currency' => $general->cur_text,
                        //     'product' => $product->name,
                        //     'product_price' => showAmount($product->price),
                        //     'post_balance' => showAmount($product->merchant->balance),
                        // ], 'merchant');
                    }
                    // }
                }
            }

            try {
                event(new EventPush($product->event_id));
                event(new ProductPush($product->id));
            }
            catch (\Exception $e) {
                Log::error($e);
                Integration::captureUnhandledException($e);
            }

        }
    }

    public function bidProduct($product_id, $amount1, $max_value1, $step,$request)//todo: check the need for this function
    {


        $product = Product::live()->with('merchant', 'admin')->findOrFail($product_id);
        if ($step) {
            if (!is_null($product->max_auto_bid_price) && $max_value1 > $product->max_auto_bid_price) {
                throw new BidValidationException('The Max price in auto bid Settings can\'t be larger than ' . showAmount($product->max_auto_bid_price));
            }

            if (!is_null($product->max_auto_bid_steps)) {
                $max_bid = $product->max_bid();
                $amount = ($max_bid) ? $max_bid->amount : $product->price;
                $max_value = $max_value1 - $amount;

                $steps_count = $max_value / $step;
                if ($steps_count > $product->max_auto_bid_steps) {
                    throw new BidValidationException('The maximum allowed steps\' count is ' . $product->max_auto_bid_steps . '. Auto bid settings can\'t be saved.');
                }
            }


            $result = $this->placeAutoBid($request);
            $last_bid = Bid::where('product_id', $product->id)->orderby('amount', 'desc')->first();
            if ($last_bid) {
                if ($last_bid->user_id == auth()->user()->id) {
                    $notify[] = ['error', 'You can\'t outbid yourself'];
                    if ($result == 0) {
                        $notify[] = ['success', 'Current Auto Bid has been updated'];
                    }
                    throw new BidValidationException($notify[0][1].'\n'.($notify[1][1]??''));//todo check this scenario
                }
            }
            if ($result == 1) {
                throw new BidValidationException('Your Step must be greater than ' . $product->less_bidding_value);
            }
            if ($result == 2) {
                throw new BidValidationException('Your Step must be smaller than ' . $product->event->max_bidding_value);
            }
            $max_amount = Bid::where('product_id', $product_id)->max('amount');
            if ($max_amount) {
                if ($result == 3) {
                    throw new BidValidationException('Your Max Value must be greater than ' . $max_amount);
                }
                $new_bid = $max_amount + $step;
                if (round($new_bid - $max_value1,getRoundPrecision())>=0) {
                    $setting = AutoBidSetting::where('product_id', $product_id)->where('user_id', auth()->user()->id)->where('status', 'active')->first();
                    $setting->status = 'disable';
                    $setting->save();
                    if (round($max_value1 - $max_amount, 2) >= $product->less_bidding_value) {
                        $new_bid = $max_value1;
                    } else {
                        throw new BidValidationException('The difference between your maximum value and the highest bid value must be be greater than ' . $product->less_bidding_value);
                    }
                }
            } else {
                if ($result == 4) {
                    throw new BidValidationException('Your Max Value must be greater than ' . $product->price);
                }
                $new_bid = $product->price + $step;
                if ($new_bid >= $max_value1) {
                    $setting = AutoBidSetting::where('product_id', $product_id)->where('user_id', auth()->user()->id)->where('status', 'active')->first();
                    $setting->status = 'disable';
                    $setting->save();
                    if (round($max_value1 - $product->price, 2) >= $product->less_bidding_value) {
                        $new_bid = $max_value1;
                    } else {
                        throw new BidValidationException('The difference between your maximum value and the highest bid value must be be greater than ' . $product->less_bidding_value);
                    }
                }
            }
            $amount1 = $new_bid;
        }

        $last_bid = Bid::where('product_id', $product_id)->orderby('amount', 'desc')->first();
        if ($last_bid) {
            if ($last_bid->user_id == auth()->user()->id) {
                throw new BidValidationException('You can\'t outbid yourself');
            }
        }


        $user = auth()->user();
        $event = Event::find($product->event_id);
        $product->event->start_counter = $event->start_counter + 1;
        $product->event->save();
        $group_id = user_in_group($event, $user->id);
        if ($group_id > 0) {
            $group = Group::find($group_id);
            if (!$this->user_leader($group, $user->id)) {
                $last_bid = Bid::where('product_id', $product_id)->orderby('amount', 'desc')->first();
                if ($last_bid) {
                    if ($this->user_leader($group, $last_bid->user_id)) {
                        throw new BidValidationException('Your Group Leader has Last Bid');
                    }
                }
            }
        }


        $bid_exist = Bid::where('product_id', $product_id)->where('user_id', $user->id)->exists();
        $bid_exist_different_user = Bid::where('product_id', $product_id)->exists();

        if ($bid_exist) {
            $max_amount = Bid::where('product_id', $product_id)->max('amount');
            $bid = Bid::where('product_id', $product_id)->where('user_id', $user->id)->first();
            if (round($max_amount-$amount1,getRoundPrecision()) >= 0) {
                throw new BidValidationException("Bid amount must be higher than the current bid");
            }
            // if ($request->amount > $user->balance) {
            //     $notify[] = ['error', 'Insufficient Balance'];
            //     return back()->withNotify($notify);
            // }
            if (round($amount1 - $max_amount, 2) < $product->less_bidding_value) {
                throw new BidValidationException('The minimum required bid increment is ' . $product->less_bidding_value);
            }
            $Highest_possible_bid = $product->event->max_bidding_value + $max_amount;
            if (round($amount1 - $max_amount, 2) > $product->event->max_bidding_value) {
                throw new BidValidationException('Your max bid is too high. Please place a bid less than US$ ' . $Highest_possible_bid);
            }
            $user_updated = $bid->user_updated;
            $bid->amount = $amount1;
            $bid->prev_amount = 1;
            $date = new DateTime();
            $user_updated[] = $date->format('Y-m-d H:i:s');
            $bid->user_updated = $user_updated;
            $bid->save();
        } elseif ($bid_exist_different_user) {
            $max_amount = Bid::where('product_id', $product_id)->max('amount');
            if (round($max_amount-$amount1,getRoundPrecision()) >= 0) {
                throw new BidValidationException("Bid amount must be higher than the current bid");
            }
            // if ($request->amount > $user->balance) {
            //     $notify[] = ['error', 'Insufficient Balance'];
            //     return back()->withNotify($notify);
            // }
            $Highest_possible_bid = $product->event->max_bidding_value + $max_amount;
            if (round($amount1 - $max_amount, 2) < $product->less_bidding_value) {
                throw new BidValidationException('The minimum required bid increment is ' . $product->less_bidding_value);
            }
            if (round($amount1 - $max_amount, 2) > $product->event->max_bidding_value) {
                throw new BidValidationException('Your max bid is too high. Please place a bid less than US$ ' . $Highest_possible_bid);
            }
            $date = new DateTime();
            $user_updated[] = $date->format('Y-m-d H:i:s');
            $bid = new Bid();
            $bid->product_id = $product->id;
            $bid->user_id = $user->id;
            $bid->prev_amount = 1;
            $bid->amount = $amount1;
            $bid->user_updated = $user_updated;
            $bid->save();
        } else {
            // $product_price = Product::select('price')->where('id', $request->product_id)->first();
            $max_amount = $product->price;
            if (round($max_amount-$amount1,getRoundPrecision()) > 0) {
                throw new BidValidationException("Bid amount must be greater than the product price");
            }

            // if ($request->amount > $user->balance) {
            //     $notify[] = ['error', 'Insufficient Balance'];
            //     return back()->withNotify($notify);
            // }
            $Highest_possible_bid = $product->event->max_bidding_value + $max_amount;
            // if (round($request->amount - $max_amount,2) < $product->less_bidding_value) {
            //     $notify[] = ['error', 'The minimum required bid increment is ' . $product->less_bidding_value];
            //     return back()->withNotify($notify);
            // }
            if (round($amount1 - $max_amount, 2) > $product->event->max_bidding_value) {
                throw new BidValidationException('Your max bid is too high. Please place a bid less than US$ ' . $Highest_possible_bid);
            }
            $date = new DateTime();
            $user_updated[] = $date->format('Y-m-d H:i:s');
            $bid = new Bid();
            $bid->product_id = $product->id;
            $bid->user_id = $user->id;
            $bid->prev_amount = 1;
            $bid->amount = $amount1;
            $bid->user_updated = $user_updated;
            $bid->save();
        }


        $product->total_bid += 1;
        $product->save();
        // $user->balance -= $request->amount;
        // $user->save();

        // $general = GeneralSetting::first();

        // $trx = getTrx();

        // $transaction = new Transaction();
        // $transaction->user_id = $user->id;
        // $transaction->amount = $request->amount;
        // $transaction->post_balance = $user->balance;
        // $transaction->trx_type = '-';
        // $transaction->details = 'Subtracted for a new bid';
        // $transaction->trx = $trx;
        // $transaction->save();

        if ($product->admin) {
            $adminNotification = new AdminNotification();
            $adminNotification->user_id = auth()->user()->id;
            $adminNotification->title = 'A user has bid on your product';
            $adminNotification->click_url = urlPath('admin.product.bids', $product->id);
            $adminNotification->save();

            if (!$product->FirstBidDone) {
                $product->FirstBidDone = 1;
                $product->save();
            }
            if ($product->event->EventClockStartOn == 1 && $product->event->start_status == 'started') { //Manually
                $new_end_date = now()->addMinutes($product->event->less_bidding_time);
                if (isset($product->event->max_end_date)) {
                    if ($new_end_date >= $product->event->max_end_date) {
                        $product->event->update([
                            'end_date' => $product->event->max_end_date,

                        ]);
                    } else {
                        $product->event->update([
                            'end_date' => $new_end_date,

                        ]);
                    }
                } else {
                    $product->event->update([
                        'end_date' => $new_end_date,

                    ]);
                }
            }

            if ($product->event->EventClockStartOn == 0) { //All product have a bid

                $allbid = true;
                foreach ($product->event->products as $product) {
                    if (!$product->FirstBidDone) {
                        $allbid = false;
                        break;
                    }
                }

                if ($allbid) {
                    $new_end_date = now()->addMinutes($product->event->less_bidding_time);
                    if (isset($product->event->max_end_date)) {
                        if ($new_end_date >= $product->event->max_end_date) {
                            $product->event->update([
                                'end_date' => $product->event->max_end_date,
                                'start_status' => 'started',
                            ]);
                        } else {
                            $product->event->update([
                                'end_date' => $new_end_date,
                                'start_status' => 'started',

                            ]);
                        }
                    } else {
                        $product->event->update([
                            'end_date' => $new_end_date,
                            'start_status' => 'started',

                        ]);
                    }
                }
            }

            // if (now()->diffInMinutes($product->event->end_date) <= $product->event->less_bidding_time) {
            //     $new_end_date = now()->addMinutes($product->event->less_bidding_time);
            //     if ($product->event->max_end_date) {
            //         if ($new_end_date >= $product->event->max_end_date) {
            //             $product->event->update([
            //                 'end_date' => $product->event->max_end_date,
            //             ]);
            //         } else {
            //             $product->event->update([
            //                 'end_date' => $new_end_date,
            //             ]);
            //         }
            //     } else {
            //         $product->event->update([
            //             'end_date' =>   $new_end_date,
            //         ]);
            //     }
            // }


            try {
                event(new EventPush($product->event_id));
                event(new ProductPush($product->id));
            } catch (\Exception $e) {
                Log::error($e);
                Integration::captureUnhandledException($e);
            }


            $this->autobid($product_id, $user->id);



            return;
        }

        // $product->merchant->balance += $request->amount;
        // $product->merchant->save();

        // $transaction = new Transaction();
        // $transaction->merchant_id = $product->merchant_id;
        // $transaction->amount = $request->amount;
        // $transaction->post_balance = $product->merchant->balance;
        // $transaction->trx_type = '+';
        // $transaction->details = showAmount($request->amount) . ' ' . $general->cur_text . ' Added for Bid';
        // $transaction->trx =  $trx;
        // $transaction->save();

        // notify($product->merchant, 'BID_COMPLETE', [
        //     'trx' => $trx,
        //     'amount' => showAmount($request->amount),
        //     'currency' => $general->cur_text,
        //     'product' => $product->name,
        //     'product_price' => showAmount($product->price),
        //     'post_balance' => showAmount($product->merchant->balance),
        // ], 'merchant');


        // if (now()->diffInHours($product->event->end_date) <= $product->less_bidding_time) {
        //     $new_end_date = now()->addHours($product->less_bidding_time);
        //     if ($product->event->max_end_date) {
        //         if ($new_end_date >= $product->event->max_end_date) {
        //             $product->event->update([
        //                 'end_date' => $product->event->max_end_date,
        //             ]);
        //         } else {
        //             $product->event->update([
        //                 'end_date' => $new_end_date,
        //             ]);
        //         }
        //     } else {
        //         $product->event->update([
        //             'end_date' =>   $new_end_date,
        //         ]);
        //     }
        // }

        if (!$product->FirstBidDone) {
            $product->FirstBidDone = 1;
            $product->save();
        }
        if ($product->event->EventClockStartOn == 1 && $product->event->start_status == 'started') { //Manually
            $new_end_date = now()->addMinutes($product->event->less_bidding_time);
            if (isset($product->event->max_end_date)) {
                if ($new_end_date >= $product->event->max_end_date) {
                    $product->event->update([
                        'end_date' => $product->event->max_end_date,

                    ]);
                } else {
                    $product->event->update([
                        'end_date' => $new_end_date,

                    ]);
                }
            } else {
                $product->event->update([
                    'end_date' => $new_end_date,
                ]);
            }
        }

        if ($product->event->EventClockStartOn == 0) { //All product have a bid

            $allbid = true;
            foreach ($product->event->products as $product) {
                if (!$product->FirstBidDone) {
                    $allbid = false;
                    break;
                }
            }

            if ($allbid) {
                $new_end_date = now()->addMinutes($product->event->less_bidding_time);
                if (isset($product->event->max_end_date)) {
                    if ($new_end_date >= $product->event->max_end_date) {
                        $product->event->update([
                            'end_date' => $product->event->max_end_date,
                            'start_status' => 'started',

                        ]);
                    } else {
                        $product->event->update([
                            'end_date' => $new_end_date,
                            'start_status' => 'started',

                        ]);
                    }
                } else {
                    $product->event->update([
                        'end_date' => $new_end_date,
                        'start_status' => 'started',

                    ]);
                }
            }
        }


        try {
            event(new EventPush($product->event_id));
            event(new ProductPush($product->id));
        } catch (\Exception $e) {
            Log::error($e);
            Integration::captureUnhandledException($e);
        }

        $this->autobid($product_id, $user->id);

    }
    public function user_leader($group, $user_id)//todo check the need for this function
    {
        $result = false;
        if ($user_id == $group->leader_id) {
            $result = true;
        }
        return $result;
    }
    #endregion
}
