<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Cart
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $route_id
 * @property string $places
 * @property string $passengers
 * @property float $price
 * @property int $currency_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart wherePassengers($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart wherePlaces($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart whereUserId($value)
 * @property int $sale_id
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart whereSaleId($value)
 * @property string $transaction_id
 * @property-read \App\Sales $sales
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Cart whereTransactionId($value)
 */
class Cart extends Model
{
    protected $fillable = ['user_id','sale_id','transaction_id'];
    protected $table = 'cart';
    public $timestamps = false;

    public function sales() {
        return $this->belongsTo('App\Sales', 'sale_id');
    }
}
