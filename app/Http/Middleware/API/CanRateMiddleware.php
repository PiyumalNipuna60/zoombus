<?php

namespace App\Http\Middleware\API;

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
        if($request->id) {
            if(!Sales::current($request->user()->id)->whereRaw('md5(id) = ?', [$request->id])->where('status', 3)->exists()) {
                if($request->expectsJson()) {
                    return response()->json(['status' => 0, 'text' => \Lang::get('validation.method_not_allowed')], 422);
                }
                else {
                    return abort(404);
                }
            }
        }

        return $next($request);
    }
}
