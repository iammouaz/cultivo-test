<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Closure;
use Auth;
class CartDisable extends  Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */

    public function handle($request, Closure $next, ...$guards)
    {

        $is_cart_disable = get_is_stop_cart() ?? false;

        if ($is_cart_disable) {
            $notify[] = ['error',__('Cart is disabled')];

            return redirect()->route('home')->withNotify($notify);
        }


        return $next($request);

    }


}
