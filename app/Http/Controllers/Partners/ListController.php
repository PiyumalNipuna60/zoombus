<?php

namespace App\Http\Controllers\Partners;

use App\AffiliateCodes;
use App\Driver;
use App\Partner;
use App\Sales;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Lang;
use Jenssegers\Agent\Agent;

class ListController extends Controller {
    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer')->except('update');
            $this->middleware('partner_active')->except('update');
        }
    }


    public function allPartnersListData() {
        $data = [];

        $vehicles = Vehicle::whereHas('affiliate', function ($q) {
            $q->where('user_id', \Auth::user()->id)->where('status', 1);
            $q->orWhere(function ($q) {
                $q->where('tier_one_user_id', \Auth::user()->id)->where('status', 1);
            });
        })->active();
        $existsV = $vehicles->exists();

        $partners = AffiliateCodes::where('user_id', \Auth::user()->id)
            ->where('status', 1)->whereDoesntHave('activeVehicles')->orWhere(function ($q) {
                $q->where('tier_one_user_id', \Auth::user()->id)->where('status', 1);
            })->whereDoesntHave('activeVehicles');
        $existsP = $partners->exists();

        if ($existsV) {
            $vehiclesQ = $vehicles->with([
                'affiliate',
                'user',
                'routeTypes.translated',
            ]);

            $vehicles = $vehiclesQ->get()->toArray();


            foreach ($vehicles as $key => $val) {
                $the_type = [];
                $the_type_slug = [];
                $data[$key]['name'] = $val['user']['name'];
                $data[$key]['vehicle_type'] = $val['route_types']['translated']['name'];
                if (Driver::where('user_id', $val['user']['id'])->active()->exists()) :
                    $the_type[] = \Lang::get('driver.driver');
                    $the_type_slug[] = 'driver';
                endif;
                if (Partner::where('user_id', $val['user']['id'])->active()->exists()) :
                    $the_type[] = \Lang::get('driver.partner');
                    $the_type_slug[] = 'partner';
                endif;
                if (Sales::where('user_id', $val['user']['id'])->statusNot(2)->exists()) :
                    $the_type[] = Lang::get('misc.passenger');
                    $the_type_slug[] = 'passenger';
                endif;
                $data[$key]['phone_number'] = $val['user']['phone_number'];
                $data[$key]['the_type'] = implode(", ", $the_type ?? [\Lang::get('misc.user')]);
                $data[$key]['number_of_seats'] = $val['number_of_seats'];

                if(in_array('driver', $the_type_slug) || in_array('partner', $the_type_slug)) {
                    if ($val['affiliate']['tier_one_user_id'] == \Auth::user()->id) {
                        $data[$key]['tier_2_cut'] = config('app.tier2_affiliate') / 100 . '%';
                        $data[$key]['tier_1_cut'] = '-';
                    } else {
                        $data[$key]['tier_1_cut'] = config('app.tier1_affiliate') / 100 . '%';
                        $data[$key]['tier_2_cut'] = '-';
                    }
                }
                else {
                    $data[$key]['tier_1_cut'] = '-';
                    $data[$key]['tier_2_cut'] = '-';
                }

                if(in_array('passenger', $the_type_slug)) {
                    $data[$key]['passenger_cut'] = config('app.passenger_affiliate') / 100 . '%';
                }
                else {
                    $data[$key]['passenger_cut'] = '-';
                }
            }

        }

        if ($existsP) {
            $partnersQ = $partners->with([
                'user',
            ]);
            $k = count($data);


            $partners = $partnersQ->get()->toArray();

            foreach ($partners as $key => $val) {
                $the_type = [];
                $the_type_slug = [];
                $data[$key + $k]['name'] = $val['user']['name'];
                $data[$key + $k]['vehicle_type'] = '-';
                $data[$key + $k]['number_of_seats'] = '-';
                if (Driver::where('user_id', $val['user']['id'])->active()->exists()) :
                    $the_type[] = \Lang::get('driver.driver');
                    $the_type_slug[] = 'driver';
                endif;
                if (Partner::where('user_id', $val['user']['id'])->active()->exists()) :
                    $the_type[] = \Lang::get('driver.partner');
                    $the_type_slug[] = 'partner';
                endif;
                if (Sales::where('user_id', $val['user']['id'])->statusNot(2)->exists()) :
                    $the_type[] = Lang::get('misc.passenger');
                    $the_type_slug[] = 'passenger';
                endif;
                $data[$key + $k]['phone_number'] = $val['user']['phone_number'];
                $data[$key + $k]['the_type'] = implode(", ", $the_type ?? [\Lang::get('misc.user')]);

                if(in_array('driver', $the_type_slug) || in_array('partner', $the_type_slug)) {
                    if ($val['tier_one_user_id'] == \Auth::user()->id) {
                        $data[$key + $k]['tier_2_cut'] = config('app.tier2_affiliate') / 100 . '%';
                        $data[$key + $k]['tier_1_cut'] = '-';
                    }
                    else {
                        $data[$key + $k]['tier_1_cut'] = config('app.tier1_affiliate') / 100 . '%';
                        $data[$key + $k]['tier_2_cut'] = '-';
                    }
                }
                else {
                    $data[$key + $k]['tier_1_cut'] = '-';
                    $data[$key + $k]['tier_2_cut'] = '-';
                }
                if(in_array('passenger', $the_type_slug)) {
                    $data[$key + $k]['passenger_cut'] = config('app.passenger_affiliate') / 100 . '%';
                }
                else {
                    $data[$key + $k]['passenger_cut'] = '-';
                }
            }
        }

        return datatables()->of($data)->toJson();
    }


    public function view() {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();
            return view('partner.list', $data);
        } else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }
    }
}
