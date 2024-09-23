<?php

namespace App\Http\Controllers\Api;

use App\ForgotPassword;
use App\Http\Controllers\Auth\ForgotPasswordController as FPC;
use App\Http\Controllers\ValidationController;
use App\Notifications\ForgotPasswordSend;
use App\User;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class ForgotPasswordController extends FPC {

    public function request(Request $request) {
        if($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data = $request->only('phone_number');
        $response = ValidationController::response($this->validator($data), \Lang::get('validation.sms_successfully_sent'));
        if($response->original['status'] == 1) {
            $newPass = rand(1000, 9999);
            $cur = $request->lang ?? 'en';
            User::wherePhoneNumber($data['phone_number'])->whereIn('status', [1,2])->update(['status' => 1, 'password' => \Hash::make($newPass)]);
            User::wherePhoneNumber($data['phone_number'])->first()->notify(
                new ForgotPasswordSend($cur, $newPass)
            );
            ForgotPassword::create(['phone_number' => $data['phone_number']]);
            $statusCode = 200;
        }
        else {
            $statusCode = 422;
        }
        return response()->json($response->original, $statusCode);
    }

}
