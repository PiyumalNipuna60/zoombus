<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Currencies
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $key
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereKey($value)
 * @property int $code
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereCode($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency current($cur)
 * @property float $value
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Currency whereValue($value)
 */
class Currency extends Model
{
    protected $fillable = ['value'];
    protected $table = 'currencies';
    public $timestamps = false;

    public function scopeCurrent($q, $cur) {
        $q->where('key', $cur);
    }
}
