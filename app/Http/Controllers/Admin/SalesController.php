<?php

namespace App\Http\Controllers\Admin;

use App\AffiliateCodes;
use App\BalanceUpdates;
use App\Http\Controllers\StatusController;
use App\Payouts;
use App\Sales;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;

class SalesController extends AdminController
{

    private function store($data)
    {
        Payouts::updateOrCreate(['id' => $data['id'] ?? 0], Arr::except($data, 'id'));
    }

    public function approve(Request $request)
    {
        $d = $request->only('id');
        Sales::where('id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_sale_approve')]);
    }

    public function refund(Request $request)
    {
        $d = $request->only('id');
        Sales::where('id', $d['id'])->update(['status' => 4]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_sale_refund')]);
    }

    public function delete(Request $request)
    {
        $d = $request->only('id');
        Sales::where('id', $d['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_sale_delete')]);
    }


    public function viewDataColumns()
    {
        return [
            'date', 'passenger', 'route_id', 'manufacturer_model', 'seat_number', 'price', 'driver_cut', 'partner_cut', 'partner2_cut', 'passenger_cut', 'company_cut', 'status', 'actions'
        ];
    }


    private function saleActions($id, $status)
    {
        $actions = [
            [
                'url' => route('secure_ticket', ['id' => md5($id)]),
                'faicon' => 'fa-ticket',
                'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
            ]
        ];
        if ($status == 1) {
            $actions[] = [
                'url' => route('admin_sale_refund'),
                'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                'anchor' => \Lang::get('misc.refund'),
                'alertify' => [
                    'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                    'success-msg' => Lang::get('alerts.success_sale_refund'),
                    'error-msg' => Lang::get('alerts.error_sale_refund'),
                ],
                'ajaxData' => [
                    'id' => $id,
                ],
            ];
        }
        else if ($status > 4) {
            $actions[] = [
                'url' => route('admin_sale_approve'),
                'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                'anchor' => \Lang::get('misc.approve'),
                'alertify' => [
                    'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                    'success-msg' => Lang::get('alerts.success_sale_approve'),
                    'error-msg' => Lang::get('alerts.error_sale_approve'),
                ],
                'ajaxData' => [
                    'id' => $id,
                ],
            ];
        }
        if ($status != 3) {
            $actions[] = [
                'faicon' => 'fa-times',
                'url' => route('admin_sale_delete'),
                'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                'alertify' => [
                    'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                    'success-msg' => Lang::get('alerts.success_payout_delete'),
                    'error-msg' => Lang::get('alerts.error_payout_delete'),
                ],
                'ajaxData' => [
                    'id' => $id
                ],
            ];
        }
        return $actions;
    }

    private function saleCuts($bUps, $price)
    {
        if (in_array(1, array_column($bUps, 'type'))) {
            $data['driver_cut'] = $bUps[array_search(1, array_column($bUps, 'type'))]['amount'];
            $data['driver_cut_percent'] = round($data['driver_cut'] / $price * 100, 3);
        }
        else {
            $data['driver_cut'] = 0;
            $data['driver_cut_percent'] = 0;
        }


        if (in_array(2, array_column($bUps, 'type'))) {
            $data['tier1_cut'] = $bUps[array_search(2, array_column($bUps, 'type'))]['amount'];
            $data['tier1_cut_percent'] = round($data['tier1_cut'] / $price * 100, 3);
        }
        else {
            $data['tier1_cut'] = 0;
            $data['tier1_cut_percent'] = 0;
        }

        if (in_array(3, array_column($bUps, 'type'))) {
            $data['tier2_cut'] = $bUps[array_search(3, array_column($bUps, 'type'))]['amount'];
            $data['tier2_cut_percent'] = round($data['tier2_cut'] / $price * 100, 3);
        }
        else {
            $data['tier2_cut'] = 0;
            $data['tier2_cut_percent'] = 0;
        }


        if (in_array(4, array_column($bUps, 'type'))) {
            $data['company_cut'] = $bUps[array_search(4, array_column($bUps, 'type'))]['amount'];
            $data['company_cut_percent'] = round($data['company_cut'] / $price * 100, 3);
        }
        else {
            $data['company_cut'] = 0;
            $data['company_cut_percent'] = 0;
        }

        if (in_array(5, array_column($bUps, 'type'))) {
            $data['passenger_cut'] = $bUps[array_search(5, array_column($bUps, 'type'))]['amount'];
            $data['passenger_cut_percent'] = round($data['passenger_cut'] / $price * 100, 3);
        }
        else {
            $data['passenger_cut'] = 0;
            $data['passenger_cut_percent'] = 0;
        }


        return $data;
    }

    public function viewData(Request $request)
    {
        $salesQ = Sales::with(
            'users', 'balanceUpdates', 'routes', 'routes.vehicles', 'routes.vehicles.manufacturers', 'routes.citiesFrom',
            'routes.citiesFrom.translated', 'routes.citiesTo', 'routes.citiesTo.translated'
        )->statusNot(2);

        if (isset($request->user_id) && $request->user_id != 'all') {
            $salesQ->whereHas('routes', function ($q) use ($request) {
                $q->where('user_id', $request->user_id);
            });
        }
        else if ($request->user_id != 'all') {
            $salesQ->whereHas('routes', function ($q) {
                $q->where('user_id', 0);
            });
        }


        $sales = $salesQ->get()->toArray();

        foreach ($sales as $key => $val) {
            $actions = $this->saleActions($val['ticket_number'], $val['status']);
            $cuts = $this->saleCuts($val['balance_updates'], $val['price']);
            $sales[$key]['passenger'] = $val['users']['name'];
            $sales[$key]['manufacturer_model'] = $val['routes']['vehicles']['manufacturers']['name'] . ' ' . $val['routes']['vehicles']['model'];
            $sales[$key]['route_id'] = view('components.a', ['class' => 'blue', 'href' => route('admin_route_edit', ['id' => $val['route_id']]), 'target' => '_blank', 'anchor' => $val['routes']['cities_from']['code'] . $val['route_id']])->render();
            $sales[$key]['driver_cut'] = $cuts['driver_cut'] . ' (' . $cuts['driver_cut_percent'] . '%)';
            $sales[$key]['partner_cut'] = $cuts['tier1_cut'] . ' (' . $cuts['tier1_cut_percent'] . '%)';
            $sales[$key]['partner2_cut'] = $cuts['tier2_cut'] . ' (' . $cuts['tier2_cut_percent'] . '%)';
            $sales[$key]['passenger_cut'] = $cuts['passenger_cut'] . ' (' . $cuts['passenger_cut'] . '%)';
            $sales[$key]['company_cut'] = $cuts['company_cut'] . ' (' . $cuts['company_cut_percent'] . '%)';
            $sales[$key]['date'] = Carbon::parse($val['created_at'])->format('Y-m-d H:i');
            $sales[$key]['status'] = view('components.status-admin', ['text' => Lang::get('statuses/sale.' . $val['status'] . '.text'), 'class' => StatusController::statusSaleLabelClass($val['status'])])->render();
            $sales[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }


        return datatables()->of($sales)->rawColumns(['route_id', 'status', 'actions'])->toJson();

    }


    public function viewDataColumnsPartner() {
        return [
            'sale_id', 'date', 'user_name', 'user_type', 'route_id', 'price', 'driver_cut', 'partner_cut', 'partner2_cut', 'passenger_cut', 'company_cut', 'status', 'actions'
        ];
    }

    public function viewPartnerSalesData(Request $request)
    {
        $sales = BalanceUpdates::whereUserId($request->user_id)->with([
            'sale' => function ($q) {
                $q->whereStatus(3);
            },
            'sale.routes',
            'sale.balanceUpdates',
            'sale.routes.citiesFrom.translated',
            'sale.routes.citiesTo.translated',
            'sale.routes.vehicles.manufacturers',
            'sale.currency',
            'sale.users',
            'sale.routes.vehicles.routeTypes.translated',
            'sale.routes.affiliate.user'
        ])->whereHas('sale', function ($q) {
            $q->whereStatus(3);
        })->whereIn('type', [2,3,5])->get()->toArray();

        $data = [];
        foreach ($sales as $key => $val) {
            if ($val['type'] == 5) {
                $user = $val['sale']['users']['name'];
                $type = \Lang::get('misc.passenger');
            }
            else {
                $user = $val['sale']['routes']['affiliate']['user']['name'];
                $type = \Lang::get('misc.driver');
            }
            $actions = $this->saleActions($val['sale']['ticket_number'], $val['sale']['status']);
            $cuts = $this->saleCuts($val['sale']['balance_updates'], $val['sale']['price']);
            $data[$key]['sale_id'] = $val['sale']['id'];
            $data[$key]['date'] = Carbon::parse($val['sale']['created_at'])->format('Y-m-d H:i');
            $data[$key]['user_name'] = $user;
            $data[$key]['user_type'] = $type;
            $data[$key]['price'] = $val['sale']['price'];
            $data[$key]['route_id'] = view('components.a', ['class' => 'blue', 'href' => route('admin_route_edit', ['id' => $val['sale']['route_id']]), 'target' => '_blank', 'anchor' => $val['sale']['routes']['cities_from']['code'] . $val['sale']['route_id']])->render();
            $data[$key]['driver_cut'] =
                ($val['type'] == 1) ?
                view('components.b', ['value' => $cuts['driver_cut'] . ' (' . $cuts['driver_cut_percent'] . '%)'])->render() :
                $cuts['driver_cut'] . ' (' . $cuts['driver_cut_percent'] . '%)';

            $data[$key]['partner_cut'] =
                ($val['type'] == 2) ?
                    view('components.b', ['value' => $cuts['tier1_cut'] . ' (' . $cuts['tier1_cut_percent'] . '%)'])->render() :
                    $cuts['tier1_cut'] . ' (' . $cuts['tier1_cut_percent'] . '%)';
            $data[$key]['partner2_cut'] =
                ($val['type'] == 3) ?
                    view('components.b', ['value' => $cuts['tier2_cut'] . ' (' . $cuts['tier2_cut_percent'] . '%)'])->render() :
                    $cuts['tier2_cut'] . ' (' . $cuts['tier2_cut_percent'] . '%)';
            $data[$key]['passenger_cut'] =
                ($val['type'] == 5) ?
                    view('components.b', ['value' => $cuts['passenger_cut'] . ' (' . $cuts['passenger_cut_percent'] . '%)'])->render() :
                    $cuts['passenger_cut'] . ' (' . $cuts['passenger_cut_percent'] . '%)';
            $data[$key]['company_cut'] = $cuts['company_cut'] . ' (' . $cuts['company_cut_percent'] . '%)';
            $data[$key]['status'] = view('components.status-admin', ['text' => Lang::get('statuses/sale.' . $val['sale']['status'] . '.text'), 'class' => StatusController::statusSaleLabelClass($val['sale']['status'])])->render();
            $data[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }


        return datatables()->of($data ?? [])->rawColumns(['partner_cut', 'partner2_cut', 'passenger_cut', 'route_id', 'status', 'actions'])->toJson();
    }

    public function view()
    {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.all_sales');
        $data['columns'] = $this->viewDataColumns();
        $data['ajaxUrl'] = route('admin_sale_data');
        $data['ajaxData'] = ['user_id' => 'all'];
        $data['labels'] = [
            ['name' => Lang::get('admin.total'), 'value' => $data['sidebar']['sales_total_count'], 'label' => 'success'],
            ['name' => Lang::get('admin.status_purchased'), 'value' => Sales::whereStatus(1)->count(), 'label' => 'success'],
            ['name' => Lang::get('admin.status_parsed'), 'value' => Sales::whereStatus(3)->count(), 'label' => 'success'],
            ['name' => Lang::get('admin.status_refunded'), 'value' => Sales::whereStatus(4)->count(), 'label' => 'danger', 'divide' => true],
            ['name' => Lang::get('admin.total_in_currency'), 'value' => Sales::status([1, 3])->sum('price') . ' GEL', 'label' => 'success'],
            ['name' => Lang::get('admin.driver_earnings'), 'value' => BalanceUpdates::whereType(1)->sum('amount') . ' GEL', 'label' => 'success'],
            ['name' => Lang::get('admin.affiliate_earnings'), 'value' => BalanceUpdates::where('type', 2)->orWhere('type', 3)->sum('amount') . ' GEL', 'label' => 'success'],
            ['name' => Lang::get('admin.zoombus_earnings'), 'value' => BalanceUpdates::whereType(4)->sum('amount') . ' GEL', 'label' => 'success'],

        ];
        $data['dateDefs'] = [0];
        return view('admin.pages.dataTables', $data);
    }
}
