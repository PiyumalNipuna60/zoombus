@extends('admin.app')


@push('styles_admin')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/themes/default.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/flexslider.css') }}" rel="stylesheet">
@endpush

@push('scripts_admin')
    <script src="{{ URL::asset('js/alertify.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('js/jquery.flexslider-min.js') }}"></script>
@endpush


@section('title', 'Editing Vehicle | Zoombus Admin')
@section('page_title', 'Editing Vehicle')
@section('breadcrumb', 'Editing vehicle')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active">
                        <a href="#info" role="tab" data-toggle="tab">{{ Lang::get('admin.information') }}</a>
                    </li>
                    <li>
                        <a href="#routes" role="tab" data-toggle="tab">
                            {{ Lang::get('admin_titles.registered_routes') }} <span class="label label-success">{{ $vehicle['routes_count'] ?? 0 }}</span>
                        </a>
                    </li>
                </ul>
                <div class="panel-body tab-content">
                    <div class="tab-pane active" id="info">
                        <!-- PAGE CONTENT WRAPPER -->
                        <div class="row">
                            <div class="col-md-3">
                                @component('admin.pages.users.components.edit-profile', ['vehicle' => $vehicle, 'fields' => $fields, 'ajax' => route('admin_vehicle_edit_action')])
                                @endcomponent
                            </div>
                            <div class="col-md-6">
                                @component('admin.pages.vehicles.components.vehicle-license', ['vehicle' => $vehicle])
                                @endcomponent
                            </div>
                            <div class="col-md-3">
                                @component('admin.pages.vehicles.components.vehicle-info', ['vehicle' => $vehicle ?? []])
                                @endcomponent
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="routes">
                        <!-- PAGE CONTENT WRAPPER -->
                        @component('admin.pages.components.dataTable', [
                            'panel_title' => $panel_title ?? null,
                            'page_title' => $page_title ?? null,
                            'seo_title' => $seo_title ?? null,
                            'addNew' => $addNew ?? null,
                            'ajaxUrl' => $ajaxUrl ?? null,
                            'ajaxData' => $ajaxData ?? null,
                            'columnDefs' => $routeColumnDefs ?? null,
                            'dateDefs' => $dateDefs ?? null,
                            'columns' => $routeColumns ?? null
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
