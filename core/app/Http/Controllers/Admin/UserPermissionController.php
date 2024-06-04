<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\UserRequest;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use App\Models\PermissionGroup;
use App\Models\Product;
use App\Models\User;
use App\Models\UserEvent;
use App\Models\UserPermission;
use Illuminate\Support\Facades\DB;

class UserPermissionController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;


    public function index()
    {
        $userpermissions      = DB::table('user_events')->distinct('user_id')->select('users.id','users.firstname','users.lastname','events.name as event')
                                                        ->leftjoin('users','user_events.user_id','=','users.id')
                                                        ->leftjoin('events','user_events.event_id','=','events.id')
                                                        ->where('users.id','!=',null)
                                                        ->paginate(getPaginate());
        $pageTitle      = __("All User Permission");
        $emptyMessage   = __("No Permission User Found");
        return view('admin.user_permission.index', compact('pageTitle', 'emptyMessage', 'userpermissions'));
    }

    public function create()
    {
        $pageTitle = __('Create user permission');
        $events = Event::all();
        $users = User::all()->where('status' , '!=' ,0);
        $products       = Product::live()->get();
        return view('admin.user_permission.create', compact('pageTitle','events','users','products'));
    }

    public function edit($id)
    {
       /* $pageTitle = 'Update user permission';

        $userpermission = UserPermission::with('user')->with('products')->findOrFail($id);
        $events = Event::all();
        $users = User::all()->where('status' , '!=' ,0);
        $products       = Product::live()->get();


        return view('admin.user_permission.edit', compact('pageTitle', 'userpermission', 'events','users','products'));*/
    }

    public function store(Request $request)
    {
        $this->validation($request, 'required');

        $this->savePermission($request);
        $notify[] = ['success', __('User Permission added successfully')];
        return back()->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        $this->validation($request, 'required');

        $this->updatePermission($request,$id);
        $notify[] = ['success', __('User Permission updated successfully')];
        return back()->withNotify($notify);
    }
    public function savePermission($request)//todo check the need for this function
    {

        if(!$request->has('product_id')){
            $notify[] = ['error', __('Please select products')];
            return back()->withNotify($notify);
            }
            $user=User::find($request->user_id);
            foreach($request->events_id as $event_id){
                $userRequest = UserRequest::where('event_id',$event_id)->where('user_id', $request->user_id)->orderBy('id', 'desc')->first();
                if(!$userRequest){
                    $userRequest=new UserRequest();
                    $userRequest->user_id = $request->user_id;
                    $userRequest->event_id = $event_id;
                    $userRequest->status = 1;
                    $userRequest->save();
                }
                $user->products()->attach($request->product_id,['event_id'=>$event_id,'is_active'=>$userRequest->terms_accept?true:false]);
                $user->save();
            }


    }

    public function updatePermission($request, $id)
    {

    }
    protected function validation($request)
    {
        $request->validate([
            'events_id'     => 'required',
            'user_id'        => 'required',
            'product_id'    => 'required',
        ]);
    }


}
