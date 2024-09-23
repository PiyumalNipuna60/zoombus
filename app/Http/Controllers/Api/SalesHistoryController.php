<?php

namespace App\Http\Controllers\Api;


use App\BalanceUpdates;
use App\Http\Controllers\Driver\SalesController as SC;
use App\Sales;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class SalesHistoryController extends SC {

    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data['items'] = $this->allSalesHistoryData($request);
        $data['total_sold'] = Sales::whereHas('routes', function ($q) use ($request) {
            $q->where('user_id', $request->user()->id);
        })->statusNot(2)->count();

        $totalSoldPriceApproved = BalanceUpdates::whereUserId($request->user()->id)->whereType(1)->whereStatus(1)->sum('amount');
        $totalSoldPriceUnapproved = BalanceUpdates::whereUserId($request->user()->id)->whereType(1)->whereStatus(0)->sum('amount');
        $data['total_sold_price_approved'] = (substr($totalSoldPriceApproved, -4) === '.000') ? rtrim($totalSoldPriceApproved, '.00') : $totalSoldPriceApproved;
        $data['total_sold_price_unapproved'] = (substr($totalSoldPriceUnapproved, -4) === '.000') ? rtrim($totalSoldPriceUnapproved, '.00') : $totalSoldPriceUnapproved;

        return response()->json($data, 200);

    }



}
