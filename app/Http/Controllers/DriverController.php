<?php

namespace App\Http\Controllers;

use App\Driver;
use App\Vehicle;
use GPBMetadata\Google\Api\Auth;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;

class DriverController extends ValidationController
{

    public function __construct()
    {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer')->except('searchCity', 'searchAddress', 'seatsMax', 'tenCitiesByCountry', 'weekDays', 'weekMap');
            $this->middleware('driver')->except('searchCity', 'searchAddress', 'seatsMax', 'tenCitiesByCountry', 'weekDays', 'weekMap');
        }
    }


    protected function licenseFrontSide($id = null)
    {
        $realId = $id ?? \Auth::user()->id;
        $agent = new Agent();
        $extension = Driver::whereUserId($realId)->first('front_side_extension');
        if (Storage::disk('s3')->exists('drivers/license/' . $realId . '/front_side.' . $extension->front_side_extension)) {
            if ($agent->isMobile()) {
                return Storage::temporaryUrl('drivers/license/' . $realId . '/front_side.' . $extension->front_side_extension, now()->addMinutes(5));
            }
            else {
                return 'drivers/license/' . $realId . '/front_side.' . $extension->front_side_extension;
            }
        }
        else {
            return false;
        }
    }


    protected function licenseBackSide($id = null)
    {
        $realId = $id ?? \Auth::user()->id;
        $agent = new Agent();
        $extension = Driver::whereUserId($realId)->first('back_side_extension');
        if (Storage::disk('s3')->exists('drivers/license/' . $realId . '/back_side.' . $extension->back_side_extension)) {
            if ($agent->isMobile()) {
                return Storage::temporaryUrl('drivers/license/' . $realId . '/back_side.' . $extension->back_side_extension, now()->addMinutes(5));
            }
            else {
                return 'drivers/license/' . $realId . '/front_side.' . $extension->back_side_extension;
            }
        }
        else {
            return false;
        }
    }

    protected function vehicleFrontSide($id)
    {
        $extension = Vehicle::whereId($id)->first('front_side_extension');
        if ($extension && Storage::disk('s3')->exists('drivers/vehicles/' . $id . '/front_side.' . $extension->front_side_extension)) {
            return Storage::temporaryUrl('drivers/vehicles/' . $id . '/front_side.' . $extension->front_side_extension, now()->addMinutes(5));
        }
        else {
            return false;
        }
    }

    protected function vehicleBackSide($id)
    {
        $extension = Vehicle::whereId($id)->first('back_side_extension');
        if ($extension && Storage::disk('s3')->exists('drivers/vehicles/' . $id . '/back_side.' . $extension->back_side_extension)) {
            return Storage::temporaryUrl('drivers/vehicles/' . $id . '/back_side.' . $extension->back_side_extension, now()->addMinutes(5));
        }
        else {
            return false;
        }
    }


    protected function store(array $data)
    {
        if (!Driver::current()->where('status', 1)->exists()) {
            return Driver::current()->update($data);
        }
        else {
            return false;
        }
    }

}
