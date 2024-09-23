<?php

namespace App\Http\Middleware;

use App\Routes;
use Closure;

class CanEditRouteMiddleware
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
        if(!Routes::current()->where('id',$request->id)->statusNot(3)->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return redirect()->route('routes_list');
            }
        }
        return $next($request);
    }
}
