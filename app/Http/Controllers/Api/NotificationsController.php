<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Pages\NotificationsController as NC;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\LaravelLocalization;

class NotificationsController extends NC {
    public function getCount() {
        $agent = new Agent();
        if ($agent->isMobile()) {
            return Auth::user()->unreadNotifications()->count();
        } else {
            return 0;
        }
    }

    public function list(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $request->zType = 'initial';
        $request->mobile = true;
        $request->user()->unreadNotifications()->where('notifiable_id', $request->user()->id)->update(['read_at' => now()]);
        return $this->singleItem($request);
    }
}
