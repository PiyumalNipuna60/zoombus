<?php

namespace App\Http\Middleware;

use App\Admins;
use App\Country;
use App\Currency;
use Closure;

class EssentialMiddleware
{
    public function handle($request, Closure $next)
    {

        $currencies = Currency::all()->toArray();
        $ip = $_SERVER['REMOTE_ADDR'];
        $locale = Country::where('code', strtoupper(geoip($ip)->iso_code))->first(['locale']);
        if($locale) {
            $locale_by_ip = $locale->locale;
        }
//        $currency_by_ip = strtolower(geoip($ip)->currency);

        if(config('app.mode') == 'development') {
            if(!Admins::where('ip', $ip)->exists()) {
                if($request->expectsJson()) {
                    return response()->json(['status' => 0]);
                }
                else {
                    abort(404);
                }
            }
        }

        $request->attributes->add(['currencies' => $currencies]);


        //setting locale
        if (isset($locale_by_ip) && isset(config('laravellocalization.supportedLocales')[$locale_by_ip]) && empty(session('locale'))) {
            //session()->put('locale', $locale_by_ip);
            session()->put('locale', 'en');
        }

        //check if user is banned or not
        if (\Auth::check()):
            if (\Auth::user()->status > 2):
                \Auth::logout();
                redirect('/')->with(['popup' => 'login', 'alert' => 'response-danger', 'text' => \Lang::get('auth.banned')])->send();
            endif;
        endif;

        return $next($request);
    }
}
