<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AddressTranslatable
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AddressTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AddressTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AddressTranslatable query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $address_id
 * @property string $name
 * @property string $locale
 * @property-read \App\Address $address
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AddressTranslatable whereAddressId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AddressTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AddressTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AddressTranslatable whereName($value)
 */
class AddressTranslatable extends Model
{
    protected $fillable = ['address_id','name','locale'];
    public $timestamps = false;

    public function address() {
        return $this->belongsTo('App\Address', 'address_id');
    }

}
