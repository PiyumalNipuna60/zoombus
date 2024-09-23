<?php

namespace App\Http\Controllers\Api;


use App\BalanceUpdates;
use App\Http\Controllers\Partners\SalesController as SC;
use App\Routes;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class PartnerSalesController extends SC {

    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data['items'] = $this->allSalesData($request);

        $totalSoldPriceApproved = BalanceUpdates::whereUserId($request->user()->id)->whereIn('type', [2, 3, 5])->whereStatus(1)->sum('amount');
        $data['total_sold_amount'] = (substr($totalSoldPriceApproved, -4) === '.000') ? rtrim($totalSoldPriceApproved, '.000') : $totalSoldPriceApproved;

        return response()->json($data, 200);

    }


}
