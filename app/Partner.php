<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Partner
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner current($user = null)
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner active()
 * @property float $balance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner whereBalance($value)
 * @property int $status
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Partner suspended()
 * @property-read \App\User $user
 */
class Partner extends Model
{
    protected $fillable = [
        'user_id', 'license_number', 'balance'
    ];

    public function scopeCurrent($query, $user = null) {
        if(\Auth::check()) {
            $query->where('user_id', \Auth::user()->id);
        }
        else if (isset($user)) {
            $query->where('user_id', $user);
        }
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeSuspended($query) {
        $query->where('status', 3);
    }

    public function scopeActive($query) {
        $query->where('status', 1);
    }

}
