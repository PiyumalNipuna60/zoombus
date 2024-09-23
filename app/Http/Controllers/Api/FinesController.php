<?php

namespace App\Http\Controllers\Api;

use App\Fine;
use App\Http\Controllers\DriverController;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class FinesController extends DriverController {
    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $dataQ = Fine::with('route', 'route.citiesFrom', 'route.citiesFrom.translated', 'route.citiesTo', 'route.citiesTo.translated')
            ->whereHas('route', function ($q) use ($request) {
                $q->whereUserId($request->user()->id);
            })->skip($request->skip)->take(config('app.fines_per_page'))->get()->toArray();

        $data['items'] = collect($dataQ)->map(function($v) {
            $v['route']['departure_date'] = Carbon::parse($v['route']['departure_date'])->translatedFormat('j\ M Y');
            return $v;
        });


        $data['total'] = Fine::whereHas('route', function ($q) use ($request) {
            $q->whereUserId($request->user()->id);
        })->count();

        $data['total_fined'] = Fine::whereHas('route', function ($q) use ($request) {
            $q->whereUserId($request->user()->id);
        })->sum('amount');



        return $data;
    }
}
