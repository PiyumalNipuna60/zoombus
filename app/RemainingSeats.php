<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\RemainingSeats
 *
 * @property int $id
 * @property int $route_id
 * @property int $remaining_seats
 * @property-read \App\Sales $sales
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RemainingSeats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RemainingSeats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RemainingSeats query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RemainingSeats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RemainingSeats whereRemainingSeats($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\RemainingSeats whereRouteId($value)
 * @mixin \Eloquent
 */
class RemainingSeats extends Model
{
    protected $table = 'remaining_seats';
    protected $fillable = ['route_id','departure_date','remaining_seats'];
    public $timestamps = false;


    public function sales()
    {
        return $this->belongsTo('App\Sales', 'route_id', 'route_id');
    }


}
