<?php

namespace App\Http\Controllers\Driver;

use App\Fine;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DriverController;
use Carbon\Carbon;
use Jenssegers\Agent\Agent;

class FinesController extends DriverController {
    public function __construct() {
        parent::__construct();
    }

    public function allFinesData() {
        $fines = Fine::with('route', 'route.citiesFrom', 'route.citiesFrom.translated', 'route.citiesTo', 'route.citiesTo.translated')->whereHas('route', function ($q) {
            $q->whereUserId(\Auth::user()->id);
        })->get()->toArray();

        foreach ($fines as $key => $val) {
            $fines[$key]['route'] = $val['route']['cities_from']['translated']['name'] . ' ' . view('components.font-awesome', ['icon' => 'fa-arrow-right'])->render() . ' ' . $val['route']['cities_to']['translated']['name'];
            $fines[$key]['date'] = Carbon::parse($val['route']['departure_date'])->translatedFormat('j\ F Y');
        }

        return datatables()->of($fines)->rawColumns(['route'])->toJson();
    }

    public function view() {
        $agent = new Agent();
        if(!$agent->isMobile()) {
            $data = Controller::essentialVars();
            return view('driver.fines', $data);
        }
        else {
            return view('mobile.main', []);
        }
    }


}
