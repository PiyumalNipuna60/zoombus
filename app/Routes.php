<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Arr;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * App\Routes
 *
 * @property int $id
 * @property int $type
 * @property int $vehicle_id
 * @property int $from
 * @property string $from_address
 * @property int $to
 * @property string $to_address
 * @property int $currency_id
 * @property int $refundable
 * @property float $price
 * @property string $departure_time
 * @property string $arrival_time
 * @property string $departure_date
 * @property string $arrival_date
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereArrivalDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereArrivalTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereDepartureDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereDepartureTime($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereFrom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereFromAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereRefundable($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereTo($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereToAddress($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereVehicleId($value)
 * @mixin \Eloquent
 * @property int $status
 * @property int $route_type
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereRouteType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereStatus($value)
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes current($user = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereUserId($value)
 * @property string $stopping_time
 * @property-read \App\Address $addressFrom
 * @property-read \App\Address $addressTo
 * @property-read \App\City $citiesFrom
 * @property-read \App\City $citiesTo
 * @property-read \App\Currency $currency
 * @property-read \App\Vehicle $vehicles
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes whereStoppingTime($value)
 * @property-read \App\Driver $drivers
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rating[] $ratings
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes active($departure_date = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes departure($departure_date, $from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes places($relationship, $value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes return ($return_date, $from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes nowOrFuture()
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sales[] $sales
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes passengers($departure_date = null, $return_date = null, $from = null, $to = null)
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes returns($return_date, $from = null, $to = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes statusNot($status)
 * @property-read \App\RouteDateTypes $routeDateTypes
 * @property string $pre_orders
 * @property-read int|null $ratings_count
 * @property-read \App\Routes $sale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Routes wherePreOrders($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Rating[] $ratingsLimited
 * @property-read int|null $ratings_limited_count
 * @property-read \App\RemainingSeats $remainingSeats
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\ReservedSeats[] $reservedSeats
 * @property-read int|null $reserved_seats_count
 * @property-read int|null $sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sales[] $activeSales
 * @property-read int|null $active_sales_count
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sales[] $parsedSales
 * @property-read int|null $parsed_sales_count
 * @property-read \App\AffiliateCodes|null $affiliate
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Sales[] $salesLimited
 * @property-read int|null $sales_limited_count
 */
class Routes extends Model {

    /**
     * query scope nPerGroup
     *
     * @return void
     */


    protected $fillable = [
        'type', 'user_id', 'vehicle_id', 'from', 'from_address', 'to', 'to_address', 'currency_id', 'stopping_time', 'price',
        'departure_time', 'arrival_time', 'departure_date', 'arrival_date', 'valid_till', 'status'
    ];


    public function scopeCurrent($query, $user = null) {
        if ($user) {
            $query->where('user_id', $user);
        } else if (\Auth::check()) {
            $query->where('user_id', \Auth::user()->id);
        }
    }

    public function scopePlaces($query, $relationship, $value) {
        return $query->whereHas($relationship, function ($q) use ($value) {
            $q->where('name', $value);
        });
    }


    public function scopeNowOrFuture($query) {
        $query->where(function ($q) {
            $q->where(function ($qu) {
                $qu->whereRaw('timestamp(departure_date, departure_time) >= now() + INTERVAL 1 HOUR');
            });
        });
    }


    public function scopeDeparture($query, $departure_date = null, $from = null, $to = null) {
        if (!empty($departure_date)) {
            $query->where(function ($q) use ($departure_date, $from, $to) {
                $q->whereDepartureDate($departure_date);
                if (!empty($from)) {
                    $q->places('citiesFrom.translate', $from);
                }
                if (!empty($to)) {
                    $q->places('citiesTo.translate', $to);
                }
            })->whereRaw('timestamp(now() + INTERVAL 2 MONTH) >= ?', [$departure_date]);
        }
    }

    public function scopeReturns($query, $return_date, $from = null, $to = null) {
        if (!empty($from)) {
            $query->orWhere(function ($q) use ($return_date, $from, $to) {
                $q->departure($return_date, $from, $to);
            });
        }
    }


    public function scopeStatus($query, $status) {
        if (is_array($status)) {
            $query->whereIn('status', $status);
        } else {
            $query->where('status', $status);
        }
    }

    public function scopeStatusNot($query, $status) {
        if (is_array($status)) {
            $query->whereNotIn('status', $status);
        } else {
            $query->where('status', '!=', $status);
        }
    }

    public function remainingSeats() {
        return $this->hasOne('App\RemainingSeats', 'route_id');
    }

    public function reservedSeats() {
        return $this->hasMany('App\ReservedSeats', 'route_id');
    }


    public function scopePassengers($query, $passengers) {
        $query->whereHas('remainingSeats', function ($q) use ($passengers) {
            $q->where('remaining_seats', '>=', $passengers);
        });
    }


    public function scopeActive($query, $departure_date = null) {
        $query->where('status', 1);
    }


    public function ratingsLimited() {
        return $this->hasMany('App\Rating', 'driver_user_id', 'user_id')->take(config('app.listings_per_page') * config('app.ratings_per_page'));
    }


    public function ratings() {
        return $this->hasMany('App\Rating', 'driver_user_id', 'user_id')->take(config('app.ratings_per_page'));
    }

    public function sales() {
        return $this->hasMany('App\Sales', 'route_id');
    }

    public function salesLimited() {
        return $this->hasMany('App\Sales', 'route_id')->orderBy('created_at', 'desc')->take(config('app.sales_by_route_per_page'));
    }

    public function parsedSales() {
        return $this->hasMany('App\Sales', 'route_id')->where('status', 3);
    }

    public function activeSales() {
        return $this->hasMany('App\Sales', 'route_id')->whereIn('status', [1, 3]);
    }


    public function affiliate() {
        return $this->hasOne('App\AffiliateCodes', 'user_used', 'user_id');
    }



    public function drivers() {
        return $this->belongsTo('App\Driver', 'user_id', 'user_id');
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function sale() {
        return $this->hasOne('App\Routes', 'route_id');
    }


    public function vehicles() {
        return $this->belongsTo('App\Vehicle', 'vehicle_id');
    }

    public function currency() {
        return $this->belongsTo('App\Currency');
    }

    public function citiesFrom() {
        return $this->belongsTo('App\City', 'from');
    }

    public function addressFrom() {
        return $this->belongsTo('App\Address', 'from_address');
    }

    public function addressTo() {
        return $this->belongsTo('App\Address', 'to_address');
    }

    public function citiesTo() {
        return $this->belongsTo('App\City', 'to');
    }


    public function routeDateTypes() {
        return $this->belongsTo('App\RouteDateTypes', 'type');
    }


}
