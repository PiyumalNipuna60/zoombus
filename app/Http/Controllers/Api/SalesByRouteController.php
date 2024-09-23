<?php

namespace App\Http\Controllers\Api;


use App\BalanceUpdates;
use App\Http\Controllers\Driver\SalesController as SC;
use App\Routes;
use App\Sales;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class SalesByRouteController extends SC {

    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data['items'] = $this->allSalesData($request);
        $data['total_sold'] = Routes::nowOrFuture()->status(1)->current($request->user()->id)->count();


        $totalSoldPriceApprovedQ = BalanceUpdates::whereHas('sale.routes', function($q) use ($request) {
            $q->status(($request->history) ? 2 : 1);
        })->whereUserId($request->user()->id)->whereType(1);
        if($request->history) {
            $totalSoldPriceApprovedQ->whereStatus(1);
        }
        $totalSoldPriceApproved = $totalSoldPriceApprovedQ->sum('amount');
        $data['total_sold_amount'] = (substr($totalSoldPriceApproved, -4) === '.000') ? rtrim($totalSoldPriceApproved, '.00') : $totalSoldPriceApproved;

        return response()->json($data, 200);

    }


}
