<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ReservedSeats
 *
 * @property int $id
 * @property int $route_id
 * @property int $gender_id
 * @property int $seat_number
 * @property-read \App\Gender $gender
 * @property-read \App\Routes $routes
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservedSeats newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservedSeats newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservedSeats query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservedSeats whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservedSeats whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservedSeats whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ReservedSeats whereSeatNumber($value)
 * @mixin \Eloquent
 */
class ReservedSeats extends Model
{
    protected $table = 'reserved_seats';
    protected $fillable = ['route_id','gender_id','seat_number'];
    public $timestamps = false;


    public function routes() {
        return $this->belongsTo('App\Routes', 'route_id');
    }


    public function gender() {
        return $this->belongsTo('App\Gender', 'gender_id');
    }

}
