<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\User\ProfileController as PC;
use App\Http\Controllers\ValidationController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Mcamara\LaravelLocalization\LaravelLocalization;

class ChangePasswordController extends PC {
    public function __construct() {
        parent::__construct();
    }

    public function update(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $assignable = ['old_password','password','password_confirmation'];
        $data = $request->only($assignable);
        $response = ValidationController::response($this->validator($data,'password', false, $request->lang), \Lang::get('validation.password_updated'));
        if ($response->original['status'] == 1) {
            $pass['password'] = Hash::make($data['password']);
            $this->store($pass);
            $statusCode = 200;
        }
        else {
            $statusCode = 422;
        }
        return response()->json($response->original, $statusCode);
    }
}
