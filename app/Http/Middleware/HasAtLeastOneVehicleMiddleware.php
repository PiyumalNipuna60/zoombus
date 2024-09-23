<?php

namespace App\Http\Middleware;

use App\Vehicle;
use Closure;

class HasAtLeastOneVehicleMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     *
     */
    public function handle($request, Closure $next)
    {
        if(!Vehicle::current()->active()->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                if(Vehicle::current()->exists()) {
                    return redirect()->route('vehicles_list')->with(['alert' => 'response-info', 'text' => \Lang::get('driver.at_least_one_active')]);
                }
                else {
                    return redirect()->route('vehicle_registration')->with(['alert' => 'response-info', 'text' => \Lang::get('driver.please_add_vehicle')]);
                }

            }
        }
        return $next($request);
    }
}
