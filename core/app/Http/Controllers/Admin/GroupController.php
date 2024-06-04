<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Group;
use App\Models\Invitation;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;

class GroupController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;

    public function index($id)
    {
        $event=Event::find($id);
        $groups       = $event->groups()->latest()->paginate(getPaginate());
        $pageTitle      = __("All Event Groups");
        $emptyMessage   = __("No Groups Found");
        return view('admin.group.index', compact('pageTitle', 'emptyMessage', 'groups','event'));
    }

    public function create($id)
    {
       
        $pageTitle = __('Create group');
        $users=User::orderBy('id','desc')->get();
        return view('admin.group.create', compact('pageTitle','users','id'));
    }

    public function edit($id)
    {
       
        $pageTitle = __('Update group');
        $group = Group::findOrFail($id);
        return view('admin.group.edit', compact('pageTitle','group'));
    }

    public function store(Request $request)
    {
        $this->validation($request, 'required');
        $group = new Group();
        $this->saveGroup($request, $group);
        Invitation::create([
            'user_id'=>$request->user_id,
            'group_id'=>$group->id,
            'status'=>1,
            'invitation_type'=>1,
        ]);
        $user=User::findOrFail($request->user_id);
        sendEmail($user, 'Group_Created',[
            'name'=>$user->fullname,
            'event'=>$group->event->name,
        ]);
        $notify[] = ['success', __('Group added successfully')];
        return back()->withNotify($notify);
    }
    public function update(Request $request,$id)
    {
        $this->validation($request, 'nullable');
        $group = Group::findOrFail($id);
        $this->saveGroup($request, $group);
        $notify[] = ['success', __('Group updated successfully')];
        return back()->withNotify($notify);
    }
    public function saveGroup($request, $group)
    {
        if ($request->hasFile('image')) {
            try {
                $group->image = uploadImage($request->image, imagePath()['group']['path'], imagePath()['group']['size'], $group->image, imagePath()['group']['thumb']);
            } catch (\Exception $exp) {
                $notify[] = ['error', __('Image could not be uploaded.')];
                return back()->withNotify($notify);
            }
        }
        if($request->has('user_id')){

            $group->leader_id=$request->user_id;
        }

        $group->name = $request->name;
        $group->description = $request->description;
        $group->event_id = $request->event_id;
        $group->save();
    }

    protected function validation($request, $imgValidation)
    {
        $request->validate([
            'name'            => 'required',
            'description'     => 'required',
            'image'           => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        ]);
    }


    public function group_users($group_id)
    {

        $group = Group::find($group_id);
        $event=$group->event;
        $users = $group->users()->paginate(getPaginate());
        $pageTitle      = __("All Group Users");
        $emptyMessage   = __("No Users Found");
        return view('admin.group.users', compact('pageTitle', 'emptyMessage', 'users','event', 'group_id'));
    }

  
}
