<?php

namespace App\Http\Controllers;


use App\Rating;
use App\RouteTypes;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Routes;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ListingController extends Controller {

    public function __construct() {
        parent::__construct();
    }


    public function sorting(Request $request) {
        $all_route_types = Controller::essentialVars(['all_route_types'])['all_route_types'];
        $sortables = $this->sortables();
        $sort_by = $request->sort_by_act;
        $route_type = $request->route_type;
        $page = $request->page;
        if (!empty($sort_by) && in_array($sort_by, $sortables)) {
            if ($request->page > 1) {
                if (in_array($route_type, array_column($all_route_types, 'key'))) {
                    $response = ['status' => 1, 'text' => route('listings_rt_order_page', ['sort_by' => $sort_by, 'route_type' => $route_type, 'page' => $page])];
                } else {
                    $response = ['status' => 1, 'text' => route('listings_order_page', ['sort_by' => $sort_by, 'page' => $page])];
                }
            } else {
                if (in_array($route_type, array_column($all_route_types, 'key'))) {
                    $response = ['status' => 1, 'text' => route('listings_rt_order', ['sort_by' => $sort_by, 'route_type' => $route_type])];
                } else {
                    $response = ['status' => 1, 'text' => route('listings_order', ['sort_by' => $sort_by])];
                }
            }

        }

        return response()->json($response);
    }

    public function chooseSeat(Request $request) {
        $gender = Controller::essentialVars(['gender'])['gender'];
        $data = $request->only(['seat_number', 'number', 'preserving']);
        $data['gender'] = $gender;
        if ($data['number'] != 1) {
            unset($data['number']);
        } else {
            if (\Auth::check()) {
                $data['current_user'] = 1;
            }
        }
        return view('components.booking-seat', $data)->render();
    }


    public function sortables() {
        return [
            'price_asc', 'price_desc', 'rating_asc', 'rating_desc', 'date'
        ];
    }


    public function singleItem(Request $request) {
        $skip = $request->zSkip;
        $type = $request->zType;

        //Search Variables
        $searchData = array_filter($request->only(['route_type', 'from', 'to', 'passengers', 'departure_date', 'return_date', 'sort_by']));
        if (isset($searchData['departure_date']) && $searchData['departure_date'] == "Invalid date") {
            unset($searchData['departure_date']);
        }

        if (isset($searchData['return_date']) && $searchData['return_date'] == "Invalid date") {
            unset($searchData['return_date']);
        }

        $data = Controller::essentialVars(['all_route_types']);

        $perPage = config('app.listings_per_page');
        $data['perPage'] = $perPage;

        $sort_by = $request->sort_by;


        if (!empty($request->route_type)) {
            if (in_array($request->route_type, array_column($data['all_route_types'], 'slug'))) {
                $data['route_type'] = [$data['all_route_types'][array_search($request->route_type, array_column($data['all_route_types'], 'slug'))]['id']];
            } else {
                $data['route_type'] = null;
            }
        } else if (empty($request->route_type_array)) {
            $data['route_type'] = [1, 2];
        }


        if (!empty($request->route_type_array)) {
            foreach ($request->route_type_array as $k => $rta) {
                if (in_array($rta, array_column($data['all_route_types'], 'id'))) {
                    $data['route_type'][$k] = $rta;
                }
            }
        }


        $query = Routes::with([
            'vehicles',
            'vehicles.manufacturers:id,name as manufacturer_name',
            'citiesFrom:id,code as city_code,extension',
            'citiesFrom.translated',
            'citiesFrom.translateToEn',
            'citiesTo',
            'citiesTo.translated',
            'citiesTo.translateToEn',
            'addressFrom:id',
            'addressFrom.translated',
            'addressTo:id',
            'addressTo.translated',
            'vehicles.specifications',
            'vehicles.specifications.translated',
            'ratingsLimited:user_id,driver_user_id,rating,comment',
            'ratingsLimited.user:id,name',
            'currency:id,key as currency_key',
            'user.driver',
            'remainingSeats'
        ])->withCount(['ratings as average_rating' => function ($query) {
            $query->select(DB::raw('avg(rating)'));
        }])->whereHas('user.driver', function ($q) {
            $q->where('status', 1);
        })->whereHas('vehicles', function ($q) {
            $q->where('status', 1);
        })->nowOrFuture()->active($searchData['departure_date'] ?? null)->passengers($searchData['passengers'] ?? 1);


        if (isset($searchData['departure_date']) || isset($searchData['return_date'])) {
            $query->where(function ($q) use ($searchData) {
                if (isset($searchData['departure_date'])) {
                    $q->departure($searchData['departure_date'], $searchData['from'] ?? null, $searchData['to'] ?? null);
                }

                if (isset($searchData['return_date'])) {
                    $q->returns($searchData['return_date'], $searchData['to'] ?? null, $searchData['from'] ?? null);
                }
            });
        } else if (!empty($searchData['from']) || !empty($searchData['to'])) {
            if (!empty($searchData['from'])) {
                $query->places('citiesFrom.translate', $searchData['from']);
            }
            if (!empty($searchData['to'])) {
                $query->places('citiesTo.translate', $searchData['to']);
            }
        }


        if (!empty($data['route_type'])) {
            $query->whereHas('vehicles', function ($qu) use ($data) {
                $qu->whereIn('type', $data['route_type']);
            });
        }

        $this->sortBy($query, $sort_by);


        $data['total_results'] = $query->count();


        $results = $query->skip($skip)->limit($perPage)->get();

        if ($results->count() > 0) {
            $results = $results->toArray();
            foreach ($results as $key => $val) {
                $results[$key]['ratings_limited'] = collect($val['ratings_limited'])->take(4);

                $vehicleImages = json_decode($val['vehicles']['images_extension'], true);
                foreach($vehicleImages as $k => $image) {
                    if (Storage::disk('s3')->exists('drivers/vehicles/'.$val['vehicle_id'].'/'.$image)) {
                        $data['results']['vehicle_images'][$k] = Storage::temporaryUrl('drivers/vehicles/'.$val['vehicle_id'].'/'.$image, now()->addMinutes(5));
                    }
                }
            }
            $data['results'] = $results;
            $data['skip'] = $skip;
        }


        if ($type == 'initial') {
            return $data;
        } else {
            $data['results'] = view('components.listing-item', $data)->render();
            return $data;
        }


    }


    private function sortBy($query, $sort) {
        if (isset($sort)) {
            if ($sort == 'rating_asc') {
                $query->orderBy('average_rating', 'asc');
            } else if ($sort == 'rating_desc') {
                $query->orderBy('average_rating', 'desc');
            } else if ($sort == 'price_desc') {
                $query->orderBy('price', 'desc');
            } else if ($sort == 'price_asc') {
                $query->orderBy('price', 'asc');
            } else {
                $query->orderBy('departure_date', 'asc');
            }
        } else {
            $query->orderBy('departure_date', 'asc');
        }
    }


    public function viewAll(Request $request) {
        $data = Controller::essentialVars();
        $data['page'] = $request->page ?? 1;
        $perPage = config('app.listings_per_page');
        $skip = ($data['page'] - 1) * $perPage;

        $sortables = $this->sortables();
        $sort_by = $request->sort_by;
        if (!empty($sort_by) && !in_array($sort_by, $sortables)) {
            abort(404);
        }
        $data['sorts'] = [
            ['id' => 'price_desc', 'name' => \Lang::get('misc.price_desc')],
            ['id' => 'price_asc', 'name' => \Lang::get('misc.price_asc')],
            ['id' => 'rating_desc', 'name' => \Lang::get('misc.rating_desc')],
            ['id' => 'rating_asc', 'name' => \Lang::get('misc.rating_asc')],
        ];

        if (!empty($request->route_type)) {
            if (in_array($request->route_type, array_column($data['all_route_types'], 'slug'))) {
                $data['route_type'] = [$data['all_route_types'][array_search($request->route_type, array_column($data['all_route_types'], 'slug'))]['id']];
            } else {
                abort(404);
            }
        } else {
            $data['route_type'] = [1, 2];
        }

        $data['route_types'] = RouteTypes::with('translated')->get()->toArray();


        $data['sort_by'] = $request->sort_by;
        $data['route_type_param'] = $request->route_type;

        // redirect to main page if page is strictly set to 1 as url parameter to avoid duplicate page
        if ($request->page == 1) {
            if (!empty($request->route_type)) {
                return redirect()->route('listings_rt', ['route_type' => $request->route_type]);
            } else {
                return redirect()->route('listings');
            }
        }

        $request->zSkip = $skip;
        $request->zType = 'initial';

        return view('listings', array_merge($data, $this->singleItem($request)));
    }

    public function view(Request $request) {
        $id = $request->id;
        $departure_date = $request->departure_date;
        $from = $request->from;
        $to = $request->to;

        if (empty($id) || empty($departure_date) || empty($from) || empty($to)) {
            abort(404);
        } else {
            $expDate = explode('-', $departure_date);
            foreach ($expDate as $eD) {
                if (strlen($eD) < 2) {
                    abort(404);
                }
            }
        }


        $data = Controller::essentialVars();
        $query = Routes::with([
            'vehicles',
            'vehicles.manufacturers:id,name as manufacturer_name',
            'citiesFrom:id,code as city_code,extension',
            'citiesFrom.translated',
            'citiesFrom.translateToEn',
            'citiesTo',
            'citiesTo.translated',
            'citiesTo.translateToEn',
            'addressFrom:id',
            'addressFrom.translated',
            'addressTo:id',
            'addressTo.translated',
            'vehicles.fullspecifications',
            'vehicles.fullspecifications.translated',
            'ratings:user_id,driver_user_id,rating,comment',
            'ratings.user:id,name',
            'currency:id,key as currency_key',
            'sales.users',
            'sales.users.gender',
            'reservedSeats',
            'reservedSeats.gender'
        ])->with(['sales' => function ($q) {
            $q->status([1, 3]);
        }])->withCount(['ratings as average_rating' => function ($query) {
            $query->select(DB::raw('avg(rating)'));
        }])->whereHas('citiesFrom.translateToEn', function ($q) use ($from) {
            $q->whereRaw('LOWER(name) = ?', [$from]);
        })->whereHas('citiesTo.translateToEn', function ($q) use ($to) {
            $q->whereRaw('LOWER(name) = ?', [$to]);
        })->whereHas('user.driver', function ($q) {
            $q->where('status', 1);
        })->whereHas('vehicles', function ($q) {
            $q->where('status', 1);
        })->whereId($id)->nowOrFuture()->active($departure_date)->departure($departure_date);


        if (!$query->exists()) {
            abort(404);
        }


        $data['result'] = $query->first()->toArray();


        $start = count($data['result']['sales']);
        foreach ($data['result']['reserved_seats'] as $key => $val) {
            $data['result']['sales'][$start]['users']['gender']['key'] = $val['gender']['key'];
            $data['result']['sales'][$start]['seat_number'] = $val['seat_number'];
            $start++;
        }


        $vehicleImages = json_decode($data['result']['vehicles']['images_extension'], true);
        foreach($vehicleImages as $k => $image) {
            if (Storage::disk('s3')->exists('drivers/vehicles/'.$data['result']['vehicle_id'] .'/'.$image)) {
                $data['result']['vehicle_images'][$k] = Storage::temporaryUrl('drivers/vehicles/'.$data['result']['vehicle_id'].'/'.$image, now()->addMinutes(5));
            }
        }

        $data['title'] = $data['result']['cities_from']['translated']['name'] .
            ' - ' . $data['result']['cities_to']['translated']['name'] .
            ' - ' . Carbon::parse($data['result']['departure_date'])->translatedFormat('j\ F Y') .
            ' - ' . $data['result']['cities_from']['city_code'] . $data['result']['id'] .
            ' | Zoombus';

        $dif = strtotime($data['result']['departure_date'] . ' ' . $data['result']['departure_time'] . ':00');
        $dif2 = strtotime($data['result']['arrival_date'] . ' ' . $data['result']['arrival_time'] . ':00');
        $data['tt'] = $dif2 - $dif;


        return view('single-listing', $data);
    }

    public function showMoreRatings(Request $request) {
        $data = $request->only('id', 'skip');
        $getRatingsCount = Rating::with('user')->whereDriverUserId($data['id'])->count();
        if ($getRatingsCount > $data['skip']) {
            $getRatings = Rating::with('user')->whereDriverUserId($data['id'])->orderBy('id', 'desc')->skip($data['skip'])->limit(3);
            if ($getRatingsCount < $data['skip'] + 3) {
                $response['hideShowMore'] = true;
            }
            $reses = $getRatings->get()->toArray();
            $response['results'] = view('components.reviews', ['reviews' => $reses])->render();
            $response['skip'] = $data['skip']+3;
        }
        else {
            $response = false;
        }
        return response()->json($response);
    }
}
