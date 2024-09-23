<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CityTranslatable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CityTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CityTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CityTranslatable query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $city_id
 * @property string $name
 * @property string $locale
 * @property-read \App\City $city
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CityTranslatable whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CityTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CityTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CityTranslatable whereName($value)
 */
class CityTranslatable extends Model
{
    protected $fillable = ['city_id','name','locale'];
    public $timestamps = false;

    public function city() {
        return $this->belongsTo('App\City', 'city_id');
    }


}
