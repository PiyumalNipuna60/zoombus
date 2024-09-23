<?php

namespace App\Http\Controllers\Scheduled;

use App\Sales;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Notifications\RouteReminder as NRouteReminder;
use Mcamara\LaravelLocalization\LaravelLocalization;

class RouteReminder extends Controller
{
    public function __invoke() {
        $lng = config('laravellocalization.supportedLocales');
        $sls = Sales::nonReminded()->status(1)->take(30)->departureInDay(1)->with([
            'routes:id,from,to,departure_date','routes.citiesFrom','routes.citiesFrom.translate','routes.citiesTo', 'routes.citiesTo.translate', 'users:id,phone_number,locale'])->get()->toArray();
        if(count($sls) > 0) {
            foreach($sls as $sl) {
                $translateFrom = $sl['routes']['cities_from']['translate'];
                $translateTo = $sl['routes']['cities_to']['translate'];
                $cur = $sl['users']['locale'];
                foreach($lng as $k=>$l) {
                    $departure_date = Carbon::parse($sl['routes']['departure_date']);
                    $data[$k]['date'] = $departure_date->locale($k)->translatedFormat('j F');
                    $data[$k]['route_name'] = $translateFrom[array_search($k, array_column($translateFrom, 'locale'))]['name'].' - '.$translateTo[array_search($k, array_column($translateTo, 'locale'))]['name'];
                }

                User::where('id', $sl['user_id'])->first()->notify(
                    new NRouteReminder($data,
                        $cur,
                        route('single_ticket', ['id' => $sl['ticket_number']])
                    )
                );
                Sales::where('id', $sl['id'])->update(['reminded' => 1]);
            }
        }
    }
}
