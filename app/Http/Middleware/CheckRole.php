<?php
/**
 * Created by NiNaCoder.
 * Date: 2019-07-20
 * Time: 17:56
 */

namespace App\Http\Middleware;

use Auth;
use Closure;
use Redirect;
use App\Models\Banned;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Role;

class CheckRole
{
    /**
     * Check if user have role access, also check if the site is offline of not
     * @param $request
     * @param Closure $next
     * @param $permission
     * @return mixed|void
     */
    public function handle($request, Closure $next, $permission)
    {
        if(! Role::getValue($permission)) {
            if(config('settings.site_offline')){
                abort(503, config('settings.offline_reason'));
            } else {
                abort(403);
            }
        }

        if(auth()->user()->banned) {
            $banned = Banned::findOrFail(auth()->user()->id);

            if(Carbon::now()->timestamp >= Carbon::parse($banned->end_at)->timestamp){
                User::where('id', auth()->user()->id)->update(['banned' => 0]);
                Banned::destroy($banned->user_id);
            } else {
                abort('403', __('auth.banned', ['banned_reason' => $banned->reason, 'banned_time' =>  Carbon::parse($banned->end_at)->format('H:i F j Y')]));
            }
        }

        return $next($request);
    }
}