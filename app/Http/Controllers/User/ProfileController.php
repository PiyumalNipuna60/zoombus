<?php

namespace App\Http\Controllers\User;

use App\Country;
use App\Driver;
use App\Gender;
use App\Http\Controllers\Controller;
use App\Http\Controllers\ValidationController;
use App\Partner;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager as Image;
use Jenssegers\Agent\Agent;

class ProfileController extends ValidationController
{
    public function __construct() {
        parent::__construct();
        $agent = new Agent();
        if(!$agent->isMobile()) {
            $this->middleware('customer');
        }
    }

    public function store(array $data, $user = null) {
        if(isset($data['name'])) {
            $data['name'] = ValidationController::removeCommas($data['name']);
        }
        return User::current($user)->update($data);
    }

    protected function validator($data, $validate, $isWizard = false, $locale = null) {
        if($validate == 'profile') {
            if($isWizard) {
                $req = 'required|';
            }
            else {
                $req = 'nullable|';
            }
            $fields = [
                'email' => 'required|email|'.Rule::unique('users')->ignore(\Auth::user()->id),
                'country_id' => 'required|'.Rule::exists('countries','id'),
                'birth_date' => $req.'date|before:'.Carbon::now()->subYears(18)->format('Y-m-d'),
                'id_number' => $req.Rule::unique('users')->ignore(\Auth::user()->id),
                'city' => 'required|string',
                'gender_id' => 'required|'.Rule::exists('genders','id'),
                'name' => 'required|string|max:255',
                'phone_number' => 'sometimes|required|phone:AUTO|'.Rule::unique('users')->ignore(\Auth::user()->id),
                'profilePicture' => 'image|mimes:jpeg,png,jpg,svg|max:10000|dimensions:min_width=125,min_height=125,max_width=8000,max_height=8000,ratio=1'
            ];
        }
        else if ($validate == 'password') {
            $fields = [
                'old_password' => 'required|current_password',
                'password' => 'required|min:5|confirmed'
            ];
        }
        else if ($validate == 'avatar') {
            $fields = [
                'avatar' => 'required|image|mimes:jpeg,png,jpg,svg|max:10000|dimensions:min_width=150,min_height=150,max_width=8000,max_height=8000',
            ];
        }

        $validator = \Validator::make($data, $fields);

        if(!empty($locale)) {
            $validator->getTranslator()->setLocale($locale);
        }

        return $validator;
    }


    public function profile(Request $request) {
        $assignable = ['email','country_id','birth_date','id_number','gender_id','city','name','phone_number'];
        if(\Auth::user()->status == 1) : unset($assignable[array_search('phone_number', $assignable)]); endif;
        $data = $request->only($assignable);
        if($data['birth_date'] == 'Invalid date') {
            unset($data['birth_date']);
        }
        $response = ValidationController::response($this->validator($data,'profile', (Driver::current()->exists() || Partner::current()->exists()) ? true : false), \Lang::get('auth.profile_updated'));
        if($response->original['status'] == 1) {
            $this->store($data);
        }
        return response()->json($response->original);
    }

    public function password(Request $request) {
        $assignable = ['old_password','password','password_confirmation'];
        $data = $request->only($assignable);
        $response = ValidationController::response($this->validator($data,'password'), \Lang::get('validation.password_updated'));
        if($response->original['status'] == 1) {
            $pass['password'] = Hash::make($data['password']);
            $this->store($pass);
        }
        return response()->json($response->original);
    }

    protected function avatarAction($image, $userId) {
        $imageMake = new Image();
        // 200x200 resize
        $image_original = $imageMake->make($image->getRealPath());
        $image_original->orientate();
        $image_original->resize(200, 200);
        Storage::disk('s3')->put('users/'. $userId.'.'.$image->extension(), $image_original->encode());
        // 30x30 resize
        $image_resize = $imageMake->make($image->getRealPath());
        $image_resize->orientate();
        $image_resize->resize(30, 30);
        Storage::disk('s3')->put('users/small/'. $userId.'.'.$image->extension(), $image_resize->encode());
        User::whereId($userId)->update(['extension' => $image->extension()]);
    }

    public function avatar(Request $request) {
        $assignable = ['avatar'];
        $data = $request->only($assignable);
        $image = $request->file('avatar');
        $userId = $request->user()->id;
        $response = ValidationController::response($this->validator($data,'avatar'));
        if($response->original['status'] == 1) {
            $this->avatarAction($image, $userId);
        }
        return response()->json($response->original);
    }

    public function removeAvatar() {
        if(\Auth::user()->photoExists()) {
            Storage::disk('s3')->delete('users/'.Auth::user()->id.'.'.Auth::user()->extension);
            Storage::disk('s3')->delete('users/small/'.Auth::user()->id.'.'.Auth::user()->extension);
            $response = array('status' => 1, 'text' => \Lang::get('auth.image_removed'));
        }
        else {
            $response = array('status' => 0, 'text' => \Lang::get('auth.image_not_removed'));
        }
        return response()->json($response);
    }

    public function viewProfile(){
        $agent = new Agent();
        if($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.edit_profile');
            return view('mobile.profile.edit', $data);
        }
        else {
            $data = Controller::essentialVars();
            if(!$agent->isMobile() && \Auth::user()->status == 1 ) : $data['disable_phone'] = true; endif;
            $countries = Country::with('translated')->get()->toArray();
            foreach($countries as $key => $val) {
                $data['countries'][$key]['id'] = $val['id'];
                $data['countries'][$key]['name'] = $val['translated']['name'];
            }
            $genders = Gender::with('translated')->get()->toArray();
            foreach($genders as $key => $val) {
                $data['genders'][$key]['id'] = $val['id'];
                $data['genders'][$key]['name'] = $val['translated']['name'];
            }
            return view('profile.edit', $data);
        }
    }

    public function viewChangePassword(){
        $agent = new Agent();
        if($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.password');
            return view('mobile.profile.change-password', $data);
        } else {
            $data = Controller::essentialVars();
            return view('profile.change-password', $data);
        }
    }



}
