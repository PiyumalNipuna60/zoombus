<?php
namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Page;
use Illuminate\Http\Request;
use Mcamara\LaravelLocalization\LaravelLocalization;

class FAQsController extends Controller {
    public function __construct() {
        parent::__construct();
    }

    public function get(Request $request) {
        if ($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $data = Page::with('translated')->whereInFaq(true)->get();
        return response()->json($data);
    }
}
