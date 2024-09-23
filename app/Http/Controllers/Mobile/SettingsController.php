<?php
namespace App\Http\Controllers\Mobile;


use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class SettingsController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function view() {
        $agent = new Agent();
        $data['title'] = \Lang::get('titles.profile');
        if($agent->isMobile()) {
            return view('mobile.profile.settings', $data);
        }
        else {
            abort(404);
        }
    }

}
