<?php

namespace App\Http\Controllers\Driver;

use App\Http\Controllers\Controller;
use App\Http\Controllers\PayoutController;
use App\Payouts;
use Jenssegers\Agent\Agent;

class DriverPayoutController extends PayoutController
{
    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if(!$agent->isMobile()) {
            $this->middleware('customer');
            $this->middleware('driver_active');
        }
    }

    public function view() {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();
            $data['balance'] = \Auth::user()->balance - Payouts::whereStatus(2)->current()->sum('amount');
            return view('driver.payouts', $data);
        }
        else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }

    }
}
