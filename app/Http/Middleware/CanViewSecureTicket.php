<?php

namespace App\Http\Middleware;

use App\Sales;
use Closure;

class CanViewSecureTicket
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
        if(!Sales::status([1,3])->whereRaw('md5(ticket_number) = ?', [$request->id])->exists()) {
            abort(404);
        }
        return $next($request);
    }
}
