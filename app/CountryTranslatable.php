<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\CountryTranslatable
 *
 * @property int $id
 * @property int $country_id
 * @property string $name
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CountryTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CountryTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CountryTranslatable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CountryTranslatable whereCountryId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CountryTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CountryTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\CountryTranslatable whereName($value)
 * @mixin \Eloquent
 */
class CountryTranslatable extends Model
{
    public $timestamps = false;
    protected $fillable = ['country_id','name','locale'];
}
