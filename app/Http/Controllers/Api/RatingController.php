<?php

namespace App\Http\Controllers\Api;


use App\Sales;
use Illuminate\Http\Request;
use App\Http\Controllers\RatingController as RC;
use Illuminate\Support\Facades\DB;
use Mcamara\LaravelLocalization\LaravelLocalization;

class RatingController extends RC
{
    public function get(Request $request)
    {
        if($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $id = $request->id;
        $sale = Sales::whereHas('routes')->whereRaw('md5(id) = ?', [$id])->where('status', 3)->current($request->user()->id)->with([
            'rating',
            'routes.vehicles:id,manufacturer,model,license_plate,number_of_seats,type',
            'routes.vehicles.routeTypes',
            'routes.vehicles.manufacturers:id,name as manufacturer_name',
            'routes.citiesFrom:id,code as city_code,extension',
            'routes.citiesFrom.translated',
            'routes.citiesTo',
            'routes.citiesTo.translated',
            'routes.addressFrom:id',
            'routes.addressFrom.translated',
            'routes.addressTo:id',
            'routes.addressTo.translated',
            'routes.ratings:user_id,driver_user_id,rating,comment',
            'routes.ratings.user:id,name',
            'routes.currency:id,key as currency_key',
        ])->with(['routes' => function ($query) {
            $query->withCount(['ratings as average_rating' => function ($q) {
                $q->select(DB::raw('avg(rating)'));
            }]);
        }])->first()->toArray();
        return response()->json($sale, 200);
    }

}
