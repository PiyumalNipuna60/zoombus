<?php

namespace App\Http\Controllers;

use App\Notification;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class NotificationController extends Controller
{

    public function __construct() {
        $this->middleware('customer');
    }

    public function readAll() {
        if(\Auth::check()) {
            \Auth::user()->unreadNotifications()->where('notifiable_id', \Auth::user()->id)->update(['read_at' => now()]);
        }
    }

    public function unsubscribe($id) {
        if(\Auth::check()) {
           User::where('id', $id)->update(['subscribed' => 0]);
           redirect()->route('index')->send();
        }
    }
}
