<?php

namespace App\Http\Controllers\Admin\Users;


use App\AffiliateCodes;
use App\Driver;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\StatusController;
use App\Partner;
use App\Sales;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;

class PassengersController extends AdminController
{
    public function __construct() {
        parent::__construct();
    }

    public function suspend(Request $request) {
        $d = $request->only('id');
        User::where('id', $d['id'])->update(['status' => 3]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_user_suspend')]);
    }

    public function unsuspend(Request $request) {
        $d = $request->only('id');
        User::where('id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_user_unsuspend')]);
    }

    public function delete(Request $request) {
        $d = $request->only('id');
        $user =  User::where('id', $d['id'])->first('extension');

        if(Storage::disk('s3')->exists('users/'.$d['id'].'.'.$user->extension)) {
            Storage::disk('s3')->delete('users/'.$d['id'].'.'.$user->extension);
            Storage::disk('s3')->delete('users/small/'.$d['id'].'.'.$user->extension);
        }
        if(Storage::disk('s3')->exists('drivers/license/'.$d['id'])) {
            Storage::disk('s3')->deleteDirectory('drivers/license/'.$d['id']);
        }
        if(Vehicle::whereUserId($d['id'])->exists()) {
            $vehicles = Vehicle::whereUserId($d['id'])->get('id')->toArray();
            foreach($vehicles as $vehicle) {
                if(Storage::disk('s3')->exists('drivers/vehicles/'.$vehicle['id'])) {
                    Storage::disk('s3')->deleteDirectory('drivers/vehicles/'.$vehicle['id']);
                }
            }
        }
        User::where('id', $d['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_user_delete')]);
    }

    public function viewData() {
        $users = User::with('country','country.translated')->whereDoesntHave('partner')->whereDoesntHave('driver')->withCount(['sales' => function($q) {
            $q->status([1,3]);
        }])->get()->toArray();

        foreach($users as $key => $val) {
            $user = new User();
            if($val['status'] == 3) {
                $sua = 'unsuspend';
            }
            else if ($val['status'] == 1) {
                $sua = 'suspend';
            }
            else {
                $sua = 'approve';
            }
            $actions = [
                [
                    'url' => route('admin_user_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'url' => route('admin_user_'.$sua),
                    'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                    'anchor' => \Lang::get( 'misc.'.$sua),
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_user_'.$sua),
                        'error-msg' => Lang::get('alerts.error_user_'.$sua),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_user_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_user_delete'),
                        'error-msg' => Lang::get('alerts.error_user_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
            ];
            $users[$key]['created_at'] = Carbon::parse($val['created_at'])->translatedFormat('Y-m-d');
            $users[$key]['country_id'] = view('components.img', ['tooltip' => 'yes', 'class' => 'img-fluid', 'title' => $val['country']['translated']['name'], 'src' => $user->countryImageById($val['country']['code'])])->render();
            $users[$key]['avatar'] = view('components.img', ['src' => $user->photoSmallById($val['id'])])->render();
            $users[$key]['tickets_bought'] = $val['sales_count'];
            $users[$key]['status'] = view('components.status-admin', ['text' => StatusController::statusLabelVU($val['status'], 'en'), 'class' => StatusController::statusLabelClass($val['status'])])->render();
            $users[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($users)->rawColumns(['avatar','country_id','status','actions'])->toJson();
    }


    public function view() {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.passengers');
        $data['ajaxUrl'] = route('admin_passengers_data');
        $data['columns'] = ['id','created_at','avatar','country_id','name','phone_number','sales_count','status','actions'];
        $data['columnDefs'] = [['className' => 'text-center', 'targets' => [0,1,2]]];
        return view('admin.pages.dataTables', $data);
    }
}
