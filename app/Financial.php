<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Financial
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial current($user_id = null)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $user_id
 * @property int $type
 * @property string|null $card_number
 * @property string|null $card_identifier
 * @property string|null $paypal_email
 * @property string|null $your_name
 * @property string|null $bank_name
 * @property string|null $account_number
 * @property string|null $swift
 * @property int $status
 * @property \Illuminate\Support\Carbon|null $created_at
 * @property \Illuminate\Support\Carbon|null $updated_at
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereAccountNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereBankName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereCardIdentifier($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereCardNumber($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereCreatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial wherePaypalEmail($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereStatusChild($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereSwift($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereUpdatedAt($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereUserId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial whereYourName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Financial active()
 * @property string|null $card_type
 * @property int $status_child
 * @method static \Illuminate\Database\Eloquent\Builder|Financial whereCardType($value)
 */
class Financial extends Model {
    protected $fillable = ['user_id', 'type', 'card_number', 'name_on_card', 'valid_till', 'ccv', 'paypal_email', 'your_name', 'bank_name', 'account_number', 'swift'];

    public function scopeCurrent($query, $user = null) {
        if (\Auth::check()) {
            $query->where('user_id', \Auth::user()->id);
        }
        else if (isset($user)) {
            $query->where('user_id', $user);
        }
    }

    public function scopeActive($query) {
        $query->where('status', 1);
    }
}
