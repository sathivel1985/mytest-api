<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HeaderMiddleWare
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
        if (!empty($request->header('X-API-VALUE')) && $request->header('X-API-VALUE') == '12345') {
            return $next($request);
        }
    }
}
