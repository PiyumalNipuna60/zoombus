<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RouteDateTypes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypes query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name_ka
 * @property string $name_en
 * @property string $name_ru
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypes whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypes whereNameKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RouteDateTypes whereNameRu($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RouteDateTypesTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\RouteDateTypesTranslatable $translated
 */
class RouteDateTypes extends Model
{
   public $table = 'route_date_types';
   public $timestamps = false;

    public function translated() {
        return $this->hasOne('App\RouteDateTypesTranslatable', 'route_date_type_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\RouteDateTypesTranslatable', 'route_date_type_id');
    }
}
