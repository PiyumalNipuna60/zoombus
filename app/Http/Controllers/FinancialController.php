<?php

namespace App\Http\Controllers;

use App\Financial;
use App\Payouts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;
use Mcamara\LaravelLocalization\LaravelLocalization;

class FinancialController extends ValidationController {
    public function __construct() {
        parent::__construct();
        if(!(new Agent())->isMobile()) {
            $this->middleware('customer')->except('getData','delete');
            $this->middleware('can_edit_financial')->only('edit', 'viewEdit', 'remove', 'activate');
        }
    }


    private function store($data) {
        if (Financial::current($data['user_id'])->where('type', $data['type'])->exists() && !isset($data['id'])) {
            Financial::current($data['user_id'])->update(['status' => 0]);
            Financial::current($data['user_id'])->where('type', $data['type'])->update(['status_child' => 0]);
        }
        if(isset($data['id'])) {
            Financial::whereId($data['id'])->whereUserId($data['user_id'])->update($data);
        }
        else {
            Financial::create($data);
        }
    }

    private function update($data) {
        Financial::where('id', $data['id'])->where('user_id', \Auth::user()->id)->update(Arr::except($data, ['id', 'user_id']));
    }

    protected function validator(array $data, $type = 'type', $userId = null) {
        if ($type == "type") {
            if($userId) {
                $idRule = Rule::exists('financials', 'id')->where('user_id', $userId);
            }
            else {
                $idRule = Rule::exists('financials', 'id');
            }
            $check = [
                'type' => 'required|numeric|' . Rule::in([1, 2, 3]),
                'id' => 'sometimes|required|' . $idRule
            ];
        } else if ($type == "card") {
            $check = [

            ]; //TODO: Credit Card adding with API
        } else if ($type == "paypal") {
            $check = [
                'paypal_email' => 'required|email|unique:financials'
            ];
        } else if ($type == "bank") {
            if(!isset($data['id'])) {
                $appendUnique = 'unique:financials';
            }
            else {
                $appendUnique = '';
            }
            $check = [
                'your_name' => 'required|string',
                'bank_name' => 'required|string',
                'account_number' => 'required|alpha_num|'.$appendUnique,
                'swift' => 'required|alpha_num',
            ];
        }

        return \Validator::make($data, $check);
    }

    private function action($request) {
        $first_vld = ['type'];
        if(isset($request->id)) {
            $first_vld[] = 'id';
        }
        $response_type = ValidationController::response($this->validator($request->only($first_vld), 'type'));
        if ($response_type->original['status'] == 1) {
            if ($request->type == 1) {
                $request_data[] = ''; //TODO: Credit Card adding with API
                $type = "card";
            } else if ($request->type == 2) {
                $request_data[] = 'paypal_email';
                $type = "paypal";
            } else if ($request->type == 3) {
                $request_data[] = 'your_name';
                $request_data[] = 'bank_name';
                $request_data[] = 'account_number';
                $request_data[] = 'swift';
                $type = "bank";
            }

            if (!isset($request->id) && Financial::whereUserId($request->user()->id ?? \Auth::user()->id)->whereType($request->type)->count() >= config('app.max_'.$type)) {
                return response()->json(['status' => 0, 'text' => \Lang::get('validation.can_add_more_than', ['value' => config('app.max_'.$type)])]);
            }

            $data = $request->only($request_data);
            if($request->id) {
                $data['id'] = $request->id;
            }
            $data['user_id'] = $request->user()->id ?? \Auth::user()->id;
            $data['type'] = $request->type;
            $response = ValidationController::response($this->validator($data, $type, $data['user_id']), \Lang::get('validation.successfully_added_financial'));
            if ($response->original['status'] == 1) {
                $this->store($data);

            }
            return response()->json($response->original);
        } else {
            return response()->json($response_type->original);
        }
    }

    public function activate(Request $request) {
        $data = $request->only('id');
        if (Financial::current()->where('id', $data['id'])->exists()) {
            Financial::current()->update(['status' => 0]);
            Financial::where('id', $data['id'])->update(['status' => 1]);
            $response = ['status' => 1, 'text' => \Lang::get('validation.successfully_activated_financial')];
        } else {
            $response = ['status' => 0, 'text' => \Lang::get('validation.method_not_allowed')];
        }
        return response()->json($response);
    }

    public function remove(Request $request) {
        $data = $request->only('id');
        if (Financial::current($request->user()->id ?? null)->where('id', $data['id'])->exists() && !Payouts::whereFinancialId($data['id'])->exists()) {
            if (Financial::current($request->user()->id ?? null)->whereId($data['id'])->whereStatus(1)->exists()) {
                Financial::current($request->user()->id ?? null)->take(1)->update(['status' => 1]);
                Financial::current($request->user()->id ?? null)->take(1)->update(['status_child' => 1]);
            }
            Financial::where('id', $data['id'])->delete();
            $response = ['status' => 1, 'text' => \Lang::get('validation.successfully_removed_financial')];
        } else {
            $response = ['status' => 0, 'text' => \Lang::get('validation.financial_method_cant_be_deleted')];
        }
        return response()->json($response);
    }

    public function add(Request $request) {
        return $this->action($request);
    }

    public function updateAct(Request $request) {
        return $this->action($request);
    }


    public function allFinancialData(Request $request) {
        $finData = Financial::current()->get()->toArray();

        if($request->lang) {
            (new LaravelLocalization())->setLocale($request->lang);
        }

        $data = collect($finData)->map(function ($f) {
            $f['date'] = Carbon::parse($f['created_at'])->translatedFormat('j\ F Y');
            if ($f['type'] == 1) {
                $f['account'] = $f['card_number'];
            } else if ($f['type'] == 2) {
                $f['account'] = $f['paypal_email'];
            } else if ($f['type'] == 3) {
                $f['account'] = $f['account_number'];
            }
            $fData = ['financial_id' => $f['id']];
            if (!$f['status']) {
                $fData['make_default'] = 'yes';
            }
            $f['type'] = view('components.img', ['class' => 'h-12-px', 'src' => \URL::asset('images/financial-types/' . $f['type'] . '.png'), 'alt' => $f['type']])->render();
            $f['action'] = view('components.financial-edit', $fData)->render();

            $f['status'] = view('components.radio', [
                'checked' => $f['status'], 'id' => $f['id'],
                'label' => StatusController::fetch($f['status'], '', [], \Lang::get('statuses/financial'))
            ])->render();
            return $f;
        });

        if ($request->fromPhone) {
            return json_encode($data);
        } else {
            return datatables()->of($data ?? [])->rawColumns(['status', 'action', 'type'])->toJson();
        }

    }


    public function view() {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.financial');
            return view('mobile.profile.financial', $data);
        } else {
            $data = Controller::essentialVars();
            return view('profile.financial', $data);
        }
    }

    public function viewAdd() {
        $agent = new Agent();
        if ($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.financial');
            return view('mobile.profile.financial-add', $data);
        } else {
            $data = Controller::essentialVars();
            for ($i = 1; $i < 13; $i++) {
                $data['valid_till_month']['id'] = $i;
                $data['valid_till_month']['name'] = $i;
            }

            for ($i = Carbon::now()->format('Y'); $i < Carbon::now()->addYears(11)->format('Y'); $i++) {
                $data['valid_till_year']['id'] = $i;
                $data['valid_till_year']['name'] = $i;
            }
            return view('profile.financial-add', $data);
        }


    }

    public function viewEdit($id) {
        $data = Controller::essentialVars();
        $fData = Financial::current()->where('id', $id)->first()->toArray();
        return view('profile.financial-edit', array_merge($data, $fData));
    }
}
