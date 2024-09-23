<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\SiteReviews
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SiteReviews newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SiteReviews newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SiteReviews query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property int $rating
 * @property string $comment
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SiteReviews whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SiteReviews whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SiteReviews whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SiteReviews whereRating($value)
 * @property string $image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\SiteReviews whereImage($value)
 */
class SiteReviews extends Model
{
    protected $table = 'site_reviews';
    public $timestamps = false;
}

