<?php

namespace App\Http\Controllers\Admin\Misc;

use App\Address;
use App\AddressTranslatable;
use App\CityTranslatable;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ValidationController;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\LaravelLocalization;

class AddressController extends AdminController {
    public function __construct() {
        parent::__construct();
    }

    private function store(array $data) {
        $address = Address::updateOrCreate(['id' => $data['id'] ?? 0], Arr::except($data, 'id'));
        foreach ((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
            AddressTranslatable::updateOrCreate(['address_id' => $data['id'] ?? $address->id ?? 0, 'locale' => $keys], ['name' => $data['translate_' . $keys]]);
        }
        return $address;
    }


    public function delete(Request $request) {
        $d = $request->only('id');
        AddressTranslatable::where('address_id', $d['id'])->delete();
        Address::where('id', $d['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_address_delete')]);
    }

    protected function validator($data, $mode = 'add') {
        if ($mode == 'edit') {
            $fields['id'] = 'required|integer';
        }
        foreach (Arr::except($data, ['city_id', 'user_id', 'id']) as $key => $val) {
            if ($mode == 'edit') {
                $unique = Rule::unique('address_translatables', 'name')->where('locale', explode('_', $key)[1])->ignore($data['id'], 'address_id');
            } else {
                $unique = Rule::unique('address_translatables', 'name')->where('locale', explode('_', $key)[1]);
            }
            $fields[$key] = 'required|string|' . $unique;
        }
        return \Validator::make($data, $fields);
    }


    private function action($request, $mode) {
        $fillables = Arr::except((new Address())->fillable, [array_search('user_id', (new Address())->fillable)]);
        foreach ((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
            $fillables[] = 'translate_' . $keys;
        }
        if ($mode == 'edit') {
            $fillables[] = 'id';
        }

        $data = $request->only($fillables);
        $response = ValidationController::response($this->validator($data, $mode), Lang::get('alerts.successfully_added'));
        if ($response->original['status'] == 1) {
            $cityTranslatable = CityTranslatable::where('name', $data['city_id'])->first('city_id');
            $data['city_id'] = ($cityTranslatable) ? $cityTranslatable->city_id : 0;
            $this->store($data);
        }
        return response()->json($response->original);
    }

    public function add(Request $request) {
        return $this->action($request, 'add');
    }

    public function edit(Request $request) {
        return $this->action($request, 'edit');
    }

    public function viewData() {
        $addresses = Address::with('user', 'city', 'city.translated', 'city.country', 'city.country.translated', 'translate')->get()->toArray();

        foreach ($addresses as $key => $val) {
            $actions = [
                [
                    'url' => route('admin_address_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_address_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_address_delete'),
                        'error-msg' => Lang::get('alerts.error_address_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
            ];

            foreach((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
                $addresses[$key]['translate_'.$keys] = $val['translate'][array_search($keys, array_column($val['translate'], 'locale'))]['name'];
            }
            $addresses[$key]['user_id'] = $val['user']['name'];
            $addresses[$key]['city_id'] = ($val['city']) ? $val['city']['translated']['name'] . ', ' . $val['city']['country']['translated']['name'] : null;
            $addresses[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($addresses)->rawColumns(['actions'])->toJson();
    }

    public function view() {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.addresses');
        $data['ajaxUrl'] = route('admin_address_data');
        $data['columns'] = ['id'];
        foreach((new LaravelLocalization)->getSupportedLocales() as $key => $val) {
            $data['columns'][] = 'translate_'.$key;
        }
        foreach (Arr::except((new Address())->fillable, [array_search('user_id', (new Address())->fillable)]) as $val) {
            $data['columns'][] = $val;
        }
        $data['columns'][] = 'actions';
        $data['columnDefs'] = [
            ['className' => 'text-center', 'targets' => [0]],
            ['className' => 'language_ge', 'targets' => [2]]
        ];
        $data['addNew'] = [
            'url' => route('admin_address_add'),
            'anchor' => Lang::get('admin.add_new')
        ];
        return view('admin.pages.dataTables', $data);
    }


    public function viewAdd() {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.address_add');
        $data['ajaxUrl'] = route('admin_address_add_action');
        $data['typeahead'] = true;

        foreach ((new LaravelLocalization())->getSupportedLocales() as $key => $val) {
            $cData['translate_' . $key] = [];
        }
        $cData['city_id'] = [
            'typeahead' => [
                'remote' => '/'.$data['current_locale'].'/search/cities/%QUERY.json',
                'display' => 'name'
            ],
            'addon' => 'typeahead'
        ];
        $data['fields'] = $this->fields($cData, (new Address())->fillable, (new Address())->nonFields);
        return view('admin.pages.add-edit', $data);
    }

    public function viewEdit($id) {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.address_edit');
        $data['ajaxUrl'] = route('admin_address_edit_action');
        $data['typeahead'] = true;

        $addressData = Address::with('translate', 'city', 'city.translated')->where('id', $id)->first();
        if ($addressData) :
            $cData = $addressData->toArray();
            $cData['id'] = ['value' => $cData['id'], 'readonly' => true];
            $cData['city_id'] = [
                'typeahead' => [
                    'remote' => '/'.$data['current_locale'].'/search/cities/%QUERY.json',
                    'display' => 'name'
                ],
                'addon' => 'typeahead',
                'value' => $cData['city']['translated']['name']
            ];
            foreach ((new LaravelLocalization())->getSupportedLocales() as $key => $val) {
                $cData['translate_' . $key] = ['value' => $cData['translate'][array_search($key, array_column($cData['translate'], 'locale'))]['name']];
            }
        endif;
        $data['fields'] = $this->fields($cData, (new Address())->fillable, (new Address())->nonFields);
        return view('admin.pages.add-edit', $data);
    }
}
