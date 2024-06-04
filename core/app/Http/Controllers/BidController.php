<?php

namespace App\Http\Controllers;

use App\Exceptions\BidValidationException;
use App\Http\PusherEvents\EventPush;
use App\Http\PusherEvents\ProductAutoBidEndPush;
use App\Http\PusherEvents\ProductAutoBidPush;
use App\Http\PusherEvents\ProductPush;
use App\Jobs\AutoBidForProductId;
use App\Models\AdminNotification;
use App\Models\AutoBidSetting;
use App\Models\Bid;
use App\Models\Event;
use App\Models\Group;
use App\Models\Product;
use App\Models\User;
use App\Services\BidService;
use DateTime;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class BidController extends Controller
{
    /**
     * @var BidService
     */
    protected $bidService;
    public function __construct()
    {
        $this->bidService = app('bidService');
    }
    public function store(Request $request)
    {
        clear_all_cache();

        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'amount' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
        ]);


        $user = auth()->user();


        try {
            $this->bidService->bid($request->product_id, $request->amount, $user);
        }
        catch (BidValidationException $ex){
            $notify= ['error'=> $ex->getMessage()];
            return response()->json($notify, 400);
        }

        $has_auto_bid = $this->bidService->checkIfHasAutoBid($request->product_id);
        if ($has_auto_bid) {
            //event(new ProductAutoBidPush($request->product_id));
            AutoBidForProductId::dispatch($request->product_id);
        }

        $notify = ['success' => __('Successful Bid')];
        return response()->json($notify, 200);
    }

    public function storeAutoBidSettings(Request $request)
    {
        $validatedData = $request->validate([
            'product_id' => 'required|integer',
            'max_value' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            'step' => 'required|regex:/^\d{1,13}+(\.\d{1,2})?$/',
            'is_disable' => 'nullable',
        ]);
        if ($request->input('is_disable')) {

            try {
                $this->bidService->disableAutoBid($validatedData['product_id'], auth()->user()->id);
            } catch (BidValidationException $ex) {
                $notify = ['error' => $ex->getMessage()];
                return response()->json($notify, 400);
            }
            $notify = ['success' => __('Current Auto Bid has been disabled')];
            return $notify;
        }
        try {
            $this->bidService->storeAutoBidSettings($request);
        }
        catch (BidValidationException $ex){
            $notify= ['error'=> $ex->getMessage()];
            return response()->json($notify, 400);
        }

        return response()->json(['success' => __('Auto Bid Settings saved successfully.')]);

    }




}

