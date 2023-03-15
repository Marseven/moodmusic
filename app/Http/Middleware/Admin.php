<?php

namespace App\Http\Middleware;
use Closure;
use Auth;
use App\Models\User;
use App\Models\Role;

class Admin
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
        if (auth()->check()) {
            // if has admin access
            if(Role::getValue('admin_access')) {
                /**
                 * Save user logged IP
                 */
                $user = User::find((auth()->user()->id));
                $user->logged_ip = request()->ip();
                $user->save();

                return $next($request);
            } else {
                Auth::logout();
                abort(403, 'Access denied.');
            }
        } else
            return redirect(route('backend.login'));

    }
}
