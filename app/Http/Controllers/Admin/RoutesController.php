<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Driver\RoutesController as DriverRoutesController;
use App\Http\Controllers\StatusController;
use App\RemainingSeats;
use App\Routes;
use App\RouteTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;

class RoutesController extends AdminController
{


    public function suspend(Request $request)
    {
        $d = $request->only('id');
        Routes::where('id', $d['id'])->update(['status' => 3]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_route_suspend')]);
    }

    public function unsuspend(Request $request)
    {
        $d = $request->only('id');
        Routes::where('id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_route_unsuspend')]);
    }

    public function approve(Request $request)
    {
        $d = $request->only('id');
        Routes::where('id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_route_approve')]);
    }


    public function delete(Request $request)
    {
        $d = $request->only('id');
        RemainingSeats::whereRouteId($d['id'])->delete();
        Routes::where('id', $d['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_route_delete')]);
    }


    public function viewDataColumns()
    {
        return [
            'route_number', 'from_to', 'driver', 'manufacturer_model', 'vehicle_type', 'departure', 'seats', 'sold', 'arrival', 'status', 'actions'
        ];
    }

    public function viewData(Request $request)
    {
        $routesQ = Routes::with([
            'citiesFrom',
            'citiesFrom.translated',
            'citiesTo',
            'citiesTo.translated',
            'user',
            'addressFrom', 'addressFrom.translated',
            'addressTo', 'addressTo.translated', 'user', 'vehicles', 'vehicles.manufacturers',
            'vehicles.routeTypes', 'vehicles.routeTypes.translated'
        ])->withCount(['sales' => function ($q) {
            $q->status([1, 3]);
        }, 'reservedSeats']);
        if (!empty($request->vehicle_id) && $request->vehicle_id != 0) {
            $routesQ->where('vehicle_id', $request->vehicle_id);
        }
        if (!empty($request->user_id) && $request->user_id != 0) {
            $routesQ->where('user_id', $request->user_id);
        }
        $routes = $routesQ->get()->toArray();

        foreach ($routes as $key => $val) {
            if ($val['status'] == 3) {
                $sua = 'unsuspend';
            }
            else if ($val['status'] == 1) {
                $sua = 'suspend';
            }

            $actions = [
                [
                    'url' => route('admin_route_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ]
            ];
            if ($val['status'] == 3 || $val['status'] == 1) {
                $actions[] = [
                    'url' => route('admin_route_' . $sua),
                    'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                    'anchor' => \Lang::get('misc.' . $sua),
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_route_' . $sua),
                        'error-msg' => Lang::get('alerts.error_route_' . $sua),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ];
            }

            $actions[] = [
                'faicon' => 'fa-times',
                'url' => route('admin_route_delete'),
                'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                'alertify' => [
                    'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                    'success-msg' => Lang::get('alerts.success_route_delete'),
                    'error-msg' => Lang::get('alerts.error_route_delete'),
                ],
                'ajaxData' => [
                    'id' => $val['id']
                ],
            ];
            $routes[$key]['route_number'] = $val['cities_from']['code'] . $val['id'];
            $routes[$key]['vehicle_type'] = $val['vehicles']['route_types']['translated']['name'];
            $routes[$key]['driver'] = view('components.a', ['class' => 'blue', 'target' => '_blank', 'href' => route('admin_user_edit', ['id' => $val['user']['id']]), 'anchor' => $val['user']['name']])->render();
            $routes[$key]['manufacturer_model'] = $val['vehicles']['manufacturers']['name'] . ' ' . $val['vehicles']['model'];
            $routes[$key]['seats'] = $val['vehicles']['number_of_seats'];
            $routes[$key]['sold'] = $val['sales_count']+$val['reserved_seats_count'];
            $routes[$key]['departure'] = Carbon::parse($val['departure_date'])->format('Y-m-d') . ' ' . $val['departure_time'];
            $routes[$key]['arrival'] = Carbon::parse($val['arrival_date'])->format('Y-m-d') . ' ' . $val['arrival_time'];
            $routes[$key]['from_to'] = view('components.span-route-table', ['from' => $val['cities_from']['translated']['name'], 'to' => $val['cities_to']['translated']['name']])->render();
            $routes[$key]['status'] = view('components.status-admin', ['text' => StatusController::statusLabelRT($val['status'], 'en'), 'class' => StatusController::statusLabelClass($val['status'])])->render();
            $routes[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($routes)->rawColumns(['from_to', 'status', 'actions', 'driver'])->toJson();
    }

    public function viewEdit($id)
    {
        $data = AdminController::essentialVars();
        if (Routes::where('id', $id)->exists()) :
            $types = RouteTypes::with('translated')->get()->toArray();
            $cData = Routes::with(
                'citiesFrom', 'citiesFrom.translated', 'citiesTo', 'citiesTo.translated',
                'addressFrom', 'addressFrom.translated', 'addressTo', 'addressTo.translated',
                'vehicles', 'vehicles.manufacturers', 'vehicles.routeTypes:id', 'vehicles.routeTypes.translated',
                'currency'
            )->where('id', $id)->first()->toArray();
            $data['route'] = $cData;
            $data['route']['weekDays'] = (new DriverRoutesController())->weekDays();
            $data['route']['disabled'][$cData['status']] = true;
            $data['route']['ajaxData'] = ['id' => $cData['id']];
            $cData['id'] = ['value' => $cData['id'], 'readonly' => true];
            $cData['vehicles']['type'] = ['values' => $types, 'field' => 'select', 'class' => 'form-control select', 'value' => $cData['vehicles']['type']];
            $data['fields'] = Arr::only($cData, ['id', 'vehicles']);
        else:
            abort(404);
        endif;
        return view('admin.pages.routes.edit', $data);
    }

    public function viewAll()
    {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.all_routes');
        $data['columns'] = $this->viewDataColumns();
        $data['labels'] = [
            ['name' => Lang::get('admin.total'), 'value' => $data['sidebar']['routes_total_count'], 'label' => 'success'],
            ['name' => Lang::get('admin.status_active'), 'value' => Routes::whereStatus(1)->count(), 'label' => 'success'],
            ['name' => Lang::get('admin.status_suspended'), 'value' => Routes::whereStatus(3)->count(), 'label' => 'danger'],
            ['name' => Lang::get('admin.status_completed'), 'value' => Routes::whereStatus(2)->count(), 'label' => 'warning'],
        ];
        $data['columnDefs'] = [
            ['className' => 'lh-16', 'targets' => 2],
        ];
        $data['dateDefs'] = [4, 5];
        $data['ajaxUrl'] = route('admin_route_data');
        return view('admin.pages.dataTables', $data);
    }
}
