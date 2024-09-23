<?php

namespace App\Http\Controllers\Admin\Users;


use App\AffiliateCodes;
use App\Driver;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\StatusController;
use App\Partner;
use App\Sales;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;

class PartnersController extends AdminController
{
    public function __construct() {
        parent::__construct();
    }

    public function suspend(Request $request) {
        $d = $request->only('id');
        Partner::where('user_id', $d['id'])->update(['status' => 3]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_partner_suspend')]);
    }

    public function unsuspend(Request $request) {
        $d = $request->only('id');
        Partner::where('user_id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_partner_unsuspend')]);
    }

    public function delete(Request $request) {
        (new PassengersController())->delete($request);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_partner_delete')]);
    }

    public function viewDataColumns() {
        return ['created_at','avatar','country_id','name','phone_number','affiliate_count','affiliate_2_count','total_sold','withdrawn','status','actions'];
    }

    public function viewUserDataColumns() {
        return ['created_at','avatar','country_id','name','phone_number','account_type','partner_user_cut','passenger_user_cut','status','actions'];
    }

    public function viewByUser(Request $request) {
        $b = 0;
        $partnersList = AffiliateCodes::with('user','user.country','user.country.translated')->whereHas('user')->where('user_id', $request->user_id)->where('status', '!=', 2)->get()->toArray();
        foreach($partnersList as $key=> $val) {
            $the_type = [];
            $the_type_slug = [];
            if(Driver::where('user_id', $val['user']['id'])->active()->exists()) :
                $the_type[] = \Lang::get('driver.driver');
                $the_type_slug[] = 'driver';
            endif;
            if(Partner::where('user_id', $val['user']['id'])->active()->exists()) :
                $the_type[] = \Lang::get('driver.partner');
                $the_type_slug[] = 'partner';
            endif;
            if(Sales::where('user_id', $val['user']['id'])->statusNot(2)->exists()) :
                $the_type[] = \Lang::get('misc.passenger');
                $the_type_slug[] = 'passenger';
            endif;
            $user = new User();
            $actions = [
                [
                    'url' => route('admin_user_edit', ['id' => $val['user']['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ]
            ];
            $partners[$b]['name'] = $val['user']['name'];
            $partners[$b]['phone_number'] = $val['user']['phone_number'];
            $partners[$b]['created_at'] = Carbon::parse($val['user']['created_at'])->translatedFormat('Y-m-d');
            $partners[$b]['country_id'] = view('components.img', ['tooltip' => 'yes', 'class' => 'img-fluid', 'title' => $val['user']['country']['translated']['name'], 'src' => $user->countryImageById($val['user']['country']['code'])])->render();
            $partners[$b]['avatar'] = view('components.img', ['src' => $user->photoSmallById($val['user']['id'])])->render();
            $partners[$b]['account_type'] = (sizeof($the_type) > 0) ? implode(", ", $the_type) : \Lang::get('misc.passenger');
            $partners[$b]['partner_user_cut'] = (in_array('partner', $the_type_slug) || in_array('driver', $the_type_slug)) ? config('app.tier1_affiliate').'%' : '-';
            $partners[$b]['passenger_user_cut'] = (in_array('passenger', $the_type_slug)) ? config('app.passenger_affiliate').'%' : '-';
            $partners[$b]['status'] = view('components.status-admin', ['text' => StatusController::statusLabelVU($val['status'], 'en'), 'class' => StatusController::statusLabelClass($val['status'])])->render();
            $partners[$b]['actions'] = view('components.table-actions', ['actions' => $actions])->render();

            $b++;

            $tier2 = AffiliateCodes::with('user','user.country','user.country.translated')->whereHas('user')->where('user_id', $val['user']['id'])->where('status', '!=', 2);
            if($tier2->exists()){
                $c = $b+1;
                foreach($tier2->get()->toArray() as $k => $vl) {
                    $the_types = [];
                    $the_type_slug = [];
                    if(Driver::where('user_id', $vl['user']['id'])->active()->exists()) :
                        $the_types[] = \Lang::get('driver.driver');
                        $the_type_slug[] = 'driver';
                    endif;
                    if(Partner::where('user_id', $vl['user']['id'])->active()->exists()) :
                        $the_types[] = \Lang::get('driver.partner');
                        $the_type_slug[] = 'partner';
                    endif;
                    if(Sales::where('user_id', $vl['user']['id'])->statusNot(2)->exists()) :
                        $the_type[] = \Lang::get('misc.passenger');
                        $the_type_slug[] = 'passenger';
                    endif;
                    $user = new User();
                    $actions2 = [
                        [
                            'url' => route('admin_user_edit', ['id' => $vl['user']['id']]),
                            'faicon' => 'fa-pencil',
                            'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                        ]
                    ];
                    $partners[$b+$c]['name'] = $vl['user']['name'];
                    $partners[$b+$c]['phone_number'] = $vl['user']['phone_number'];
                    $partners[$b+$c]['created_at'] = Carbon::parse($vl['user']['created_at'])->translatedFormat('Y-m-d');
                    $partners[$b+$c]['country_id'] = view('components.img', ['tooltip' => 'yes', 'class' => 'img-fluid', 'title' => $vl['user']['country']['translated']['name'], 'src' => $user->countryImageById($vl['user']['country']['code'])])->render();
                    $partners[$b+$c]['avatar'] = view('components.img', ['src' => $user->photoSmallById($vl['user']['id'])])->render();
                    $partners[$b+$c]['account_type'] = (sizeof($the_types) > 0) ? implode(", ", $the_types) : \Lang::get('misc.passenger');
                    $partners[$b+$c]['partner_user_cut'] = (in_array('partner', $the_type_slug) || in_array('driver', $the_type_slug)) ? config('app.tier2_affiliate').'%' : '-';
                    $partners[$b+$c]['passenger_user_cut'] = (in_array('passenger', $the_type_slug)) ? config('app.passenger_affiliate').'%' : '-';
                    $partners[$b+$c]['status'] = view('components.status-admin', ['text' => StatusController::statusLabelVU($vl['status'], 'en'), 'class' => StatusController::statusLabelClass($vl['status'])])->render();
                    $partners[$b+$c]['actions'] = view('components.table-actions', ['actions' => $actions2])->render();
                    $c++;
                }
            }
        }
        return datatables()->of($partners ?? [])->rawColumns(['avatar','country_id','status','actions'])->toJson();
    }


    private function tier2_count($user_id) {
        return AffiliateCodes::where('tier_one_user_id', $user_id)->where('status', 1)->count();
    }

    public function viewData(Request $request) {
        $partnersQ = User::with(['country','country.translated','payouts','payouts.currency','partner','balanceUpdates' => function ($q) {
            $q->whereIn('type', [2,3,5])->where('status', 1);
        }])->withCount(['affiliate' => function($q) {
            $q->where('status', 1);
        }])->whereHas('partner');

        if(isset($request->user_id)) {
            $partnersQ->whereHas('affiliate', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }

        $partners = $partnersQ->get()->toArray();

        foreach($partners as $key => $val) {
            $user = new User();

            if($val['partner']['status'] == 3) {
                $sua = 'unsuspend';
            }
            else if ($val['partner']['status'] == 1) {
                $sua = 'suspend';
            }
            else {
                $sua = 'approve';
            }




            $sm = $this->tier2_count($val['id']);


            $actions = [
                [
                    'url' => route('admin_user_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'url' => route('admin_partner_' . $sua),
                    'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                    'anchor' => \Lang::get('misc.' . $sua),
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_partner_' . $sua),
                        'error-msg' => Lang::get('alerts.error_partner_' . $sua),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_partner_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_partner_delete'),
                        'error-msg' => Lang::get('alerts.error_partner_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id']
                    ],
                ]
            ];
            $partners[$key]['created_at'] = Carbon::parse($val['partner']['created_at'])->translatedFormat('Y-m-d');
            $partners[$key]['total_sold'] = array_sum(array_column($val['balance_updates'], 'amount')).' GEL'; //future multiple currency
            $partners[$key]['withdrawn'] = array_sum(array_column($val['payouts'] ?? [], 'amount')); //future multiple currency
            $partners[$key]['country_id'] = view('components.img', ['tooltip' => 'yes', 'class' => 'img-fluid', 'title' => $val['country']['translated']['name'], 'src' => $user->countryImageById($val['country']['code'])])->render();
            $partners[$key]['avatar'] = view('components.img', ['src' => $user->photoSmallById($val['id'])])->render();
            $partners[$key]['affiliate_2_count'] = $sm;
            $partners[$key]['status'] = view('components.status-admin', ['text' => StatusController::statusLabelVU($val['partner']['status'], 'en'), 'class' => StatusController::statusLabelClass($val['partner']['status'])])->render();
            $partners[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($partners)->rawColumns(['avatar','country_id','status','actions'])->toJson();
    }


    public function view() {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.partners');
        $data['columns'] = $this->viewDataColumns();
        $data['ajaxUrl'] = route('admin_partners_data');
        $data['columnDefs'] = [['className' => 'text-center', 'targets' => [0,1,2]]];
        return view('admin.pages.dataTables', $data);
    }
}
