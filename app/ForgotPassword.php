<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\ForgotPassword
 *
 * @property int $id
 * @property int $user_id
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForgotPassword newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForgotPassword newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForgotPassword query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForgotPassword whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForgotPassword whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForgotPassword whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForgotPassword whereUserId($value)
 * @mixin \Eloquent
 * @property string $phone_number
 * @method static \Illuminate\Database\Eloquent\Builder|\App\ForgotPassword wherePhoneNumber($value)
 */
class ForgotPassword extends Model
{
    protected $fillable = ['phone_number'];
}
