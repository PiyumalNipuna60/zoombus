<?php

namespace App\Http\Middleware\API;

use App\Vehicle;
use Closure;

class CanEditVehicleMiddleware
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
            if(!Vehicle::current($request->user()->id)->where('id', $request->id)->exists()) {
                if($request->expectsJson()) {
                    return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
                }
            }
        }

        return $next($request);
    }
}
