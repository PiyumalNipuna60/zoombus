<?php

namespace App\Http\Controllers\Partners;

use App\Financial;
use App\Payouts;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Controllers\PayoutController;
use Jenssegers\Agent\Agent;

class PartnerPayoutController extends PayoutController
{
    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if(!$agent->isMobile()) {
            $this->middleware('customer');
            $this->middleware('partner_active');
        }
    }

    public function view() {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();
            $data['balance'] = \Auth::user()->balance - Payouts::whereStatus(2)->current()->sum('amount');
            return view('partner.payouts', $data);
        }
        else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }
    }
}
