<?php

namespace App\Http\Middleware;

use App\Partner;
use Closure;
use Illuminate\Support\Facades\Auth;

class PartnerMiddleware
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
        if (!Partner::current()->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                if(Auth::check()) {
                    return redirect()->route('partner_registration');
                }
                else {
                    return redirect()->route('register_as_partner');
                }
            }
        }
        return $next($request);
    }
}
