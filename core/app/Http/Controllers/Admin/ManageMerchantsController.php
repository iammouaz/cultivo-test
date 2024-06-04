<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use App\Models\GeneralSetting;
use App\Models\Merchant;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\Withdrawal;
use App\Models\WithdrawMethod;
use App\Rules\FileTypeValidate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;





class ManageMerchantsController extends Controller
{
    public function allMerchants()
    {
        $pageTitle = __('Manage Merchants');
        $emptyMessage = __('No merchant found');
        $merchants = Merchant::orderBy('id','desc')->paginate(getPaginate());
        return view('admin.merchants.list', compact('pageTitle', 'emptyMessage', 'merchants'));
    }
    public function addMerchant()
    {
        $pageTitle = __('Add Merchant');
        $info = json_decode(json_encode(getIpInfo()), true);
        $mobile_code = @implode(',', $info['code']);
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.merchants.add_merchant', compact('pageTitle','mobile_code','countries'));
    }
    protected function validation($request, $imgValidation){
        $request->validate([
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'username' => 'required|max:50',
            'email' => 'nullable|email|max:90|unique:merchants,email,',
            'mobile' => 'nullable|unique:merchants,mobile,',
            'country' => 'nullable',
        ]);
    }

    public function regMerchant(Request $request)
    {

        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $this->validation($request, 'required');
        $countryCode = $request->country;
        $merchant = new Merchant();
        $merchant->mobile = $request->mobile;
        $merchant->country_code = $countryCode;
        $merchant->username = $request->username;
        $merchant->firstname = $request->firstname;
        $merchant->lastname = $request->lastname;
        $merchant->email = $request->email;
        $merchant->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => @$countryData->$countryCode->country,
                        ];
        $merchant->status = $request->status ? 1 : 0;
        $merchant->ev = $request->ev ? 1 : 0;
        $merchant->sv = $request->sv ? 1 : 0;
        $merchant->ts = $request->ts ? 1 : 0;
        $merchant->tv = $request->tv ? 1 : 0;
        $merchant->save();

