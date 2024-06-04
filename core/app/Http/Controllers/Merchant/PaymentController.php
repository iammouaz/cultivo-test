<?php

namespace App\Http\Controllers\Merchant;

use App\Http\Controllers\Controller;
use App\Models\Payment;
use Illuminate\Http\Request;

class PaymentController extends Controller
{
    public function index()
    {
        $payments  = Payment::where('user_id', auth()->user()->id)->get();
        return view('merchant.payment.index', compact('payments'));
    }

    public function create()
    {
        return view('merchant.payment.create');
    }
}
