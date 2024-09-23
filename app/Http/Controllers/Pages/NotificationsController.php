<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\SupportTickets;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class NotificationsController extends Controller
{
    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer');
        }
    }

    public function singleItem(Request $request) {
        $perPage = config('app.notifications_per_page');
        $skip = $request->zSkip;
        $type = $request->zType;
        $mobile = $request->mobile;

        $q = \Auth::user()->notifications();

        $data['total_results'] = $q->count();

        $data['perPage'] = $perPage;

        $collect = $q->skip($skip)->limit($perPage)->get();



        $data['results'] = collect($collect)->map(function ($d) use ($mobile) {
            $d['created_at_formatted'] = Carbon::parse($d['created_at'])->translatedFormat('j M Y');
            if($mobile && isset($d['data']['url'])) {
                $d['url_path'] = str_replace($this->getProtocol().$_SERVER['SERVER_NAME'], '', $d['data']['url']);
            }
            if(isset($d['data']['user_id'])) {
                $d['user_avatar'] = (new User())->photoById($d['data']['user_id']);
            }
            if(isset($d['ticket_id'])) {
                $ticket = SupportTickets::whereId($d['ticket_id'])->first('user_read');
                $d['user_read'] = ($ticket) ? $ticket->user_read : false;
            }
            return $d;
        });

        $data['skip'] = $skip;

        if ($type == 'initial') {
            return $data;
        } else {
            $data['results'] = view('components.notification-item', $data)->render();
            return $data;
        }
    }

    public function viewAll(Request $request) {
        $data = Controller::essentialVars();
        $page = $request->page ?? 1;
        $perPage = config('app.notifications_per_page');
        $skip = ($page - 1) * $perPage;

        if ($request->page == 1) {
            return redirect()->route('notifications');
        }

        $request->zSkip = $skip;
        $request->zType = 'initial';

        $agent = new Agent();
        if (!$agent->isMobile()) {
            return view('notifications', array_merge($data, $this->singleItem($request)));
        }
        else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }

    }
}
