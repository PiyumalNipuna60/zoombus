<?php

namespace App\Http\Controllers\Admin\Users;

use App\Admins;
use App\Http\Controllers\Admin\AdminController;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class AdministratorsController extends AdminController
{
    public function __construct() {
        parent::__construct();
    }


    public function delete(Request $request) {
        $d = $request->only('id');
        Admins::where('user_id', $d['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_admin_delete')]);
    }

    public function viewData() {
        $admins = User::whereHas('admin')->get()->toArray();

        foreach($admins as $key => $val) {
            $user = new User();
            $actions = [
                [
                    'url' => route('admin_user_edit', ['id' => $val['id']]),
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                    'faicon' => 'fa-pencil',
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_administrator_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm remove_admin',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_admin_delete'),
                        'error-msg' => Lang::get('alerts.error_admin_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
            ];
            $admins[$key]['avatar'] = view('components.img', ['src' => $user->photoSmallById($val['id'])])->render();
            $admins[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($admins)->rawColumns(['actions', 'avatar', 'status'])->toJson();
    }


    public function view() {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.administrators');
        $data['columns'] = ['id','avatar','name','phone_number','email','actions'];
        $data['ajaxUrl'] = route('admin_admistrators_data');
        $data['columnDefs'] = [['className' => 'text-center', 'targets' => [0,1]]];
        return view('admin.pages.dataTables', $data);
    }


}
