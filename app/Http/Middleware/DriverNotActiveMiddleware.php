<?php

namespace App\Http\Middleware;

use App\Driver;
use Closure;

class DriverNotActiveMiddleware {
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next) {
        if (!Driver::current()->notActive()->exists() && Driver::current()->exists()) {
            if ($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            } else {
                return redirect()->route('driver_profit');
            }
        }
        return $next($request);
    }
}
