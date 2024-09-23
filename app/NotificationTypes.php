<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\NotificationTypes
 *
 * @property int $id
 * @property string|null $key
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NotificationTypes newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NotificationTypes newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NotificationTypes query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NotificationTypes whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\NotificationTypes whereKey($value)
 * @mixin \Eloquent
 */
class NotificationTypes extends Model
{
    public $timestamps = false;
}
