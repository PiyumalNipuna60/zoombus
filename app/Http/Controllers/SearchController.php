<?php

namespace App\Http\Controllers;

use App\RouteTypes;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Mcamara\LaravelLocalization\LaravelLocalization;

class SearchController extends ValidationController {



    public function view(Request $request) {
        if($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }
        $datas = Controller::essentialVars();
        $searchData = array_filter($request->only(['route_type_array', 'from', 'to', 'passengers', 'departure_date', 'return_date', 'sort_by']));
        $data = Arr::collapse([$datas, $searchData]);
        $skip = 0;

        $sortables = (new ListingController())->sortables();
        $sort_by = $request->sort_by;
        if(!empty($sort_by) && !in_array($sort_by, $sortables)) {
            abort(404);
        }
        $data['sorts'] = [
            ['id' => 'price_desc', 'name' => \Lang::get('misc.price_desc')],
            ['id' => 'price_asc', 'name' => \Lang::get('misc.price_asc')],
            ['id' => 'rating_desc', 'name' => \Lang::get('misc.rating_desc')],
            ['id' => 'rating_asc', 'name' => \Lang::get('misc.rating_asc')],
        ];


        if(!empty($searchData['route_type_array'])) {
            foreach($searchData['route_type_array'] as $key => $rta) {
                if(!in_array((int) $rta, array_column($data['all_route_types'], 'id'))) {
                    abort(404);
                }
            }
        }
        else {
            $data['route_type_array'] = array_column($data['all_route_types'], 'id');
        }


        $data['route_types'] = RouteTypes::with('translated')->get()->toArray();


        $request->zSkip = $skip;
        $request->zType = 'initial';

        $data['robots'] = 'noindex, nofollow';

        return view('listings', array_merge($data, (new ListingController())->singleItem($request)));
    }
}
