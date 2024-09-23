<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\GenderTranslatable
 *
 * @property int $id
 * @property int $gender_id
 * @property string $name
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GenderTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GenderTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GenderTranslatable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GenderTranslatable whereGenderId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GenderTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GenderTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\GenderTranslatable whereName($value)
 * @mixin \Eloquent
 */
class GenderTranslatable extends Model
{
    protected $fillable = ['gender_id','name','locale'];
    public $timestamps = false;
}
