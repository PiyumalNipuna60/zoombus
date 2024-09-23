<?php

namespace App\Http\Controllers;

use App\Rating;
use Carbon\Carbon;
use App\Sales;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;

class RatingController extends ValidationController
{
    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $this->middleware('customer');
            $this->middleware('can_rate')->except('rate');
        }
    }

    private function store($data) {
        return Rating::updateOrCreate(Arr::only($data, ['sale_id','user_id']), Arr::except($data, ['sale_id','user_id']));
    }

    private function validator($data) {
        $fields = [
            'sale_id' => 'required|integer|'.
                Rule::exists('sales','id')->where('status', 3)->where('user_id', \Auth::user()->id),
            'rating' => 'required|integer|between:1,5',
            'comment' => 'nullable|string',
        ];
        return \Validator::make($data, $fields);
    }

    public function rate(Request $request) {
        $data = $request->only(['sale_id','rating','comment']);
        $response = ValidationController::response($this->validator($data), \Lang::get('validation.successfully_sent_rating'));
        if($response->original['status'] == 1) {
            if(Rating::whereUserId(\Auth::user()->id)->whereSaleId($data['sale_id'])->where('created_at', '<=', Carbon::now()->subDay()->format('Y-m-d H:i:s'))->exists()) {
                return response()->json(['status' => 0, 'text' => \Lang::get('validation.cant_leave_review')]);
            }
            $driverId = Sales::with('routes:id,user_id')->where('id', $data['sale_id'])->first();
            $data['driver_user_id'] = ($driverId) ? $driverId->toArray()['routes']['user_id'] : 0;
            $data['user_id'] = \Auth::user()->id;
            $this->store($data);
        }
        return response()->json($response->original);
    }

    public function view($id) {
        $agent = new Agent();
        if (!$agent->isMobile()) {
            $data = Controller::essentialVars();
            $data['result'] =
                Sales::whereHas('routes')->whereRaw('md5(id) = ?', [$id])->where('status', 3)->current()
                    ->with([
                        'rating',
                        'routes.vehicles:id,manufacturer,model,license_plate,number_of_seats,type',
                        'routes.vehicles.manufacturers:id,name as manufacturer_name',
                        'routes.citiesFrom:id,code as city_code,extension',
                        'routes.citiesFrom.translated',
                        'routes.citiesTo',
                        'routes.citiesTo.translated',
                        'routes.addressFrom:id',
                        'routes.addressFrom.translated',
                        'routes.addressTo:id',
                        'routes.addressTo.translated',
                        'routes.ratings:user_id,driver_user_id,rating,comment',
                        'routes.ratings.user:id,name',
                        'routes.currency:id,key as currency_key',
                    ])->with(['routes' => function($query) {
                        $query->withCount(['ratings as average_rating' => function ($q) {
                            $q->select(DB::raw('avg(rating)'));
                        }]);
                    }])->first()->toArray();
            return view('rating', $data);
        }
        else {
            $data['title'] = \Lang::get('titles.main');
            return view('mobile.main', $data);
        }

    }

}
