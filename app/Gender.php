<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Gender
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Gender newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Gender newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Gender query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name_ka
 * @property string $name_en
 * @property string $name_ru
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Gender whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Gender whereNameEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Gender whereNameKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Gender whereNameRu($value)
 * @property string $key
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Gender whereKey($value)
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\GenderTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\GenderTranslatable $translated
 */
class Gender extends Model
{
    public $timestamps = false;

    public function translated() {
        return $this->hasOne('App\GenderTranslatable', 'gender_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\GenderTranslatable', 'gender_id');
    }
}
