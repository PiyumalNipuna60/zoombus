<?php

namespace App\Http\Controllers\Api;


use App\Driver;
use App\Http\Controllers\Driver\VehiclesController as VC;
use App\SupportTicketMessages;
use App\Vehicle;
use Carbon\Carbon;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Mcamara\LaravelLocalization\LaravelLocalization;

class VehiclesController extends VC {
    public function __construct() {
        parent::__construct();
        $this->middleware('api_can_edit_vehicle')->only('constructor');
    }

    private function calculation($type, $width = null) {
        if ($type == 1) {
            $data['mult'] = 1.5;
            $data['plus'] = 75;
            $data['rmult'] = 1.25;
            $data['rplus'] = ($width) ? ($width/2)-175.5 : 12;
        } else if ($type == 2) {
            $data['mult'] = 1.5;
            $data['plus'] = 85;
            $data['rmult'] = 1;
            $data['rplus'] = ($width) ? ($width/2)-159.5 : 28;
        } else if ($type == 3) {
            $data['mult'] = 1.35;
            $data['plus'] = -30;
            $data['rmult'] = 1.16;
            $data['rplus'] = ($width) ? ($width/2)-174.5 : 13;
        } else {
            $data = [];
        }
        return $data;
    }

    public function recalculateLogic($scheme = null, $typeEdit = null, $width = null) {
        $new = [];
        for ($i = 1; $i <= 3; $i++) {
            $calc = $this->calculation($i, $width);
            $desktopSchemes = ($typeEdit == $i) ? $scheme : $this->defaultSchemes($i);
            foreach (json_decode($desktopSchemes, true) as $k => $ds) {
                $new[$i][$k]['value'] = (int) $ds['value'];
                $new[$i][$k]['top'] = $ds['left'] / $calc['mult'] + $calc['plus'];
                $new[$i][$k]['right'] = $ds['top'] / $calc['rmult'] + $calc['rplus'];
            }
        }
        return $new;
    }

    private function encodeLogic($schemes, $type, $width = null) {
        $schemesArray = json_decode($schemes, true);
        $new = [];
        $calc = $this->calculation($type, $width);
        foreach ($schemesArray as $k => $ds) {
            $new[$k]['value'] = (int) $ds['value'];
            $new[$k]['top'] = round(($ds['right'] - $calc['rplus']) * $calc['rmult']);
            $new[$k]['left'] = round(($ds['top'] - $calc['plus']) * $calc['mult']);
        }
        return $new;
    }


    public function constructor(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data = $this->dataLoad(false);
        $data['driverStatus'] = Driver::current($request->user()->id)->pluck('status')->first();
        if ($request->id) {
            $data['vehicle'] = Vehicle::with('fullspecifications:vehicle_id,vehicle_specification_id', 'routeTypes')
                ->current($request->user()->id)->where('id', $request->id)->first()->toArray();
            $vehicleImages = json_decode($data['vehicle']['images_extension'], true);

            foreach($vehicleImages as $k => $image) {
                if (Storage::disk('s3')->exists('drivers/vehicles/'.$request->id.'/'.$image)) {
                    $data['vehicle_images'][$k] = Storage::temporaryUrl('drivers/vehicles/'.$request->id.'/'.$image, now()->addMinutes(5));
                }
            }
            $data['front_side'] = $this->vehicleFrontSide($request->id);
            $data['back_side'] = $this->vehicleBackSide($request->id);
            $data['scheme'] = $this->recalculateLogic($data['vehicle']['seat_positioning'], $data['vehicle']['type'], $request->width);

        } else {
            $data['scheme'] = $this->recalculateLogic(null, null, $request->width);

        }

        $hashids = new Hashids('', 16);
        if($request->ticket && $request->message) {
            $data['errorMessage'] = SupportTicketMessages::whereTicketId($hashids->decode($request->ticket)[0])->whereId($hashids->decode($request->message)[0])->first('message')->message;
        }

        return response()->json($data);
    }

    public function list(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $skip = $request->skip ?? 0;
        $list = Vehicle::current($request->user()->id)
            ->with('routeTypes:id,key', 'routeTypes.translated', 'manufacturers:id,name as manufacturer_name')
            ->skip($skip)->take(10)->get()->toArray();
        $col['items'] = collect($list)->map(function ($item) {
            $item['created_at_formatted'] = Carbon::parse($item['created_at'])->translatedFormat('j M Y');
            return $item;
        });
        $col['total'] = Vehicle::current($request->user()->id)->count();

        return response()->json($col);
    }

    public function addAction(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $request->merge(['seat_positioning' => $this->encodeLogic($request->seat_positioning, $request->type, $request->width)]);
        $request->merge(['vehicle_features' => explode(",", $request->vehicle_features)]);
        $response = $this->add($request);
        if ($response->original['status'] == 1) {
            if($request->isWizard) {
                $response->original = ['step' => 4, 'id' => $response->original['vehicle_id']];
            }
            return response()->json($response->original, 200);
        } else {
            return response()->json($response->original, 422);
        }
    }

    public function editAction(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $request->merge(['seat_positioning' => $this->encodeLogic($request->seat_positioning, $request->type, $request->width)]);
        $request->merge(['vehicle_features' => explode(",", $request->vehicle_features)]);
        $response = $this->edit($request);
        if ($response->original['status'] == 1) {
            if($request->isWizard) {
                $response->original = ['step' => 4];
            }
            return response()->json($response->original, 200);
        } else {
            return response()->json($response->original, 422);
        }
    }


}
