<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\BalanceUpdates
 *
 * @property int $id
 * @property int $sale_id
 * @property int $user_id
 * @property float $amount
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates current($user = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates daily($sub = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates monthly($sub = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates range($fromDate, $toDate)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates whereSaleId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates yearly($sub = null)
 * @mixin \Eloquent
 * @property int $type
 * @property int $status
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\BalanceUpdates whereType($value)
 * @property-read \App\Sales $sale
 */
class BalanceUpdates extends Model
{
    protected $fillable = ['user_id','sale_id','amount','type','status'];

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }


    public function sale() {
        return $this->belongsTo('App\Sales', 'sale_id');
    }



    public function scopeCurrent($query, $user = null) {
        if (isset($user)) {
            $query->where('user_id', $user);
        }
        else if (\Auth::check()) {
            $query->where('user_id', \Auth::user()->id);
        }
    }

    public function scopeYearly($query, $sub = null) {
        if(isset($sub)) {
            $query->whereRaw('YEAR(created_at) = YEAR(NOW() - INTERVAL ? YEAR) ', [$sub]);
        }
        else {
            $query->whereRaw('YEAR(created_at) = YEAR(NOW())');
        }
    }

    public function scopeMonthly($query, $sub = null) {
        if(isset($sub)) {
            $query->whereRaw('MONTH(created_at) = MONTH(NOW() - INTERVAL ? MONTH)', [$sub]);
        }
        else {
            $query->whereRaw('MONTH(created_at) = MONTH(NOW())');
        }

    }

    public function scopeDaily($query, $sub = null) {
        if(isset($sub)) {
            $query->whereRaw('DATE(created_at) = CURRENT_DATE() - INTERVAL ? DAY', [$sub]);
        }
        else {
            $query->whereRaw('DATE(created_at) = CURRENT_DATE()');
        }
    }

    public function scopeRange($query, $fromDate, $toDate) {
        $query->whereRaw('DATE(created_at) >= ?', [$fromDate]);
        $query->whereRaw('DATE(created_at) <= ?', [$toDate]);
    }

}
