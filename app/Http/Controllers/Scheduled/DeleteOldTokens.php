<?php

namespace App\Http\Controllers\Scheduled;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Sales;

class DeleteOldTokens extends Controller {
    public function __invoke() {
        \DB::table('oauth_access_tokens')->whereRaw('expires_at < now()')->delete();
        \DB::table('oauth_refresh_tokens')->whereRaw('expires_at < now()')->delete();
    }
}
