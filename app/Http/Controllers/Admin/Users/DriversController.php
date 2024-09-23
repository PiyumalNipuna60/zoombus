<?php

namespace App\Http\Controllers\Admin\Users;

use App\BalanceUpdates;
use App\Driver;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\StatusController;
use App\Notifications\DriversLicense;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class DriversController extends AdminController {
    public function __construct() {
        parent::__construct();
    }

    private function licenseNotification($status, $user_id, $supportTicketId = null, $supportTicketMessageId = null) {
        if(in_array($status, [1,3])) {
            $lng = config('laravellocalization.supportedLocales');
            $sttsChange = Driver::with('user')->where('user_id', $user_id)->first();
            if ($sttsChange) {
                $usr = $sttsChange->toArray();
                if ($usr['status'] != $status) {
                    $cur = $sttsChange['user']['locale'];
                    foreach($lng as $k=>$l) {
                        $translate[$k]['status'] = StatusController::licenseLabel($status, $k);
                    }
                    User::where('id', $user_id)->first()->notify(
                        new DriversLicense($translate, $cur, $supportTicketId, $supportTicketMessageId)
                    );
                }
            }
        }
    }

    public function licenseChange(Request $request) {
        $d = $request->only('user_id', 'status', 'reason');
        if(isset($d['reason']) && !empty($d['reason'])) {
            $user = User::whereId($d['user_id'])->first()->toArray();
            $rejRes = $this->rejectionReason($user['email'], $user['name'], $d['user_id'], $d['reason']);
            $this->licenseNotification($d['status'], $d['user_id'], $rejRes['id'], $rejRes['latest_message']);
        }
        else {
            $this->licenseNotification($d['status'], $d['user_id']);
        }
        Driver::where('user_id', $d['user_id'])->update(['status' => $d['status']]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_info_update')]);
    }

    public function suspend(Request $request) {
        $d = $request->only('id');
        Driver::where('user_id', $d['id'])->update(['status' => 3]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_driver_suspend')]);
    }


    public function approve(Request $request) {
        $d = $request->only('id');
        $this->licenseNotification(1,$d['id']);
        Driver::where('user_id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_driver_approve')]);
    }

    public function unsuspend(Request $request) {
        $d = $request->only('id');
        Driver::where('user_id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_driver_unsuspend')]);
    }

    public function delete(Request $request) {
        (new PassengersController())->delete($request);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_driver_delete')]);
    }

    public function viewData() {
        $drivers = User::with(['country', 'country.translated', 'payouts', 'payouts.currency', 'driver', 'balanceUpdates' => function($q) {
            $q->where('type', 1)->where('status', 1);
        }])->withCount('vehicles', 'routes')->whereHas('driver')->get()->toArray();
        foreach ($drivers as $key => $val) {
            $user = new User();

            if ($val['driver']['status'] == 3) {
                $sua = 'unsuspend';
            } else if ($val['driver']['status'] == 1) {
                $sua = 'suspend';
            } else {
                $sua = 'approve';
            }

            $actions = [
                [
                    'url' => route('admin_user_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'url' => route('admin_driver_' . $sua),
                    'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                    'anchor' => \Lang::get('misc.' . $sua),
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_driver_' . $sua),
                        'error-msg' => Lang::get('alerts.error_driver_' . $sua),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_driver_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_driver_delete'),
                        'error-msg' => Lang::get('alerts.error_driver_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id']
                    ],
                ],
            ];
            $drivers[$key]['created_at'] = Carbon::parse($val['driver']['created_at'])->translatedFormat('Y-m-d');
            $drivers[$key]['total_sold'] = array_sum(array_column($val['balance_updates'], 'amount')).' GEL'; //future multiple currency
            $drivers[$key]['withdrawn'] = array_sum(array_column($val['payouts'] ?? [], 'amount')); //future multiple currency
            $drivers[$key]['country_id'] = view('components.img', ['tooltip' => true, 'class' => 'img-fluid', 'title' => $val['country']['translated']['name'], 'src' => $user->countryImageById($val['country']['code'])])->render();
            $drivers[$key]['avatar'] = view('components.img', ['src' => $user->photoSmallById($val['id'])])->render();
            $drivers[$key]['status'] = view('components.status-admin', ['text' => StatusController::statusLabelVU($val['driver']['status'], 'en'), 'class' => StatusController::statusLabelClass($val['driver']['status'])])->render();
            $drivers[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($drivers)->rawColumns(['avatar', 'country_id', 'status', 'actions'])->toJson();
    }

    public function view() {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.drivers');
        $data['columns'] = ['created_at', 'avatar', 'country_id', 'name', 'phone_number', 'vehicles_count', 'routes_count', 'total_sold', 'withdrawn', 'status', 'actions'];
        $data['ajaxUrl'] = route('admin_drivers_data');
        $data['columnDefs'] = [['className' => 'text-center', 'targets' => [0, 1, 2]]];
        return view('admin.pages.dataTables', $data);
    }

}
