<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\AffiliateCodes
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AffiliateCodes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AffiliateCodes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AffiliateCodes query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AffiliateCodes current($user = null)
 * @property int $id
 * @property int $user_id
 * @property string $code
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AffiliateCodes whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AffiliateCodes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AffiliateCodes whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\AffiliateCodes whereUserId($value)
 * @property-read \App\User|null $user
 * @property int|null $user_used
 * @property int|null $tier_one_user_id
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\Vehicle[] $activeVehicles
 * @property-read int|null $active_vehicles_count
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateCodes whereTierOneUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|AffiliateCodes whereUserUsed($value)
 */
class AffiliateCodes extends Model
{
    public $timestamps = false;
    protected $fillable = ['user_id','code','status', 'user_used', 'tier_one_user_id'];

    public function scopeCurrent($query, $user = null) {
        if (isset($user)) {
            $query->where('user_id', $user);
        }
        else if (\Auth::check()) {
            $query->where('user_id', \Auth::user()->id);
        }
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_used');
    }

    public function activeVehicles() {
        return $this->hasMany('App\Vehicle', 'user_id', 'user_used')->where('status', 1);
    }

}
