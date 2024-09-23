<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Driver
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver query()
 * @mixin \Eloquent
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver current($user = null)
 * @property int $id
 * @property int $user_id
 * @property int $license_number
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver notActive()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereLicenseNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereUserId($value)
 * @property float $balance
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereBalance($value)
 * @property-read \App\User $user
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver status($status)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver suspended()
 * @property int $step
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Driver whereStep($value)
 * @property string|null $front_side_extension
 * @property string|null $back_side_extension
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereBackSideExtension($value)
 * @method static \Illuminate\Database\Eloquent\Builder|Driver whereFrontSideExtension($value)
 */
class Driver extends Model {
    protected $fillable = [
        'user_id', 'license_number', 'balance',
    ];

    public function scopeCurrent($query, $user = null) {
        if (isset($user)) {
            $query->where('user_id', $user);
        }
        else if (\Auth::check()) {
            $query->where('user_id', \Auth::user()->id);
        }
    }

    public function user() {
        return $this->belongsTo('App\User', 'user_id');
    }

    public function scopeActive($query) {
        $query->where('status', 1);
    }

    public function scopeSuspended($query) {
        $query->where('status', 3);
    }

    public function scopeStatus($query, $status) {
        $query->where('status', $status);
    }

    public function scopeNotActive($query) {
        $query->where('status', '!=', 1);
    }


}
