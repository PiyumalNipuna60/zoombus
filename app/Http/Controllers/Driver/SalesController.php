<?php

namespace App\Http\Controllers\Driver;

use App\BalanceUpdates;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\StatusController;
use App\ReservedSeats;
use App\Routes;
use App\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Jenssegers\Agent\Agent;
use function foo\func;

class SalesController extends DriverController {

    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer');
            $this->middleware('driver_active');
            $this->middleware('can_edit_sale')->only('viewEdit');
        }
    }


    public function allCurrentSalesData(Request $request) {
        $request->history = false;
        return $this->allSalesData($request);
    }


    public function allSalesData(Request $request) {
        $routesQ = Routes::with([
            'citiesFrom.translated',
            'citiesTo.translated',
            'vehicles.manufacturers',
        ])->withCount(['sales' => function ($q) use ($request) {
            if ($request->history) {
                $q->whereStatus(3);
            } else {
                $q->status([1, 3]);
            }
        }])->whereStatus(($request->history) ? 2 : 1);

        $data = [];

        if ($request->mobile) {
            $routes = $routesQ->whereUserId($request->user()->id)
                ->skip($request->skip)
                ->take(($request->history) ? config('app.sales_by_route_per_page') : config('app.current_sales_by_route_per_page'))
                ->get()->toArray();
        } else {
            $routes = $routesQ->whereUserId(\Auth::user()->id)->get()->toArray();
        }

        foreach ($routes as $k => $r) {
            $totalSoldCurrencyQ = BalanceUpdates::whereHas('sale', function ($q) use ($r) {
                $q->whereRouteId($r['id']);
            });
            $totalSoldCurrency = $totalSoldCurrencyQ->whereStatus(($request->history) ? 1 : 0)->whereType(1)->whereUserId(($request->mobile) ? $request->user()->id : \Auth::user()->id)->sum('amount');
            if (!$request->mobile) {
                $totalSoldCurrencyAll = BalanceUpdates::whereHas('sale', function ($q) use ($r) {
                    $q->whereRouteId($r['id']);
                })->whereStatus(($request->history) ? 1 : 0)->sum('amount');
                if ($totalSoldCurrencyAll > 0) :
                    $percentage = round($totalSoldCurrency / $totalSoldCurrencyAll * 100, 3);
                    $companyPercentage = 100 - $percentage;
                else:
                    $percentage = 0;
                    $companyPercentage = 0;
                endif;

                $companyPriceWithCurrency = $totalSoldCurrencyAll - $totalSoldCurrency;

                if ($request->history) {
                    $actions = [
                        [
                            'url' => route('route_edit', ['id' => $r['id'], 'hideFields' => 1]),
                            'anchor' => Lang::get('misc.view_more'),
                        ]
                    ];
                } else {
                    $actions = [
                        [
                            'url' => route('route_edit', ['id' => $r['id'], 'hideFields' => 1]),
                            'anchor' => Lang::get('misc.reserve_ticket'),
                        ]
                    ];
                }
            }

            $data[$k]['id'] = $r['id'];
            $data[$k]['route_id'] = $r['cities_from']['code'] . $r['id'];
            if ($request->mobile) {
                $data[$k]['from'] = $r['cities_from']['translated']['name'];
                $data[$k]['to'] = $r['cities_to']['translated']['name'];
                $data[$k]['departure_date'] = Carbon::parse($r['departure_date'])->translatedFormat('j M Y');
                $data[$k]['total_sold'] = $totalSoldCurrency;
            } else {
                $data[$k]['the_route'] = $r['cities_from']['translated']['name'] . ' ' . view('components.font-awesome', ['icon' => 'fa-arrow-right'])->render() . ' ' . $r['cities_to']['translated']['name'];
                $data[$k]['the_transport'] = $r['vehicles']['manufacturers']['name'] . ' ' . $r['vehicles']['model'] . ' / ' . $r['vehicles']['license_plate'];
                $data[$k]['departure_date'] = Carbon::parse($r['departure_date'])->format('Y-m-d');
                $data[$k]['price'] = $r['price'] . ' GEL';
                $data[$k]['total_sold'] = $r['sales_count'];
                $data[$k]['total_sold_currency'] = $totalSoldCurrency . ' GEL (' . $percentage . '%)';
                $data[$k]['total_company_currency'] = $companyPriceWithCurrency . ' GEL (' . $companyPercentage . '%)';
                $data[$k]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
            }

        }

        if ($request->mobile) {
            return $data;
        } else {
            return datatables()->of($data ?? [])->rawColumns(['the_route', 'the_transport', 'the_status', 'actions'])->toJson();
        }
    }


    public function allSalesHistoryData(Request $request) {
        if ($request->mobile) {
            $user = $request->user();
        } else {
            $user = \Auth::user();
        }
        $salesQ = Sales::with([
            'routes',
            'routes.citiesFrom:id,code as city_code,extension',
            'routes.citiesFrom.translated',
            'routes.citiesTo',
            'routes.citiesTo.translated',
            'routes.vehicles:id,model,license_plate,manufacturer',
            'routes.vehicles.manufacturers:id,name as manufacturer_name',
            'users:id,name',
            'balanceUpdates:id,sale_id,amount',
            'currency:id,key'
        ])->whereHas('routes', function ($q) use ($user) {
            $q->where('user_id', $user->id)->select(['id', 'from', 'to', 'vehicle_id']);
        })->with(['balanceUpdates' => function ($q) use ($user) {
            $q->where('user_id', $user->id)->whereType(1)->select(['id', 'sale_id', 'amount']);
        }])->statusNot(2);

        if ($request->mobile) {
            $salesQ->skip($request->skip)->take(config('app.sales_history_per_page'));
        }

        if (!empty($request->route_id)) {
            $salesQ->whereRouteId($request->route_id);
        }


        $sales = $salesQ->get()->toArray();

        $data = [];
        foreach ($sales as $key => $val) {
            $price = array_sum(array_column($val['balance_updates'], 'amount'));
            if ($val['status'] == 4) {
                $price = '-' . $price;
            }

            $data[$key]['id'] = $val['routes']['cities_from']['city_code'] . $val['id'];
            $data[$key]['customer'] = $val['users']['name'];
            if ($request->mobile) {
                $data[$key]['departure_date'] = Carbon::parse($val['routes']['departure_date'])->translatedFormat('j\ M Y');
                $data[$key]['from'] = $val['routes']['cities_from']['translated']['name'];
                $data[$key]['to'] = $val['routes']['cities_to']['translated']['name'];
                $data[$key]['departure_time'] = $val['routes']['departure_time'];
                $data[$key]['arrival_time'] = $val['routes']['arrival_time'];
                $data[$key]['price'] = $price;
                $data[$key]['currency'] = $val['currency']['key'];
                $data[$key]['status'] = $val['status'];
            } else {
                $percentage = round($price / $val['price'] * 100, 3);
                $companyPercentage = 100 - $percentage;
                $companyPriceWithCurrency = $val['price'] - $price . ' ' . $val['currency']['key'];
                $priceWithCurrency = $price . ' ' . $val['currency']['key'];
                $data[$key]['the_route'] = $val['routes']['cities_from']['translated']['name'] . ' ' . view('components.font-awesome', ['icon' => 'fa-arrow-right'])->render() . ' ' . $val['routes']['cities_to']['translated']['name'];
                $data[$key]['the_transport'] = $val['routes']['vehicles']['manufacturers']['manufacturer_name'] . ' ' . $val['routes']['vehicles']['model'] . ' / ' . $val['routes']['vehicles']['license_plate'];
                $data[$key]['departure_date'] = Carbon::parse($val['routes']['departure_date'])->format('Y-m-d');
                $data[$key]['price'] = $priceWithCurrency . ' (' . $percentage . '%)';
                $data[$key]['company_cut'] = $companyPriceWithCurrency . ' (' . $companyPercentage . '%)';
                $data[$key]['the_status'] = StatusController::fetchWithoutText($val['status'], Lang::get('statuses/sale'));
            }
            $data[$key]['seat_number'] = $val['seat_number'];

        }


        $reservedSalesQ = ReservedSeats::with('routes', 'routes.vehicles', 'routes.vehicles.manufacturers', 'routes.citiesFrom.translated', 'routes.citiesTo.translated');
        if (!empty($request->route_id)) {
            $reservedSalesQ->whereRouteId($request->route_id);
        }

        if (!empty($request->route_id)) {
            $reservedSales = $reservedSalesQ->get()->toArray();
            $theKey = count($sales);
            foreach ($reservedSales as $key => $val) {
                $data[$theKey + 1 + $key]['id'] = $val['routes']['cities_from']['code'] . $val['routes']['id'];
                $data[$theKey + 1 + $key]['customer'] = '-';
                $data[$theKey + 1 + $key]['the_route'] = $val['routes']['cities_from']['translated']['name'] . ' ' . view('components.font-awesome', ['icon' => 'fa-arrow-right'])->render() . ' ' . $val['routes']['cities_to']['translated']['name'];
                $data[$theKey + 1 + $key]['the_transport'] = $val['routes']['vehicles']['manufacturers']['name'] . ' ' . $val['routes']['vehicles']['model'] . ' / ' . $val['routes']['vehicles']['license_plate'];
                $data[$theKey + 1 + $key]['seat_number'] = $val['seat_number'];
                $data[$theKey + 1 + $key]['departure_date'] = Carbon::parse($val['routes']['departure_date'])->format('Y-m-d');
                $data[$theKey + 1 + $key]['price'] = '-';
                $data[$theKey + 1 + $key]['the_status'] = StatusController::fetchWithoutText(0, Lang::get('statuses/driverSales'));
            }
        }


        if ($request->mobile) {
            return $data;
        } else {
            return datatables()->of($data ?? [])->rawColumns(['the_route', 'the_transport', 'the_status', 'actions'])->toJson();
        }

    }


    public function viewCurrent() {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();

            $totalSold = Sales::whereHas('routes', function ($q) {
                $q->whereUserId(\Auth::user()->id);
            })->status(1)->count();

            $totalSoldCurrency = BalanceUpdates::whereUserId(\Auth::user()->id)->whereType(1)->whereStatus(0)->sum('amount');

            $data['total_sold_currency'] = $totalSoldCurrency;
            $data['total_sold'] = $totalSold;
            return view('driver.current-sales', $data);
        } else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }

    }

    public function viewAll() {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();

            $totalSold = Sales::whereHas('routes', function ($q) {
                $q->whereUserId(\Auth::user()->id);
            })->status(3)->count();

            $totalSoldCurrency = BalanceUpdates::whereUserId(\Auth::user()->id)->whereType(1)->whereStatus(1)->sum('amount');

            $data['total_sold_currency'] = $totalSoldCurrency;
            $data['total_sold'] = $totalSold;
            return view('driver.sales', $data);
        } else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }

    }

    public function viewAllHistory() {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();

            $approvedSalesCount = Sales::whereHas('routes', function ($q) {
                $q->where('user_id', \Auth::user()->id);
            })->status(3)->count();
            $sumApproved = BalanceUpdates::whereUserId(\Auth::user()->id)->whereType(1)->whereStatus(1)->sum('amount');

            $data['total_approved'] = $sumApproved;
            $data['total_approved_count'] = $approvedSalesCount;


            $unApprovedSalesCount = Sales::whereHas('routes', function ($q) {
                $q->where('user_id', \Auth::user()->id);
            })->status(1)->count();
            $sumUnapproved = BalanceUpdates::whereUserId(\Auth::user()->id)->whereType(1)->whereStatus(0)->sum('amount');

            $data['total_unapproved'] = $sumUnapproved;
            $data['total_unapproved_count'] = $unApprovedSalesCount;


            $refundedSalesCount = Sales::whereHas('routes', function ($q) {
                $q->where('user_id', \Auth::user()->id);
            })->status(4)->count();

            $data['total_refunded_count'] = $refundedSalesCount;

            $driverReservedCount = ReservedSeats::whereHas('routes', function ($q) {
                $q->whereUserId(\Auth::user()->id);
            })->count();

            $data['total_driver_reserved_count'] = $driverReservedCount;
            return view('driver.salesHistory', $data);
        } else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }

    }

    public function viewEdit($id) {
        $data = Controller::essentialVars();
        $data['sale'] = Sales::with(
            'routes', 'routes.citiesFrom.translated', 'routes.citiesTo.translated',
            'routes.vehicles', 'routes.vehicles.manufacturers',
            'users:id,gender', 'users.gender:id,key', 'routes.sales', 'routes.sales.currency', 'routes.sales.users', 'routes.sales.users.gender', 'routes.reservedSeats', 'routes.reservedSeats.gender')
            ->whereId($id)->first()->toArray();


        if (count($data['sale']['routes']['sales']) > 0) {
            $data['sale']['deleteAlertify'] = [
                'confirm-msg' => Lang::get('alerts.confirm_route_sale_refund',
                    [
                        'fine' => round(array_sum(array_column($data['sale']['routes']['sales'], 'price')) * config('app.driver_fine_commission') / 100, 3),
                        'currency' => $data['sale']['routes']['sales'][0]['currency']['key'] //Future multiple currency
                    ]
                ),
                'success-msg' => Lang::get('alerts.success_route_sale_refund'),
                'error-msg' => Lang::get('alerts.error_route_sale_refund'),
                'title' => Lang::get('alerts.title_sale_refund'),
                'ok' => Lang::get('alerts.ok_route_sale_refund'),
                'cancel' => Lang::get('alerts.cancel_route_sale_refund'),
                'id' => $data['sale']['route_id']
            ];

        } else {
            $data['sale']['deleteAlertify'] = [
                'confirm-msg' => Lang::get('alerts.confirm_route_delete'),
                'success-msg' => Lang::get('alerts.success_route_delete'),
                'error-msg' => Lang::get('alerts.error_route_delete'),
                'title' => Lang::get('alerts.title_route_delete'),
                'ok' => Lang::get('alerts.ok_route_delete'),
                'cancel' => Lang::get('alerts.cancel_route_delete'),
                'id' => $data['sale']['route_id']
            ];
        }

        return view('driver.sale-edit', $data);
    }
}
