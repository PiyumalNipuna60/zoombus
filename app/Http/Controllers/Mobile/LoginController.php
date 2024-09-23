<?php
namespace App\Http\Controllers\Mobile;


use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class LoginController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function view() {
        $agent = new Agent();
        $data['title'] = \Lang::get('titles.login');
        if($agent->isMobile()) {
            return view('mobile.login', $data);
        }
        else {
            abort(404);
        }
    }

}
