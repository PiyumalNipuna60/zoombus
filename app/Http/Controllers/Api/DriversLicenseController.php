<?php

namespace App\Http\Controllers\Api;

use App\Driver;
use App\Http\Controllers\Driver\LicenseController;
use App\SupportTicketMessages;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class DriversLicenseController extends LicenseController {
    public function __construct() {
        parent::__construct();
    }

    public function add(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->__invoke($request);
        if ($response->original['status'] == 1) {
            if($request->isWizard) {
                $response->original = ['step' => 3];
            }
            $statusCode = 200;
        }
        else {
            $statusCode = 422;
        }

        return response()->json($response->original, $statusCode);
    }


    public function driverStatus(Request $request) {
        $data['status'] = Driver::current($request->user()->id)->pluck('status')->first();
        return response()->json($data);
    }


    public function get(Request $request) {
        $hashids = new Hashids('', 16);
        $data['license_number'] = Driver::current($request->user()->id)->pluck('license_number')->first();
        $data['front_side_path'] = $this->frontSide($request->user()->id);
        $data['back_side_path'] = $this->backSide($request->user()->id);
        $data['status'] = Driver::current($request->user()->id)->pluck('status')->first();
        if($request->ticket && $request->message) {
            $data['errorMessage'] = SupportTicketMessages::whereTicketId($hashids->decode($request->ticket)[0])->whereId($hashids->decode($request->message)[0])->first('message')->message;
        }
        return response()->json($data);
    }
}
