<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Group;
use App\Models\Invitation;
class InvitationController extends Controller
{
    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }

    public function invitations(){
        $pageTitle = __('My Invitations');
        $emptyMessage = __('No Invitations found');
        $invitations=Invitation::where('user_id',auth()->user()->id)->orderby('id','desc')->paginate(getPaginate());
        return view($this->activeTemplate.'user.invitations', compact('pageTitle', 'emptyMessage', 'invitations'));
    }

    public function approve_invitation($id){

        $invitation=Invitation::find($id);
        $group=Group::find($invitation->group_id);
        $user=auth()->user();
        $groups=Invitation::where('user_id',$user->id)->where('group_id',$group->id)->where('invitation_type',0)->get();
        if($groups->count()>1){
            $invitation->status=0;
            $invitation->save();
            $notify[] = ['error', __('you have joined to other group in the same event.')];
            return back()->withNotify($notify);
        }
        $invitation->status=1;
        $invitation->save();
        if($invitation->invitation_type==1){
            $group->leader_id=$user->id;
            $group->save();
        }
        $notify[] = ['success', __('Invitation approved successfully.')];
        return back()->withNotify($notify);
    }

    public function reject_invitation($id){
        $invitation=Invitation::find($id);
        $invitation->status=0;
        $invitation->save();
        $notify[] = ['success', __('Invitation reject successfully.')];
        return back()->withNotify($notify);
    }


}
