<?php

namespace App\Http\Controllers;

use App\Http\PusherEvents\RequestPush;
use App\Jobs\SendEmail;
use App\Models\UserRequest;
use App\Models\Event;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Sentry\Laravel\Integration;

class RequestController extends Controller
{

    public function __construct()
    {
        $this->activeTemplate = activeTemplate();
    }



    public function store($id)
    {
        $event=Event::find($id);
        $request=UserRequest::where('event_id',$id)->where('user_id',auth()->user()->id)->orderBy('id', 'desc')->first();
        if($request){

        if($request->status==-1){
        $notify[] = ['error', __('Registration request is pending')];
        return back()->withNotify($notify);
        }

        if($request->status==1){
        $notify[] = ['error', __('Registration request is approved')];
        return back()->withNotify($notify);
        }

       }
        $this->saveRequest($id);
        $emails=explode(',',$event->emails);
        $userName = auth()->user()->username;
        foreach ($emails as $email) {
                SendEmail::dispatch($email, $event,$userName)->onConnection(config('app.email_job_queue_connection'));

//            sendEmail_v2($email,'Request_Access_Notification',[
//                'user_name'=>auth()->user()->username,
//                'event_name'=>$event->name
//            ]);
        }

        $notify[] = ['success',__('Your request has been submitted, your access will be granted once the Admin approves it.')];


        return back()->withNotify($notify);
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
    public function saveRequest($id)
    {

          $userrequest=new UserRequest();
          $userrequest->user_id = auth()->user()->id;
          $userrequest->event_id = $id;
          $userrequest->save();
    }


    public function confirmation($id){
        $pageTitle = __('Request Confirmation');
        $user = Auth::user();
        return view($this->activeTemplate.'request.confirmation',compact('pageTitle','id','user'));
    }
    public function termsAccept($id)
    {
        try{
            $userRequest = UserRequest::where('user_id', auth()->user()->id)->where('event_id', $id)->where('status', 1)->firstOrFail();
            DB::table('user_events')->where('user_id', auth()->user()->id)->where('event_id', $id)->update(['is_active' => 1]);
            $userRequest->terms_accept = 1;
            $userRequest->date_accept = now();
            $userRequest->save();
            clear_all_cache();
        }
        catch (\Exception $e) {
            Log::error($e->getMessage());
            Integration::captureUnhandledException($e);
            return response('Something went wrong',500);
        }
        return response()->json(['success' => 'Terms accepted successfully']);
    }




}
