<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Fine
 *
 * @property int $id
 * @property int $route_id
 * @property float $amount
 * @property-read \App\Routes $route
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fine newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fine newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fine query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fine whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fine whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Fine whereRouteId($value)
 * @mixin \Eloquent
 */
class Fine extends Model
{
    public $timestamps = false;
    protected $fillable = ['route_id', 'amount'];

    public function route() {
        return $this->belongsTo('App\Routes', 'route_id');
    }

}
