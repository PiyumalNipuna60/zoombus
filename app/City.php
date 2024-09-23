<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Cities
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $code
 * @property int $country_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereNameKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City names($value)
 * @property-read \App\Country $country
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CityTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\CityTranslatable $translated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\City search($value)
 * @property-read \App\CityTranslatable $translateToEn
 */
class City extends Model
{
    protected $table = 'cities';
    public $fillable = ['code','country_id'];

    public $nonFields = ['translate','extension'];

    public $timestamps = false;

    public function scopeNames($query, $value) {
        $query->whereHas('translate', function ($q) use ($value) {
            $q->where('name', $value);
        });
    }

    public function country() {
        return $this->belongsTo('App\Country', 'country_id');
    }

    public function translated() {
        return $this->hasOne('App\CityTranslatable', 'city_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\CityTranslatable', 'city_id');
    }

    public function translateToEn() {
        return $this->hasOne('App\CityTranslatable', 'city_id')->where('locale', 'en');
    }

    public function scopeSearch($query, $value) {
        $query->whereHas('translate', function ($q) use ($value) {
            $q->where('name', 'LIKE', "%{$value}%");
        });
    }

    public function countryImageById($id) {
        if (file_exists( public_path() . '/admin/img/flags/' . strtolower($id). '.png')) {
            return '/admin/img/flags/' . strtolower($id) .'.png';
        } else {
            return '/admin/img/flags/_European Union.png';
        }
    }




}
