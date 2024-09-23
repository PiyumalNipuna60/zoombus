<?php

namespace App\Http\Controllers\Pages;

use App\Http\Controllers\Controller;
use App\Page;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\LaravelLocalization;

class FAQsController extends Controller
{
    public function viewAll() {
        $agent = new Agent();
        if(!$agent->isMobile()) {
            $data = Controller::essentialVars();
            $faqs = Page::with('translated')->whereInFaq(true)->get();
            if ($faqs) {
                foreach ($faqs->toArray() as $key => $faq) {
                    $data['allFAQs'][$key]['url'] = (isset($faq['slug'])) ? (new LaravelLocalization())->getLocalizedURL(null, $faq['slug']) : route($faq['route_name']);
                    $data['allFAQs'][$key]['seo_title'] = $faq['translated']['seo_title'];
                }
            }
            return view('faqs', $data);
        }
        else {
            return view('mobile.main');
        }
    }
}
