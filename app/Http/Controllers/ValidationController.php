<?php

namespace App\Http\Controllers;

use ReCaptcha\ReCaptcha;

class ValidationController extends Controller {
    protected static function response($validator, $success_text = null) {
        $errors = $validator->errors();
        if ($validator->fails()) {
            $response = array('status' => 0, 'text' => $errors->first());

        } else {
            $response = array('status' => 1, 'text' => $success_text);
        }
        return response()->json($response);
    }

    protected function validateRecaptcha($value) {
        $recaptcha = new ReCaptcha(config('services.google-recaptcha.secret'));
        $resp = $recaptcha->verify($value);
        if ($resp->isSuccess()) {
            return true;
        } else {
            return false;
        }
    }


    protected static function removeCommas($string) {
        return str_replace(',', '', $string);
    }
}
