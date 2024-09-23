<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class Guest
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        if (Auth::guard($guard)->check()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return redirect()->intended('/');
            }
        }
        return $next($request);
    }
}
