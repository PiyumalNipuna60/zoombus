<?php

namespace App\Http\Controllers\Api;

use App\Driver;
use App\Http\Controllers\User\ProfileController as PC;
use App\Http\Controllers\ValidationController;
use App\Partner;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class ProfileController extends PC {
    public function __construct() {
        parent::__construct();
    }

    public function avatarSet(Request $request) {
        $assignable = ['avatar'];
        $data = $request->only($assignable);
        $image = $request->file('avatar');
        $userId = $request->user()->id;
        $response = ValidationController::response($this->validator($data,'avatar'));
        if($response->original['status'] == 1) {
            $this->avatarAction($image, $userId);
            $response->original['text'] = Storage::temporaryUrl('users/'.$userId.'.'.$image->extension(), now()->addMinutes(5));
            $responseCode = 200;
        }
        else {
            $responseCode = 422;
        }
        return response()->json($response->original, $responseCode);
    }


    public function update(Request $request) {
        $assignable = ['profilePicture','email', 'country_id', 'birth_date', 'id_number', 'gender_id', 'city', 'name', 'phone_number'];
        if ($request->user()->status == 1) :
            unset($assignable[array_search('phone_number', $assignable)]);
        endif;
        $data = $request->only($assignable);
        if ($data['birth_date'] == 'Invalid date') {
            unset($data['birth_date']);
        }
        $response = ValidationController::response(
            $this->validator($data, 'profile',
                (Driver::current($request->user()->id)->exists() || Partner::current($request->user()->id)->exists()) ? true : false,
                $request->lang),
            \Lang::get('auth.profile_updated'));
        if ($response->original['status'] == 1) {
            $statusCode = 200;
            $this->store(Arr::except($data, ['profilePicture']));
            if($request->hasFile('profilePicture')) {
                $image = $request->file('profilePicture');
                $this->avatarAction($image, $request->user()->id);
            }
            if($request->isWizard) {
                $response->original = ['step' => 2];
            }
            else {
                $response->original = $this->get($request)->original;
            }
        }
        else {
            $statusCode = 422;
        }

        return response()->json($response->original, $statusCode);
    }

    public function get(Request $request) {
        $userData = User::whereId($request->user()->id)->first();
        $userData->avatar = (new User())->photoById($request->user()->id);
        return response()->json($userData);
    }
}
