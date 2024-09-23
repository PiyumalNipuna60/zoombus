<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FuelTypes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypes query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name_ka
 * @property string $name_en
 * @property string $name_ru
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypes whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypes whereNameKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypes whereNameRu($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\FuelTypesTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\FuelTypesTranslatable $translated
 */
class FuelTypes extends Model
{
    public $timestamps = false;

    public function translated() {
        return $this->hasOne('App\FuelTypesTranslatable', 'fuel_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\FuelTypesTranslatable', 'fuel_id');
    }
}
