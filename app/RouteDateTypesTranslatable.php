<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RouteDateTypesTranslatable
 *
 * @property int $id
 * @property int $route_date_type_id
 * @property string $name
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypesTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypesTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypesTranslatable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypesTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypesTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypesTranslatable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypesTranslatable whereRouteDateTypeId($value)
 * @mixin \Eloquent
 */
class RouteDateTypesTranslatable extends Model
{
    protected $fillable = ['route_date_type_id','name','locale'];
    public $timestamps = false;
}
