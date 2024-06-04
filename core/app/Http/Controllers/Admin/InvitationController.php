<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;

class InvitationController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;

    public function index($id)
    {
        $users       = User::paginate(getPaginate());
        $pageTitle      = __("All Users");
        $emptyMessage   = __("No Users Found");
        $group_id = $id;
        $group = Group::find($group_id);
        $event = $group->event;
        return view('admin.invitation.index', compact('pageTitle', 'emptyMessage', 'users', 'event', 'group_id'));
    }

    public function store($group_id, $user_id)
    {
        $user = User::find($user_id);
        $group = Group::find($group_id);
        if ($group->leader_id == $user_id) {
            $notify[] = ['error', __('This user is leader of the group')];
            return back()->withNotify($notify);
        }
        $prev_invitation = Invitation::where('user_id', $user_id)->where('group_id', $group_id)->where('invitation_type',0)->where('status', -1)->first();
        if ($prev_invitation) {
            $notify[] = ['error', __('Invitation Pending')];
            return back()->withNotify($notify);
        }
        $invitation = new Invitation();
        $this->saveInvitation($group_id, $user_id, $invitation,0);
        sendEmail($user, 'INVITATION', [
            'name'=>$user->fullname,
            'event'=>$group->event->name,
            'leader'=>$group->leader->fullname,
            'link' => route('user.home'),
        ]);
        $notify[] = ['success', __('Invitation added successfully')];
        return back()->withNotify($notify);
    }

    public function make_leader($group_id, $user_id)
    {
        $user = User::find($user_id);
        $group = Group::find($group_id);
        if ($group->leader_id == $user_id) {
            $notify[] = ['error', __('This user is leader of the group')];
            return back()->withNotify($notify);
        }
        $prev_invitation = Invitation::where('user_id', $user_id)->where('group_id', $group_id)->where('invitation_type',1)->where('status', -1)->first();
        if ($prev_invitation) {
            $notify[] = ['error', __('Invitation Pending')];
            return back()->withNotify($notify);
        }
        $invitation = new Invitation();
        $this->saveInvitation($group_id, $user_id, $invitation,1);
        sendEmail($user, 'Delegate_Group_Leader',[
            'name'=>$user->fullname(),
            'event'=>$group->event->name,
            'delegator'=>auth()->user()->fullname,
            'link' => route('user.home'),
        ]);
        $notify[] = ['success', __('Invitation added successfully.')];
        return back()->withNotify($notify);
    }

    public function saveInvitation($group_id, $user_id, $invitation,$type)
    {
        $invitation->user_id = $user_id;
        $invitation->group_id = $group_id;
        $invitation->invitation_type = $type;
        $invitation->save();
    }
}
