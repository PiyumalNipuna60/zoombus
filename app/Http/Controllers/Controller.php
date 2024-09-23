<?php

namespace App\Http\Controllers;

use App\Cart;
use App\Gender;
use App\User;
use Auth;
use Illuminate\Support\Facades\Storage;
use Jenssegers\Agent\Agent;
use App\Page;
use App\RouteDateTypes;
use App\RouteTypes;
use Carbon\Carbon;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use App\SiteReviews;
use App\Driver;
use App\Partner;
use Illuminate\Support\Arr;
use Mcamara\LaravelLocalization\LaravelLocalization;
use Illuminate\Support\Facades\Request;


class Controller extends BaseController {
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected $currencies;
    protected $ip;

    public function __construct() {
    }


    protected function getProtocol() {
        return (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' || $_SERVER['SERVER_PORT'] == 443) ? "https://" : "http://";
    }

    public static function userAvatarById($id) {
        $user = User::whereId($id)->first('extension');
        if (Storage::disk('s3')->exists('users/' . $id . '.'.$user->extension)) {
            return Storage::temporaryUrl('users/'.$id.'.'.$user->extension, now()->addMinutes());
        } else {
            return '/images/users/default.png';
        }
    }

    public static function addZero($num) {
        if ($num < 10) {
            return '0' . $num;
        } else {
            return $num;
        }
    }

    public static function timeForHumans($time = null) {
        if ($time) {
            $exp = explode(":", $time);

            $return = \Lang::get('misc.stopping_time_search') . ' ';
            if ($exp[0] > 0) {
                $return .= (int)$exp[0];
                $return .= ' ' . trans_choice('misc.hours', $exp[0]);
            }

            if ($exp[0] > 0 && $exp[1] > 0) {
                $return .= ' ' . \Lang::get('misc.and') . ' ';
            }

            if ($exp[1] > 0) {
                $return .= (int)$exp[1];
                $return .= ' ' . trans_choice('misc.minutes', $exp[1]);
            }
        } else {
            $return = \Lang::get('misc.non_stop');
        }


        return $return;
    }


    public static function essentialVars($fields = null) {
        $currencies = \Request::get('currencies');
        $data = array(
            'current_locale' => config('app.locale'),
            'currencies' => $currencies,
            'current_currency' => array_search((session('currency') ?: config('app.currency')), array_column($currencies, 'key')),
            'site_reviews' => SiteReviews::all()->toArray(),
            'current_country_code' => geoip($_SERVER['REMOTE_ADDR'])->iso_code,
            'all_route_types' => RouteTypes::all(['id', 'key', 'slug', 'faicon'])->toArray(),
        );

        $footerNews = Page::with('translated')->where('in_footer', true)->get();
        if($footerNews) {
            foreach($footerNews as $k =>$fn) {
                $data['footerNews'][$k]['id'] = $fn['id'];
                $data['footerNews'][$k]['url'] = (!empty($fn['slug'])) ? route('page', ['slug' => $fn['slug']]) : route($fn['route_name']);
                $data['footerNews'][$k]['title'] = $fn['translated']['seo_title'];
                $data['footerNews'][$k]['date'] = Carbon::parse($fn['created_at'])->translatedFormat('j\ F Y');
            }
        }

        $fks = Page::with(['translated' => function($q) {
            $q->select('page_id','seo_title');
        }])->where('in_faq', true)->get();
        if ($fks) {
            foreach ($fks->toArray() as $key => $faq) {
                $data['faqs'][$key]['url'] = (isset($faq['slug'])) ? '/'.$faq['slug'] : route($faq['route_name']);
                $data['faqs'][$key]['seo_title'] = $faq['translated']['seo_title'];
            }
        }


        if (Request::route() && Page::where('route_name', Request::route()->getName())->whereNull('slug')->active()->exists()) {
            $seoData = Page::with('translated')->where('route_name', Request::route()->getName())->whereNull('slug')->active()->first();
            $sData = $seoData->toArray();
            $data['title'] = $sData['translated']['seo_title'];
            $data['description'] = $sData['translated']['seo_description'];
            $data['title_page'] = $sData['translated']['title'];
            $data['text_page'] = $sData['translated']['text'];
            $data['robots'] = $sData['robots'];
            $data['videoUrl'] = $sData['video_url'] ?? null;
        }


        $data['gender'] = Gender::with('translated')->get()->toArray();


        $data['current_locale_id'] = self::addZero((new LaravelLocalization)->getSupportedLocales()[$data['current_locale']]['id']);

        $data['current_currency_id'] = $data['currencies'][$data['current_currency']]['id'];
        $data['current_currency_key'] = $data['currencies'][$data['current_currency']]['key'];
        $data['current_currency_code'] = $data['currencies'][$data['current_currency']]['code'];

        if (Auth::check() && Driver::current()->exists()): $data['isDriver'] = 1; endif;
        if (Auth::check() && Partner::current()->exists()): $data['isPartner'] = 1; endif;

        if (Auth::check()) {
            $data['cart_items'] = Cart::with([
                'sales',
                'sales.users:id,name',
                'sales.routes',
                'sales.routes.vehicles.routeTypes',
                'sales.routes.vehicles.routeTypes.translated',
                'sales.routes.user:id,balance',
                'sales.routes.citiesFrom',
                'sales.routes.citiesFrom.translated',
                'sales.routes.citiesFrom.translateToEn',
                'sales.routes.citiesTo',
                'sales.routes.citiesTo.translated',
                'sales.routes.citiesTo.translateToEn'
                ])
                ->where('user_id', Auth::user()->id)
                ->get()->toArray();

            $data['notifications'] = Auth::user()->notifications()->take(5)->get()->toArray();
            $data['new_notifications'] = Auth::user()->unreadNotifications()->count();
        }

        if ($fields) {
            return Arr::only($data, $fields);
        } else {
            return $data;
        }
    }


    public static function uniqueString($length) {
        $token = "";
        $codeAlphabet = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
        $codeAlphabet.= "abcdefghijklmnopqrstuvwxyz";
        $codeAlphabet.= "0123456789";
        $max = strlen($codeAlphabet); // edited

        for ($i=0; $i < $length; $i++) {
            $token .= $codeAlphabet[random_int(0, $max-1)];
        }

        return $token;
    }

}
