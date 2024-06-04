<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $lang_id = auth()->user()->language_id ?? null ;
        if($lang_id){
            $lang = \App\Models\Language::find($lang_id);
            if ($lang) {
                app()->setLocale($lang->code ?? 'en');
            }
        }
        else{
            $lang_code = session()->get('lang_code');
            app()->setLocale( $lang_code??'en');
        }
        return $next($request);
    }
}
