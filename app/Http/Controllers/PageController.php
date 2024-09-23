<?php

namespace App\Http\Controllers;

use App\Page;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mcamara\LaravelLocalization\LaravelLocalization;

class PageController extends Controller
{
    public function __construct() {
        parent::__construct();
    }

    public function viewBlank(Request $request) {
        $data = Controller::essentialVars();
        return view('page', $data);
    }

    public function view(Request $request) {
        $prs = parse_url((new LaravelLocalization())->getNonLocalizedURL($request->getRequestUri()));
        $slug = ltrim($prs['path'], '/page') ?? null.'?'.$prs['query'] ?? null;
        $data = Controller::essentialVars();
        $pageg = Page::with('translated')->where('slug', $slug)->active()->first();
        if(!$pageg) {
            abort(404);
        }
        else {
            $page = $pageg->toArray();
            $page['title'] = $page['translated']['seo_title'];
            $page['description'] = $page['translated']['seo_description'];
            $page['title_page'] = $page['translated']['title'];
            $page['text_page'] = $page['translated']['text'];
        }
        return view('page', Arr::collapse([$data, $page]));
    }
}
