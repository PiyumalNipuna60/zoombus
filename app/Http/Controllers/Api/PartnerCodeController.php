<?php

namespace App\Http\Controllers\Api;


use App\AffiliateCodes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class PartnerCodeController extends Controller {

    public function get(Request $request) {
        $data['code'] = AffiliateCodes::whereUserId($request->user()->id)->whereStatus(2)->first('code')->code;
        return response()->json($data, 200);
    }


}
