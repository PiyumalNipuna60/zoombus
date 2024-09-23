<?php

namespace App\Http\Controllers\Auth;

use App\Driver;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class LoginController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest')->except('current_locale');
    }

    public function current_locale() {
        return Controller::essentialVars(['current_country_code'])['current_country_code'];
    }

    public function __invoke(Request $request) {
      



        $credentials = $request->only('phone_number', 'password');
        if(isset($credentials['phone_number']) && isset($credentials['password'])) {
            $credentials['status'] = [1,2];
            if (!Auth::attempt($credentials)) {
                $user = User::where('phone_number', $request->phone_number)->first();
                if ($user && \Hash::check($request->password, $user->password) && !in_array($user->status, [1,2])) {
                    $response = array('status' => 0, 'text' => \Lang::get('validation.unverified'));
                }
                else {
                    $response = array('status' => 0, 'text' => \Lang::get('validation.failed_login'));
                }
            }
            else {
                if(session()->has('url.intended')) {
                    $response = array('status' => 1, 'text' => redirect()->intended('/')->getTargetUrl());
                }
                else if (Driver::current()->notActive()->where('step', '<=', 4)->exists()) {
                    $response = ['status' => 1, 'text' => route('driver_wizard')];
                }
                else {
                    $response = array('status' => 3);
                }
            }
        }
        else {
            $response = array('status' => 0, 'text' => \Lang::get('validation.fill_phone_and_password_field'));
        }


        return response()->json($response);
    }



}
