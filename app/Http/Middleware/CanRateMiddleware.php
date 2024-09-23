<?php

namespace App\Http\Middleware;

use App\Sales;
use Closure;

class CanRateMiddleware
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
        if(!Sales::current()->whereRaw('md5(id) = ?', [$request->id])->where('status', 3)->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return abort(404);
            }
        }
        return $next($request);
    }
}
