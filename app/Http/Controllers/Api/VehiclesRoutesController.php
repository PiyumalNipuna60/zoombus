<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Routes;
use App\Vehicle;
use Illuminate\Http\Request;

class VehiclesRoutesController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function getVehicleCount(Request $request) {
        $vehicleCount = Vehicle::whereUserId($request->user()->id)->count();
        $data = [
            'count' => $vehicleCount
        ];
        return response()->json($data);
    }

    public function getRouteCount(Request $request) {
        $routesCount = Routes::whereUserId($request->user()->id)->active()->nowOrFuture()->count();
        $data = [
            'count' => $routesCount,
        ];
        return response()->json($data);
    }

}
