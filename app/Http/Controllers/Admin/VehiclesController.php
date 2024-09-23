<?php

namespace App\Http\Controllers\Admin;

use App\Country;
use App\FuelTypes;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ValidationController;
use App\Manufacturer;
use App\Notifications\VehicleValidation;
use App\RouteTypes;
use App\User;
use App\Vehicle;
use App\VehicleSpecs;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class VehiclesController extends AdminController {
    public function __construct() {
        parent::__construct();
    }

    private function store($data) {
        Vehicle::updateOrCreate(['id' => $data['id'] ?? null], $data);
    }

    private function vehicleNotification($status, $id, $supportTicketId = null, $supportTicketMessageId = null) {
        if (in_array($status, [1, 3])) {
            $lng = config('laravellocalization.supportedLocales');
            $sttsChange = Vehicle::with('user', 'manufacturers')->where('id', $id)->first();
            if ($sttsChange) {
                $usr = $sttsChange->toArray();
                if ($usr['status'] != $status) {
                    $cur = $sttsChange['user']['locale'];
                    $translate = [];
                    foreach ($lng as $k=>$l) {
                        $translate[$k]['status'] = StatusController::vehicleLabel($status, $k);
                        $translate[$k]['vehicle'] = $usr['manufacturers']['name'] . ' ' . $usr['model'];
                    }
                    User::where('id', $usr['user']['id'])->first()->notify(
                        new VehicleValidation($translate, $id, $cur, $supportTicketId, $supportTicketMessageId)
                    );
                }
            }
        }
    }

    private function validator($data, $type) {
        if(in_array($type, ['add','edit'])) {
            for ($i = date('Y'); $i >= date('Y') - 100; $i--) {
                $year[] = $i;
            }
            $vehicleValidator = array_column(Vehicle::status([1, 2])->where('id', '!=', $data['id'] ?? 0)->where('country_id', $data['country_id'] ?? 0)->get('license_plate')->toArray(), 'license_plate');
            $fields = [
                'type' => 'required|integer|' . Rule::exists('route_types', 'id'),
                'country_id' => 'required|integer|'  .Rule::exists('countries', 'id'),
                'manufacturer' => 'required|integer|' .Rule::exists('manufacturers', 'id'),
                'license_plate' => 'required|string|' . Rule::notIn($vehicleValidator),
                'model' => 'required|string',
                'fuel_type' => 'required|integer|' . Rule::exists('fuel_types', 'id'),
                'year' => 'required|integer|' . Rule::in($year),
                'date_of_registration' => 'required|date',
                'fullspecifications' => 'required|array|',
                'fullspecifications.*' => 'required|integer|'.Rule::exists('vehicle_specifications', 'id'),
            ];
            if ($type == 'edit'):
                $fields['id'] = 'required|integer|' . Rule::exists('vehicles', 'id');
            endif;
        }
        return \Validator::make($data, $fields);
    }

    private function action($request, $type = 'add') {
        $fillable = (new Vehicle())->fillable;
        $nonFields = (new Vehicle())->nonFields;
        foreach($fillable as $k=>$f) {
            if(in_array($f, $nonFields)) {
                unset($fillable[$k]);
            }
        }
        $fillable[] = 'vehicle_features';
        if($type == 'edit') {
            $fillable[] = 'id';
        }

        $data = $request->only($fillable);
        $response = ValidationController::response($this->validator($data, $type));
        if($response->original['status'] == 1) {
            $this->store($data);
        }
        return response()->json($response->original);
    }

    public function edit(Request $request) {
        return $this->action($request, 'edit');
    }

    public function add(Request $request) {
        return $this->action($request, 'add');
    }

    public function statusChange(Request $request) {
        $d = $request->only('id', 'status');
        $this->vehicleNotification($d['status'], $d['id']);
        Vehicle::where('id', $d['id'])->update(['status' => $d['status']]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_info_update')]);
    }

    public function suspend(Request $request) {
        $d = $request->only('id');
        Vehicle::where('id', $d['id'])->update(['status' => 3]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_vehicle_suspend')]);
    }


    public function approve(Request $request) {
        $d = $request->only('id');
        $this->vehicleNotification(1, $d['id']);
        Vehicle::where('id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_vehicle_approve')]);
    }

    public function unsuspend(Request $request) {
        $d = $request->only('id');
        Vehicle::where('id', $d['id'])->update(['status' => 1]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_vehicle_unsuspend')]);
    }

    public function delete(Request $request) {
        $d = $request->only('id');
        Vehicle::where('id', $d['id'])->delete();
        if(Storage::disk('s3')->exists('drivers/vehicles/'.$d['id'])) {
            Storage::disk('s3')->deleteDirectory('drivers/vehicles/'.$d['id']);
        }
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_vehicle_delete')]);
    }


    public function licenseChange(Request $request) {
        $d = $request->only('id', 'status', 'reason', 'seat_positioning');
        if(isset($d['reason']) && !empty($d['reason'])) {
            $vWithUser = Vehicle::with('user')->whereId($d['id'])->first()->toArray();
            $rejRes = $this->rejectionReason($vWithUser['user']['email'], $vWithUser['user']['name'], $vWithUser['user_id'], $d['reason']);
            $this->vehicleNotification($d['status'], $d['id'], $rejRes['id'], $rejRes['latest_message']);
        }
        else {
            $this->vehicleNotification($d['status'], $d['id']);
        }
        Vehicle::where('id', $d['id'])->update(['status' => $d['status'], 'seat_positioning' => $d['seat_positioning']]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_vehicle_change')]);
    }

    public function viewData(Request $request) {
        $vehiclesQ = Vehicle::with('user', 'user.country', 'user.country.translated', 'manufacturers', 'fuelTypes', 'fuelTypes.translated')->withCount('routes');
        if(isset($request->user_id)) {
            $vehiclesQ->where('user_id', $request->user_id);
        }

        if(isset($request->type) && $request->type != 'all') {
            $vehiclesQ->whereHas('routeTypes', function ($q) use ($request)  {
                $q->where('key', $request->type);
            });
        }



        $vehicles = $vehiclesQ->get()->toArray();

        foreach ($vehicles as $key => $val) {
            $user = new User();

            if ($val['status'] == 3) {
                $sua = 'unsuspend';
            } else if ($val['status'] == 1) {
                $sua = 'suspend';
            } else {
                $sua = 'approve';
            }

            $actions = [
                [
                    'url' => route('admin_vehicle_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'url' => route('admin_vehicle_' . $sua),
                    'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                    'anchor' => \Lang::get('misc.' . $sua),
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_vehicle_' . $sua),
                        'error-msg' => Lang::get('alerts.error_vehicle_' . $sua),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_vehicle_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_vehicle_delete'),
                        'error-msg' => Lang::get('alerts.error_vehicle_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id']
                    ],
                ],
            ];
            $vehicles[$key]['manufacturer_model'] = $val['manufacturers']['name'] . ' ' . $val['model'];
            $vehicles[$key]['country_id'] = view('components.img', ['tooltip' => true, 'class' => 'img-fluid', 'title' => $val['user']['country']['translated']['name'], 'src' => $user->countryImageById($val['user']['country']['code'])])->render();
            $vehicles[$key]['avatar'] = view('components.img', ['src' => $user->photoSmallById($val['user']['id'])])->render();
            $vehicles[$key]['status'] = view('components.status-admin', ['text' => StatusController::statusLabelVU($val['status'], 'en'), 'class' => StatusController::statusLabelClass($val['status'])])->render();
            $vehicles[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($vehicles)->rawColumns(['avatar', 'country_id', 'status', 'actions'])->toJson();
    }

    public function viewDataColumns() {
        return [
            'manufacturer_model', 'license_plate', 'country_id', 'avatar',
            'user.name', 'user.phone_number', 'year', 'number_of_seats',
            'date_of_registration', 'routes_count', 'fuel_types.translated.name',
            'status', 'actions'
        ];
    }

    public function view($type = null) {
        $data = AdminController::essentialVars();
        $seo_title = $type ?? 'all_vehicles';
        $data['seo_title'] = Lang::get('admin_titles.' . $seo_title);
        $data['columns'] = $this->viewDataColumns();
        $data['ajaxUrl'] = route('admin_vehicle_data');
        $data['ajaxData'] = ['type' => $type ?? 'all'];
        return view('admin.pages.dataTables', $data);
    }



    public function viewEdit($id) {
        $data = AdminController::essentialVars();
        if(Vehicle::where('id', $id)->exists()) :
            $types = RouteTypes::with('translated')->get()->toArray();
            $countries = Country::with('translated')->get()->toArray();
            $manufacturers = Manufacturer::all('id','name')->toArray();
            $fuelTypes = FuelTypes::with('translated')->get()->toArray();
            $cData = Vehicle::with('manufacturers','routeTypes:id,key', 'routeTypes.translated', 'fullspecifications','user','routes','routes.citiesFrom','routes.citiesFrom.translated','routes.citiesTo','routes.citiesTo.translated','routes.addressFrom','routes.addressFrom.translated','routes.addressTo','routes.addressTo.translated')->withCount('routes')->where('id', $id)->first()->toArray();
            $specs = VehicleSpecs::with('translated')->get()->toArray();
            $data['vehicle'] = $cData;
            $data['vehicle']['statuses'] = StatusController::fetchText([1 => null,2  => null,3 => Lang::get('status.suspended')]);
            $cData['id'] = ['value' => $cData['id'], 'readonly' => true];
            unset($cData['user_id']);
            $cData['country_id'] = ['values' => $countries, 'field' => 'select', 'class' => 'form-control select', 'value' => $cData['country_id']];
            $cData['manufacturer'] = ['values' => $manufacturers, 'field' => 'select', 'class' => 'form-control select', 'value' => $cData['manufacturer']];
            $cData['fuel_type'] = ['values' => $fuelTypes, 'field' => 'select', 'class' => 'form-control select', 'value' => $cData['fuel_type']];
            $cData['date_of_registration'] = ['addon' => 'datepickerb', 'value' => Carbon::parse($cData['date_of_registration'])->format('j F Y')];
            $cData['type'] = ['values' => $types, 'field' => 'select', 'class' => 'form-control select', 'value' => $cData['type']];
            $cData['fullspecifications'] = ['values' => $specs, 'nolabel' => true, 'group_class' => 'form-group no-border', 'field' => 'checkbox', 'checkedOnes' => array_column($cData['fullspecifications'], 'id')];
            $data['fields'] = $this->fields($cData, (new Vehicle())->fillable, (new Vehicle())->nonFields);
            $data['routeColumns'] = (new RoutesController())->viewDataColumns();
            $data['ajaxUrl'] = route('admin_route_data');
            $data['ajaxData'] = ['vehicle_id' => $id];
        else:
            abort(404);
        endif;
        return view('admin.pages.vehicles.edit', $data);
    }
}
