<?php
namespace App\Http\Controllers\Mobile;


use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class RegisterController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function view() {
        $agent = new Agent();
        $data['title'] = \Lang::get('titles.register');
        if($agent->isMobile()) {
            return view('mobile.register', $data);
        }
        else {
            abort(404);
        }
    }

}
