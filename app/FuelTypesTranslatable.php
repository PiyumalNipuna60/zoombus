<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\FuelTypesTranslatable
 *
 * @property int $id
 * @property int $fuel_id
 * @property string $name
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypesTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypesTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypesTranslatable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypesTranslatable whereFuelId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypesTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypesTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\FuelTypesTranslatable whereName($value)
 * @mixin \Eloquent
 */
class FuelTypesTranslatable extends Model
{
    protected $fillable = ['fuel_id','name','locale'];
    public $timestamps = false;
}
