<?php

namespace App\Http\Controllers\Api;


use App\Driver;
use App\Http\Controllers\Driver\RoutesController as RC;
use App\RemainingSeats;
use App\ReservedSeats;
use App\Routes;
use App\Sales;
use App\Vehicle;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Mcamara\LaravelLocalization\LaravelLocalization;

class RoutesController extends RC {
    public function __construct() {
        parent::__construct();
    }

    public function preserve(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        if(Routes::whereId($request->id)->whereUserId($request->user()->id)->whereStatus(1)->exists()) {
            if($request->seats) {
                $seats = json_decode($request->seats, true);
                foreach($seats as $seat) {
                    if(Sales::whereSeatNumber($seat['seat_number'])->statusNot([2, 4])->whereRouteId($request->id)->exists()) {
                        return response()->json(['status' => 0, 'text' => Lang::get('validation.already_reserved', ['seat' => $seat['seat_number']])], 422);
                    }
                    if(ReservedSeats::whereSeatNumber($seat['seat_number'])->whereRouteId($request->id)->exists()) {
                        ReservedSeats::whereRouteId($request->id)->whereSeatNumber($seat['seat_number'])->delete();
                        RemainingSeats::whereRouteId($request->id)->increment('remaining_seats');
                    }
                    else {
                        ReservedSeats::create([
                            'route_id' => $request->id,
                            'seat_number' => $seat['seat_number'],
                            'gender_id' => $seat['gender_id'],
                        ]);
                        RemainingSeats::whereRouteId($request->id)->decrement('remaining_seats');
                    }
                }
                $response = ['status' => 1];
                $code = 200;
            }
        }
        else {
            $response = ['status' => 0, 'text' => Lang::get('validation.method_not_allowed')];
            $code = 422;
        }

        return response()->json($response, $code);
    }

    public function constructor(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }

        $data = $this->view($request->id ?? null, true, $request->user()->id, $request->width);
        $data['tenCities'] = $this->tenCities();
        $data['driverStatus'] = Driver::current($request->user()->id)->pluck('status')->first();
        return response()->json($data);
    }

    public function list(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $routes = Routes::current($request->user()->id)->nowOrFuture()->with([
            'sales' => function ($q) {
                $q->where('status', 1);
            },
            'sales.currency',
            'vehicles',
            'vehicles.manufacturers:id,name',
            'citiesFrom:id,code as city_code,extension',
            'citiesFrom.translated',
            'citiesTo',
            'citiesTo.translated',
            'addressFrom:id',
            'addressFrom.translated',
            'addressTo:id',
            'addressTo.translated'
        ])->statusNot(3)->skip($request->skip)->take(10)->orderBy('departure_date', 'ASC')->get()->toArray();

        $data['total'] = Routes::current($request->user()->id)->statusNot(3)->nowOrFuture()->count();

        foreach ($routes as $key => $route) {
            $data['items'][$key] = $route;
            $data['items'][$key]['departure_date'] = Carbon::parse($route['departure_date'])->translatedFormat('j M Y');
            if (count($route['sales']) > 0) {
                $data['items'][$key]['deleteAlertify'] = Lang::get('alerts.confirm_route_sale_refund',
                    [
                        'fine' => round(array_sum(array_column($route['sales'], 'price')) * config('app.driver_fine_commission') / 100, 3),
                        'currency' => $route['sales'][0]['currency']['key'] //Future multiple currency
                    ]);
            } else {
                $data['items'][$key]['deleteAlertify'] = Lang::get('alerts.confirm_route_delete');
            }
        }

        return response()->json($data);

    }


    public function addAction(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->add($request, true)->original;
        if ($response['status'] == 1) {
            if($request->isWizard) {
                Driver::whereUserId($request->user()->id)->update(['step' => 5]);
                $response = ['step' => 5];
            }
            return response()->json($response, 200);
        } else {
            return response()->json($response, 422);
        }
    }

    public function editAction(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->edit($request, true)->original;
        if ($response['status'] == 1) {
            return response()->json($response, 200);
        } else {
            return response()->json($response, 422);
        }
    }

    public function deleteAction(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->actionDelete($request)->original;
        if ($response['status'] == 1) {
            return response()->json($response, 200);
        } else {
            return response()->json($response, 422);
        }
    }

    public function cancelAction(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $response = $this->cancelRoute($request)->original;
        if ($response['status'] == 1) {
            return response()->json($response, 200);
        } else {
            return response()->json($response, 422);
        }
    }

    public function changeType(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        return $this->typeChange($request)->original;
    }

    public function changeVehicle(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        return $this->vehicleChange($request)->original;
    }

    public function tenCities() {
        return $this->tenCitiesByCountry()->original;
    }

    public function searchCities(Request $request) {
        return $this->searchCity($request->q);
    }

    public function searchAddresses(Request $request) {
        return $this->searchAddress($request->q);
    }


}
