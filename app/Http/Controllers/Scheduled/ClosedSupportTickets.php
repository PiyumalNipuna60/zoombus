<?php

namespace App\Http\Controllers\Scheduled;

use App\Sales;
use App\SupportTicketMessages;
use App\SupportTickets;
use App\User;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use App\Notifications\RouteReminder as NRouteReminder;
use Mcamara\LaravelLocalization\LaravelLocalization;

class ClosedSupportTickets extends Controller
{
    public function __invoke() {
       SupportTicketMessages::whereDoesntHave('ticket')->delete();
       SupportTickets::whereStatus(0)->whereRaw('created_at <= now() - INTERVAL 2 DAY AND updated_at IS NULL OR updated_at IS NOT NULL AND updated_at <= now() - INTERVAL 2 DAY')->take(30)->delete();
    }
}
