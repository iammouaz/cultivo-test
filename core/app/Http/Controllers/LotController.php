<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\Lot;
use App\Models\Product;
use Illuminate\Http\Request;

class LotController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }
    public function show_users($id)
    {
        $pageTitle = __('My Members');
        $emptyMessage = __('No Members found');
        $product = Product::find($id);
        $event = $product->event;
        $group_id = 0;
        foreach ($event->groups as $group) {
            $invitation = Invitation::where('user_id', auth()->user()->id)->where('group_id', $group->id)->where('status', 1)->exists();
            if ($invitation) {
                $group_id = $group->id;
                break;
            }
        }
        if ($group_id > 0) {
            $group = Group::find($group_id);
        }
        $users = $group->users()->paginate(getPaginate());
        return view($this->activeTemplate . 'user.split_lot', compact('pageTitle', 'emptyMessage', 'users','group_id','event','product'));
    }


    public function store(Request $request){
        if(!isset($request->lot)){
            $notify[] = ['error', __('Please Set Lot.')];
            return back()->withNotify($notify);
        }
        Lot::create($request->all());
        $notify[] = ['success', __('Lot Store successfully.')];
        return back()->withNotify($notify);
     }
}
