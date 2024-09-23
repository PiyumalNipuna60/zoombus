<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RouteTypesTranslatable
 *
 * @property int $id
 * @property int $route_type_id
 * @property string $name
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypesTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypesTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypesTranslatable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypesTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypesTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypesTranslatable whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypesTranslatable whereRouteTypeId($value)
 * @mixin \Eloquent
 */
class RouteTypesTranslatable extends Model
{
    protected $fillable = ['route_type_id','name','locale'];
    public $timestamps = false;
}
