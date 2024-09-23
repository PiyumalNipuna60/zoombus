<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Page
 *
 * @property int $id
 * @property string $slug
 * @property string $route_name
 * @property string|null $title_ka
 * @property string|null $title_en
 * @property string|null $title_ru
 * @property string|null $seo_title_ka
 * @property string|null $seo_title_en
 * @property string|null $seo_title_ru
 * @property string|null $seo_description_ka
 * @property string|null $seo_description_en
 * @property string|null $seo_description_ru
 * @property string|null $text_ka
 * @property string|null $text_en
 * @property string|null $text_ru
 * @property int $status
 * @property int $in_faq
 * @property int $in_footer
 * @property string|null $robots
 * @property-read \Illuminate\Database\Eloquent\Collection|\App\CityTranslatable[] $translate
 * @property-read int|null $translate_count
 * @property-read \App\CityTranslatable $translated
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page active()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereInFaq($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereInFooter($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereRobots($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereRouteName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSeoDescriptionEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSeoDescriptionKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSeoDescriptionRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSeoTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSeoTitleKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSeoTitleRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereSlug($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereTextEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereTextKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereTextRu($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereTitleEn($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereTitleKa($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereTitleRu($value)
 * @mixin \Eloquent
 * @property string|null $video_url
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Page whereVideoUrl($value)
 */
class Page extends Model
{
    public $fillable = ['route_name','slug','video_url','in_faq','in_footer','status','robots'];
    public $nonFields = ['translate'];
    public $timestamps = false;

    public function scopeActive($query) {
        $query->where('status', 1);
    }

    public function translated() {
        return $this->hasOne('App\PageTranslatable', 'page_id')->where('locale', config('app.locale'));
    }

    public function translate() {
        return $this->hasMany('App\PageTranslatable', 'page_id');
    }


}
