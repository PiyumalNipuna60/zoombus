<?php

namespace App\Http\Controllers\Admin\Misc;


use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\ValidationController;
use App\VehicleSpecs;
use App\VehicleSpecsTranslatable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\LaravelLocalization;

class VehicleSpecsController extends AdminController {
    public function __construct() {
        parent::__construct();
    }

    private function store(array $data) {
        if(!isset($data['id'])) {
            $specs = VehicleSpecs::create();
        }
        else {
            $specs = null;
        }
        foreach((new LaravelLocalization())->getSupportedLocales() as $keys => $vals) {
            VehicleSpecsTranslatable::updateOrCreate(['vehicle_spec_id' => $data['id'] ?? $specs->id ?? 0, 'locale' => $keys],['name' => $data['translate_'.$keys]]);
        }
        return $specs;
    }

    public function delete(Request $request) {
        $d = $request->only('id');
        VehicleSpecsTranslatable::where('vehicle_spec_id', $d['id'])->delete();
        VehicleSpecs::where('id', $d['id'])->delete();
        $this->deleteImage($d['id']);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_specs_delete')]);
    }

    protected function validator($data, $mode = 'add') {
        $fields = [
            'image' => 'sometimes|required|image|mimes:jpeg,png,jpg,svg|max:10000|dimensions:min_width=15,min_height=15,max_width=500,max_height=500',
        ];
        if ($mode == 'edit') {
            $fields['id'] = 'required|integer';
        }
        foreach (Arr::except($data, ['image', 'id']) as $key => $val) {
            if ($mode == 'edit') {
                $unique = Rule::unique('vehicle_specs_translatables', 'name')->where('locale', explode('_', $key)[1])->ignore($data['id'], 'vehicle_spec_id');
            } else {
                $unique = Rule::unique('vehicle_specs_translatables', 'name')->where('locale', explode('_', $key)[1]);
            }
            $fields[$key] = 'required|string|' . $unique;
        }
        return \Validator::make($data, $fields);
    }

    public function deleteImage($id) {
        $extension = VehicleSpecs::whereId($id)->first('extension');
        if (Storage::disk('s3')->exists('vehicle-features/' . $id . '.'.$extension->extension)) {
            Storage::disk('s3')->delete('vehicle-features/' . $id . '.'.$extension->extension);
            return response()->json(['status' => 1, 'text' => 'Successfully deleted.']);
        } else {
            return response()->json(['status' => 0, 'text' => 'Error deleting. File not found.']);
        }
    }


    private function action($request, $mode) {
        $fillables = (new VehicleSpecs())->fillable;
        $fillables[] = 'image';
        foreach ((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
            $fillables[] = 'translate_' . $keys;
        }
        if ($mode == 'edit') {
            $fillables[] = 'id';
        }
        if (!$request->hasFile('image') && $mode == 'edit') {
            unset($fillables[array_search('image', $fillables)]);
        }

        $image = $request->file('image');

        $data = $request->only($fillables);
        if (!$request->hasFile('image') && $mode == 'add') {
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
                Storage::disk('s3')->putFileAs('vehicle-features/', $image, $id.'.'.$image->extension());
                VehicleSpecs::whereId($id)->update(['extension' => $image->extension()]);
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
        $vehicleSpecs = VehicleSpecs::with('translate')->get()->toArray();

        foreach ($vehicleSpecs as $key => $val) {
            $spec = new VehicleSpecs();
            $actions = [
                [
                    'url' => route('admin_specs_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_specs_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_specs_delete'),
                        'error-msg' => Lang::get('alerts.error_specs_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ],
                ],
            ];
            foreach((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
                $vehicleSpecs[$key]['translate_'.$keys] = $val['translate'][array_search($keys, array_column($val['translate'], 'locale'))]['name'];
            }
            $vehicleSpecs[$key]['image'] = view('components.img', ['class' => 'img-fluid', 'src' => $spec->specImageById($val['id'])])->render();
            $vehicleSpecs[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($vehicleSpecs)->rawColumns(['image', 'actions'])->toJson();
    }

    public function view() {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.specs');
        $data['ajaxUrl'] = route('admin_specs_data');
        $data['columns'] = ['id'];
        foreach((new LaravelLocalization)->getSupportedLocales() as $key => $val) {
            $data['columns'][] = 'translate_'.$key;
        }
        foreach ((new VehicleSpecs())->fillable as $val) {
            $data['columns'][] = $val;
        }
        $data['columns'][] = 'image';
        $data['columns'][] = 'actions';
        $data['columnDefs'] = [
            ['className' => 'text-center', 'targets' => [0]],
            ['className' => 'language_ge', 'targets' => [2]],
        ];
        $data['addNew'] = [
            'url' => route('admin_specs_add'),
            'anchor' => Lang::get('admin.add_new')
        ];
        return view('admin.pages.dataTables', $data);
    }


    public function viewAdd() {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.specs_add');
        $data['ajaxUrl'] = route('admin_specs_add_action');
        foreach ((new LaravelLocalization())->getSupportedLocales() as $key => $val) {
            $cData['translate_' . $key] = [];
        }
        $cData['image'] = [
            'field' => 'image'
        ];
        $data['fields'] = $this->fields($cData, (new VehicleSpecs())->fillable, (new VehicleSpecs())->nonFields);
        return view('admin.pages.add-edit', $data);
    }

    public function viewEdit($id) {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.specs_edit');
        $data['ajaxUrl'] = route('admin_specs_edit_action');
        $specData = VehicleSpecs::with('translate')->where('id', $id)->first();
        if ($specData) :
            $cData = $specData->toArray();
            $cData['id'] = ['value' => $cData['id'], 'readonly' => true];
            foreach ((new LaravelLocalization())->getSupportedLocales() as $key => $val) {
                $cData['translate_' . $key] = ['value' => $cData['translate'][array_search($key, array_column($cData['translate'], 'locale'))]['name']];
            }
            $cData['image'] = [
                'field' => 'image',
                'image_path' => 'vehicle-features/' . $cData['id']['value'] . '.'.$cData['extension'],
                'image_ajax' => route('admin_specs_delete_image', ['id' => $cData['id']['value'], 'extension' => $cData['extension'] ?? 'jpg'])
            ];

        endif;
        $data['fields'] = $this->fields($cData, (new VehicleSpecs())->fillable, (new VehicleSpecs())->nonFields);
        return view('admin.pages.add-edit', $data);
    }
}
