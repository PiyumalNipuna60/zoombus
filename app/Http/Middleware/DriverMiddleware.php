<?php

namespace App\Http\Middleware;

use App\Driver;
use Closure;
use Illuminate\Support\Facades\Auth;

class DriverMiddleware
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
        if (!Driver::current()->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                if(Auth::check()) {
                    return redirect()->route('driver_registration');
                }
                else {
                    return redirect()->route('register_as_driver');
                }
            }
        }
        return $next($request);
    }
}
