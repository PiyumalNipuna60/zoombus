<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\ValidationController;
use App\Page;
use App\PageTranslatable;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Facades\Route;
use Illuminate\Validation\Rule;
use Mcamara\LaravelLocalization\LaravelLocalization;

class PagesController extends AdminController {
    public function __construct() {
        parent::__construct();
    }

    private function store(array $data) {
        $page = Page::updateOrCreate(['id' => $data['id'] ?? 0], Arr::except($data, 'id'));
        foreach ((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
            PageTranslatable::updateOrCreate(['page_id' => $data['id'] ?? $page->id ?? 0, 'locale' => $keys],
                [
                    'seo_title' => $data['seo_title_' . $keys],
                    'seo_description' => $data['seo_description_' . $keys],
                    'title' => $data['title_' . $keys],
                    'text' => $data['text_' . $keys],
                ]
            );
        }
        return $page;
    }

    public function publish(Request $request) {
        $id = $request->only('id');
        Page::where('id', $id)->update(['status' => true]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_page_publish')]);
    }

    public function draft(Request $request) {
        $id = $request->only('id');
        Page::where('id', $id)->update(['status' => false]);
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_page_draft')]);
    }

    public function delete(Request $request) {
        $id = $request->only('id');
        Page::where('id', $id)->delete();
        return response()->json(['status' => 1, 'text' => Lang::get('alerts.success_page_delete')]);
    }

    protected function validator($data, $mode = 'add') {
        $fields = [
            'route_name' => 'nullable|string|'. Rule::unique('pages')->ignore($data['id'] ?? 0),
            'slug' => 'nullable|string|unique:pages',
            'status' => 'required|integer',
            'robots' => 'nullable|string',
            'in_faq' => 'required|integer|' . Rule::in([0, 1]),
            'in_footer' => 'required|integer|' . Rule::in([0, 1]),
            'video_url' => 'nullable|url',

        ];
        if ($mode == 'edit') {
            $fields['id'] = 'required|integer';
        }
        foreach (Arr::except($data, ['status', 'robots', 'id', 'in_faq', 'in_footer', 'slug', 'route_name', 'video_url']) as $key => $val) {
            $locale = substr($key, -2);
            $exp = explode('_'.$locale, $key);


            if ($exp[0] === 'text') {
                $type = '|';
                $unique = null;
            } else {
                $type = '|string|';
                if ($mode == 'edit') {
                    $unique = Rule::unique('page_translatables', $exp[0])->where('locale', $locale)->ignore($data['id'], 'page_id');
                } else {
                    $unique = Rule::unique('page_translatables', $exp[0])->where('locale', $locale);
                }
            }
            $fields[$key] = 'nullable' . $type . $unique;
        }
        return \Validator::make($data, $fields);
    }

    private function action($request, $mode) {
        $fillables = (new Page())->fillable;
        if ($mode == 'edit') {
            $fillables[] = 'id';
        }
        foreach ((new LaravelLocalization)->getSupportedLocales() as $keys => $vals) {
            $fillables[] = 'seo_title_' . $keys;
            $fillables[] = 'seo_description_' . $keys;
            $fillables[] = 'title_' . $keys;
            $fillables[] = 'text_' . $keys;
        }

        $data = $request->only($fillables);
        $response = ValidationController::response($this->validator($data, $mode), Lang::get('alerts.successfully_added'));
        if ($response->original['status'] == 1) {
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
        $pages = Page::with('translated')->get()->toArray();
        foreach ($pages as $key => $val) {
            if ($val['status'] == 0) {
                $sua = 'publish';
            } else {
                $sua = 'draft';
            }
            $actions = [
                [
                    'url' => route('admin_pages_edit', ['id' => $val['id']]),
                    'faicon' => 'fa-pencil',
                    'url_class' => 'btn btn-default btn-rounded btn-condensed btn-sm',
                ],
                [
                    'url' => route('admin_page_' . $sua),
                    'url_class' => 'btn btn-danger btn-condensed btn-sm btn-sm mb-control',
                    'anchor' => \Lang::get('misc.' . $sua),
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_page_' . $sua),
                        'error-msg' => Lang::get('alerts.error_page_' . $sua),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ]
                ],
                [
                    'faicon' => 'fa-times',
                    'url' => route('admin_page_delete'),
                    'url_class' => 'btn btn-danger btn-rounded btn-condensed btn-sm',
                    'alertify' => [
                        'confirm-msg' => Lang::get('alerts.standard_are_you_sure_you_want_to_perform_this_action'),
                        'success-msg' => Lang::get('alerts.success_page_delete'),
                        'error-msg' => Lang::get('alerts.error_page_delete'),
                    ],
                    'ajaxData' => [
                        'id' => $val['id'],
                    ]
                ],
            ];
            $pages[$key]['seo_title'] = $val['translated']['seo_title'];
            $pages[$key]['slug'] = $val['slug'] ?? route($val['route_name'], [], false);
            $pages[$key]['seo_description'] = (strlen($val['translated']['seo_description']) > 255) ? substr($val['translated']['seo_description'], 0, 252) . '...' : $val['translated']['seo_description'];
            $pages[$key]['actions'] = view('components.table-actions', ['actions' => $actions])->render();
        }
        return datatables()->of($pages)->rawColumns(['actions'])->toJson();
    }


    public function view() {
        $data = AdminController::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.pages');
        $data['addNew'] = [
            'url' => route('admin_pages_add'),
            'anchor' => Lang::get('admin.add_new')
        ];
        $data['columns'] = ['slug', 'seo_title', 'seo_description', 'actions'];
        $data['ajaxUrl'] = route('admin_pages_data');
        $data['columnDefs'] = [['className' => 'text-center', 'targets' => [0, 1]]];
        return view('admin.pages.dataTables', $data);
    }

    public function viewAdd() {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.pages_add');
        $data['ajaxUrl'] = route('admin_pages_add_action');
        $cData = [
            'route_name' => ['field' => 'select', 'class' => 'form-control select',],
            'slug' => ['placeholder' => Lang::get('admin.leave_empty_if_top')],
        ];
        $routeCollection = Route::getRoutes();
        $cData['route_name']['values'][] = ['id' => '', 'name' => 'None'];
        foreach ($routeCollection as $key => $value) {
            if (
                substr($value->getName(), 0, 6) != 'admin_'
                && !empty($value->getName())
                && $value->methods()[0] == 'GET'
                && strpos($value->uri, '{') === False
                && $value->getName() != 'paypal_execute'
            ) {
                $cData['route_name']['values'][] = ['id' => $value->getName(), 'name' => $value->uri];
            }
        }


        foreach ((new LaravelLocalization())->getSupportedLocales() as $key => $val) {
            $cData['seo_title_' . $key] = [];
            $cData['seo_description_' . $key] = ['field' => 'textarea'];
            $cData['title_' . $key] = [];
            $cData['text_' . $key] = ['field' => 'textarea', 'class' => 'summernote'];
        }

        $cData['video_url'] = [];
        $cData['robots'] = ['values' => [false, 'noindex', 'nofollow', 'noindex, nofollow'], 'field' => 'select', 'class' => 'form-control select'];
        $cData['in_faq'] = ['values' => [['id' => 0, 'name' => 'No'], ['id' => 1, 'name' => 'Yes']], 'field' => 'select', 'class' => 'form-control select'];
        $cData['in_footer'] = ['values' => [['id' => 0, 'name' => 'No'], ['id' => 1, 'name' => 'Yes']], 'field' => 'select', 'class' => 'form-control select'];
        $cData['status'] = ['values' => [
            ['id' => 1, 'name' => 'Publish'],
            ['id' => 0, 'name' => 'Draft'],
        ], 'field' => 'select', 'class' => 'form-control select'];


        $data['summernote'] = true;
        $data['fields'] = $this->fields($cData, (new Page())->fillable);
        return view('admin.pages.add-edit', $data);
    }

    public function viewEdit($id) {
        $data = self::essentialVars();
        $data['seo_title'] = Lang::get('admin_titles.pages_edit');
        $data['ajaxUrl'] = route('admin_pages_edit_action');
        $data['summernote'] = true;
        $pageData = Page::with('translate')->where('id', $id)->first();

        $cData['route_name']['values'][] = ['id' => '', 'name' => 'None'];
        if ($pageData) :
            $cDataB = $pageData->toArray();
            $cData['id'] = ['value' => $cDataB['id'], 'readonly' => true];
            $cData['route_name'] = ['field' => 'select', 'class' => 'form-control select'];
            $routeCollection = Route::getRoutes();
            foreach ($routeCollection as $key => $value) {
                if (substr($value->getName(), 0, 6) != 'admin_' && !empty($value->getName()) && $value->methods()[0] == 'GET') {
                    $cData['route_name']['values'][] = ['id' => $value->getName(), 'name' => $value->uri];
                    $cData['route_name']['value'] = $cDataB['route_name'];
                }
            }


            $cData['slug'] = ['placeholder' => Lang::get('admin.leave_empty_if_top'), 'value' => $cDataB['slug'] ?? null];


            foreach ((new LaravelLocalization())->getSupportedLocales() as $key => $val) {
                $cData['seo_title_' . $key] = ['value' => $cDataB['translate'][array_search($key, array_column($cDataB['translate'], 'locale'))]['seo_title']];
                $cData['seo_description_' . $key] = ['field' => 'textarea', 'value' => $cDataB['translate'][array_search($key, array_column($cDataB['translate'], 'locale'))]['seo_description']];
                $cData['title_' . $key] = ['value' => $cDataB['translate'][array_search($key, array_column($cDataB['translate'], 'locale'))]['title']];
                $cData['text_' . $key] = ['field' => 'textarea', 'class' => 'summernote', 'value' => $cDataB['translate'][array_search($key, array_column($cDataB['translate'], 'locale'))]['text']];
            }

            $cData['video_url'] = ['value' => $cDataB['video_url']];

            $cData['robots'] = ['values' => [false, 'noindex', 'nofollow', 'noindex, nofollow'], 'field' => 'select', 'class' => 'form-control select', 'value' => $cDataB['robots']];
            $cData['in_faq'] = ['values' => [['id' => 0, 'name' => 'No'], ['id' => 1, 'name' => 'Yes']], 'field' => 'select', 'value' => $cDataB['in_faq']];
            $cData['in_footer'] = ['values' => [['id' => 0, 'name' => 'No'], ['id' => 1, 'name' => 'Yes']], 'field' => 'select', 'value' => $cDataB['in_footer']];
            $cData['status'] = ['values' => [
                ['id' => 1, 'name' => 'Publish'],
                ['id' => 0, 'name' => 'Draft'],
            ], 'field' => 'select', 'class' => 'form-control select', 'value' => $cDataB['status']];



            $data['fields'] = $this->fields($cData, (new Page())->fillable, (new Page())->nonFields);
        else:
            abort(404);
        endif;

        return view('admin.pages.add-edit', $data);
    }
}
