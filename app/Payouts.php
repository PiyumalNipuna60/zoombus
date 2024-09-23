<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Payouts
 *
 * @property int $id
 * @property float|null $amount
 * @property string $type
 * @property string $method
 * @property string $currency_id
 * @property int $user_id
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @property-read \App\Currency $currency
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts current($user = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereAmount($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereCurrencyId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereUserId($value)
 * @mixin \Eloquent
 * @property string|null $comment
 * @property int $financial_id
 * @property-read \App\Financial $financial
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Payouts whereFinancialId($value)
 */
class Payouts extends Model
{
    public $fillable = ['type','user_id','amount','currency_id','comment','status','financial_id'];

    public $nonFields = ['type','user_id','currency_id','financial_id','created_at','updated_at','currency'];

    public function scopeCurrent($query, $user = null) {
        if($user) {
            $query->where('user_id', $user);
        }
        else if (\Auth::check()) {
            $query->where('user_id', \Auth::user()->id);
        }
    }

    public function scopeStatus($query, $status) {
        $query->where('status', $status);
    }


    public function currency()
    {
        return $this->belongsTo('App\Currency', 'currency_id');
    }

    public function user()
    {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function financial()
    {
        return $this->belongsTo('App\Financial', 'financial_id');
    }


}
