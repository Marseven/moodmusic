<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Auth;

class CheckSingleSession
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(env('SESSION_DRIVER') == 'file') {
            if(auth()->check() && config('settings.log_hash')) {
                $previous_session = auth()->user()->session_id;
                if ($previous_session !== Session::getId()) {
                    Session::getHandler()->destroy($previous_session);
                    $request->session()->regenerate();
                    auth()->user()->session_id = Session::getId();
                    auth()->user()->save();
                }
            }
        }
        return $next($request);
    }
}
