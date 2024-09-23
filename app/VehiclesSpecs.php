<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VehiclesSpecs
 *
 * @property int $id
 * @property int $vehicle_id
 * @property int $vehicle_specification_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehiclesSpecs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehiclesSpecs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehiclesSpecs query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehiclesSpecs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehiclesSpecs whereVehicleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehiclesSpecs whereVehicleSpecificationId($value)
 * @mixin \Eloquent
 */
class VehiclesSpecs extends Model
{
    public $fillable = ['vehicle_id','vehicle_specification_id'];
    public $timestamps = false;
    protected $table = 'vehicles_specifications';
}
