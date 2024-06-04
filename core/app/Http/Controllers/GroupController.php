<?php

namespace App\Http\Controllers;

use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;

class GroupController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }
    public function group_users($id){

        $group = Group::find($id);
        $users = $group->users()->paginate(getPaginate());
        $pageTitle      = "All Group Users";
        $emptyMessage   = "No Users Found";
        return view($this->activeTemplate.'user.group.users', compact('pageTitle', 'emptyMessage', 'users', 'id'));

    }

    public function make_leader($id, $user_id)
    {
        $user=User::find($user_id);
        $group = Group::find($id);
        Invitation::create([
            'user_id'=>$user_id,
            'group_id'=>$group->id,
            'invitation_type'=>1,
        ]);
        sendEmail($user, 'Delegate_Group_Leader',[
            'name'=>$user->fullname(),
            'event'=>$group->event->name,
            'delegator'=>auth()->user()->fullname,
            'link' => route('user.home'),
        ]);
        $notify[] = ['success', __('Leader added successfully.')];
        return back()->withNotify($notify);
    }

   
}
