<?php

namespace App\Http\Controllers\Api;


use App\AffiliateCodes;
use App\Http\Controllers\Partners\ListController as LC;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class PartnerListController extends LC {

    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }

        $dataQ = AffiliateCodes::where('user_id', $request->user()->id)->where('status', 1)->orWhere(function ($q) use ($request) {
            $q->where('tier_one_user_id', $request->user()->id)->where('status', 1);
        });

        $items = [];
        if ($dataQ->exists()) {
            $items = $dataQ->with('user','user.driver','user.partner')->skip($request->skip)->take(config('app.partner_list_per_page'))->get()->toArray();
            foreach($items as $key => $item) {
                $roles = [];
                if ($item['user']['driver']) {
                    $roles[] = \Lang::get('driver.driver');
                }
                else if ($item['user']['partner']){
                    $roles[] = \Lang::get('driver.partner');
                }
                else {
                    $roles[] = \Lang::get('misc.passenger');
                }
                $items[$key]['roles'] = implode(', ', $roles);
                if($item['tier_one_user_id'] == $request->user()->id) {
                    $items[$key]['percent'] = config('app.tier2_affiliate') / 100 . '%';
                }
                else {
                    if(sizeof($roles) == 1 && $roles[0] == \Lang::get('misc.passenger')) {
                        $percent = config('app.passenger_affiliate');
                    }
                    else {
                        $percent = config('app.tier1_affiliate');
                    }
                    $items[$key]['percent'] = $percent / 100 . '%';
                }
                $items[$key]['date'] = Carbon::parse($item['user']['created_at'])->translatedFormat('j M Y');
                $items[$key]['avatar'] = (new User())->photoById($item['user']['id']);

            }
        }
        $data['total'] = $dataQ->count();
        $data['items'] = $items;

        return response()->json($data, 200);
    }

    public function getDetails(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data['user'] = User::whereId($request->user_id)->first(['name','email','phone_number'])->toArray();
        if(Vehicle::whereUserId($request->user_id)->active()->exists()) {
            $vehs = Vehicle::with('routeTypes:id,key', 'routeTypes.translated', 'manufacturers:id,name as manufacturer_name')
                ->whereUserId($request->user_id)
                ->active();

            $data['total'] = $vehs->count();
            $vehicles = $vehs->skip($request->skip)->take(config('app.partner_list_vehicles_per_page'))
                ->get()->toArray();
            $data['vehicles'] = collect($vehicles)->map(function ($item) {
                $item['created_at_formatted'] = Carbon::parse($item['created_at'])->translatedFormat('j M Y');
                return $item;
            });
        }

        return response()->json($data);
    }


}
