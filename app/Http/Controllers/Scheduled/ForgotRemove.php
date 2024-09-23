<?php

namespace App\Http\Controllers\Scheduled;

use App\ForgotPassword;
use App\Http\Controllers\Controller;

class ForgotRemove extends Controller
{
    public function __invoke() {
        ForgotPassword::whereRaw('created_at <= now() - INTERVAL 1 MONTH')->take(30)->delete();
    }
}
