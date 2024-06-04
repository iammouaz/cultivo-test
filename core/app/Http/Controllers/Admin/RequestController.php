<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\PusherEvents\RequestPush;
use App\Models\UserRequest;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use App\Rules\FileTypeValidate;
use Carbon\Carbon;
use App\Models\Event;
use Illuminate\Support\Facades\Log;

class RequestController extends Controller
{
    protected $pageTitle;
    protected $emptyMessage;
    // protected $search;


    public function index()
    {
        $requests       = UserRequest::latest()->where('status', -1)->paginate(getPaginate());
        $pageTitle      = __("All Requests");
        $emptyMessage   = __("No Requests Found");
        return view('admin.request.index', compact('pageTitle', 'emptyMessage', 'requests'));
        //return view('admin.request.index');
    }

    public function approve_interface($event_id, $user_id)
    {
        $ev_id = $event_id;
        $us_id = $user_id;
        $products      = Product::latest()->where('event_id', $event_id)->paginate(getPaginate(100));
        $pageTitle      = __("All Products");
        $emptyMessage   = __("No Products Found");
        return view('admin.request.products', compact('pageTitle', 'emptyMessage', 'products', 'ev_id', 'us_id'));
        //return view('admin.request.index');
    }

    public function approved(Request $request)
    {
        //return $request->products;

        $requests       = UserRequest::latest()->where('status', -1)->paginate(getPaginate());
        $pageTitle      = __("All Requests");
        $emptyMessage   = __("No Requests Found");

        if (!$request->has('products')) {
            $notify[] = ['error', __('Please select products')];
            return back()->withNotify($notify);
        }
        $userRequest = UserRequest::where('event_id', $request->event_id)->where('user_id', $request->user_id)->where('status', -1)->first();
        if(!$userRequest){
            $notify[] = ['error', __('No pending request found for this user, try refreshing the requests page')];
            return redirect()->route('admin.request.index')->withNotify($notify);
        }
        $user = User::find($request->user_id);
        $user->products()->attach($request->products, ['event_id' => $request->event_id,'is_active'=>false]);
        $userRequest->status = 1;
        $userRequest->save();
        $notify[] = ['success', __('Approved successfully')];
//        Log::info('sending pusher notification of the request #'.$userRequest->id.' to the user #'.$userRequest->user_id.' for the event #'.$userRequest->event_id);

        $event = Event::find($userRequest->event_id);
        //send pusher notification to the approved user
        event(new RequestPush($event,$userRequest->id,$userRequest->user_id));
        //Send an email notification to the approved user
        notify($user, 'Bidder_Approval', [
            'event' => $event->name,
            'hyperlink to login page'=>route('event.details',[$event->id, slug($event->name)])
        ]);

        return redirect()->route('admin.request.index')->withNotify($notify);
    }
    public function editapproved(Request $request)
    {
        //return $request->products;

        // $pageTitle = 'Manage Users';
        // $emptyMessage = 'No user found';
        // $users = User::orderBy('id', 'desc')->paginate(getPaginate());
        // $events=Event::all();

        $user = User::find($request->user_id);
        $product_ids = $user->products()->wherepivot('event_id', $request->event_id)->pluck('product_id')->toArray();
        if ($request->has('products')) {
            foreach ($product_ids as $product) {
                if (!in_array($product, $request->products)) {
                    $user->products()->detach($product);
                }
            }
            $userRequest = UserRequest::where('event_id', $request->event_id)->where('user_id', $request->user_id)->first();
            if ($userRequest) {
                $userRequest->status = 1;
                $userRequest->save();
            }
            else{
                $userRequest = new UserRequest();
                $userRequest->user_id = $request->user_id;
                $userRequest->event_id = $request->event_id;
                $userRequest->status = 1;
                $userRequest->save();
            }
            foreach ($request->products as $product) {
                if (!in_array($product, $product_ids)) {
                    $user->products()->attach($product, ['event_id' => $request->event_id,'is_active'=>$userRequest->terms_accept?true:false]);

                }

            }

        }else{
            $user->products()->detach($product_ids);
        }

        $notify[] = ['success', __('Edit Approved successfully')];
        return redirect()->route('admin.users.all')->withNotify($notify);
    }

    public function update(Request $request, $id)
    {
        // $this->validation($request, 'nullable');
        // $event = Event::findOrFail($id);
        // if (Carbon::parse($request->end_date)->diffInHours(Carbon::parse($request->start_date)) < $request->less_bidding_time) {
        //     $notify[] = ['error', 'Less Bidding Time must be Lesser than Event Period'];
        //     return back()->withNotify($notify);
        // }
        // $this->saveEvent($request, $event);
        // $notify[] = ['success', 'Event updated successfully'];
        // return back()->withNotify($notify);
    }

    public function reject($id)
    {

        $request = UserRequest::find($id);
        $request->status = 0;
        $request->save();
        $notify[] = ['success', __('Reject successfully')];
        return back()->withNotify($notify);
    }

    protected function validation($request, $imgValidation)
    {
        // $request->validate([
        //     'name'            => 'required',
        //     'description'     => 'required',
        //     'end_date'        => 'required',
        //     'max_end_date'    => 'nullable|after:end_date',
        //     'less_bidding_time'        => 'required',
        //     'start_date'    => 'required|date|after:yesterday|before:end_date',
        //     'image'           => [$imgValidation, 'image', new FileTypeValidate(['jpeg', 'jpg', 'png'])]
        // ]);
    }

    public function accept_event_terms(Request $request){

        $pageTitle      = __("All Requests");
        $emptyMessage   = __("No Requests Found");
        $events = Event::latest()->get();
        $users=User::latest()->get();
        //first call
        $requests=UserRequest::Orderby('date_accept','desc')->paginate(getPaginate());
        //if we have filter
        if($request->has('user_id')||$request->has('event_id')){
            $requests= UserRequest::where(function($query) use ($request){
                $query->where('user_id', $request->user_id)->orWhere('event_id',$request->event_id);
            })->whereNotNull('date_accept')->OrderBy('date_accept','desc')->paginate(getPaginate());
            if($request->user_id==0&&$request->event_id==0){
                $requests=UserRequest::Orderby('date_accept','desc')->paginate(getPaginate());
            }
            session()->put('user_id',$request->user_id);
            session()->put('event_id',$request->event_id);
        }
        return view('admin.request.event_terms_accpetence', compact('pageTitle', 'emptyMessage', 'requests','events','users'));
    }
}
