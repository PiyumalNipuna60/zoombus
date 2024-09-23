<?php

namespace App\Http\Controllers\Scheduled;


use App\Sales;
use App\User;
use App\Notifications\RatingNotification;
use App\Http\Controllers\Controller;
use Mcamara\LaravelLocalization\LaravelLocalization;

class RatingSchedule extends Controller
{
    public function __invoke() {
        $lng = config('laravellocalization.supportedLocales');
        $sls = Sales::status(3)->where('rating_sent', false)->take(30)->whereHas('routes', function($q) {
           $q->whereRaw('timestamp(arrival_date, arrival_time) <= now()');
        })->with(['routes:id,from,to','routes.citiesFrom', 'routes.citiesFrom.translate', 'routes.citiesTo', 'routes.citiesTo.translate', 'users:id,phone_number,locale'])->get()->toArray();
        if(count($sls) > 0) {
            foreach($sls as $sl) {
                $cur = $sl['users']['locale'];
                foreach($lng as $k=>$l) {
                    $translateFrom = $sl['routes']['cities_from']['translate'];
                    $translateTo = $sl['routes']['cities_to']['translate'];
                    $data[$k]['route_name'] = $translateFrom[array_search($k, array_column($translateFrom, 'locale'))]['name'].' - '.$translateTo[array_search($k, array_column($translateTo, 'locale'))]['name'];
                }

                User::where('id', $sl['user_id'])->first()->notify(
                    new RatingNotification(
                        $data,
                        $cur,
                        route('rate_driver', ['id' => md5($sl['id'])])
                    )
                );

                Sales::where('id', $sl['id'])->update(['rating_sent' => true]);
            }
        }
    }
}
