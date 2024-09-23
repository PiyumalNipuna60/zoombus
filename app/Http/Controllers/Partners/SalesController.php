<?php

namespace App\Http\Controllers\Partners;

use App\AffiliateCodes;
use App\BalanceUpdates;
use App\Http\Controllers\StatusController;
use App\Routes;
use App\Sales;
use Carbon\Carbon;
use App\Http\Controllers\Controller;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;

class SalesController extends Controller
{
    public function __construct()
    {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer');
            $this->middleware('partner_active');
        }
    }

    public function allSalesData(Request $request)
    {


        $salesQ = BalanceUpdates::whereUserId(($request->mobile) ? $request->user()->id : \Auth::user()->id)->with([
            'sale' => function($q) {
                $q->whereStatus(3);
            },
            'sale.routes',
            'sale.routes.citiesFrom.translated',
            'sale.routes.citiesTo.translated',
            'sale.routes.vehicles.manufacturers',
            'sale.currency',
            'sale.users',
            'sale.routes.vehicles.routeTypes.translated',
            'sale.routes.affiliate.user'
        ])->whereHas('sale', function ($q) {
            $q->whereStatus(3);
        })->whereIn('type', [2,3,5]);

        if($request->mobile) {
            $salesQ->skip($request->skip)->take(config('app.partner_sales_per_page'));
        }
        $sales = $salesQ->get()->toArray();

        $data = [];
        foreach ($sales as $key => $val) {
            if ($val['type'] == 5) {
                $user = $val['sale']['users']['name'];
                $phone = $val['sale']['users']['phone_number'];
                $type = \Lang::get('misc.passenger');
            }
            else {
                $user = $val['sale']['routes']['affiliate']['user']['name'];
                $phone = $val['sale']['routes']['affiliate']['user']['phone_number'];
                $type = \Lang::get('misc.driver');
            }
            if ($request->mobile) {
                $data[$key]['amount'] = $val['amount'] . ' GEL';
                $data[$key]['from'] = $val['sale']['routes']['cities_from']['translated']['name'];
                $data[$key]['to'] = $val['sale']['routes']['cities_to']['translated']['name'];
                $data[$key]['departure_date'] = Carbon::parse($val['sale']['routes']['departure_date'])->translatedFormat('j M Y');
                $data[$key]['vehicle_type_id'] = $val['sale']['routes']['vehicles']['type'];
                $data[$key]['route_id'] = $val['sale']['routes']['cities_from']['code'] . $val['sale']['routes']['id'];
                $data[$key]['license_plate'] = $val['sale']['routes']['vehicles']['license_plate'];
                $data[$key]['percent'] = round($val['amount'] / $val['sale']['price']  * 100, 3) . '%';
                $data[$key]['user_name'] = $user;
                $data[$key]['vehicle_type'] = explode("(", $val['sale']['routes']['vehicles']['route_types']['translated']['name'])[0];
            }
            else {
                $data[$key]['user_phone'] = $phone;
                $data[$key]['user_type'] = $type;
                $data[$key]['the_route'] = $val['sale']['routes']['cities_from']['translated']['name'] . ' ' . view('components.font-awesome', ['icon' => 'fa-arrow-right'])->render() . ' ' . $val['sale']['routes']['cities_to']['translated']['name'];
                $data[$key]['departure_date'] = Carbon::parse($val['sale']['routes']['departure_date'])->format('Y-m-d');
                $data[$key]['amount'] = $val['amount'] . ' ' . $val['sale']['currency']['key'];
                $data[$key]['percent'] = round($val['amount'] / $val['sale']['price'] * 100, 3) . '%';
                $data[$key]['user_name'] = $user;
                $data[$key]['vehicle_type'] = explode("(", $val['sale']['routes']['vehicles']['route_types']['translated']['name'])[0];
            }

        }

        if ($request->mobile) {
            return $data;
        }
        else {
            return datatables()->of($data ?? [])->rawColumns(['the_route'])->toJson();
        }
    }

    public function view()
    {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();
            return view('partner.sales', $data);
        }
        else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }
    }
}
