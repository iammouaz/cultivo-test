<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use App\Models\PermissionGroup;
use App\Models\Product;
use App\Models\User;

class PermissiongroupController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;


    public function index()
    {
        $permissiongroups       = PermissionGroup::with('event')->paginate(getPaginate());
        $pageTitle      = __("All Permission Group");
        $emptyMessage   = __("No Permission Group Found");
        return view('admin.permission_group.index', compact('pageTitle', 'emptyMessage', 'permissiongroups'));
    }

    public function create()
    {
        $pageTitle = __('Create permission group');
        $events = Event::all();
        $users = User::all()->where('status' , '!=' ,0);
        $products       = Product::live()->get();
        return view('admin.permission_group.create', compact('pageTitle','events','users','products'));
    }

    public function edit($id)
    {
        $pageTitle = __('Update permission group');

        $permissiongroup = PermissionGroup::with('users')->with('products')->findOrFail($id);
        $events = Event::all();
        $users = User::all()->where('status' , '!=' ,0);
        $products       = Product::live()->get();
        

        return view('admin.permission_group.edit', compact('pageTitle', 'permissiongroup', 'events','users','products'));
    }

    public function store(Request $request)
    {
        $this->validation($request, 'required');
        $permissiongroup = new PermissionGroup();
        
        $this->savePermission($request, $permissiongroup);
        $notify[] = ['success', __('Permission Group added successfully')];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $this->validation($request, 'nullable');
        $permissiongroup = PermissionGroup::findOrFail($id);
        $this->savePermission($request, $permissiongroup);
        $notify[] = ['success', __('Permission Group  updated successfully')];
        return back()->withNotify($notify);
    }
    public function savePermission($request, $permissiongroup)
    {

        $permissiongroup->name_group = $request->name;
        $permissiongroup->status = $request->status;
        $permissiongroup->save();
        
        $permissiongroup->event()->sync($request->events_id);
        $permissiongroup->users()->sync($request->users_id);
        $permissiongroup->products()->sync($request->product_id);
        
    }

    protected function validation($request)
    {
        $request->validate([
            'name'            => 'required',
            'events_id'     => 'required',
            'users_id'        => 'required',
        ]);
    }

    
}
