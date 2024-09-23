<?php

namespace App\Http\Middleware;

use App\Partner;
use Closure;

class NotAPartnerMiddleware
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
        if (Partner::whereUserId(\Auth::user()->id)->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return redirect()->route('partner_profit');
            }
        }
        return $next($request);
    }
}