        $notify[] = ['success', __('The account has been added successfully')];
        return redirect()->back()->withNotify($notify);
    }

    public function activeMerchants()
    {
        $pageTitle = __('Manage Active Merchants');
        $emptyMessage = __('No active merchant found');
        $merchants = Merchant::active()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.merchants.list', compact('pageTitle', 'emptyMessage', 'merchants'));
    }

    public function bannedMerchants()
    {
        $pageTitle = __('Banned Merchants');
        $emptyMessage = __('No banned merchant found');
        $merchants = Merchant::banned()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.merchants.list', compact('pageTitle', 'emptyMessage', 'merchants'));
    }

    public function emailUnverifiedMerchants()
    {
        $pageTitle = __('Email Unverified Merchants');
        $emptyMessage = __('No email unverified merchant found');
        $merchants = Merchant::emailUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.merchants.list', compact('pageTitle', 'emptyMessage', 'merchants'));
    }
    public function emailVerifiedMerchants()
    {
        $pageTitle = __('Email Verified Merchants');
        $emptyMessage = __('No email verified merchant found');
        $merchants = Merchant::emailVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.merchants.list', compact('pageTitle', 'emptyMessage', 'merchants'));
    }


    public function smsUnverifiedMerchants()
    {
        $pageTitle = __('SMS Unverified Merchants');
        $emptyMessage = __('No sms unverified merchant found');
        $merchants = Merchant::smsUnverified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.merchants.list', compact('pageTitle', 'emptyMessage', 'merchants'));
    }


    public function smsVerifiedMerchants()
    {
        $pageTitle = __('SMS Verified Merchants');
        $emptyMessage = __('No sms verified merchant found');
        $merchants = Merchant::smsVerified()->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.merchants.list', compact('pageTitle', 'emptyMessage', 'merchants'));
    }

    
    public function merchantsWithBalance()
    {
        $pageTitle = __('Merchants with balance');
        $emptyMessage = __('No merchant found with balance');
        $merchants = Merchant::where('balance','!=',0)->orderBy('id','desc')->paginate(getPaginate());
        return view('admin.merchants.list', compact('pageTitle', 'emptyMessage', 'merchants'));
    }


    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $merchants = Merchant::where(function ($merchant) use ($search) {
            $merchant->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%");
        });
        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $merchants = $merchants->where('status', 1);
        }elseif($scope == 'banned'){
            $pageTitle = 'Banned';
            $merchants = $merchants->where('status', 0);
        }elseif($scope == 'emailUnverified'){
            $pageTitle = 'Email Unverified ';
            $merchants = $merchants->where('ev', 0);
        }elseif($scope == 'smsUnverified'){
            $pageTitle = 'SMS Unverified ';
            $merchants = $merchants->where('sv', 0);
        }elseif($scope == 'withBalance'){
            $pageTitle = 'With Balance ';
            $merchants = $merchants->where('balance','!=',0);
        }

        $merchants = $merchants->paginate(getPaginate());
        $pageTitle .= 'Merchant Search - ' . $search;
        $emptyMessage = __('No search result found');
        return view('admin.merchants.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'merchants'));
    }


    public function detail($id)
    {
        $pageTitle = __('Merchant Detail');
        $merchant = Merchant::findOrFail($id);
        $totalWithdraw = Withdrawal::where('merchant_id',$merchant->id)->where('status',1)->sum('amount');
        $totalTransaction = Transaction::where('merchant_id',$merchant->id)->count();
        $total_products = Product::where('merchant_id', $id)->count();

        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.merchants.detail', compact('pageTitle', 'merchant','totalWithdraw','totalTransaction', 'total_products', 'countries'));
    }


    public function update(Request $request, $id)
    {
        $merchant = Merchant::findOrFail($id);

        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $request->validate([
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => 'nullable|email|max:90|unique:merchants,email,' . $merchant->id,
            'mobile' => 'nullable|unique:merchants,mobile,' . $merchant->id,
            'country' => 'nullable',
            'image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])],
            'cover_image' => ['nullable','image',new FileTypeValidate(['jpg','jpeg','png'])]
        ]);
        $countryCode = $request->country;
        $merchant->mobile = $request->mobile;
        $merchant->country_code = $countryCode;
        $merchant->firstname = $request->firstname;
        $merchant->lastname = $request->lastname;
        $merchant->email = $request->email;
        $merchant->address = [
                            'address' => $request->address,
                            'city' => $request->city,
                            'state' => $request->state,
                            'zip' => $request->zip,
                            'country' => @$countryData->$countryCode->country,
                        ];
        $merchant->status = $request->status ? 1 : 0;
        $merchant->ev = $request->ev ? 1 : 0;
        $merchant->sv = $request->sv ? 1 : 0;
        $merchant->ts = $request->ts ? 1 : 0;
        $merchant->tv = $request->tv ? 1 : 0;
        if ($request->hasFile('image')) {
            try {
                $old = $merchant->image ?: null;
                $merchant->image = uploadImageToS3($request->image, imagePath()['profile']['merchant']['path'], imagePath()['profile']['merchant']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Image could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }

        if ($request->hasFile('cover_image')) {
            try {
                $old = $merchant->cover_image ?: null;
                $merchant->cover_image = uploadImageToS3($request->cover_image, imagePath()['profile']['merchant_cover']['path'], imagePath()['profile']['merchant_cover']['size'], $old);
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Image could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }
        $merchant->save();

        $notify[] = ['success', __('Merchant detail has been updated')];
        return redirect()->back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric|gt:0']);

        $merchant = Merchant::findOrFail($id);
        $amount = $request->amount;
        $general = GeneralSetting::first(['cur_text','cur_sym']);
        $trx = getTrx();

        if ($request->act) {
            $merchant->balance += $amount;
            $merchant->save();
            $notify[] = ['success', $general->cur_sym . $amount . ' has been added to ' . $merchant->username . '\'s balance'];

            $transaction = new Transaction();
            $transaction->merchant_id = $merchant->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $merchant->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->details = 'Added Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();

            notify($merchant, 'BAL_ADD', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($merchant->balance),
            ], 'merchant');

        } else {
            if ($amount > $merchant->balance) {
                $notify[] = ['error', $merchant->username . '\'s has insufficient balance.'];
                return back()->withNotify($notify);
            }
            $merchant->balance -= $amount;
            $merchant->save();

            $transaction = new Transaction();
            $transaction->merchant_id = $merchant->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $merchant->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '-';
            $transaction->details = 'Subtract Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();


            notify($merchant, 'BAL_SUB', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($merchant->balance)
            ], 'merchant');
            $notify[] = ['success', $general->cur_sym . $amount . ' has been subtracted from ' . $merchant->username . '\'s balance'];
        }
        return back()->withNotify($notify);
    }


    public function merchantLoginHistory($id)
    {
        $merchant = Merchant::findOrFail($id);
        $pageTitle = 'Merchant Login History - ' . $merchant->username;
        $emptyMessage = __('No merchants login found.');
        $login_logs = $merchant->login_logs()->orderBy('id','desc')->with('merchant')->paginate(getPaginate());
        return view('admin.merchants.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }



    public function showEmailSingleForm($id)
    {
        $merchant = Merchant::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $merchant->username;
        return view('admin.merchants.email_single', compact('pageTitle', 'merchant'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $merchant = Merchant::findOrFail($id);
        sendGeneralEmail($merchant->email, $request->subject, $request->message, $merchant->username);
        $notify[] = ['success', $merchant->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function transactions(Request $request, $id)
    {
        $merchant = Merchant::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search Merchant Transactions : ' . $merchant->username;
            $transactions = $merchant->transactions()->where('trx', $search)->with('merchant')->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = __('No transactions');
            return view('admin.reports.merchant_transactions', compact('pageTitle', 'search', 'merchant', 'transactions', 'emptyMessage'));
        }
        $pageTitle = 'Merchant Transactions : ' . $merchant->username;
        $transactions = $merchant->transactions()->with('merchant')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = __('No transactions');
        return view('admin.reports.merchant_transactions', compact('pageTitle', 'merchant', 'transactions', 'emptyMessage'));
    }

    public function products(Request $request, $id){
        $merchant = Merchant::findOrFail($id);
        $pageTitle = 'Merchant Product : ' . $merchant->username;
        $products = Product::with('merchant')->where('merchant_id', $id)->orderBy('id', 'DESC')->paginate(getPaginate());
        $emptyMessage = __('No product found');
        return view('admin.product.index', compact('pageTitle', 'merchant', 'products', 'emptyMessage'));
    }


    public function withdrawals(Request $request, $id)
    {
        $merchant = Merchant::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search Merchant Withdrawals : ' . $merchant->username;
            $withdrawals = $merchant->withdrawals()->where('trx', 'like',"%$search%", )->orderBy('id','desc')->paginate(getPaginate());
            $emptyMessage = __('No withdrawals');
            return view('admin.withdraw.withdrawals', compact('pageTitle', 'merchant', 'search', 'withdrawals', 'emptyMessage'));
        }
        $pageTitle = 'Merchant Withdrawals : ' . $merchant->username;
        $withdrawals = $merchant->withdrawals()->with('method', 'merchant')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = __('No withdrawals');
        $merchantId = $merchant->id;
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'merchant', 'withdrawals', 'emptyMessage','merchantId'));
    }

    public  function withdrawalsViaMethod($method,$type,$merchantId){
        $method = WithdrawMethod::findOrFail($method);
        $merchant = Merchant::findOrFail($merchantId);
        if ($type == 'approved') {
            $pageTitle = 'Approved Withdrawal of '.$merchant->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 1)->where('merchant_id',$merchant->id)->with(['merchant','method'])->orderBy('id','desc')->paginate(getPaginate());
        }elseif($type == 'rejected'){
            $pageTitle = 'Rejected Withdrawals of '.$merchant->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 3)->where('merchant_id',$merchant->id)->with(['merchant','method'])->orderBy('id','desc')->paginate(getPaginate());

        }elseif($type == 'pending'){
            $pageTitle = 'Pending Withdrawals of '.$merchant->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', 2)->where('merchant_id',$merchant->id)->with(['merchant','method'])->orderBy('id','desc')->paginate(getPaginate());
        }else{
            $pageTitle = 'Withdrawals of '.$merchant->username.' Via '.$method->name;
            $withdrawals = Withdrawal::where('status', '!=', 0)->where('merchant_id',$merchant->id)->with(['merchant','method'])->orderBy('id','desc')->paginate(getPaginate());
        }
        $emptyMessage = 'Withdraw Log Not Found';
        return view('admin.withdraw.withdrawals', compact('pageTitle', 'withdrawals', 'emptyMessage','method'));
    }

    public function showEmailAllForm()
    {
        $pageTitle = __('Send Email To All Merchants');
        return view('admin.merchants.email_all', compact('pageTitle'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (Merchant::where('status', 1)->cursor() as $merchant) {
            sendGeneralEmail($merchant->email, $request->subject, $request->message, $merchant->username);
        }

        $notify[] = ['success', __('All merchants will receive an email shortly.')];
        return back()->withNotify($notify);
    }

    public function login($id){
        $merchant = Merchant::findOrFail($id);
        Auth::guard('merchant')->login($merchant);
        return redirect()->route('merchant.dashboard');
    }

    public function emailLog($id){
        $merchant = Merchant::findOrFail($id);
        $pageTitle = 'Email log of '.$merchant->username;
        $logs = EmailLog::where('merchant_id',$id)->with('merchant')->orderBy('id','desc')->paginate(getPaginate());
        $emptyMessage = __('No data found');
        return view('admin.merchants.email_log', compact('pageTitle','logs','emptyMessage','merchant'));
    }

    public function emailDetails($id){
        $email = EmailLog::findOrFail($id);
        $pageTitle = __('Email details');
        return view('admin.merchants.email_details', compact('pageTitle','email'));
    }
}
