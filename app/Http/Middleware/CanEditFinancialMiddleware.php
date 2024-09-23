<?php

namespace App\Http\Middleware;

use App\Financial;
use Closure;
use Lang;

class CanEditFinancialMiddleware
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
        if(!Financial::current()->where('id', $request->id)->exists()) {
            if($request->expectsJson()) {
                return response()->json(['status' => 0, 'text' => Lang::get('validation.method_not_allowed')]);
            }
            else {
                return redirect()->route('financial')->with(['alert' => 'response-danger', 'text' => Lang::get('validation.cannot_edit_financial')])->send();
            }
        }
        return $next($request);
    }
}
