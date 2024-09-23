<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Partners\GeneratorController;
use App\Partner;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;

class RegisterAsPartnerController extends RegisterController {

    public function __construct() {
        parent::__construct();
        if(!(new Agent())->isMobile()) {
            $this->middleware('customer')->only('viewLoggedIn', 'loggedIn');
            $this->middleware('not_partner')->only('viewLoggedIn', 'loggedIn');
        }
    }

    public static function store(array $data) {
        User::where('id', $data['partner']['user_id'])->update($data['user']);
        GeneratorController::generate($data['partner']['user_id']);
        return Partner::create($data['partner']);
    }

    protected function validator(array $data, array $addition = null, $ignoreCaptcha = false, $ignorePassword = false) {
        $addition = [
            'country_id' => 'required|' . Rule::exists('countries', 'id'),
            'id_number' => 'nullable|unique:users',
        ];
        $fields = array_merge($addition, $this->validatorFields());

        return \Validator::make($data, $fields);
    }

    public function __invoke(Request $request) {
        $response = $this->invokeDriverPartner($request, 'partner')['response'];
        $data = $this->invokeDriverPartner($request, 'partner')['data'];
        if ($response->original['status'] == 1) {
            $data['user']['password'] = Hash::make($data['user']['password']);
            $data['user']['locale'] = config('app.locale');
            $reg = RegisterController::store($data['user']);
            $data['partner']['user_id'] = $reg->id;
            $this->store($data);
        }
        return response()->json($response->original);
    }


    public function loggedIn(Request $request) {
        $userId = ($request->mobile) ? $request->user()->id : \Auth::user()->id;
        if(!Partner::whereUserId($userId)->exists()) {
            $data['user']['affiliate_code'] = ($request->mobile) ? $request->user()->affiliate_code : \Auth::user()->affiliate_code ?? null;
            $data['partner']['user_id'] = $userId;
            $this->store($data);
            $response = ['status' => 1, 'text' => \Lang::get('validation.partner_program_successfully_created')];
        }
        else {
            $response = ['status' => 0, 'text' => \Lang::get('validation.partner_program_already_active')];
        }
        return response()->json($response);
    }


    public function view() {
        return $this->viewPage('partner');
    }

    public function viewLoggedIn() {
        $data = Controller::essentialVars();
        return view('partner.registration', $data);
    }

}
