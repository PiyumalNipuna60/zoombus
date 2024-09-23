<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Auth\RegisterAsPartnerController;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class BecomePartnerController extends RegisterAsPartnerController {

    public function post(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->loggedIn($request);
        if($response->original['status'] == 1) {
            $code = 200;
        }
        else {
            $code = 422;
        }
        return response()->json($response->original, $code);
    }
}
