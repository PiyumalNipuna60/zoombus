<?php

namespace App\Http\Middleware;

use App\Partner;
use Closure;

class PartnerActiveMiddleware
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
        if (!Partner::current()->active()->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return redirect()->route('partner_registration')->with(['alert' => 'response-danger', 'text' => \Lang::get('auth.partner_suspended')]);
            }
        }
        return $next($request);
    }
}
