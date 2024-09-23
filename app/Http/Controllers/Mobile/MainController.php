<?php
namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use Jenssegers\Agent\Agent;

class MainController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function view() {
        $agent = new Agent();
        if($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }
        else {
            abort(404);
        }
    }

}
