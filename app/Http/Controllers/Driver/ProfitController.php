<?php

namespace App\Http\Controllers\Driver;

use App\BalanceUpdates;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DriverController;
use Carbon\Carbon;

class ProfitController extends DriverController
{
    public function __construct() {
        parent::__construct();
        $this->middleware('customer');
        $this->middleware('driver_active');
    }


    public function view() {
        $data = Controller::essentialVars();
        $data['total_sales'] = BalanceUpdates::where('status', 1)->where('type', 1)->current()->sum('amount');
        $data['yearly_sales'] = BalanceUpdates::where('status', 1)->where('type', 1)->current()->yearly()->sum('amount');
        $data['monthly_sales'] = BalanceUpdates::where('status', 1)->where('type', 1)->current()->monthly()->sum('amount');
        $data['daily_sales'] = BalanceUpdates::where('status', 1)->where('type', 1)->current()->daily()->sum('amount');

        $prev_day = BalanceUpdates::where('status', 1)->where('type', 1)->current()->daily(1)->sum('amount');
        $prev_month = BalanceUpdates::where('status', 1)->where('type', 1)->current()->monthly(1)->sum('amount');
        $prev_year = BalanceUpdates::where('status', 1)->where('type', 1)->current()->yearly(1)->sum('amount');

        $data['vs_prev_day'] = ($prev_day == 0) ? -999 : (($data['daily_sales'] > 0) ? round(100 - ($data['daily_sales']/$prev_day)*100, 3) : 0);
        $data['vs_prev_month'] = ($prev_month == 0) ? -999 : (($data['monthly_sales'] > 0) ?  round(100 -($data['monthly_sales']/$prev_month)*100, 3) : 0);
        $data['vs_prev_year'] = ($prev_year == 0) ? -999 : (($data['yearly_sales'] > 0) ? round(100 - ($data['yearly_sales']/$prev_year)*100, 3) : 0);

        return view('driver.profits', $data);
    }

    public function chart() {
        for($i = 11; $i > 0; $i--) {
            $data[Carbon::now()->subMonths($i)->translatedFormat('F Y')] = BalanceUpdates::where('status', 1)->where('type', 1)->current()->monthly($i)->sum('amount');
        }
        $data[Carbon::now()->translatedFormat('F Y')] = BalanceUpdates::where('status', 1)->where('type', 1)->monthly()->current()->sum('amount');
        return [
            'labels' => array_keys($data),
            'data' => array_values($data),
        ];
    }
}
