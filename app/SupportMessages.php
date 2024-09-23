<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SupportMessages
 *
 * @property int $id
 * @property int $user_id
 * @property string $message
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages whereMessage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SupportMessages whereUserId($value)
 * @mixin \Eloquent
 */
class SupportMessages extends Model
{
    protected $fillable = ['message','status','user_id'];
}
