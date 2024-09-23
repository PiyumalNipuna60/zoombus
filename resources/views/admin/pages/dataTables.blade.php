@extends('admin.app')


@push('styles_admin')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/themes/default.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
@endpush

@push('scripts_admin')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/alertify.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
@endpush


@section('title', $seo_title.' | Zoombus Admin' ?? 'Zoombus Admin')
@section('page_title', $page_title ?? $seo_title ?? 'List of data')
@section('breadcrumb', $breadcrumb ?? $seo_title ?? 'Page')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title">
                        @if(isset($labels))
                            @foreach($labels as $label)
                                {{ $label['name'] }} <span class="label label-{{ $label['label'] }}">{{ $label['value'] }}</span>
                                @if(isset($label['divide'])) <br><br> @endif
                            @endforeach
                        @else
                            {{ $panel_title ?? $page_title ?? $seo_title ?? 'List of data' }}
                        @endif
                    </h3>
                </div>
                @component('admin.pages.components.dataTable', [
                    'panel_title' => $panel_title ?? null,
                    'page_title' => $page_title ?? null,
                    'seo_title' => $seo_title ?? null,
                    'addNew' => $addNew ?? null,
                    'ajaxUrl' => $ajaxUrl ?? null,
                    'ajaxData' => $ajaxData ?? null,
                    'columnDefs' => $columnDefs ?? null,
                    'dateDefs' => $dateDefs ?? null,
                    'sort_order' => $order ?? null,
                    'columns' => $columns ?? null
                ])
                @endcomponent
            </div>
        </div>
    </div>
@endsection
