<?php

namespace App\Http\Controllers\Driver;

use App\Driver;
use App\Http\Controllers\Controller;
use App\Http\Controllers\DriverController;
use App\Http\Controllers\ValidationController;
use App\SupportTicketMessages;
use Hashids\Hashids;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Jenssegers\Agent\Agent;

class LicenseController extends DriverController {
    public function __construct() {
        parent::__construct();
    }

    public function view(Request $request) {
        $agent = new Agent();
        if($agent->isMobile()) {
            $data['title'] = \Lang::get('titles.license');
            return view('mobile.main', $data);
        } else {
            $data = Controller::essentialVars();
            $hashids = new Hashids('', 16);
            if(isset($request->ticket) && isset($request->message) &&
                SupportTicketMessages::whereTicketId($hashids->decode($request->ticket)[0])->whereId($hashids->decode($request->message)[0])->exists()
            ) {
                $data['ticketId'] = $request->ticket;
                $data['latestMessage'] = $request->message;
                $data['message'] = SupportTicketMessages::whereTicketId($hashids->decode($request->ticket)[0])->whereId($hashids->decode($request->message)[0])->first('message')->message;
            }
            $data['license_number'] = Driver::current()->pluck('license_number')->first();
            $data['front_side'] = $this->frontSide();
            $data['back_side'] = $this->backSide();
            $data['status'] = Driver::current()->pluck('status')->first();
            return view('driver.license', $data);
        }
    }


    protected function validator($data) {
        $fields = [
            'license_number' => 'required|alpha_num|' . Rule::unique('drivers')->ignore(\Auth::user()->id, 'user_id'),
            'front_side' => 'sometimes|required|image|mimes:jpeg,jpg,png|max:10000|dimensions:min_width=200,min_height=200,max_width=8000,max_height=8000',
            'back_side' => 'sometimes|required|image|mimes:jpeg,jpg,png|max:10000|dimensions:min_width=200,min_height=200,max_width=8000,max_height=8000',
        ];
        return \Validator::make($data, $fields);
    }

    protected function frontSide($id = null) {
        return $this->licenseFrontSide($id);
    }

    protected function backSide($id = null) {
        return $this->licenseBackSide($id);
    }


    public function deleteFront() {
        if($this->frontSide() && Auth::user()->driver()->first()->status != 1) {
            $driver = Driver::whereUserId(Auth::user()->id)->first('front_side_extension');
            Storage::disk('s3')->delete('drivers/license/'.\Auth::user()->id.'/front_side.'.$driver->front_side_extension);
            $updateable['status'] = 0;
            $this->store($updateable);
            $response = array('status' => 1, 'text' => \Lang::get('auth.image_removed'));
        }
        else {
            $response = array('status' => 0, 'text' => \Lang::get('auth.image_not_removed'));
        }
        return response()->json($response);
    }

    public function deleteBack() {
        if($this->backSide() && Auth::user()->driver()->first()->status != 1) {
            $driver = Driver::whereUserId(Auth::user()->id)->first('back_side_extension');
            Storage::disk('s3')->delete('drivers/license/'.\Auth::user()->id.'/back_side.'.$driver->back_side_extension);
            $updateable['status'] = 0;
            $this->store($updateable);
            $response = array('status' => 1, 'text' => \Lang::get('auth.image_removed'));
        }
        else {
            $response = array('status' => 0, 'text' => \Lang::get('auth.image_not_removed'));
        }
        return response()->json($response);
    }

    public function __invoke(Request $request) {
        $assignable = ['front_side', 'back_side', 'license_number'];
        $image_front = $request->file('front_side');
        $image_back = $request->file('back_side');

        if($this->frontSide() && !$request->hasFile('front_side')) {
            unset($assignable[array_search('front_side', $assignable)]);
        }
        if($this->backSide() && !$request->hasFile('back_side')) {
            unset($assignable[array_search('back_side', $assignable)]);
        }


        $data = $request->only($assignable);
        if(!$this->frontSide() && !$request->hasFile('front_side')) {
            $data['front_side'] = 1;
        }
        if(!$this->backSide() && !$request->hasFile('back_side')) {
            $data['back_side'] = 1;
        }

        $response = ValidationController::response($this->validator($data), \Lang::get('auth.successfully_updated_sent_to_review'));
        if ($response->original['status'] == 1) {
            if($request->hasFile('front_side')) {
                Storage::disk('s3')->putFileAs('drivers/license/'.$request->user()->id, $image_front, 'front_side.'.$image_front->extension());
                Driver::whereUserId($request->user()->id)->update(['front_side_extension' => $image_front->extension()]);
            }
            if($request->hasFile('back_side')) {
                Storage::disk('s3')->putFileAs('drivers/license/'.$request->user()->id, $image_back, 'back_side.'.$image_back->extension());
                Driver::whereUserId($request->user()->id)->update(['back_side_extension' => $image_back->extension()]);
            }
            //push status and store
            $updateable = $request->only('license_number');
            $updateable['status'] = 2;
            $this->store($updateable);
        }
        return response()->json($response->original);
    }
}
