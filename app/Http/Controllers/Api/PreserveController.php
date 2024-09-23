<?php


namespace App\Http\Controllers\Api;

use App\Http\Controllers\Driver\RoutesController as RC;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;


class PreserveController extends RC {

    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
    }
}
