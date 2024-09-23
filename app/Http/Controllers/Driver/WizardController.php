<?php

namespace App\Http\Controllers\Driver;

use App\Country;
use App\Currency;
use App\Driver;
use App\FuelTypes;
use App\Gender;
use App\Http\Controllers\Api\DriversLicenseController as DLC;
use App\Http\Controllers\Api\ProfileController as PC;
use App\Http\Controllers\Api\RoutesController as RC;
use App\Http\Controllers\Api\VehiclesController as VC;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DriverController;
use App\Manufacturer;
use App\RouteDateTypes;
use App\Routes;
use App\RouteTypes;
use App\Vehicle;
use App\VehicleSpecs;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

class WizardController extends DriverController {
    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer');
            $this->middleware('driver_not_active');
        }
    }

    public function step1($render = true) {
        $data['countries'] = Country::with('translated')->get()->toArray();
        $data['genders'] = Gender::with('translated')->get()->toArray();
        $data['submit'] = 'continue';
        if ($render) {
            Driver::current()->update(['step' => 1]);
            return view('sections.edit-profile', $data)->render();
        } else {
            return $data;
        }
    }

    public function step2($render = true) {
        $data['submit'] = 'continue';
        $data['license_number'] = Driver::current()->pluck('license_number')->first();
        $data['front_side'] = $this->licenseFrontSide();
        $data['back_side'] = $this->licenseBackSide();
        $data['status'] = Driver::current()->pluck('status')->first();
        if ($render) {
            Driver::current()->update(['step' => 2]);
            return view('sections.drivers-license', $data)->render();
        } else {
            return $data;
        }
    }


    private function firstVehicleId($user = null) {
        $vehicle = Vehicle::current($user)->first('id');
        return ($vehicle) ? $vehicle->id : 0;
    }

    public function step3($render = true) {
        $data['submit'] = 'continue';
        $data['route_types'] = RouteTypes::with('translated')->get()->toArray();
        $data['fuel_types'] = FuelTypes::with('translated')->get()->toArray();
        $data['countries'] = Country::with('translated')->get()->toArray();
        if (Vehicle::current()->exists()) {
            $data['vehicle'] = Vehicle::with('fullspecifications:vehicle_id,vehicle_specification_id', 'routeTypes')->current()->first()->toArray();
            $vehicleImages = json_decode($data['vehicle']['images_extension'], true);
            foreach($vehicleImages as $k => $image) {
                if (Storage::disk('s3')->exists('drivers/vehicles/'.$data['vehicle']['id'].'/'.$image)) {
                    $data['vehicle_images'][$k] = Storage::temporaryUrl('drivers/vehicles/'.$data['vehicle']['id'].'/'.$image, now()->addMinutes(5));
                }
            }
            $data['front_side'] = $this->vehicleFrontSide($data['vehicle']['id'] ?? null);
            $data['back_side'] = $this->vehicleBackSide($data['vehicle']['id'] ?? null);
        }

        $data['vehicle_specs'] = VehicleSpecs::with('translated')->get()->toArray();
        $data['manufacturers'] = Manufacturer::all(['id', 'name'])->toArray();
        for ($i = date('Y'); $i >= date('Y') - 100; $i--) {
            $data['year_manufactured'][] = $i;
        }
        $data['scheme'] = (new VehiclesController())->defaultSchemes(1);
        if ($render) {
            Driver::current()->update(['step' => 3]);
            return view('sections.vehicle-registration', $data)->render();
        } else {
            return $data;
        }
    }

    public function step4($render = true) {
        $currentCurrencyId = Controller::essentialVars(['current_currency_id'])['current_currency_id'];
        $data['submit'] = 'continue';
        $data['vehicle_id'] = Vehicle::current()->first('id')->id;
        $data['currencies_all'] = Currency::all(['id', DB::raw('upper(`key`) as `name`')])->toArray();
        $data['current_currency_route'] = $currentCurrencyId;
        $data['types'] = RouteDateTypes::with('translated')->get()->toArray();
        $data['days'] = (new RoutesController())->getDays();
        if ($render) {
            Driver::current()->update(['step' => 4]);
            return view('ajax.route-registration', $data)->render();
        } else {
            return $data;
        }
    }

    protected function wizardData($user, Request $request, $mobile = false) {
        $driverStep = Driver::current($user->id)->first('step')->step;
        $dataEssential = (!$mobile) ? Controller::essentialVars() : [];

        if (!empty($user->id_number) && !empty($user->birth_date) && !empty($user->city) && $driverStep >= 2) {
            if(!$mobile) {
                $data = Arr::collapse([['step' => 2], $this->step2(false), $dataEssential]);
            }
            else {
                $data = (new DLC())->get($request)->original;
                $data['step'] = 2;
            }
            if (Driver::current($user->id)->whereNotNull('license_number')->exists()
                && $this->licenseFrontSide($user->id)
                && $this->licenseBackSide($user->id) && $driverStep >= 3) {
                if(!$mobile) {
                    $data = Arr::collapse([['step' => 3], $this->step3(false), $dataEssential]);
                }
                else {
                    $data = (new VC())->constructor($request)->original;
                    $data['step'] = 3;
                }
                if (Vehicle::current($user->id)->exists()
                    && $this->vehicleBackSide($this->firstVehicleId($user->id))
                    && $this->vehicleFrontSide($this->firstVehicleId($user->id))
                    && $driverStep == 4) {
                    if(!$mobile) {
                        $data = Arr::collapse([['step' => 4], $this->step4(false), $dataEssential]);
                    }
                    else {
                        $data = (new RC())->constructor($request)->original;
                        $data['step'] = 4;
                    }
                }
            }
        } else {
            if(!$mobile) {
                $data = Arr::collapse([['step' => 1], $this->step1(false), $dataEssential]);
            }
            else {
                $data = (new PC())->get($request)->original;
                $data['step'] = 1;
            }
        }

        return $data;
    }

    public function view(Request $request) {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.license');
            return view('mobile.main', $data);
        } else {
            $user = \Auth::user();
            $data = $this->wizardData($user, $request);
            return view('driver.wizard', $data);
        }
    }

    public function skipRoute() {
        if (!empty(\Auth::user()->id_number) && !empty(\Auth::user()->birth_date) && !empty(\Auth::user()->city)) {
            if (Driver::current()->whereNotNull('license_number')->exists() && $this->licenseFrontSide() && $this->licenseBackSide()) {
                if (Vehicle::current()->exists() && $this->vehicleBackSide($this->firstVehicleId()) && $this->vehicleFrontSide($this->firstVehicleId())) {
                    Driver::whereUserId(\Auth::user()->id)->update(['step' => 5]);
                    return response()->json(['status' => 1]);
                } else {
                    return response()->json(['status' => 0]);
                }
            } else {
                return response()->json(['status' => 0]);
            }
        } else {
            return response()->json(['status' => 0]);
        }
    }


}
