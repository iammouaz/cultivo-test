<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;
class Authenticate extends  Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    public function handle($request, Closure $next, ...$guards)
    {
        session(['perv_url' => url()->previous()]);

        if (Auth::check()) {
            return $next($request);
        }

        if ($request->has('event_type') && $request->input('event_type') == "ace_event") {//ace event login
            return redirect()->route('user.login',['ace_member','ace_member']);
        }
        if ($request->has('login_type')) {
            return redirect()->route('user.login',['login_type',$request->login_type??"normal"]);
        }
        return redirect()->route('user.login');
    }



}
