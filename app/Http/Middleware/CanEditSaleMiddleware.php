<?php

namespace App\Http\Middleware;

use App\Sales;
use Closure;

class CanEditSaleMiddleware
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

        if(!Sales::whereHas('routes', function ($q) { $q->current(); })->whereId($request->id)->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return redirect()->route('sales');
            }
        }

        return $next($request);
    }
}
