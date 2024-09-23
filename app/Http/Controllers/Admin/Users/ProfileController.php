<?php

namespace App\Http\Controllers\Admin\Users;

use App\Country;
use App\Driver;
use App\Financial;
use App\Gender;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\PayoutsController;
use App\Http\Controllers\Admin\RoutesController;
use App\Http\Controllers\Admin\SalesController;
use App\Http\Controllers\Admin\VehiclesController;
use App\Http\Controllers\StatusController;
use App\Http\Controllers\ValidationController;
use App\Partner;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\ImageManager as Image;

class ProfileController extends AdminController {

    private function updateProfile($data) {
        return User::where('id', $data['id'])->update(Arr::except($data, ['id']));
    }

    protected function validator($data) {
        $fields = [
            'avatar' => 'sometimes|required|image|mimes:jpeg,png,jpg,svg|max:10000|dimensions:min_width=88,min_height=88,max_width=2000,max_height=2000',
            'email' => 'nullable|email|'.Rule::unique('users','email')->ignore($data['id']),
            'country_id' => 'required|' . Rule::exists('countries', 'id'),
            'birth_date' => 'nullable|date|before:'.Carbon::now()->subYears(18)->format('Y-m-d'),
            'id_number' => 'nullable|' . Rule::unique('users')->ignore($data['id']),
            'city' => 'nullable',
            'status' => 'required|integer|'.Rule::in([1,2,3]),
            'gender_id' => 'required|' . Rule::exists('genders', 'id'),
            'name' => 'required|string|max:255',
            'phone_number' => 'required|phone:GE|' . Rule::unique('users')->ignore($data['id']),
            'password' => 'sometimes|required|nullable',
        ];
        return \Validator::make($data, $fields);
    }

    public function profile(Request $request) {
        $assignable = ['id', 'avatar', 'email', 'country_id', 'birth_date', 'id_number', 'gender_id', 'city', 'status', 'name', 'phone_number', 'password'];
        if (!$request->password) {
            unset($assignable[array_search('password', $assignable)]);
        }
        if (!$request->hasFile('avatar')) {
            unset($assignable[array_search('avatar', $assignable)]);
        } else {
            $image = $request->file('avatar');
        }
        $data = $request->only($assignable);
        if($data['birth_date'] == "Invalid date") {
            unset($data['birth_date']);
        }
        $response = ValidationController::response($this->validator($data), \Lang::get('alerts.success_info_update'));
        if ($response->original['status'] == 1) {
            if (!empty($data['password'])) {
                $data['password'] = Hash::make($data['password']);
            }
            if ($request->hasFile('avatar')) {
                $imageMake = new Image();
                // 125x125 resize
                $image_original = $imageMake->make($image->getRealPath());
                $image_original->orientate();
                $image_original->resize(200, 200);
                Storage::disk('s3')->put('users/'.$request->id.'.'.$image->extension(), $image_original->stream());
                // 30x30 resize
                $image_resize = $imageMake->make($image->getRealPath());
                $image_resize->orientate();
                $image_resize->resize(30, 30);
                Storage::disk('s3')->put('users/small/'.$request->id.'.'.$image->extension(), $image_resize->stream());
                User::whereId($request->id)->update(['extension' => $image->extension()]);
            }
            $this->updateProfile(Arr::except($data, ['avatar']));
        }
        return response()->json($response->original);
    }


