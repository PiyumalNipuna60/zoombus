<?php

namespace App\Http\Controllers\Scheduled;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sales;

class DeleteOldSales extends Controller
{
    public function __invoke() {
        Sales::whereDoesntHave('cart')->status(2)->whereRaw('created_at <= now() - interval 1 day')->delete();
    }
}
