<?php

namespace App\Http\Controllers\Auth;

use App\ForgotPassword;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidationController;
use App\Notifications\ForgotPasswordSend;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\LaravelLocalization;

class ForgotPasswordController extends ValidationController
{
    public function __construct(){
        parent::__construct();
        if(!(new Agent())->isMobile()) {
            $this->middleware('guest');
        }
    }

    protected function validator(array $data)
    {
        $fields = [
            'phone_number' => 'required|phone:AUTO|exists:users|unique:forgot_passwords',
        ];
        return Validator::make($data, $fields);
    }


    public function action(Request $request) {
        $data = $request->only('phone_number');
        $response = ValidationController::response($this->validator($data), \Lang::get('validation.sms_successfully_sent'));
        if($response->original['status'] == 1) {
            $newPass = rand(1000, 9999);
            $user = User::wherePhoneNumber($data['phone_number'])->first();
            $cur = $user->locale;
            User::wherePhoneNumber($data['phone_number'])->whereIn('status', [1,2])->update(['status' => 1, 'password' => \Hash::make($newPass)]);
            $user->notify(
                new ForgotPasswordSend($cur, $newPass)
            );
            ForgotPassword::create(['phone_number' => $data['phone_number']]);
        }
        return response()->json($response->original);
    }

    public function view() {
        $data = Controller::essentialVars();
        if(!(new Agent())->isMobile()) {
            return view('forgot-password', $data);
        }
        else {
            $data['title'] = \Lang::get('titles.forgot');
            return view('mobile.forgot', $data);
        }
    }

}
