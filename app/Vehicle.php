<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Vehicle
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle current($user = null)
 * @property-read \App\RouteTypes $routeTypes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle active()
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property int $status
 * @property int $manufacturer
 * @property string $model
 * @property string $license_plate
 * @property int $fuel_type
 * @property string $year
 * @property string $date_of_registration
 * @property string|null $vehicle_features
 * @property string $seat_positioning
 * @property int $number_of_seats
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereDateOfRegistration($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereFuelType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereLicensePlate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereManufacturer($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereModel($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereNumberOfSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereSeatPositioning($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereVehicleFeatures($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereYear($value)
 * @property-read \App\Manufacturer $manufacturers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VehicleSpecs[] $fullspecifications
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VehicleSpecs[] $specifications
 * @property int $country
 * @property-read \App\FuelTypes $fuelTypes
 * @property-read int|null $fullspecifications_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Routes[] $routes
 * @property-read int|null $routes_count
 * @property-read int|null $specifications_count
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereCountry($value)
 * @property int $country_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Vehicle whereCountryId($value)
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property string|null $front_side_extension
 * @property string|null $back_side_extension
 * @property string|null $images_extension
 * @property-read \App\AffiliateCodes|null $affiliate
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereBackSideExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereFrontSideExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereImagesExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Vehicle whereUpdatedAt($value)
 */
class Vehicle extends Model {

    public $fillable = [
        'type', 'country_id', 'user_id', 'manufacturer', 'model', 'license_plate', 'fuel_type', 'year',
        'date_of_registration', 'vehicle_features', 'seat_positioning', 'number_of_seats', 'status'
    ];


    public function scopeCurrent($query, $user = null) {
        if($user) {
            $query->where('user_id', $user);
        }
        else if (\Auth::check()) {
            $query->where('user_id', \Auth::user()->id);
        }
    }

    public function scopeActive($query) {
        $query->where('status', 1);
    }

    public function scopeStatus($query, $status) {
        if(is_array($status)) {
            $query->whereIn('status', $status);
        }
        else {
            $query->where('status', $status);
        }
    }

    public function affiliate() {
        return $this->hasOne('App\AffiliateCodes', 'user_used', 'user_id');
    }

    public function specifications() {
        return $this->belongsToMany('App\VehicleSpecs', 'vehicles_specifications', 'vehicle_id', 'vehicle_specification_id')->limit(4);
    }

    public function fullspecifications() {
        return $this->belongsToMany('App\VehicleSpecs', 'vehicles_specifications', 'vehicle_id', 'vehicle_specification_id');
    }

    public function routeTypes() {
        return $this->belongsTo('App\RouteTypes', 'type');
    }

    public function manufacturers() {
        return $this->belongsTo('App\Manufacturer', 'manufacturer');
    }

    public function fuelTypes() {
        return $this->belongsTo('App\FuelTypes', 'fuel_type');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function routes() {
        return $this->hasMany('App\Routes', 'vehicle_id');
    }


    public $nonFields = [
        'manufacturers','route_types','seat_positioning','number_of_seats', 'user_id', 'status', 'vehicle_features','user','routes',
        'front_side_extension','back_side_extension','created_at','updated_at','images_extension'
    ];
}
