<?php

namespace App\Http\Controllers\Partners;

use App\AffiliateCodes;
use App\User;
use Hashids\Hashids;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Jenssegers\Agent\Agent;

class GeneratorController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer')->except('update');
            $this->middleware('partner_active')->except('update');
        }
    }

    private static function store($data)
    {
        return AffiliateCodes::create($data);
    }

    private static function update($where, $update)
    {
        return AffiliateCodes::where($where)->update($update);
    }

    public static function generate($user_id = null)
    {
        if (!empty($user_id)) {
            AffiliateCodes::where('user_id', $user_id)->update(['status' => 1]);
            $hs = new Hashids('', 14, 'ABCDEFGHIJKLMNOPQRSTUVWXYZ1234567890');
            $data['user_id'] = $user_id;
            if (User::whereNotNull('affiliate_code')->whereId($user_id)->exists()) {
                $parentId = User::with('affiliateClient')->whereId($user_id)->first()->toArray();
                $data['tier_one_user_id'] = $parentId['affiliate_client']['user_id'];
            }
            $data['status'] = 2;
            $id = self::store($data)->id;
            self::update(['id' => $id], ['code' => $hs->encode($id)]);
        }
    }

    public function view()
    {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();
            $data['registerAs'] = [
                [
                    'id' => 'passenger',
                    'name' => Lang::get('misc.passenger_partner')
                ],
                [
                    'id' => 'driver',
                    'name' => Lang::get('misc.driver_partner')
                ]
            ];
            $data['affiliateCode'] = AffiliateCodes::where('user_id', \Auth::user()->id)->where('status', 2)->first(['code'])->code;
            return view('partner.affiliate_code', $data);
        }
        else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }
    }


}
