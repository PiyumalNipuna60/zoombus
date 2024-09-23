<?php

namespace App\Http\Controllers\Admin;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidationController;
use App\Partner;
use App\Payouts;
use App\Routes;
use App\RouteTypes;
use App\Sales;
use App\SupportTicketMessages;
use App\SupportTickets;
use App\User;
use App\Vehicle;
use Hashids\Hashids;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Lang;

class AdminController extends ValidationController {
    public function __construct() {
        parent::__construct();
        $this->middleware('customer');
        $this->middleware('admin');
    }

    protected static function countVehicleRequests() {
        return Vehicle::status(2)->count();
    }

    protected static function getVehicleRequests() {
        return Vehicle::with('user','manufacturers')->status(2)->take(5)->get();
    }

    protected static function countWithdrawRequests() {
        return Payouts::status(2)->count();
    }

    protected static function countTotalWithdraws() {
        return Payouts::count();
    }


    protected static function getWithdrawRequests() {
        return Payouts::with('user')->status(2)->take(5)->get();
    }

    protected static function countLicenseRequests() {
        return Driver::status(2)->count();
    }

    protected static function getLicenseRequests() {
        return Driver::with('user')->status(2)->take(5)->get();
    }

    protected static function countDrivers() {
        return Driver::count();
    }

    protected static function countPartners() {
        return Partner::count();
    }

    protected static function countPassengers() {
        return User::whereDoesntHave('driver')->whereDoesntHave('partner')->count();
    }

    protected static function countSupportTickets() {
        return SupportTickets::where('status', 1)->where('read', 0)->count();
    }

    protected static function countTotalSales() {
        return Sales::statusNot(2)->count();
    }

    protected static function countTotalRoutes() {
        return Routes::count();
    }


    protected function fields(array $data, $modelFillable, array $exceptions = [], $inverse = null) {
        $c = 0;
        $keys = array_unique(Arr::collapse(($inverse) ? [$modelFillable, array_keys($data)] : [array_keys($data), $modelFillable]));
        if (!empty($exceptions)) {
            foreach ($exceptions as $nF) {
                if(in_array($nF, $keys)) {
                    unset($keys[array_search($nF, $keys)]);
                }
            }
        }

        foreach ($keys as $val) {
            $retAr[$c]['label'] = Lang::get('admin.' . $val);
            $retAr[$c]['name'] = $val;
            if (array_key_exists($val, $data)) {
                if (is_array($data[$val])) {
                    foreach ($data[$val] as $ke => $va) {
                        $retAr[$c][$ke] = $va ?? null;
                    }
                } else {
                    $retAr[$c]['value'] = $data[$val] ?? null;
                }
            }
            $c++;
        }
        return $retAr;
    }

    public function logout() {
        if (Auth::check()) {
            Auth::logout();
            \Session::flash('popup', 'login');
            $response = array('status' => 1, 'text' => redirect()->intended('/')->getTargetUrl());

        } else {
            $response = array('status' => 0, 'text' => \Lang::get('validation.method_not_allowed'));
        }
        return response()->json($response);
    }

    protected function rejectionReason($email, $name, $user_id, $reason) {
        $st = SupportTickets::create([
            'status' => 1,
            'email' => $email,
            'name' => $name,
            'user_id' => $user_id,
            'read' => 1,
        ]);
        $stm = SupportTicketMessages::create([
            'ticket_id' => $st->id,
            'admin' => \Auth::user()->id,
            'message' => $reason
        ]);

        $hashids = new Hashids('', 16);
        return ['id' => $hashids->encode($st->id), 'latest_message' => $hashids->encode($stm->id)];
    }


    public static function essentialVars($fields = null) {
        $data = Controller::essentialVars($fields);
        $data['support_tickets'] = SupportTickets::with('user','latestMessage','latestMessage.user')->where('read', 0)->where('status', 1)->take(5)->get();
        $data['sidebar']['driver_count'] = self::countDrivers();
        $data['sidebar']['partner_count'] = self::countPartners();
        $data['sidebar']['passenger_count'] = self::countPassengers();
        $data['sidebar']['support_ticket_count'] = self::countSupportTickets();
        $data['withdraw_request_count'] = self::countWithdrawRequests();
        $data['sidebar']['withdraw_total_count'] = self::countTotalWithdraws();
        $data['withdraw_requests'] = self::getWithdrawRequests();
        $data['license_request_count'] = self::countLicenseRequests();
        $data['license_requests'] = self::getLicenseRequests();
        $data['vehicle_request_count'] = self::countVehicleRequests();
        $data['vehicle_requests'] = self::getVehicleRequests();
        $data['sidebar']['sales_total_count'] = self::countTotalSales();
        $data['sidebar']['routes_total_count'] = self::countTotalRoutes();
        $data['sidebar']['vehicle_types'] = RouteTypes::withCount('vehicle')->get()->toArray();
        return $data;
    }


    public function view() {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.dashboard');
        return view('admin.pages.dashboard', $data);
    }
}
