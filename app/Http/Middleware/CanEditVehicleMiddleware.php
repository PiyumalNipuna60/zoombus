<?php

namespace App\Http\Middleware;

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

        if(!Vehicle::current()->where('id', $request->id)->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return redirect()->route('vehicles_list');
            }
        }

        return $next($request);
    }
}
