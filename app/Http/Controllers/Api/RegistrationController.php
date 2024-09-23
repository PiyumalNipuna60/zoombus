<?php

namespace App\Http\Controllers\Api;

use App\Admins;
use App\Driver;
use App\Http\Controllers\Auth\RegisterController;
use App\Notifications\RegisteredAsPartnerPassword;
use App\Partner;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mcamara\LaravelLocalization\LaravelLocalization;

class RegistrationController extends RegisterController {

    public function create(Request $request) {
        if($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->invokeDriverPartner($request, 'driver', true)['response'];
        $data = $this->invokeDriverPartner($request,'driver', true)['data'];
        if($response->original['status'] == 1) {
            $data['user']['password'] = Hash::make($data['user']['password']);
            $data['user']['locale'] = $request->lang;
            $reg = RegisterController::store($data['user']);
            $data['driver']['user_id'] = $reg->id;
            if($request->account_type) {
                if($request->account_type == 'driver') {
                    Driver::create($data['driver']);
                }
            }
            $statusCode = 200;
        }
        else {
            $statusCode = 422;
        }
        return response()->json($response->original, $statusCode);
    }


    public function createAsPartner(Request $request) {
        if($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->invokeDriverPartner($request, 'driver', true, true)['response'];
        $data = $this->invokeDriverPartner($request,'driver', true, true)['data'];
        if($response->original['status'] == 1) {
            $newPass = rand(0000, 9999);
            $data['user']['password'] = Hash::make($newPass);
            $data['user']['locale'] = $request->lang;
            $reg = RegisterController::store($data['user']);
            $data['driver']['user_id'] = $reg->id;
            if($request->account_type) {
                if($request->account_type == 'driver') {
                    Driver::create($data['driver']);
                }
            }
            User::whereId($reg->id)->first()->notify(
                new RegisteredAsPartnerPassword($request->lang, $newPass)
            );
            $statusCode = 200;
        }
        else {
            $statusCode = 422;
        }
        return response()->json($response->original, $statusCode);
    }


}
