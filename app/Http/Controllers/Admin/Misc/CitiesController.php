<?php

namespace App\Http\Controllers\Admin\Misc;

use App\City;
use App\CityTranslatable;
use App\Country;
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ValidationController;
use App\Routes;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\LaravelLocalization;

class CitiesController extends AdminController {
    public function __construct() {
        parent::__construct();
    }

    private function store(array $data) {
        $city = City::updateOrCreate(['id' => $data['id'] ?? 0], Arr::except($data, 'id'));
        foreach((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
            CityTranslatable::updateOrCreate(['city_id' => $data['id'] ?? $city->id ?? 0, 'locale' => $keys],['name' => $data['translate_'.$keys]]);
        }
        return $city;
    }


    public function delete(Request $request) {
        $d = $request->only('id');
        $extension = City::where('id', $d['id'])->first('extension');
        $this->deleteImage($d['id'], $extension->extension);
        Routes::where('from', $d['id'])->orWhere('to', $d['id'])->delete();
        CityTranslatable::where('city_id', $d['id'])->delete();
        City::where('id', $d['id'])->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_city_delete')]);
    }

    protected function validator($data, $mode = 'add') {
        $fields = [
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,svg|max:10000|dimensions:min_width=100,min_height=100,max_width=2000,max_height=2000',
            'country_id' => 'required|' . Rule::in(array_column(Country::all('id')->toArray(), 'id')),
            'code' => 'required|string',
        ];
        if($mode == 'edit') {
            $fields['id'] = 'required|integer';
        }
        foreach (Arr::except($data, ['image', 'country_id', 'code', 'id']) as $key => $val) {
            if($mode == 'edit') {
                $citUniq = CityTranslatable::whereHas('city', function($q) use ($data) {
                    $q->where('country_id', $data['country_id'] ?? 0);
                })->where('name', $data[$key])->where('city_id', '!=', $data['id'] ?? 0)->where('locale', explode('_', $key)[1])->get();
                if($citUniq) {
                    $unique = Rule::notIn(array_column($citUniq->toArray(), 'name'));
                }
            }
            else {
                $citUniq = CityTranslatable::whereHas('city', function($q) use ($data) {
                    $q->where('country_id', $data['country_id'] ?? 0);
                })->where('name', $data[$key])->where('locale', explode('_', $key)[1])->get();
                if($citUniq) {
                    $unique = Rule::notIn(array_column($citUniq->toArray(), 'name'));
                }
            }
            $fields[$key] = 'required|string|'.$unique;
        }
        return \Validator::make($data, $fields);
    }

    public function deleteImage($id, $extension = 'jpg') {
        if(Storage::disk('s3')->exists('cities/'.$id.'.'.$extension)) {
            Storage::disk('s3')->delete('cities/'.$id.'.'.$extension);
            return response()->json(['status' => 1, 'text' => 'Successfully deleted.']);
        }
        else {
            return response()->json(['status' => 0, 'text' => 'Error deleting. File not found.']);
        }
    }


    private function action($request, $mode) {
        $fillables = (new City())->fillable;
        $fillables[] = 'image';
        foreach((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
            $fillables[] = 'translate_'.$keys;
        }
        if($mode == 'edit') {
            $fillables[] = 'id';
        }
        if (!$request->hasFile('image') && $mode == 'edit') {
            unset($fillables[array_search('image', $fillables)]);
        }

        $image = $request->file('image');

        $data = $request->only($fillables);
        if(!$request->hasFile('image') && $mode == 'add') {
            $data['image'] = 1;
        }
        $response = ValidationController::response($this->validator($data, $mode), Lang::get('alerts.successfully_added'));
        if ($response->original['status'] == 1) {
            if ($mode == 'edit') {
                $id = $data['id'];
                $this->store(Arr::except($data, ['image']));
            } else {
                $create = $this->store(Arr::except($data, ['image']));
                $id = $create->id;
            }
            if ($image) {
                Storage::disk('s3')->putFileAs('cities', $image,  $id.'.' . $image->extension());
                City::whereId($id)->update(['extension' => $image->extension()]);
            }
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
        $cities = City::with('country','country.translated', 'translate')->get()->toArray();

        foreach ($cities as $key => $val) {
            $city = new City();
            $actions = [
                [
                    'url' => route('admin_cities_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_cities_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_city_delete'),
                        'error-msg' => Lang::get('alerts.error_city_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
            ];

            foreach((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
                $cities[$key]['translate_'.$keys] = $val['translate'][array_search($keys, array_column($val['translate'], 'locale'))]['name'];
            }

            $cities[$key]['country_id'] = view('components.img', ['tooltip' => 'yes', 'class' => 'img-fluid', 'title' => $val['country']['translated']['name'], 'src' => $city->countryImageById($val['country']['code'])])->render();
            $cities[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($cities)->rawColumns(['country_id', 'actions'])->toJson();
    }

    public function view() {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.cities');
        $data['ajaxUrl'] = route('admin_cities_data');
        $data['columns'] = ['id'];
        foreach((new LaravelLocalization)->getSupportedLocales() as $key => $val) {
            $data['columns'][] = 'translate_'.$key;
        }
        foreach ((new City())->fillable as $val) {
            $data['columns'][] = $val;
        }
        $data['columns'][] = 'actions';
        $data['columnDefs'] = [
            ['className' => 'text-center', 'targets' => [0]],
            ['className' => 'language_ge', 'targets' => [2]]
        ];
        $data['addNew'] = [
            'url' => route('admin_cities_add'),
            'anchor' => Lang::get('admin.add_new')
        ];
        return view('admin.pages.dataTables', $data);
    }



    public function viewAdd() {
        $data = self::essentialVars();
        $countries = Country::with('translated')->get()->toArray();
        $data['seo_title'] = Lang::get('admin_titles.cities_add');
        $data['ajaxUrl'] = route('admin_cities_add_action');
        $cData = [
            'country_id' => ['values' => $countries, 'field' => 'select', 'class' => 'form-control select'],
        ];
        foreach((new LaravelLocalization())->getSupportedLocales() as $key => $val) {
            $cData['translate_'.$key] = [];
        }
        $cData['image'] = [
            'field' => 'image'
        ];
        $data['fields'] = $this->fields($cData, (new City())->fillable, (new City())->nonFields, true);
        return view('admin.pages.add-edit', $data);
    }

    public function viewEdit($id) {
        $data = self::essentialVars();
        $countries = Country::with('translated')->get()->toArray();
        $data['seo_title'] = Lang::get('admin_titles.cities_edit');
        $data['ajaxUrl'] = route('admin_cities_edit_action');
        $cityData = City::with('translate')->where('id', $id)->first();
        if ($cityData) :
            $cData = $cityData->toArray();
            $cData['id'] = ['value' => $cData['id'], 'readonly' => true];
            $cData['country_id'] = ['values' => $countries, 'field' => 'select', 'class' => 'form-control select', 'value' => $cData['country_id']];
            foreach((new LaravelLocalization())->getSupportedLocales() as $key => $val) {
                $cData['translate_'.$key] = ['value' => $cData['translate'][array_search($key, array_column($cData['translate'], 'locale'))]['name']];
            }
            $cData['image'] = [
                'field' => 'image',
                'image_path' => 'cities/'.$cData['id']['value'].'.'.$cData['extension'],
                'image_ajax' => route('admin_cities_delete_image', ['id' => $cData['id']['value'], 'extension' => $cData['extension'] ?? 'jpg'])
            ];
        endif;
        $data['fields'] = $this->fields($cData, (new City())->fillable, (new City())->nonFields);
        return view('admin.pages.add-edit', $data);
    }
}
