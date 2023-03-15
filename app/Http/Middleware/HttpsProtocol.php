<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Foundation\Http\Middleware\TrimStrings as Middleware;
use Illuminate\Http\Request;

class HttpsProtocol extends Middleware
{
    public function handle($request, Closure $next)
    {
        $request->setTrustedProxies([$request->getClientIp()], Request::HEADER_X_FORWARDED_ALL);

        if (!$request->secure() && config('settings.force_https')) {
            return redirect()->secure($request->getRequestUri());
        }

        return $next($request);
    }
}
