<?php

namespace App\Http\Controllers;

use App\Lib\GoogleAuthenticator;
use App\Models\Bid;
use App\Models\Country;
use App\Models\Deposit;
use App\Models\GeneralSetting;
use App\Models\SupportTicket;
use App\Models\Transaction;
use App\Models\Winner;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function home()
    {
//        return redirect()->route('user.profile.setting');//todo implement dashboard view for commerce
        $pageTitle = __('Dashboard');
        $widget['balance']              = Auth::user()->balance;
        $widget['total_deposit']        = Deposit::where('user_id', auth()->id())->where('status', 1)->sum('amount');
        $widget['total_bid']            = Bid::where('user_id', auth()->id())->count();
        $widget['total_bid_amount']     = Bid::where('user_id', auth()->id())->sum('amount');
        $widget['total_wining_product'] = Winner::where('user_id', auth()->id())->count();
        $widget['total_transactions']   = Transaction::where('user_id', auth()->id())->count();
        $widget['total_tickets']        = SupportTicket::where('user_id', auth()->id())->count();
        $widget['waiting_for_result']   = $widget['total_bid'] - Winner::with('product.bids')->whereHas('product.bids', function($bid){
            $bid->where('user_id', auth()->id());
        })->count();
        $transactions                   = Transaction::where('user_id', auth()->id())->latest()->limit(8)->get();

        return view($this->activeTemplate . 'user.dashboard', compact('pageTitle', 'widget', 'transactions'));
    }

    public function biddingHistory(){
        $pageTitle = __('My Bidding History');
        $emptyMessage = __('No bidding history found');
        $biddingHistories = Bid::where('user_id', auth()->id())->with('user', 'product')->latest()->paginate(getPaginate());

        return view($this->activeTemplate.'user.bidding_history', compact('pageTitle', 'emptyMessage', 'biddingHistories'));
    }

    public function winningHistory(){
        $pageTitle = __('My Winning History');
        $emptyMessage = __('No winning history found');
        $winningHistories = Winner::where('user_id', auth()->id())->with('user','product', 'bid')->latest()->paginate(getPaginate());

        return view($this->activeTemplate.'user.winning_history', compact('pageTitle', 'emptyMessage', 'winningHistories'));
    }

    public function transactions(){
        $pageTitle = __('All Transaction');
        $emptyMessage = __('No transaction history found');
        $transactions = Transaction::where('user_id', auth()->id())->latest()->paginate(getPaginate());

        return view($this->activeTemplate.'user.transactions', compact('pageTitle', 'emptyMessage', 'transactions'));
    }

    public function profile(Request $request)
    {
        $pageTitle = __("Profile Setting");
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']??[]);
        $user = Auth::user();
        //$countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        $countries = Country::all();

        return view($this->activeTemplate. 'user.profile_setting', compact('pageTitle',
            'user','mobile_code','countries'));
    }

    public function submitProfile(Request $request)
    {
        $perv_url = session('after_profile_submited');

        $request->validate([
            'firstname' => 'required|string|max:50',
            'lastname' => 'required|string|max:50',
//            'address' => 'sometimes|required|max:80',
//            'state' => 'sometimes|required|max:80',
//            'zip' => 'sometimes|required|max:40',
//            'city' => 'sometimes|required|max:50',
            'image' => ['image',new FileTypeValidate(['jpg','jpeg','png'])]
        ],[
            'firstname.required'=>__('First name field is required'),
            'lastname.required'=>__('Last name field is required')
        ]);

        $user = Auth::user();

        if ($user->is_agree != $request->agree){
            $in['is_agree'] = $request->agree;
            if ($request->agree == 0){
                $in['agree_date'] = null;
            }else{
                $in['agree_date'] = now();
            }
        }
        $in['firstname'] = $request->firstname;
        $in['lastname'] = $request->lastname;
        $in['email'] = $request->email;
        $in['shipping_first_name'] = $request->shipping_first_name;
        $in['shipping_last_name'] = $request->shipping_last_name;
        $in['shipping_company'] = $request->shipping_company;
        $in['shipping_address_1'] = $request->shipping_address_1;
        $in['shipping_address_2'] = $request->shipping_address_2;
        $in['shipping_city'] = $request->shipping_city;
        $in['shipping_state'] = $request->shipping_state;
        $in['shipping_postcode'] = $request->shipping_postcode;
        $in['shipping_country'] = $request->shipping_country;
        $in['shipping_phone'] = $request->shipping_phone;
        $in['billing_first_name'] = $request->billing_first_name;
        $in['billing_last_name'] = $request->billing_last_name;
        $in['billing_company'] = $request->billing_company;
        $in['billing_address_1'] = $request->billing_address_1;
        $in['billing_address_2'] = $request->billing_address_2;
        $in['billing_city'] = $request->billing_city;
        $in['billing_state'] = $request->billing_state;
        $in['billing_postcode'] = $request->billing_postcode;
        $in['billing_country'] = $request->billing_country;
        $in['billing_phone'] = $request->billing_phone;
        $in['address'] = [
            'address' => $request->address,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$user->address->country,
            'city' => $request->city,
        ];

        //new
        $in['company_name'] = $request->input('company_name');//
        $in['company_website'] = $request->input('company_website');//
        $in['describes_business'] = $request->describes_business;//
        $in['pounds_green_coffee'] = $request->pounds_green_coffee;//
        $in['hosting_cupping'] = $request->hosting_cupping;//
        $in['billing_company_name'] = $request->billing_company_name;//
     //   $in['ein_number'] = $request->ein_number;//
        $in['delivery'] = $request->delivery;//
        $in['preferred_receiving_day'] = json_encode($request->input('preferred_receiving_day'));//
        $in['preferred_receiving_times'] = json_encode($request->input('preferred_receiving_times'));//
        $in['other_special_delivery'] = $request->other_special_delivery;
        $in['exclusive_offers'] = $request->exclusive_offers;

        $in['opt_in'] = isset($request->opt_in) ? $request->opt_in : 0;
        $in['i_contacted_upcoming_auctions'] = isset($request->i_contacted_upcoming_auctions) ?
        $request->i_contacted_upcoming_auctions : false;
        $in['opt_in_upcoming_events_date'] = isset($request->i_contacted_upcoming_auctions) ?
        now() : null;



        if ($request->hasFile('image')) {
            $location = imagePath()['profile']['user']['path'];
            $size = imagePath()['profile']['user']['size'];
            $filename = uploadImage($request->image, $location, $size, $user->image);
            $in['image'] = $filename;
        }
        $user->fill($in)->save();
        $notify[] = ['success', __('Profile updated successfully.')];


        return redirect()->route('event.all')->with('notify', $notify);
        if($perv_url){
            return redirect($perv_url)->withNotify($notify);
        }

        return back()->withNotify($notify);
    }

    public function changePassword()
    {
        $pageTitle = __('Change password');
        return view($this->activeTemplate . 'user.password', compact('pageTitle'));
    }

    public function submitPassword(Request $request)
    {

        $password_validation = Password::min(6);
        $general = GeneralSetting::first();
        if ($general->secure_password) {
            $password_validation = $password_validation->mixedCase()->numbers()->symbols()->uncompromised();
        }

        $this->validate($request, [
            'current_password' => 'required',
            'password' => ['required','confirmed',$password_validation]
        ]);


        try {
            $user = auth()->user();
            if (Hash::check($request->current_password, $user->password)) {
                $password = Hash::make($request->password);
                $user->password = $password;
                $user->save();
                $notify[] = ['success', __('Password changes successfully.')];
                return back()->withNotify($notify);
            } else {
                $notify[] = ['error', __('The password doesn\'t match!')];
                return back()->withNotify($notify);
            }
        } catch (\PDOException $e) {
            $notify[] = ['error', $e->getMessage()];
            return back()->withNotify($notify);
        }
    }

    /*
     * Deposit History
     */
    public function depositHistory()
    {
        $pageTitle = __('Deposit History');
        $emptyMessage = __('No deposit history found.');
        $logs = auth()->user()->deposits()->with(['gateway'])->orderBy('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.deposit_history', compact('pageTitle', 'emptyMessage', 'logs'));
    }

    public function show2faForm()
    {
        $general = GeneralSetting::first();
        $ga = new GoogleAuthenticator();
        $user = auth()->user();
        $secret = $ga->createSecret();
        $qrCodeUrl = $ga->getQRCodeGoogleUrl($user->username . '@' . $general->sitename, $secret);
        $pageTitle = 'Two Factor';
        return view($this->activeTemplate.'user.twofactor', compact('pageTitle', 'secret', 'qrCodeUrl'));
    }

    public function create2fa(Request $request)
    {
        $user = auth()->user();
        $this->validate($request, [
            'key' => 'required',
            'code' => 'required',
        ]);
        $response = verifyG2fa($user,$request->code,$request->key);
        if ($response) {
            $user->tsc = $request->key;
            $user->ts = 1;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_ENABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', __('Google authenticator enabled successfully')];
            return back()->withNotify($notify);
        } else {
            $notify[] = ['error', __('Wrong verification code')];
            return back()->withNotify($notify);
        }
    }


    public function disable2fa(Request $request)
    {
        $this->validate($request, [
            'code' => 'required',
        ]);

        $user = auth()->user();
        $response = verifyG2fa($user,$request->code);
        if ($response) {
            $user->tsc = null;
            $user->ts = 0;
            $user->save();
            $userAgent = getIpInfo();
            $osBrowser = osBrowser();
            notify($user, '2FA_DISABLE', [
                'operating_system' => @$osBrowser['os_platform'],
                'browser' => @$osBrowser['browser'],
                'ip' => @$userAgent['ip'],
                'time' => @$userAgent['time']
            ]);
            $notify[] = ['success', __('Two factor authenticator disable successfully')];
        } else {
            $notify[] = ['error', __('Wrong verification code')];
        }
        return back()->withNotify($notify);
    }


}
