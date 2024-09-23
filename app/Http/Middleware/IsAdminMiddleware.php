<?php

namespace App\Http\Middleware;

use App\Admins;
use Closure;

class IsAdminMiddleware
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
        if(!Admins::where('ip', $_SERVER['REMOTE_ADDR'])->exists() || !Admins::where('user_id', \Auth::user()->id)->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return redirect()->route('index');
            }
        }
        return $next($request);
    }
}
