<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Manufacturer
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manufacturer newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manufacturer newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manufacturer query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manufacturer whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Manufacturer whereName($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Routes[] $routes
 * @property-read int|null $routes_count
 */
class Manufacturer extends Model
{
    public $timestamps = false;

    public function routes()
    {
        return $this->hasManyThrough('App\Routes', 'App\Vehicle', 'vehicle_id','manufacturer');
    }

}
