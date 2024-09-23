<?php

namespace App\Http\Controllers\Scheduled;

use App\BalanceUpdates;
use App\Http\Controllers\Api\QRScannerController;
use App\Http\Controllers\Controller;
use App\RemainingSeats;
use App\Routes;

class RouteComplete extends Controller
{
    public function __invoke() {
        $completeRoutes = Routes::with(['sales' => function($q) {
            $q->where('status', 1);
        }, 'sales.balanceUpdates'])->where('status', 1)->whereRaw('timestamp(arrival_date, arrival_time) <= now()')->limit(50);
        if($completeRoutes->exists()) {
            foreach($completeRoutes->get()->toArray() as $completeRoute) {
                foreach($completeRoute['sales'] as $sale) {
                    $searchForDriver = array_search(1, array_column($sale['balance_updates'], 'type'));
                    $searchForUs = array_search(4, array_column($sale['balance_updates'], 'type'));
                    $amountDriver = $sale['balance_updates'][$searchForDriver]['amount'];
                    $amountOur = $sale['balance_updates'][$searchForUs]['amount'];
                    $amountForDriver = $amountDriver*0.7;
                    $amountUs = $amountDriver*0.3;
                    BalanceUpdates::whereSaleId($sale['id'])->whereType(1)->update(['amount' => $amountForDriver]);
                    BalanceUpdates::whereSaleId($sale['id'])->whereType(4)->update(['amount' => $amountOur+$amountUs]);
                    (new QRScannerController())->balanceUpdate($sale['id']);
                }
            }
            $completeRoutes->update(['status' => 2]);
        }
        //delete those routes which didn't have a single sale or seat reserved by driver.
        if(Routes::whereDoesntHave('sales')->whereStatus(2)->exists()) {
            $dRoutes = Routes::whereDoesntHave('sales')->whereDoesntHave('reservedSeats')->whereStatus(2)->take(30)->get('id')->toArray();
            foreach ($dRoutes as $dR) {
                RemainingSeats::whereId($dR['id'])->delete();
                Routes::whereId($dR['id'])->delete();
            }
        }
    }
}
