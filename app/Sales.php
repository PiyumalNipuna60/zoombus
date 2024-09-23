<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

/**
 * App\Sales
 *
 * @property int $id
 * @property string|null $transaction_id
 * @property string|null $response
 * @property int $status
 * @property int $user_id
 * @property int $route_id
 * @property int $seat_number
 * @property float $price
 * @property int $currency_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereDepartureDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales wherePrice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereResponse($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereRouteId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereSeatNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereTransactionId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereUserId($value)
 * @mixin \Eloquent
 * @property int $payment_method
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales wherePaymentMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales transaction($transaction)
 * @property-read \App\Routes $routes
 * @property-read \App\User $users
 * @property string|null $ticket_number
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereTicketNumber($value)
 * @property-read \App\Currency $currency
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales current($userId = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales statusNot($status)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\BalanceUpdates[] $balanceUpdates
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales departureInDay($date)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales nowOrFuture()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales nonReminded()
 * @property int $reminded
 * @property int $rating_sent
 * @property-read int|null $balance_updates_count
 * @property-read \App\Rating $rating
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereRatingSent($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Sales whereReminded($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\RemainingSeats[] $remainingSeats
 * @property-read int|null $remaining_seats_count
 * @property-read \App\Rating $routeRatings
 * @property-read \App\Cart $cart
 */
class Sales extends Model
{
    protected $fillable = ['transaction_id','response','status','user_id','payment_method','route_id','seat_number','price','currency_id','ticket_number','paypal_transaction_id'];
    public $table = 'sales';
    public $userId;


    public function scopeCurrent($query, $userId = null) {
        if($userId) {
            $query->where('user_id', $userId);
        }
        else {
            $query->where('user_id', \Auth::user()->id);
        }
    }

    public function scopeNonReminded($query) {
        $query->where('reminded', 0);
    }

    public function scopeStatus($query, $status) {
        if(is_array($status)) {
            $query->whereIn('status', $status);
        }
        else {
            $query->where('status', $status);
        }
    }


    public function scopeStatusNot($query, $status) {
        if(is_array($status)) {
            $query->whereNotIn('status', $status);
        }
        else {
            $query->where('status', '!=', $status);
        }
    }



    public function scopeDepartureInDay($query, $date) {
        $query->whereHas('routes', function ($q) use ($date) {
            $q->whereRaw('DATE(departure_date) <= CURRENT_DATE() + INTERVAL ? DAY', [$date]);
        });
    }

    public function routes() {
        return $this->belongsTo('App\Routes', 'route_id');
    }


    public function balanceUpdates() {
        return $this->hasMany('App\BalanceUpdates', 'sale_id');
    }


    public function users() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function cart() {
        return $this->hasOne('App\Cart', 'sale_id');
    }

    public function rating() {
        return $this->hasOne('App\Rating', 'sale_id');
    }


    public function scopeTransaction($query, $transaction) {
        $query->where('transaction_id', $transaction);
    }

    public function currency()
    {
        return $this->belongsTo('App\Currency');
    }

    public function routeRatings() {
        return $this->belongsTo('App\Rating', 'App\Routes');
    }

}
