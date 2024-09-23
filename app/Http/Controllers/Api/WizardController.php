<?php

namespace App\Http\Controllers\Api;

use App\Driver;
use App\Http\Controllers\Driver\WizardController as WC;
use App\Vehicle;
use Illuminate\Http\Request;

class WizardController extends WC {
    public function __construct(){
        parent::__construct();
    }

    public function get(Request $request) {
        return response()->json($this->wizardData($request->user(), $request, true), 200);
    }

    public function getByStep(Request $request) {
        Driver::current($request->user()->id)->update(['step' => $request->step]);
        return response()->json($this->wizardData($request->user(), $request, true), 200);
    }

    public function wizardResume($user = null) {
        $vehicle = Vehicle::current($user)->first('id');
        $driver = Driver::current($user)->first('step');
        $response['vehicleId'] = ($vehicle) ? $vehicle->id : 0;
        $response['step'] = ($driver) ? $driver->step : 1;
        return response()->json($response);
    }



}
