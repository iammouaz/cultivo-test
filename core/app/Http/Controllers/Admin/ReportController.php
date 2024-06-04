<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BidHistory;
use App\Models\EmailLog;
use App\Models\Event;
use App\Models\Product;
use App\Models\Transaction;
use App\Models\UserLogin;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function userTransaction()
    {
        $pageTitle = __('User Transactions');
        $transactions = Transaction::where('user_id', '!=', 0)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = __('No transactions.');
        return view('admin.reports.user_transactions', compact('pageTitle', 'transactions', 'emptyMessage'));
    }

    public function userTransactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = 'User Transactions Search - ' . $search;
        $emptyMessage = __('No transactions.');

        $transactions = Transaction::with('user')->whereHas('user', function ($user) use ($search) {
            $user->where('username', 'like', "%$search%");
        })->orWhere('trx', $search)->orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.reports.user_transactions', compact('pageTitle', 'transactions', 'emptyMessage', 'search'));
    }

    public function merchantTransaction()
    {
        $pageTitle = __('Merchant Transactions');
        $transactions = Transaction::where('merchant_id', '!=', 0)->with('merchant')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = __('No transactions.');
        return view('admin.reports.merchant_transactions', compact('pageTitle', 'transactions', 'emptyMessage'));
    }

    public function merchantTransactionSearch(Request $request)
    {
        $request->validate(['search' => 'required']);
        $search = $request->search;
        $pageTitle = 'Merchant Transactions Search - ' . $search;
        $emptyMessage = __('No transactions.');

        $transactions = Transaction::with('merchant')->whereHas('merchant', function ($merchant) use ($search) {
            $merchant->where('username', 'like', "%$search%");
        })->orWhere('trx', $search)->orderBy('id', 'desc')->paginate(getPaginate());

        return view('admin.reports.merchant_transactions', compact('pageTitle', 'transactions', 'emptyMessage', 'search'));
    }

    public function userLoginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'User Logins Search - ' . $search;
            $emptyMessage = 'No search result found.';
            $login_logs = UserLogin::where('user_id', '!=', 0)->whereHas('user', function ($query) use ($search) {
                $query->where('username', $search);
            })->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
            return view('admin.reports.user_logins', compact('pageTitle', 'emptyMessage', 'search', 'login_logs'));
        }
        $pageTitle = __('User Logins');
        $emptyMessage = __('No users login found.');
        $login_logs = UserLogin::where('user_id', '!=', 0)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        return view('admin.reports.user_logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }

    public function userLoginIpHistory($ip)
    {
        $pageTitle = 'Login By - ' . $ip;
        $login_logs = UserLogin::where('user_ip', $ip)->where('user_id', '!=', 0)->orderBy('id', 'desc')->with('user')->paginate(getPaginate());
        $emptyMessage = __('No users login found.');
        return view('admin.reports.user_logins', compact('pageTitle', 'emptyMessage', 'login_logs', 'ip'));

    }

    public function merchantLoginHistory(Request $request)
    {
        if ($request->search) {
            $search = $request->search;
            $pageTitle = 'Merchant Login History Search - ' . $search;
            $emptyMessage = __('No search result found.');
            $login_logs = UserLogin::where('merchant_id', '!=', 0)->whereHas('merchant', function ($query) use ($search) {
                $query->where('username', $search);
            })->orderBy('id', 'desc')->with('merchant')->paginate(getPaginate());
            return view('admin.reports.merchant_logins', compact('pageTitle', 'emptyMessage', 'search', 'login_logs'));
        }
        $pageTitle = __('Merchant Login History');
        $emptyMessage = __('No merchants login found.');
        $login_logs = UserLogin::where('merchant_id', '!=', 0)->orderBy('id', 'desc')->with('merchant')->paginate(getPaginate());
        return view('admin.reports.merchant_logins', compact('pageTitle', 'emptyMessage', 'login_logs'));
    }

    public function merchantLoginIpHistory($ip)
    {
        $pageTitle = 'Login By - ' . $ip;
        $login_logs = UserLogin::where('user_ip', $ip)->where('merchant_id', '!=', 0)->orderBy('id', 'desc')->with('merchant')->paginate(getPaginate());
        $emptyMessage = __('No merchants login found.');
        return view('admin.reports.merchant_logins', compact('pageTitle', 'emptyMessage', 'login_logs', 'ip'));

    }

    public function userEmailHistory()
    {
        $pageTitle = __('User Email history');
        $logs = EmailLog::where('user_id', '!=', 0)->with('user')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = __('No data found');
        return view('admin.reports.user_email_history', compact('pageTitle', 'emptyMessage', 'logs'));
    }

    public function merchantEmailHistory()
    {
        $pageTitle = __('Merchant Email history');
        $logs = EmailLog::where('merchant_id', '!=', 0)->with('merchant')->orderBy('id', 'desc')->paginate(getPaginate());
        $emptyMessage = __('No data found');
        return view('admin.reports.merchant_email_history', compact('pageTitle', 'emptyMessage', 'logs'));
    }

    public function BidHistory(Request $request)
    {
        $event_id = $request->input('event_id');
        $product_id = $request->input('product_id');
        $pageTitle = __('Product Bid history');
        $emptyMessage = __('No data found');
        $events = Event::latest()->get();
        $products = null;
        if ($event_id) {
            $products = Product::where('event_id', $event_id)->get();
        }

        $logs = BidHistory::latest();

        if ($product_id) {
            $logs->where('product_id', $product_id);
        } else if ($event_id) {
            $product_ids = Product::where('event_id', $event_id)->pluck('id')->toArray();
            $logs->whereIn('product_id', $product_ids);
        }

        $logs = $logs->paginate(getPaginate());

        return view('admin.reports.product_bid_history', compact('pageTitle', 'events', 'products', 'event_id', 'product_id', 'emptyMessage', 'logs'));
    }
}
