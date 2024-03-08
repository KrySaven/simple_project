<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class AuthSupplier
{
    protected $auth;
    public function __construct(Auth $auth)
    {
        $this->auth = $auth;
    }
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    // public function handle($request, Closure $next)
     public function handle($request, Closure $next, $guard = null, $redirectTo = '/supplier-home')
    {
        // if (Auth::guard($guard)->check()) {
        //     return redirect($redirectTo);
        // }
        return $next($request);





    }

 }
