<?php

namespace App\Http\Middleware;

use App\SupportTickets;
use Closure;
use Hashids\Hashids;

class SupportTicketExists
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
        $decoded = (new Hashids('', 16))->decode($request->id)[0];
        $decodedLast = (new Hashids('', 16))->decode($request->latest_message)[0];
        if(!SupportTickets::whereId($decoded)->whereHas('latestAllMessage', function ($q) use ($decodedLast) {
            $q->whereId($decodedLast);
        })->exists()) {
            if($request->expectsJson()) {
                return response()->json(array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed')));
            }
            else {
                return abort(404);
            }
        }
        return $next($request);
    }
}
