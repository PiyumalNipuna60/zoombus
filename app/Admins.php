<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Admins
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admins newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admins newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admins query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property string $ip
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admins whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admins whereIp($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admins whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Admins current()
 */
class Admins extends Model
{
    //
    protected $fillable = ['user_id','ip'];
    public $timestamps = false;
    public $table = 'admins';

    public function scopeCurrent($q) {
        if(\Auth::check()) {
            $q->where('user_id', \Auth::user()->id);
        }
    }


}
