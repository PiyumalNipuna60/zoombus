<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Countries
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $code
 * @property string $name_ka
 * @property string $name_en
 * @property string $name_ru
 * @property string|null $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereNameKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Country whereNameRu($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CountryTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\CountryTranslatable $translated
 */
class Country extends Model
{
    protected $table = 'countries';
    public $timestamps = false;
    protected $fillable = ['code'];

    public function translated() {
        return $this->hasOne('App\CountryTranslatable', 'country_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\CountryTranslatable', 'country_id');
    }
}
