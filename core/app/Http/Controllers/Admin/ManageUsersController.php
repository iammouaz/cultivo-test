<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Bid;
use App\Models\Deposit;
use App\Models\EmailLog;
use App\Models\Event;
use App\Models\Gateway;
use App\Models\GeneralSetting;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\User;
use App\Models\UserLogin;
use App\Models\UserRequest;
use App\Models\WithdrawMethod;
use App\Models\Withdrawal;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ManageUsersController extends Controller
{
    public function allUsers()
    {
        $pageTitle = __('Manage Users');
        $emptyMessage = __('No user found');
        session()->put('event_id', 0);
        $users = User::latest()->paginate(getPaginate());
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }

    public function hasApprovedUpcomingEvent()//todo repair search bug
    {
        $pageTitle = __('Users who Opted-in for Upcoming Events');
        $emptyMessage = __('No user found');
        session()->put('event_id', 0);
        $users = User::where('i_contacted_upcoming_auctions',1)->latest()->paginate(getPaginate());
        $events = [];
        return view('admin.users.list',
            compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }
    public function DownloadCsvApprovedUpcomingEvent(Request $request)
    {
       
        $eventId = $request->event_id??null;
        if($eventId != null){
            try {
                $event = Event::findOrFail($eventId);
                $users = $event->users()->where('i_contacted_upcoming_auctions',1);
            } catch (\Exception $e) {
                $notify[] = ['error', 'Event not found'];
                return back()->withNotify($notify);
            }
           
           
        }else{
            $users = User:: where('i_contacted_upcoming_auctions',1);
        }
        $users = $users->latest()->get();

        // select firstname ,lastname ,email from users where i_contacted_upcoming_auctions=1
        $users = $users->map(function ($user) {
            return [
                'first Name' => $user->firstname ?? '',
                'last Name' => $user->lastname ?? '',
                'full Name' => $user->firstname ??'' .' '.$user->lastname ??'',
                'username' => $user->username ?? '',
                'Email' => $user->email ?? '',
                'opt_in' => $user->i_contacted_upcoming_auctions == 1 ? 'Yes' : 'No',
                'company name' => $user->company_name ?? '',
                'country code' => $user->country_code ?? '',
                'mobile' => $user->mobile ?? '',
                'country' => $user->country->Name??'',
                'ace member' => $user->ace_member == 1 ? 'Yes' : 'No',
                'company website' => $user->company_website ?? '',
            ];
        });

        $fileName = 'Approved_Upcoming_Events_Users_' . date('d-m-y') . '.csv';
        $headers = array(
            "Content-type" => "text/csv",
            "Content-Disposition" => "attachment; filename=$fileName",
            "Pragma" => "no-cache",
            "Cache-Control" => "must-revalidate, post-check=0, pre-check=0",
            "Expires" => "0"
        );
        $columns = array('First Name', 'Last Name', 'Full Name', 'username', 'Email', 'opt_in', 'company_name', 'country_code', 'mobile', 'country', 'ace_member', 'company_website');
        $callback = function () use ($users, $columns) {
            $file = fopen('php://output', 'w');
            fputcsv($file, $columns);
            foreach ($users as $user) {
                fputcsv($file, array_values($user));
            }
            fclose($file);
        };
        return response()->stream($callback, 200, $headers);
      
        
    }
    public function activeevents(Request $request)
    {
        $pageTitle = __('User Events');
        $emptyMessage = __('No events found');
        $id = $request->user_id;
        // $user=User::find($id);
        // $event_ids= UserRequest::where('status',1)->where('user_id',$id)->pluck('event_id')->toArray();
        if ($request->status == '0') {
            $events = Event::latest()->paginate(getPaginate());
            // $events=$user->events()->latest()->paginate(getPaginate());
            session()->put('filter', 0);
        }
        if ($request->status == '1') {
            $events = Event::where('status', 'active')->latest()->paginate(getPaginate());
            // $events=$user->events()->where('events.status','active')->latest()->paginate(getPaginate());
            session()->put('filter', 1);
        }
        return view('admin.users.events', compact('pageTitle', 'emptyMessage', 'events', 'id'));
    }

    public function events($id)
    {
        $user=User::find($id);
        $pageTitle = 'User '.$user->username.' Events';
        $emptyMessage = __('No events found');
        // $user=User::find($id);
        // $event_ids= UserRequest::where('status',1)->where('user_id',$id)->pluck('event_id')->toArray();
        $events = Event::latest()->paginate(getPaginate());
        // $events=$user->events()->OrderBy('date_accept','desc')->paginate(getPaginate());
        session()->put('filter', 0);
        return view('admin.users.events', compact('pageTitle', 'emptyMessage', 'events', 'id'));
    }

    // public function event_search(Request $request){
    //     $user=User::find($request->user_id);
    //     $id=$request->user_id;
    //     $pageTitle = 'User '.$user->username.' Events';
    //     $emptyMessage = __('No events found');
    //     $events=$user->events()->where('name', 'like', "%$request->search%")->OrderBy('date_accept','desc')->paginate(getPaginate());
    //     return view('admin.users.events', compact('pageTitle', 'emptyMessage', 'events', 'id'));
    // }

    public function products($event_id, $user_id)
    {
        $pageTitle = __('User Products');
        $emptyMessage = __('No products found');
        $ev_id = $event_id;
        $us_id = $user_id;
        $user = User::find($user_id);
        $product_ids = $user->products()->wherepivot('event_id', $event_id)->pluck('product_id')->toArray();
        $products = Product::latest()->where('event_id', $event_id)->paginate(getPaginate(100));

        return view('admin.users.products', compact('pageTitle', 'emptyMessage', 'products', 'product_ids', 'ev_id', 'us_id'));
    }

    public function activeUsers()
    {
        $pageTitle = __('Manage Active Users');
        $emptyMessage = __('No active user found');
        session()->put('event_id', 0);
        $users = User::active()->orderBy('id', 'desc')->paginate(getPaginate());
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }

    public function bannedUsers()
    {
        $pageTitle = __('Banned Users');
        $emptyMessage = __('No banned user found');
        session()->put('event_id', 0);
        $users = User::banned()->orderBy('id', 'desc')->paginate(getPaginate());
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }

    public function emailUnverifiedUsers()
    {
        $pageTitle = __('Email Unverified Users');
        $emptyMessage = __('No email unverified user found');
        session()->put('event_id', 0);
        $users = User::emailUnverified()->orderBy('id', 'desc')->paginate(getPaginate());
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }
    public function emailVerifiedUsers()
    {
        $pageTitle = __('Email Verified Users');
        $emptyMessage = __('No email verified user found');
        session()->put('event_id', 0);
        $users = User::emailVerified()->orderBy('id', 'desc')->paginate(getPaginate());
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }


    public function smsUnverifiedUsers()
    {
        $pageTitle = __('SMS Unverified Users');
        $emptyMessage = __('No sms unverified user found');
        session()->put('event_id', 0);
        $users = User::smsUnverified()->orderBy('id', 'desc')->paginate(getPaginate());
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }


    public function smsVerifiedUsers()
    {
        $pageTitle = __('SMS Verified Users');
        $emptyMessage = __('No sms verified user found');
        session()->put('event_id', 0);
        $users = User::smsVerified()->orderBy('id', 'desc')->paginate(getPaginate());
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }


    public function usersWithBalance()
    {
        $pageTitle = __('Users with balance');
        $emptyMessage = __('No user found with balance');
        session()->put('event_id', 0);
        $users = User::where('balance', '!=', 0)->orderBy('id', 'desc')->paginate(getPaginate());
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events'));
    }


    public function filter_by_event(Request $request, $scope)
    {

        $event = Event::find($request->event_id);
        session()->put('event_id', $request->event_id);
        $emptyMessage = __('No users found');
        if ($scope == 'all') {
            if ($request->event_id == 0) {
                $pageTitle = __('All Users');
                $users = User::latest()->paginate(getPaginate());
            } else {
                $pageTitle = 'All Users with Event ' . $event->name;
                $users = $event->users()->paginate(getPaginate());
            }
        } elseif ($scope == 'active') {
            if ($request->event_id == 0) {
                $pageTitle = __('Active Users');
                $users = User::active()->latest()->paginate(getPaginate());
            } else {
                $pageTitle = 'Active Users with Event ' . $event->name;
                $users = $event->users()->where('status', 1)->paginate(getPaginate());
            }
        } elseif ($scope == 'banned') {
            if ($request->event_id == 0) {
                $pageTitle = __('Banned Users');
                $users = User::banned()->latest()->paginate(getPaginate());
            } else {
                $pageTitle = 'Banned Users with Event ' . $event->name;
                $users = $event->users()->where('status', 0)->paginate(getPaginate());
            }
        } elseif ($scope == 'email.unverified') {
            if ($request->event_id == 0) {
                $pageTitle = __('emailUnverified Users');
                $users = User::emailUnverified()->latest()->paginate(getPaginate());
            } else {
                $pageTitle = 'emailUnverified Users with Event ' . $event->name;
                $users = $event->users()->where('ev', 0)->paginate(getPaginate());
            }
        } elseif ($scope == 'sms.unverified') {
            if ($request->event_id == 0) {
                $pageTitle = __('smsUnverified Users');
                $users = User::smsUnverified()->latest()->paginate(getPaginate());
            } else {
                $pageTitle = 'smsUnverified Users with Event ' . $event->name;
                $users = $event->users()->where('sv', 0)->paginate(getPaginate());
            }
        } elseif ($scope == 'with.balance') {
            if ($request->event_id == 0) {
                $pageTitle = __('with Balance Users');
                $users = User::where('balance', '!=', 0)->latest()->paginate(getPaginate());
            } else {
                $pageTitle = 'with Balance Users with Event ' . $event->name;
                $users = $event->users()->where('balance', '!=', 0)->paginate(getPaginate());
            }
        }


        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'emptyMessage', 'users', 'events', 'scope'));
    }



    public function search(Request $request, $scope)
    {
        $search = $request->search;
        $users = User::where(function ($user) use ($search) {
            $user->where('username', 'like', "%$search%")
                ->orWhere('email', 'like', "%$search%")
                ->orwhere('company_name','like',"%$search%")
                ->orwhere('firstname','like',"%$search%")
                ->orwhere('lastname','like',"%$search%")
                ->orWhere(DB::raw("CONCAT(`firstname`, ' ', `lastname`)"), 'LIKE', "%".$search."%");
        });
        $pageTitle = '';
        if ($scope == 'active') {
            $pageTitle = 'Active ';
            $users = $users->where('status', 1);
        } elseif ($scope == 'banned') {
            $pageTitle = 'Banned';
            $users = $users->where('status', 0);
        } elseif ($scope == 'email.unverified') {
            $pageTitle = 'Email Unverified ';
            $users = $users->where('ev', 0);
        } elseif ($scope == 'sms.unverified') {
            $pageTitle = 'SMS Unverified ';
            $users = $users->where('sv', 0);
        } elseif ($scope == 'with.balance') {
            $pageTitle = 'With Balance ';
            $users = $users->where('balance', '!=', 0);
        }

        $users = $users->paginate(getPaginate());
        $pageTitle .= 'User Search - ' . $search;
        $emptyMessage = __('No search result found');
        session()->put('event_id', 0);
        $events = Event::latest()->get();
        return view('admin.users.list', compact('pageTitle', 'search', 'scope', 'emptyMessage', 'users', 'events'));
    }


    public function detail($id)
    {
        $pageTitle = __('User Detail');
        $user = User::findOrFail($id);
        $totalDeposit = Deposit::where('user_id', $user->id)->where('status', 1)->sum('amount');
        $totalTransaction = Transaction::where('user_id', $user->id)->count();
        $totalBid = Bid::where('user_id', $user->id)->count();
        $totalBidAmount = Bid::where('user_id', $user->id)->sum('amount');
        $countries = json_decode(file_get_contents(resource_path('views/partials/country.json')));
        return view('admin.users.detail', compact('pageTitle', 'user', 'totalDeposit', 'totalTransaction', 'totalBid', 'totalBidAmount', 'countries'));
    }


    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);

        $countryData = json_decode(file_get_contents(resource_path('views/partials/country.json')));

        $request->validate([
            'firstname' => 'required|max:50',
            'lastname' => 'required|max:50',
            'email' => 'required|email|max:90|unique:users,email,' . $user->id,
            'mobile' => 'required|unique:users,mobile,' . $user->id,
            'country' => 'required',
        ]);
        $countryCode = $request->country;
        $user->mobile = $request->mobile;
        $user->country_code = $countryCode;
        $user->firstname = $request->firstname;
        $user->lastname = $request->lastname;
        $user->email = $request->email;
        $user->address = [
            'address' => $request->address,
            'city' => $request->city,
            'state' => $request->state,
            'zip' => $request->zip,
            'country' => @$countryData->$countryCode->country,
        ];
        $user->status = $request->status ? 1 : 0;
        $user->ev = $request->ev ? 1 : 0;
        $user->sv = $request->sv ? 1 : 0;
        $user->ts = $request->ts ? 1 : 0;
        $user->tv = $request->tv ? 1 : 0;
        $user->save();

        $notify[] = ['success', __('User detail has been updated')];
        return redirect()->back()->withNotify($notify);
    }

    public function addSubBalance(Request $request, $id)
    {
        $request->validate(['amount' => 'required|numeric|gt:0']);

        $user = User::findOrFail($id);
        $amount = $request->amount;
        $general = GeneralSetting::first(['cur_text', 'cur_sym']);
        $trx = getTrx();

        if ($request->act) {
            $user->balance += $amount;
            $user->save();
            $notify[] = ['success', $general->cur_sym . $amount . ' has been added to ' . $user->username . '\'s balance'];

            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '+';
            $transaction->details = 'Added Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();

            notify($user, 'BAL_ADD', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($user->balance),
            ]);
        } else {
            if ($amount > $user->balance) {
                $notify[] = ['error', $user->username . '\'s has insufficient balance.'];
                return back()->withNotify($notify);
            }
            $user->balance -= $amount;
            $user->save();



            $transaction = new Transaction();
            $transaction->user_id = $user->id;
            $transaction->amount = $amount;
            $transaction->post_balance = $user->balance;
            $transaction->charge = 0;
            $transaction->trx_type = '-';
            $transaction->details = 'Subtract Balance Via Admin';
            $transaction->trx =  $trx;
            $transaction->save();


            notify($user, 'BAL_SUB', [
                'trx' => $trx,
                'amount' => showAmount($amount),
                'currency' => $general->cur_text,
                'post_balance' => showAmount($user->balance)
            ]);
            $notify[] = ['success', $general->cur_sym . $amount . ' has been subtracted from ' . $user->username . '\'s balance'];
        }
        return back()->withNotify($notify);
    }


    public function userLoginHistory($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'User Login History - ' . $user->username;
        $emptyMessage = __('No users login found.');
        $login_logs = $user->login_logs()->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.users.logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }



    public function showEmailSingleForm($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Send Email To: ' . $user->username;
        return view('admin.users.email_single', compact('pageTitle', 'user'));
    }

    public function sendEmailSingle(Request $request, $id)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        $user = User::findOrFail($id);
        sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        $notify[] = ['success', $user->username . ' will receive an email shortly.'];
        return back()->withNotify($notify);
    }

    public function transactions(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search User Transactions : ' . $user->username;
            $transactions = $user->transactions()->where('trx', $search)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
            $emptyMessage = __('No transactions');
            return view('admin.reports.user_transactions', compact('pageTitle', 'search', 'user', 'transactions', 'emptyMessage'));
        }
        $pageTitle = 'User Transactions : ' . $user->username;
        $transactions = $user->transactions()->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = __('No transactions');
        return view('admin.reports.user_transactions', compact('pageTitle', 'user', 'transactions', 'emptyMessage'));
    }

    public function deposits(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $userId = $user->id;
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search User Deposits : ' . $user->username;
            $deposits = $user->deposits()->where('trx', $search)->orderBy('id', 'desc')->paginate(getPaginate());
            $emptyMessage = __('No deposits');
            return view('admin.deposit.log', compact('pageTitle', 'search', 'user', 'deposits', 'emptyMessage', 'userId'));
        }

        $pageTitle = 'User Deposit : ' . $user->username;
        $deposits = $user->deposits()->orderBy('id', 'desc')->with(['gateway', 'user'])->paginate(getPaginate());
        $successful = $user->deposits()->orderBy('id', 'desc')->where('status', 1)->sum('amount');
        $pending = $user->deposits()->orderBy('id', 'desc')->where('status', 2)->sum('amount');
        $rejected = $user->deposits()->orderBy('id', 'desc')->where('status', 3)->sum('amount');
        $emptyMessage = __('No deposits');
        $scope = 'all';
        return view('admin.deposit.log', compact('pageTitle', 'user', 'deposits', 'emptyMessage', 'userId', 'scope', 'successful', 'pending', 'rejected'));
    }


    public function depViaMethod($method, $type = null, $userId)
    {
        $method = Gateway::where('alias', $method)->firstOrFail();
        $user = User::findOrFail($userId);
        if ($type == 'approved') {
            $pageTitle = 'Approved Payment Via ' . $method->name;
            $deposits = Deposit::where('method_code', '>=', 1000)->where('user_id', $user->id)->where('method_code', $method->code)->where('status', 1)->orderBy('id', 'desc')->with(['user', 'gateway'])->paginate(getPaginate());
        } elseif ($type == 'rejected') {
            $pageTitle = 'Rejected Payment Via ' . $method->name;
            $deposits = Deposit::where('method_code', '>=', 1000)->where('user_id', $user->id)->where('method_code', $method->code)->where('status', 3)->orderBy('id', 'desc')->with(['user', 'gateway'])->paginate(getPaginate());
        } elseif ($type == 'successful') {
            $pageTitle = 'Successful Payment Via ' . $method->name;
            $deposits = Deposit::where('status', 1)->where('user_id', $user->id)->where('method_code', $method->code)->orderBy('id', 'desc')->with(['user', 'gateway'])->paginate(getPaginate());
        } elseif ($type == 'pending') {
            $pageTitle = 'Pending Payment Via ' . $method->name;
            $deposits = Deposit::where('method_code', '>=', 1000)->where('user_id', $user->id)->where('method_code', $method->code)->where('status', 2)->orderBy('id', 'desc')->with(['user', 'gateway'])->paginate(getPaginate());
        } else {
            $pageTitle = 'Payment Via ' . $method->name;
            $deposits = Deposit::where('status', '!=', 0)->where('user_id', $user->id)->where('method_code', $method->code)->orderBy('id', 'desc')->with(['user', 'gateway'])->paginate(getPaginate());
        }
        $pageTitle = 'Deposit History: ' . $user->username . ' Via ' . $method->name;
        $methodAlias = $method->alias;
        $emptyMessage = __('Deposit Log');
        return view('admin.deposit.log', compact('pageTitle', 'emptyMessage', 'deposits', 'methodAlias', 'userId'));
    }

    public function bids(Request $request, $id)
    {
        $user = User::findOrFail($id);
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Search User Bids : ' . $user->username;
            $bids = $user->bids()->where('trx', $search)->with('user', 'product')->orderBy('id', 'desc')->paginate(getPaginate());
            $emptyMessage = __('No bids');
            return view('admin.reports.bids', compact('pageTitle', 'search', 'user', 'bids', 'emptyMessage'));
        }
        $pageTitle = 'User Bids : ' . $user->username;
        $bids = $user->bids()->with('user', 'product')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = __('No bids');
        return view('admin.users.bids', compact('pageTitle', 'user', 'bids', 'emptyMessage'));
    }


    public function showEmailAllForm()
    {
        $pageTitle = __('Send Email To All Users');
        return view('admin.users.email_all', compact('pageTitle'));
    }

    public function sendEmailAll(Request $request)
    {
        $request->validate([
            'message' => 'required|string|max:65000',
            'subject' => 'required|string|max:190',
        ]);

        foreach (User::where('status', 1)->cursor() as $user) {
            sendGeneralEmail($user->email, $request->subject, $request->message, $user->username);
        }

        $notify[] = ['success', __('All users will receive an email shortly.')];
        return back()->withNotify($notify);
    }

    public function login($id)
    {
        $user = User::findOrFail($id);
        Auth::login($user);
        return redirect()->route('user.home');
    }

    public function emailLog($id)
    {
        $user = User::findOrFail($id);
        $pageTitle = 'Email log of ' . $user->username;
        $logs = EmailLog::where('user_id', $id)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = __('No data found');
        return view('admin.users.email_log', compact('pageTitle', 'logs', 'emptyMessage', 'user'));
    }

    public function emailDetails($id)
    {
        $email = EmailLog::findOrFail($id);
        $pageTitle = __('Email details');
        return view('admin.users.email_details', compact('pageTitle', 'email'));
    }
}
