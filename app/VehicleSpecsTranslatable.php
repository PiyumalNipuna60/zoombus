<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\VehicleSpecsTranslatable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecsTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecsTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecsTranslatable query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $vehicle_spec_id
 * @property string $name
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecsTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecsTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecsTranslatable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecsTranslatable whereVehicleSpecId($value)
 */
class VehicleSpecsTranslatable extends Model
{
    protected $fillable = ['vehicle_spec_id','name','locale'];
    public $timestamps = false;

    public function spec() {
        $this->belongsTo('App\VehicleSpecs', 'vehicle_spec_id');
    }
}
