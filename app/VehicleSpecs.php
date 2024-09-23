<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

/**
 * App\VehicleSpecs
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecs newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecs newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecs query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name_ka
 * @property string $name_en
 * @property string $name_ru
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecs whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecs whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecs whereNameKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\VehicleSpecs whereNameRu($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\VehicleSpecsTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\VehicleSpecsTranslatable $translated
 */
class VehicleSpecs extends Model
{
    public $timestamps = false;
    protected $table = 'vehicle_specifications';

    public $fillable = [];
    public $nonFields = ['translate','extension'];

    public function specImageById($id) {
        $vehicleSpec = VehicleSpecs::whereId($id)->first('extension');
        if(Storage::disk('s3')->exists('vehicle-features/'.$id.'.'.$vehicleSpec->extension)) {
            return Storage::temporaryUrl('vehicle-features/'.$id.'.'.$vehicleSpec->extension, now()->addMinutes(5));
        }
        else {
            return false;
        }
    }

    public function translated() {
        return $this->hasOne('App\VehicleSpecsTranslatable', 'vehicle_spec_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\VehicleSpecsTranslatable', 'vehicle_spec_id');
    }


}