    public function viewUser($id) {
        $data = $this->essentialVars();
        if (!User::where('id', $id)->exists()) :
            abort(404);
        endif;

        $data['user'] = User::with('payouts')->where('id', $id)->first()->toArray();
        $data['user']['total_withdrawn'] = array_sum(array_column($data['user']['payouts'], 'amount'));
        $data['user']['avatar'] = (new User())->photoById($data['user']['id']);
        $data['isPartner'] = Partner::where('user_id', $id)->exists();
        $data['isDriver'] = Driver::where('user_id', $id)->exists();
        if (isset($data['user'])) :
            $countries = Country::with('translated')->get()->toArray();
            $genders = Gender::with('translated')->get()->toArray();
            $data['user']['the_type'][] = \Lang::get('misc.user');
            $data['user']['id'] = ['value' => $data['user']['id'], 'readonly' => true];
            $data['user']['affiliate_code'] = ['value' => $data['user']['affiliate_code'], 'readonly' => true];
            $data['user']['country_id'] = ['value' => $data['user']['country_id'], 'values' => $countries, 'field' => 'select', 'class' => 'form-control select',];
            $data['user']['gender_id'] = ['value' => $data['user']['gender_id'], 'values' => $genders, 'field' => 'select', 'class' => 'form-control select',];
            $data['user']['password'] = ['type' => 'password', 'value' => ''];
            $data['user']['status'] = [
                'values' => [
                    [
                        'id' => 1,
                        'name' => \Lang::get('status.active'),
                    ],
                    [
                        'id' => 2,
                        'name' => \Lang::get('status.pending'),
                    ],
                    [
                        'id' => 3,
                        'name' => \Lang::get('status.suspended'),
                    ]
                ],
                'field' => 'select',
                'class' => 'form-control select',
                'value' => $data['user']['status'],
            ];
            $data['user']['birth_date'] = ['addon' => 'datepickerb', 'value' => (isset($data['user']['birth_date'])) ? Carbon::parse($data['user']['birth_date'])->format('j F Y') : null];
            $data['user']['subscribed'] = ['field' => 'select', 'class' => 'form-control select', 'value' => $data['user']['subscribed'], 'values' => [['id' => 1, 'name' => 'Yes'], ['id' => 2, 'name' => 'No']]];

        endif;
        if ($data['isPartner']) : $data['user']['the_type'][] = \Lang::get('driver.partner'); endif;
        if ($data['isDriver']) :
            $data['user']['the_type'][] = \Lang::get('driver.driver');
            $data['driver'] = Driver::where('user_id', $id)->first()->toArray();
            $data['driverInfo'] = User::withCount(['vehicles' => function ($q) {
                $q->where('status', 1);
            }])->withCount(['sales' => function ($q) {
                $q->status([1, 3]);
            }])->where('id', $id)->first()->toArray();
            $data['driver']['statuses'] = StatusController::fetchText([
                0 => \Lang::get('admin.status_no_info'),
                1 => \Lang::get('admin.status_approved'),
                2 => null,
                3 => \Lang::get('admin.status_suspended'),
            ]);
        endif;
        //editable fields for profile
        $data['fields'] = $this->fields($data['user'], (new User())->fillable, (new User())->nonFields);
        $data['financials'] = Financial::where('user_id', $id)->get()->toArray();

        $data['sales'] = [
            'title' => Lang::get('admin_menu.sales'),
            'ajaxUrl' => route('admin_sale_data'),
            'ajaxData' => ['user_id' => $id],
            'columns' => (new SalesController())->viewDataColumns(),
            'dateDefs' => [0]
        ];

        $data['routes'] = [
            'title' => Lang::get('admin_menu.routes'),
            'ajaxUrl' => route('admin_route_data'),
            'ajaxData' => ['user_id' => $id],
            'columns' => (new RoutesController())->viewDataColumns(),
            'dateDefs' => [4,5]
        ];

        $data['vehicles'] = [
            'title' => Lang::get('admin_menu.vehicles'),
            'ajaxUrl' => route('admin_vehicle_data'),
            'ajaxData' => ['user_id' => $id],
            'columns' => (new VehiclesController())->viewDataColumns()
        ];

        $data['withdrawals'] = [
            'title' => Lang::get('admin_menu.withdrawals'),
            'ajaxUrl' => route('admin_payout_data'),
            'ajaxData' => ['user_id' => $id],
            'columns' => (new PayoutsController())->viewDataColumns(),
            'dateDefs' => [5]
        ];

        $data['partners'] = [
            'title' => Lang::get('admin_menu.partners'),
            'ajaxUrl' => route('admin_partners_user_data'),
            'ajaxData' => ['user_id' => $id],
            'columns' => (new PartnersController())->viewUserDataColumns(),
        ];


        $data['partnerSales'] = [
            'title' => Lang::get('admin_menu.partner_sales'),
            'ajaxUrl' => route('admin_partners_sale_data'),
            'ajaxData' => ['user_id' => $id],
            'columns' => (new SalesController())->viewDataColumnsPartner(),
            'dateDefs' => [1]
        ];


        return view('admin.pages.edit-user', $data);
    }
}
