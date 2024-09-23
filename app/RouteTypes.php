<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RouteTypes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name_ka
 * @property string $name_en
 * @property string $name_ru
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes whereNameKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes whereNameRu($value)
 * @property string $key
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes whereKey($value)
 * @property string $slug
 * @property string $faicon
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RouteTypesTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\RouteTypesTranslatable $translated
 * @property-read \App\Vehicle $vehicle
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes whereFaicon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteTypes whereSlug($value)
 */
class RouteTypes extends Model
{
    public $timestamps = false;

    public function vehicle()
    {
        return $this->hasOne('App\Vehicle', 'type');
    }

    public function translated() {
        return $this->hasOne('App\RouteTypesTranslatable', 'route_type_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\RouteTypesTranslatable', 'route_type_id');
    }
}
