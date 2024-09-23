<?php

namespace App\Http\Middleware;

use App\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class Customer
{
    /**
     * Handle an incoming request.
     *
     * @param  Request  $request
     * @param Closure $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (!\Auth::guard($guard)->check() || \Auth::guard($guard)->check() &&
            User::current()->suspended()->exists()
        ) {
            if($request->route() != 'auth_logout') {
                session(['url.intended' => $request->url()]);
            }
            if($request->expectsJson()) {
                return response()->json(['status' => 0, 'text' => Lang::get('validation.method_not_allowed')]);
            }
            else {
                return redirect()->route('index')->with(['popup' => 'login', 'alert' => 'response-info', 'text' => Lang::get('auth.please_log_in')]);
            }
        }
        return $next($request);
    }
}
