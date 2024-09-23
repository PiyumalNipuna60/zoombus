<?php

namespace App\Http\Controllers\Driver;

use App\Address;
use App\AddressTranslatable;
use App\BalanceUpdates;
use App\Cart;
use App\City;
use App\CityTranslatable;
use App\Country;
use App\Currency;
use App\Driver;
use App\Fine;
use App\Http\Controllers\Api\VehiclesController;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\Payments\PayPalClient;
use App\Http\Controllers\ValidationController;
use App\Notifications\RefundedByDriver;
use App\RemainingSeats;
use App\ReservedSeats;
use App\RouteDateTypes;
use App\Routes;
use App\RouteTypes;
use App\Sales;
use App\User;
use App\Vehicle;
use Carbon\Carbon;
use Google\Cloud\Core\ServiceBuilder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\LaravelLocalization;

class RoutesController extends DriverController
{
    private $translate;


    public function __construct()
    {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('can_edit_route')->only('viewEdit', 'edit', 'cancelRoute');
        }

        $projectId = config('services.google-maps.project_id');
        $gcloud = new ServiceBuilder([
            'keyFilePath' => public_path('metal-filament-282009-ae53ff7ad2b1.json'),
            'projectId' => $projectId
        ]);

        $this->translate = $gcloud->translate();
    }

    protected function store(array $data)
    {
        if (is_array($data['departure_date'])) {
            foreach ($data['departure_date'] as $key => $departure_date) {
                $dataI = $data;
                $dataI['departure_date'] = $departure_date;
                $dataI['arrival_date'] = $data['arrival_date'][$key];
                $rc = Routes::create($dataI);
                RemainingSeats::create(['route_id' => $rc->id, 'remaining_seats' => Vehicle::whereId($data['vehicle_id'])->first('number_of_seats')->number_of_seats]);
            }
        }
        else {
            $rc = Routes::create($data);
            RemainingSeats::create(['route_id' => $rc->id, 'remaining_seats' => Vehicle::whereId($data['vehicle_id'])->first('number_of_seats')->number_of_seats]);
            return $rc;
        }
    }

    protected function update(array $data)
    {
        $data = Arr::except($data, ['route_duration_hour']);
        return Routes::whereId($data['id'])->update(Arr::except($data, ['id']));
    }

    private function delete(array $data)
    {
        RemainingSeats::whereRouteId($data['id'])->delete();
        return Routes::whereId($data['id'])->delete();
    }


    public function weekDays()
    {
        return ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday', 'Saturday', 'Sunday'];
    }


    protected function validator($data, $mode = null, $user = null)
    {
        if ($mode == 'addVehicle'):
            $fields = [
                'vehicle_id' => 'required|integer|' . Rule::exists('vehicles', 'id')->where('user_id', $user ?? Auth::user()->id),
                'increment' => 'required|integer',
            ];
        elseif ($mode == 'addRoute'):
            $fields = [
                'vehicle_id' => 'required|integer|' . Rule::exists('vehicles', 'id')->where('user_id', $user ?? Auth::user()->id),
                'from' => 'required|string',
                'to' => 'required|string',
                'from_address' => 'required|string',
                'to_address' => 'required|string',
                'departure_time' => 'required|date_format:H:i',
                'route_duration_hour' => 'required',
                'currency_id' => 'required|integer|' . Rule::exists('currencies', 'id'),
                'price' => 'required|numeric',
                'type' => 'required|integer|' . Rule::in([1, 2]),
            ];
        elseif ($mode == 'departureDates'):
            if (isset($data['type']) && $data['type'] == 2):
                $fields = [
                    'departure_date' => 'required|array',
                    'departure_date.*' => 'required|date|after_or_equal:' . Carbon::now()->format('Y-m-d')
                ];
            else :
                $fields = [
                    'departure_date' => 'required|date|after_or_equal:' . Carbon::now()->format('Y-m-d')
                ];
            endif;
        elseif ($mode == 'editRoute'):
            $fields = [
                'id' => 'required|integer|' . Rule::exists('routes', 'id')->where('user_id', $user ?? Auth::user()->id)->where('status', 1),
                'vehicle_id' => 'required|integer|' . Rule::exists('vehicles', 'id')->where('user_id', $user ?? Auth::user()->id),
                'from' => 'required|string',
                'to' => 'required|string',
                'from_address' => 'required|string',
                'to_address' => 'required|string',
                'departure_time' => 'required|date_format:H:i',
                'route_duration_hour' => 'required',
                'currency_id' => 'required|integer|' . Rule::exists('currencies', 'id'),
                'price' => 'required|numeric',
            ];
        elseif ($mode == 'editRouteSold'):
            $fields = [
                'id' => 'required|integer|' . Rule::exists('routes', 'id')->where('user_id', $user ?? Auth::user()->id)->whereNot('status', 3),
                'vehicle_id' => 'required|integer|' . Rule::exists('vehicles', 'id')->where('user_id', $user ?? Auth::user()->id),
                'price' => 'required|numeric',
            ];
        elseif ($mode == 'routeDates'):
            $fields = [
                'departure_datetime' => 'required|date|after_or_equal:' . Carbon::now()->addHours(1)->format('Y-m-d H:i'),
                'arrival_datetime' => 'required|date|after:departure_datetime'
            ];
        elseif ($mode == 'deleteRoute'):
            $fields = [
                'id' => 'required|integer|' . Rule::unique('sales', 'route_id')->whereIn('status', [1, 3])
            ];
        elseif ($mode == 'reserve') :
            $routeId = Routes::with('vehicles')->where('id', $data['route_id'] ?? 0)->status(1)->first()->toArray();
            $numberOfSeats = $routeId['vehicles']['number_of_seats'] ?? 0;
            $takenSeats = Sales::where('route_id', $data['route_id'] ?? 0)->status([1, 3])->get(['seat_number'])->toArray();

            if (!empty($data['deleteReserved'])) {
                $sometimes = 'sometimes|';
            }
            else {
                $sometimes = null;
            }
            $fields = [
                'deleteReserved' => 'sometimes|required|array',
                'deleteReserved.*' => 'sometimes|required|integer|' . Rule::in(array_column(ReservedSeats::whereRouteId($data['route_id'] ?? 0)->get()->toArray(), 'seat_number')),
                'route_id' => 'required|integer|' . Rule::exists('routes', 'id')->where('user_id', $user ?? Auth::user()->id)->where('status', 1),
                'gender_id' => $sometimes . 'required|array',
                'gender_id.*' => $sometimes . 'required|string|' . Rule::in([1, 2]),
                'seat_number' => $sometimes . 'required|array',
                'seat_number.*' => $sometimes . 'required|integer|distinct|between:1,' . $numberOfSeats . '|' . Rule::notIn(array_column($takenSeats, 'seat_number'))
            ];
        endif;


        return \Validator::make($data, $fields);
    }


    public function reserve(Request $request)
    {
        $data = $request->only(['route_id', 'seat_number', 'gender_id']);
        if (!empty($request->deleteReserved)) {
            $data['deleteReserved'] = $request->deleteReserved;
        }
        $response = ValidationController::response($this->validator($data, 'reserve'), Lang::get('auth.successfully_updated'));
        if ($response->original['status'] == 1) {
            if (!empty($data['deleteReserved'])) {
                foreach ($data['deleteReserved'] as $val) {
                    ReservedSeats::whereRouteId($data['route_id'])->whereSeatNumber($val)->delete();
                    RemainingSeats::whereRouteId($data['route_id'])->increment('remaining_seats');
                }
            }
            if (!empty($data['seat_number'])) {
                foreach ($data['seat_number'] as $key => $val) {
                    ReservedSeats::create([
                        'route_id' => $data['route_id'],
                        'seat_number' => $val,
                        'gender_id' => $data['gender_id'][$key],
                    ]);
                    RemainingSeats::whereRouteId($data['route_id'])->decrement('remaining_seats');
                }
            }
        }
        return response()->json($response->original);
    }

    public function add(Request $request, $mobile = false)
    {
        return $this->action($request, 'add', $mobile);
    }

    public function edit(Request $request, $mobile = false)
    {
        return $this->action($request, 'edit', $mobile);
    }


    private function actionStore($data, $type)
    {
        $ip = $_SERVER['REMOTE_ADDR'];

        $iData = Arr::except($data, ['from', 'to', 'from_address', 'to_address']);
        $iData['user_id'] = \Auth::user()->id;


        if ($type == 'add' || $type == 'edit' && !Sales::whereRouteId($data['id'] ?? 0)->statusNot(2)->exists()) {
            //detecting language source

            $fromAndTo = ['from', 'to'];
            foreach ($fromAndTo as $fnt) {
                $from = City::search($data[$fnt])->first(['id']);
                if ($from) {
                    $iData[$fnt] = $from->toArray()['id'];
                }
                else {
                    $insdataCity = [
                        'country_id' => Country::where('code', strtoupper(geoip($ip)->iso_code))->first(['id'])->toArray()['id'],
                        'code' => strtoupper(mb_substr($this->translate->translate($data[$fnt], ['target' => 'en'])['text'], 0, 2))
                    ];
                    $insCity = City::create($insdataCity);
                    foreach ((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
                        $insTransDataCity = ['name' => $this->translate->translate($data[$fnt], ['target' => $keys])['text'], 'locale' => $keys, 'city_id' => $insCity->id];
                        CityTranslatable::create($insTransDataCity);
                    }
                    $iData[$fnt] = $insCity->id;
                }
            }
            $fromAndToAddress = ['from_address', 'to_address'];
            foreach ($fromAndToAddress as $fnta) {
                $from_address = Address::search($data[$fnta])->first(['id']);
                if ($from_address) {
                    $iData[$fnta] = $from_address->toArray()['id'];
                }
                else {
                    $insdataAddr = [
                        'user_id' => \Auth::user()->id,
                    ];
                    $insAddr = Address::create($insdataAddr);
                    foreach ((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
                        $insTransDataAddr = ['name' => $this->translate->translate($data[$fnta], ['target' => $keys])['text'], 'locale' => $keys, 'address_id' => $insAddr->id];
                        AddressTranslatable::create($insTransDataAddr);
                    }
                    $iData[$fnta] = $insAddr->id;
                }
            }
        }


        if ($type == 'edit') {
            return $this->update($iData);
        }
        else {
            return $this->store($iData);
        }
    }

    public function actionDelete(Request $request)
    {
        $data = $request->only('id');
        $response = ValidationController::response($this->validator($data, 'deleteRoute'));
        if ($response->original['status'] == 1) {
            $this->delete($data);
        }
        return response()->json($response->original);
    }


    private function validateDateTime($data)
    {
        $data_deux['vehicle_id'] = $data['vehicle_id'];
        $data_deux['departure_datetime'] = $data['departure_date'] . ' ' . $data['departure_time'];
        $data_deux['arrival_datetime'] = $data['arrival_date'] . ' ' . $data['arrival_time'];
        $response_deux = ValidationController::response($this->validator($data_deux, 'routeDates'), \Lang::get('auth.route_successfully_registered'));
        if ($response_deux->original['status'] == 1) {
            $sxvab = Routes::where('vehicle_id', $data['vehicle_id']);
            if (isset($data['id'])) {
                $sxvab->where('id', '!=', $data['id']);
            }
            $fromCity = City::search($data['from'])->first('id');
            $sxv = $sxvab->get()->toArray();

            foreach ($sxv as $sb) {
                $sxvM = Carbon::parse($sb['departure_date'] . ' ' . $sb['departure_time']);
                $sxvA = Carbon::parse($sb['arrival_date'] . ' ' . $sb['arrival_time'])->diffInHours($sxvM);
                if ($fromCity && $fromCity->id == $sb['from']) {
                    $sxxx = Carbon::parse($sb['arrival_date'] . ' ' . $sb['arrival_time'])->addHours($sxvA);
                }
                else {
                    $sxxx = Carbon::parse($sb['arrival_date'] . ' ' . $sb['arrival_time'])->addMinutes(59);
                }
                if (Carbon::parse($data_deux['departure_datetime'])->isBetween($sxvM, $sxxx)) {
                    return response()->json(['status' => 0, 'text' => \Lang::get('validation.selected_dates_are_busy', ['date' => Carbon::parse($data_deux['departure_datetime'])->locale(config('app.locale'))->translatedFormat('j F Y')])]);
                }

                $sxvMB = Carbon::parse($data_deux['departure_datetime']);
                $sxvAB = Carbon::parse($data_deux['arrival_datetime'])->diffInHours($sxvMB);
                if ($fromCity && $fromCity->id == $sb['from']) {
                    $sxxxB = Carbon::parse($data_deux['arrival_datetime'])->addHours($sxvAB);
                }
                else {
                    $sxxxB = Carbon::parse($data_deux['arrival_datetime'])->addMinutes(59);
                }
                if (!Carbon::parse($sb['departure_date'] . ' ' . $sb['departure_time'])->greaterThan($sxxxB) &&
                    Carbon::parse($sb['departure_date'] . ' ' . $sb['departure_time'])->isBetween($sxvMB, $sxxxB)) {
                    return response()->json(['status' => 0, 'text' => \Lang::get('validation.selected_dates_are_busy', ['date' => Carbon::parse($sb['departure_date'] . ' ' . $sb['departure_time'])->locale(config('app.locale'))->translatedFormat('j F Y')])]);
                }

            }
            return response()->json(['status' => 1, 'text' => Lang::get('validation.successfully_added_route')]);
        }
        else {
            return response()->json($response_deux->original, 422);
        }

    }


    private function afterValidation($data, $type, $route = 'single', $originResponse = null)
    {
        if ($type == 'add' || $type == 'edit' && !Sales::whereRouteId($data['id'] ?? 0)->statusNot(2)->exists()) {
            if ($route == 'multiple' && $type == 'add') {
                $ids = [];
                for ($i = 0; $i < sizeof($data['departure_date']); $i++) {
                    $dataV = $data;
                    $dataV['departure_date'] = $data['departure_date'][$i];
                    $dataV['arrival_date'] = $data['arrival_date'][$i];
                    $validate = $this->validateDateTime($dataV)->original;
                    if ($validate['status'] != 1) {
                        foreach ($ids as $id) {
                            RemainingSeats::whereRouteId($id)->delete();
                            Routes::whereId($id)->delete();
                        }
                        return response()->json($validate, 422);
                    }
                    else {
                        $id = $this->actionStore($dataV, $type);
                        $ids[$i] = $id->id;
                    }
                }
            }
            else {
                $validate = $this->validateDateTime($data)->original;
                if ($validate['status'] != 1) {
                    return response()->json($validate, 422);
                }
                $this->actionStore($data, $type);
            }
            return response()->json(['status' => 1, 'text' => Lang::get('validation.successfully_added_route')]);
        }
        else {
            $this->actionStore($data, $type);
            return response()->json($originResponse);
        }
    }

    private function action($request, $type = 'add', $mobile = false)
    {
        //dd($request->departure_date);
        $req = [
            'vehicle_id', 'from', 'departure_date', 'departure_time', 'from_address',
            'to', 'route_duration_hour', 'to_address', 'stopping_time',
            'currency_id', 'price', 'type', 'continue'
        ];

        if ($type == 'edit') {
            $req[] = 'id';
            if (Sales::whereRouteId($data['id'] ?? 0)->statusNot(2)->exists()) {
                $req = Arr::only($req, ['price', 'vehicle_id']);
                $sold = 'Sold';
            }
            else {
                if (($key = array_search('type', $req)) !== false): unset($req[$key]); endif;
            }
        }

        $data = $request->only($req);
        if (isset($sold)) {
            $mode = $type . 'Route' . $sold;
        }
        else {
            $mode = $type . 'Route';
        }

        if ($mobile) {
            $user = $request->user()->id;
        }
        else {
            $user = Auth::user()->id;
        }
        $response = ValidationController::response($this->validator($data, $mode, $user), Lang::get('auth.route_successfully_registered'));
        if ($response->original['status'] == 1) {
            $route = ($type == 'add' && $data['type'] == 2) ? 'multiple' : 'single';
            $departureDateValidationFields = ['departure_date'];
            if ($route == 'multiple') {
                $departureDateValidationFields[] = 'type';
            }
            $secondResponse = ValidationController::response($this->validator($request->only($departureDateValidationFields), 'departureDates'));
            if ($secondResponse->original['status'] == 1) {
                $data['departure_date'] = $request->departure_date;
                $timeExp = explode(':', $data['route_duration_hour']);
                $hours = $timeExp[0];
                $minutes = $timeExp[1];
                if ($type == 'add' && $data['type'] == 2) {
                    $arrivalTime = Carbon::parse($request->departure_date[0] . ' ' . $request->departure_time)->addHours($hours)->addMinutes($minutes);
                    $data['arrival_time'] = $arrivalTime->format('H:i');
                    foreach ($request->departure_date as $key => $departure_date) {
                        $arrival = Carbon::parse($departure_date . ' ' . $request->departure_time)->addHours($hours)->addMinutes($minutes);
                        $data['arrival_date'][$key] = $arrival->format('Y-m-d');
                    }
                }
                else {
                    $arrivalTime = Carbon::parse($data['departure_date'] . ' ' . $request->departure_time)->addHours($hours)->addMinutes($minutes);
                    $data['arrival_time'] = $arrivalTime->format('H:i');
                    $data['arrival_date'] = $arrivalTime->format('Y-m-d');
                }
                if (isset($data['continue']) && $data['continue'] == "yes") {
                    Driver::current()->update(['step' => 5]);
                }
                return $this->afterValidation($data, $type, $route, $response->original);
            }
            else {
                return response()->json($secondResponse->original);
            }
        }
        else {
            return response()->json($response->original);
        }
    }

    public function tenCitiesByCountry()
    {
        if (City::whereHas('country', function ($q) {
            $ip = $_SERVER['REMOTE_ADDR'];
            $q->where('code', strtoupper(geoip($ip)->iso_code));
        })->exists()) {
            $data = City::with('translated')->whereHas('country', function ($q) {
                $ip = $_SERVER['REMOTE_ADDR'];
                $q->where('code', strtoupper(geoip($ip)->iso_code));
            })->take(10)->get()->toArray();
        }
        else {
            $data = [];
        }
        return response()->json($data);
    }

    public function searchCity($q)
    {
        if (City::search($q)->exists()) {
            $data = CityTranslatable::where('name', 'LIKE', "%{$q}%")->get()->take(10)->toArray();
        }
        else {
            $data = [];
        }
        return response()->json($data);
    }


    public function searchAddress($q)
    {
        if (Address::search($q)->exists()) {
            $data = AddressTranslatable::where('name', 'LIKE', "%{$q}%")->get()->take(10)->toArray();
        }
        else {
            $data = [];
        }
        return response()->json($data);
    }


    public function getDays()
    {
        $data = [];
        for ($c = 0; $c < 6; $c++) {
            $data[$c]['id'] = (string)$c;
            $data[$c]['name'] = $c . ' ' . trans_choice('misc.day_count', $c);
        }
        return $data;
    }

    private function getRouteForms($data)
    {
        $cur = Controller::essentialVars(['current_locale', 'current_currency_id']);
        $firstRoute = Routes::orderBy('id', 'desc')->first();
        $data['incremented_id'] = ($firstRoute) ? $firstRoute->id + $data['increment'] : 1;
        $data['currencies'] = Currency::all(['id', DB::raw('upper(`key`) as `name`')])->toArray();
        $data['current_currency'] = $cur['current_currency_id'];
        $data['types'] = RouteDateTypes::with('translated')->get()->toArray();


        $data['days'] = $this->getDays();

        return view('ajax.route-registration', $data)->render();
    }


    public function addVehicle(Request $request)
    {
        $vehicle_id = $request->only('vehicle_id', 'increment');
        $response = ValidationController::response($this->validator($vehicle_id, 'addVehicle'), $this->getRouteForms($vehicle_id));
        return response()->json($response->original);
    }


    public function typeChange(Request $request)
    {
        $route_type = $request->only('route_type')['route_type'];
        if (!in_array($route_type, array_column(RouteTypes::all('id')->toArray(), 'id'))) {
            return response()->json('maladec');
        }
        $vehs = Vehicle::current()->active()->where('type', $route_type)->
        with('routeTypes:id', 'manufacturers:id,name as manufacturer_name')->
        get(['id', 'type', 'model', 'manufacturer', 'license_plate', 'status'])->toArray();

        foreach ($vehs as $key => $val) {
            $manus[$key]['id'] = $val['manufacturers']['id'] . '|' . $val['model'];
            $manus[$key]['name'] = $val['manufacturers']['manufacturer_name'] . ' ' . $val['model'];
        }

        return response()->json($this->registeredVehicleData($manus, $vehs));
    }


    public function vehicleChange(Request $request)
    {
        $model_unexploded = $request->only('model')['model'];
        $exped = explode("|", $model_unexploded);
        $manufacturer = $exped[0];
        $model = $exped[1];
        $vehs = Vehicle::current()->active()->where([['manufacturer', '=', $manufacturer], ['model', '=', $model]])->
        get(['id', 'license_plate'])->toArray();

        return response()->json($vehs);
    }

    public function viewAll()
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }
        else {
            $data = Controller::essentialVars();
            return view('driver.registered-routes', $data);
        }

    }


    public function cancelRoute(Request $request)
    {
        $lng = config('laravellocalization.supportedLocales');
        $postData = $request->only('id');
        $firstCondition = Sales::whereHas('routes', function ($q) {
            $q->current()->status(1);
        })->where('route_id', $postData['id'])->status(1)->exists();
        $secondCondition = Sales::whereHas('routes', function ($q) {
            $q->current()->status(1);
        })->where('route_id', $postData['id'])->status(3)->doesntExist();
        if ($firstCondition && $secondCondition) {
            $users = Sales::with('users', 'routes', 'routes.citiesFrom', 'routes.citiesFrom.translate', 'routes.citiesTo', 'routes.citiesTo.translate')->whereHas('routes', function ($q) {
                $q->current();
            })->where('route_id', $postData['id'])->status(1)->get()->toArray();
            foreach ($users as $usr) {
                $amount[] = $usr['price'];
                $cur = $usr['users']['locale'];
                foreach ($lng as $k => $l) {
                    $translateFrom = $usr['routes']['cities_from']['translate'];
                    $translateTo = $usr['routes']['cities_to']['translate'];
                    $departure_date = Carbon::parse($usr['routes']['departure_date'])->locale($k);
                    $data[$k]['date'] = $departure_date->locale($k)->translatedFormat('j F');
                    $data[$k]['route'] = $translateFrom[array_search($k, array_column($translateFrom, 'locale'))]['name'] . ' - ' . $translateTo[array_search($k, array_column($translateTo, 'locale'))]['name'];
                }
                User::where('id', $usr['user_id'])->first()->notify(
                    new RefundedByDriver(
                        $data,
                        $cur
                    )
                );
                BalanceUpdates::whereSaleId($usr['id'])->delete();
                if ($usr['payment_method'] == 2) {
                    (new PayPalClient())->refundOrder($usr['paypal_capture_id'], $usr['price']);
                }
                else if ($usr['payment_method'] == 1) {
                    //TODO: refund action of bank with $usr['price']
                }
            }
            $amt = array_sum($amount);
            Sales::where('route_id', $postData['id'])->update(['status' => 4]);
            $decrementAmount = round($amt * config('app.driver_fine_commission') / 100, 3);
            Fine::create([
                'route_id' => $postData['id'],
                'amount' => $decrementAmount,
            ]);
            $getDriverUserId = Routes::whereId($postData['id'])->first('user_id');
            User::where('id', $getDriverUserId->user_id)->decrement('balance', $decrementAmount);
            User::whereId(1)->increment('balance', $decrementAmount); //might be optional
            Routes::whereId($postData['id'])->update(['status' => 3]);
            RemainingSeats::whereRouteId($postData['id'])->delete();
            $response = ['status' => 1];
        }
        else {
            $response = ['status' => 0, 'text' => \Lang::get('validation.error_route_cancellation')];
        }
        return response()->json($response);
    }

    public function allRoutesData()
    {
        $routes = Routes::current()->nowOrFuture()->with([
            'vehicles:id,manufacturer,model,license_plate',
            'vehicles.manufacturers:id,name as manufacturer_name',
            'citiesFrom:id,code as city_code,extension',
            'citiesFrom.translated',
            'citiesTo',
            'sales' => function ($q) {
                $q->status(1);
            },
            'sales.currency',
            'citiesTo.translated',
            'addressFrom:id',
            'addressFrom.translated',
            'addressTo:id',
            'addressTo.translated',
            'currency:id,key as currency_key'
        ])->statusNot(3)->get()->toArray();


        $data = $routes;
        foreach ($routes as $key => $val) {
            if (isset($val['sales']) && count($val['sales']) > 0) {
                $alertify = [
                    'confirm-msg' => Lang::get('alerts.confirm_route_sale_refund',
                        [
                            'fine' => round(array_sum(array_column($val['sales'], 'price')) * config('app.driver_fine_commission') / 100, 3),
                            'currency' => $val['sales'][0]['currency']['key'] //Future multiple currency
                        ]
                    ),
                    'success-msg' => Lang::get('alerts.success_route_sale_refund'),
                    'error-msg' => Lang::get('alerts.error_route_sale_refund'),
                    'ok' => Lang::get('alerts.ok_route_sale_refund'),
                    'title' => Lang::get('alerts.title_route_sale_refund'),
                    'cancel' => Lang::get('alerts.cancel_route_sale_refund'),
                    'id' => $val['id']
                ];
            }
            else {
                $alertify = [
                    'confirm-msg' => Lang::get('alerts.confirm_route_delete'),
                    'success-msg' => Lang::get('alerts.success_route_delete'),
                    'error-msg' => Lang::get('alerts.error_route_delete'),
                    'title' => Lang::get('alerts.title_route_delete'),
                    'ok' => Lang::get('alerts.ok_route_delete'),
                    'cancel' => Lang::get('alerts.cancel_route_delete'),
                    'id' => $val['id']
                ];
            }

            $actions = [
                [
                    'url' => route('route_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'blue route-edit',
                ],
                [
                    'faicon' => 'fa-times-circle',
                    'url' => route('driver_routes_delete'),
                    'url_class' => 'route_delete_alertify',
                    'alertify' => $alertify
                ]
            ];



            $data[$key]['id'] = $val['cities_from']['city_code'] . $val['id'];
            $data[$key]['the_route'] = $val['cities_from']['translated']['name'] . ' ' . view('components.font-awesome', ['icon' => 'fa-arrow-right'])->render() . ' ' . $val['cities_to']['translated']['name'];
            $data[$key]['the_transport'] = $val['vehicles']['manufacturers']['manufacturer_name'] . ' ' . $val['vehicles']['model'] . view('components.br')->render() . $val['vehicles']['license_plate'];
            $data[$key]['departure_date'] = Carbon::parse($val['departure_date'])->format('Y-m-d');
            $data[$key]['the_time'] = $val['departure_time'] . ' ' . view('components.font-awesome', ['icon' => 'fa-arrow-right'])->render() . ' ' . $val['arrival_time'];
            $data[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
            $data[$key]['currency']['currency_key'] = $val['currency']['currency_key'];
        }

        return datatables()->of($data)->rawColumns(['the_route', 'the_transport', 'actions', 'the_time', 'stopping_time'])->toJson();
    }

    public function viewAdd()
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }
        else {
            return $this->view();
        }

    }

    public function viewEdit($id)
    {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }
        else {
            return $this->view($id);
        }
    }

    private function registeredVehicleData($manus, $vehs, $aData = null)
    {
        $modelsP = array_intersect_key($manus, array_unique(array_map('serialize', $manus)));

        $k = 0;
        foreach ($modelsP as $val) {
            $data['models'][$k] = $val;
            $k++;
        }

        $manufacturers = array_column($vehs, 'manufacturer');
        foreach (array_keys($manufacturers, $manufacturers[0]) as $key => $val) {
            $models[$key] = $vehs[$val];
        }

        $modell = array_map('strtolower', array_column($models, 'model'));
        foreach (array_keys($modell, strtolower($modell[0])) as $key => $val) {
            $data['license_plates'][$key]['id'] = $models[$val]['id'];
            $data['license_plates'][$key]['name'] = $models[$val]['license_plate'];
        }

        return array_merge($data, $aData ?? []);
    }


    protected function view($id = null, $dataOnly = false, $user = null, $width = null)
    {
        if (!$dataOnly) {
            $data = Controller::essentialVars();
        }

        if ($id) {
            $rts = Routes::current($user)->with([
                'sales' => function ($q) {
                    $q->status([1, 3]);
                },
                'sales.users.gender',
                'sales.currency',
                'sales.balanceUpdates',
                'reservedSeats.gender',
                'vehicles.manufacturers',
                'citiesFrom',
                'citiesFrom.translated',
                'citiesTo',
                'citiesTo.translated',
                'addressFrom:id',
                'addressFrom.translated',
                'addressTo:id',
                'addressTo.translated',
                'routeDateTypes:id',
                'routeDateTypes.translated'
            ])->where('id', $id)->statusNot(3)->first()->toArray();
        }
        else {
            $rts = Vehicle::current($user)->with('routeTypes:id', 'routeTypes.translated', 'manufacturers:id,name as manufacturer_name')->get()->toArray();
        }


        //two dimensional unique equivalent
        $route_types = array_intersect_key(
            array_column($rts, 'route_types'),
            array_unique(array_map('serialize', array_column($rts, 'route_types')))
        );

        if ($id) {
            $data['route'] = $rts;

            $data['route']['editing'] = true;


            if (count($rts['sales']) > 0) {
                $data['route']['atLeastOneSale'] = true;
            }

            $data['route']['incremented_id'] = $rts['cities_from']['code'] . $rts['id'];
            $data['route']['currencies'] = Currency::all(['id', DB::raw('upper(`key`) as `name`')])->toArray();
            //$data['route']['current_currency'] = $data['current_currency_id'];


            $data['route']['days'] = $this->getDays();
            if ($rts['type'] == 2) {
                $data['route']['departure_day'] = $rts['departure_date'];
                $data['route']['arrival_day'] = $rts['arrival_date'];
            }
            $data['route']['type'] = $rts['route_date_types']['id'];
            $data['route']['types'] = RouteDateTypes::with('translated')->get()->toArray();


            $data['route']['sales'] = collect($data['route']['sales'])->map(function ($v) {
                $v['purchase_date'] = Carbon::parse($v['created_at'])->translatedFormat('j M Y');
                return $v;
            })->toArray();

            $diff = Carbon::parse($data['route']['departure_date'] . ' ' . $data['route']['departure_time'])->diffInMinutes($data['route']['arrival_date'] . ' ' . $data['route']['arrival_time']);
            $diffHours = floor($diff / 60);
            $diffHours = ($diffHours < 10) ? '0' . $diffHours : $diffHours;
            $diffMinutes = floor($diff - ($diffHours * 60));
            $diffMinutes = ($diffMinutes < 10) ? '0' . $diffMinutes : $diffMinutes;
            $data['route']['route_duration_hour'] = $diffHours . ':' . $diffMinutes;


            $data['route']['departure_date_header'] = Carbon::parse($data['route']['departure_date'])->translatedFormat('j M');
            $data['route']['vehicles']['scheme'] = (new VehiclesController())->recalculateLogic($data['route']['vehicles']['seat_positioning'], $data['route']['vehicles']['type'], $width);


            $data['route']['countdownTimestamp'] = Carbon::parse($data['route']['departure_date'] . ' ' . $data['route']['departure_time'])->getTimestamp() - Carbon::now()->getTimestamp();
            if (count($rts['sales']) > 0) {
                foreach ($rts['sales'] as $k => $rs) {
                    $prC = [];
                    foreach ($rs['balance_updates'] as $bu) {
                        if ($bu['user_id'] == $user) {
                            $dsC[] = $bu['amount'];
                            $prC[] = $bu['amount'];
                        }
                    }
                    $data['route']['sales'][$k]['price'] = array_sum($prC);
                }
                $data['route']['total_sold_currency'] = array_sum($dsC ?? []);
                $data['route']['total_sold'] = count($rts['sales']);

                $data['route']['deleteAlertify'] = [
                    'confirm-msg' => Lang::get('alerts.confirm_route_sale_refund',
                        [
                            'fine' => round(array_sum(array_column($data['route']['sales'], 'price')) * config('app.driver_fine_commission') / 100, 3),
                            'currency' => $data['route']['sales'][0]['currency']['key'] //Future multiple currency
                        ]
                    ),
                    'success-msg' => Lang::get('alerts.success_route_sale_refund'),
                    'error-msg' => Lang::get('alerts.error_route_sale_refund'),
                    'ok' => Lang::get('alerts.ok_route_sale_refund'),
                    'title' => Lang::get('alerts.title_route_sale_refund'),
                    'cancel' => Lang::get('alerts.cancel_route_sale_refund'),
                    'id' => $data['route']['id']
                ];

            }
            else {
                $data['route']['total_sold_currency'] = 0;
                $data['route']['total_sold'] = 0;
                $data['route']['deleteAlertify'] = [
                    'confirm-msg' => Lang::get('alerts.confirm_route_delete'),
                    'success-msg' => Lang::get('alerts.success_ticket_cancel'),
                    'error-msg' => Lang::get('alerts.error_ticket_cancel'),
                    'ok' => Lang::get('alerts.ok_route_delete'),
                    'title' => Lang::get('alerts.title_ticket_cancel'),
                    'cancel' => Lang::get('alerts.cancel_route_delete'),
                    'id' => $data['route']['id']
                ];
            }

        }
        else {
            foreach (array_keys(array_column($rts, 'type'), $route_types[0]['id']) as $val) {
                $manus[$val]['id'] = $rts[$val]['manufacturers']['id'] . '|' . $rts[$val]['model'];
                $manus[$val]['name'] = $rts[$val]['manufacturers']['manufacturer_name'] . ' ' . $rts[$val]['model'];
            }
            //two dimensional unique equivalent
            $route_types = array_intersect_key(
                array_column($rts, 'route_types'),
                array_unique(array_map('serialize', array_column($rts, 'route_types')))
            );

            $data['route_types'] = $route_types;
        }

        if ($dataOnly) {
            return isset($id) ? $data : $this->registeredVehicleData($manus, $rts, $data);
        }
        else {
            return view((isset($id)) ? 'driver.route-edit' : 'driver.route-registration', isset($id) ? $data : $this->registeredVehicleData($manus, $rts, $data));
        }

    }


}
