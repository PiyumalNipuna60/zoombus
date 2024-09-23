<?php

namespace App\Http\Controllers;

use App\BalanceUpdates;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\User;
use App\Sales;
use Mcamara\LaravelLocalization\LaravelLocalization;

class ApiController extends Controller {
    public function login(Request $request) {
        $hash = new Hashids('', 32);
        $lang = $request->header('lang');
        $credentials['phone_number'] = $request->server('PHP_AUTH_USER');
        $credentials['password'] = $request->server('PHP_AUTH_PW');
        if (isset($credentials['phone_number']) && isset($credentials['password'])) {
            $credentials['status'] = [1, 2];
            if (empty($lang)) : $la = 'en';
            else: $la = $lang; endif;
            if (array_key_exists($la, config('laravellocalization.supportedLocales'))) {
                (new LaravelLocalization)->setLocale($la);
            }
            $user = User::whereHas('driver', function ($q) {
                $q->where('status', 1);
            })->where('phone_number', $credentials['phone_number'])->first();
            if (!Auth::attempt($credentials)) {
                if ($user && \Hash::check($credentials['password'], $user->password) && !in_array($user->status, [1, 2])) {
                    $response = array('status' => 0, 'text' => \Lang::get('validation.unverified'));
                } else {
                    $response = array('status' => 0, 'text' => \Lang::get('validation.failed_login'));
                }
            } else {
                $response = array('status' => 1, 'token' => $hash->encode($user->id));
            }
        } else {
            $response = array('status' => 0, 'text' => \Lang::get('validation.fill_phone_and_password_field'));
        }
        return response()->json($response);
    }




}
