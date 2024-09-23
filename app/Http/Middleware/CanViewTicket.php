<?php

namespace App\Http\Middleware;

use App\Sales;
use Closure;

class CanViewTicket
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
        if(!Sales::status([1,3])->current()->where('ticket_number', $request->id)->exists()) {
            abort(404);
        }
        return $next($request);
    }
}
