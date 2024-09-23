<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Address
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name_ka
 * @property string $name_en
 * @property string $name_ru
 * @property int $city_id
 * @property int $user_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereCityId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereNameKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereNameRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address whereUserId($value)
 * @property-read \App\City $city
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\AddressTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\AddressTranslatable $translated
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Address search($value)
 */
class Address extends Model
{
    public $table = 'addresses';
    public $fillable = ['city_id','user_id'];
    public $timestamps = false;

    public $nonFields = ['user_id','city','translate'];

    public function scopeSearch($query, $value) {
        $query->whereHas('translate', function ($q) use ($value) {
            $q->where('name', 'LIKE', "%{$value}%");
        });
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function city() {
        return $this->belongsTo('App\City', 'city_id');
    }

    public function translated() {
        return $this->hasOne('App\AddressTranslatable', 'address_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\AddressTranslatable', 'address_id');
    }

}
