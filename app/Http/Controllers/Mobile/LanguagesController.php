<?php
namespace App\Http\Controllers\Mobile;


use App\Http\Controllers\Controller;
use App\Country;
use App\User;
use Illuminate\Http\Request;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\LaravelLocalization;

class LanguagesController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function view() {
        $agent = new Agent();
        $data['title'] = \Lang::get('titles.languages');
        if($agent->isMobile()) {
            return view('mobile.languages', $data);
        }
        else {
            abort(404);
        }
    }

    public function setPreferredLocale(Request $request) {
        $locales = (new LaravelLocalization())->getSupportedLocales();
        if($request->lang && array_key_exists($request->lang, $locales)) {
            if(\Auth::check()) {
                User::current()->update(['locale' => $request->lang]);
            }
            else if ($request->user()){
                User::current($request->user()->id)->update(['locale' => $request->lang]);
            }
            return response()->json([], 200);
        }
        else {
            return response()->json([], 422);
        }
    }

    public function getJSFormatted() {
        $locales = (new LaravelLocalization())->getSupportedLocales();
        $langs = Country::with('translate')->get()->toArray();
        foreach($locales as $keys => $vlvdl) {
            foreach ($langs as $k=>$l) {
                $data[$keys][$k]['value'] = $l['id'];
                $data[$keys][$k]['text'] = $l['translate'][array_search($keys, array_column($l['translate'], 'locale'))]['name'];
            }
        }
        return json_encode($data, JSON_UNESCAPED_UNICODE);

    }

}
