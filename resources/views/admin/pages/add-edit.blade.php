@extends('admin.app')


@push('styles_admin')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/themes/default.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
@endpush

@push('scripts_admin')
    <script src="{{ URL::asset('js/alertify.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
    <script src="{{ URL::asset('admin/js/plugins/bootstrap/bootstrap-select.js') }}"></script>
    @isset($summernote)
        <script type="text/javascript" src="{{ URL::asset('admin/js/plugins/summernote/summernote.js') }}"></script>
    @endisset
    @isset($typeahead)
        <script type="text/javascript" src="{{ URL::asset('js/bloodhound.min.js') }}"></script>
        <script type="text/javascript" src="{{ URL::asset('js/typeahead.bundle.js') }}"></script>
    @endisset
@endpush




@section('title', $seo_title.' | Zoombus Admin' ?? 'Zoombus Admin')
@section('page_title', $page_title ?? $seo_title ?? 'List of data')
@section('breadcrumb', $breadcrumb ?? $seo_title ?? 'Page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            @component('components.misc.form')
                @slot('ajax') {{ $ajaxUrl ?? null }} @endslot
                <div class="panel panel-default {{ $panel_class ?? null }}">
                    <div class="panel-body">
                        <h2>{{ $panel_title ?? $seo_title ?? $page_title ?? 'Add/Edit' }}</h2>
                    </div>
                    <div class="panel-body form-group-separated">
                        @foreach($fields as $val)
                            @component('components.misc.form-group-col', ['value' => $val['value'] ?? null, 'values' => $val['values'] ?? [], 'select_key' => $val['select_key'] ?? null, 'typeahead' => $val['typeahead'] ?? null])
                                @slot('label_class') {{ $val['label_class'] ?? 'col-md-3 col-xs-5 control-label' }} @endslot
                                @slot('label') {{ $val['label'] }} @endslot
                                @slot('name') {{ $val['name'] }} @endslot
                                @isset($val['disabled']) @slot('disabled') @endslot @endisset
                                @isset($val['readonly']) @slot('readonly') @endslot @endisset
                                @isset($val['image_path']) @slot('image_path') {{ $val['image_path'] }} @endslot @endisset
                                @isset($val['image_ajax']) @slot('image_ajax') {{ $val['image_ajax'] }} @endslot @endisset
                                @slot('field') {{ $val['field'] ?? null }} @endslot
                                @slot('field_wrapper_class') {{ $val['field_wrapper_class'] ?? 'col-md-9 col-xs-7' }} @endslot
                                @isset($val['addon']) @slot('addon') {{ $val['addon'] }} @endslot @endisset
                                @isset($val['class']) @slot('class') {{ $val['class'] }} @endslot @endisset
                                @isset($val['type']) @slot('type') {{ $val['type'] }} @endslot @endisset
                                @isset($val['placeholder']) @slot('placeholder') {{ $val['placeholder'] }} @endslot @endisset
                            @endcomponent
                        @endforeach
                        @component('components.misc.submit-button')
                            @slot('class') btn btn-danger btn-block btn-rounded mtb-15 @endslot
                            @slot('anchor') {{ Lang::get('admin.save') }} @endslot
                        @endcomponent
                    </div>
                </div>
            @endcomponent
        </div>
    </div>
@endsection
