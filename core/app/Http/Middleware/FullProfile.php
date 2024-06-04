<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;
class FullProfile extends  Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    public function handle($request, Closure $next, ...$guards)
    {

        if ($this->ifOneReqColumenNull(Auth::user())) {
            $notify[] = ['error',__('We\'ve noticed that your profile is incomplete, please complete your profile to continue using the website')];
            session(['after_profile_submited' => url()->previous()]);

            return redirect()->route('user.profile.setting')->withNotify($notify);
        }


        return $next($request);

    }

    public function ifOneReqColumenNull($user){

        if(is_null($user->firstname)){
            return true;
        }
        if(is_null($user->lastname)){
            return true;
        }


        if(is_null($user->email)){
            return true;
        }

        if(is_null($user->billing_phone)){
            return true;
        }


        if(is_null($user->company_name)){
            return true;
        }

        if(is_null($user->company_website)){
            return true;
        }

        return false;
    }


}
