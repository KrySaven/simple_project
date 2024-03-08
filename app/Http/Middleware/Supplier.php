<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Supplier
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
        dd(Auth::guard('supplieradmin'));
        // if (!Auth::guard($guard)->check()) {
        //     return redirect($redirectTo);
        // }

        return $next($request);
    }
}
