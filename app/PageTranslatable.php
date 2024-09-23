<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

/**
 * App\PageTranslatable
 *
 * @property int $id
 * @property int $page_id
 * @property string|null $title
 * @property string|null $seo_title
 * @property string|null $seo_description
 * @property string|null $text
 * @property string $locale
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable whereLocale($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable wherePageId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable whereSeoDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable whereSeoTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable whereText($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\PageTranslatable whereTitle($value)
 * @mixin \Eloquent
 */
class PageTranslatable extends Model
{
    public $timestamps = false;

    public $fillable = ['page_id','title','seo_title','seo_description','text','locale'];
}
