<?php

namespace App\Http\Controllers;

use App\Partner;

class PartnerController extends ValidationController
{

    public function __construct() {
        parent::__construct();
        $this->middleware('customer');
        $this->middleware('partner');
    }



}
