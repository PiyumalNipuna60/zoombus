@extends('admin.app')


@push('styles_admin')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/alertify.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/themes/default.min.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

@push('scripts_admin')
    <script src="{{ URL::asset('js/alertify.min.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>
    <script src="{{ URL::asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ URL::asset('js/moment.js') }}"></script>
@endpush


@section('title', 'Editing user | Zoombus Admin')
@section('page_title', 'Editing user '.$user['name'])
@section('breadcrumb', 'Editing user')

@section('content')
    <div class="row">
        <div class="col-md-12">
            <div class="panel panel-default tabs">
                <ul class="nav nav-tabs" role="tablist">
                    <li class="active">
                        <a href="#tab-personal" role="tab" data-toggle="tab">Personal Data</a>
                    </li>
                    <li>
                        <a href="#tab-withdrawals" role="tab" data-toggle="tab">
                            {{ Lang::get('admin_menu.withdrawals') }} <span class="label label-success">{{ $user['total_withdrawn'] ?? 0 }} GEL</span>
                        </a>
                    </li>
                    @if(isset($isDriver))
                        <li><a href="#tab-sales" role="tab"
                               data-toggle="tab">{{ Lang::get('admin_menu.finance.ticket_sales') }} <span
                                    class="label label-success upd">0</span></a></li>
                        <li><a href="#tab-routes" role="tab" data-toggle="tab">{{ Lang::get('admin_menu.routes') }}
                                <span class="label label-info upd">0</span></a></li>
                        <li><a href="#tab-vehicles" role="tab" data-toggle="tab">{{ Lang::get('admin_menu.vehicles') }}
                                <span class="label label-info upd">0</span></a></li>
                    @endif
                    @if(isset($isPartner))
                        <li><a href="#tab-partners-list" role="tab"
                               data-toggle="tab"> {{ Lang::get('admin_menu.partners') }} <span
                                    class="label label-warning upd">0</span></a></li>
                        <li><a href="#tab-partners-sales" role="tab"
                               data-toggle="tab"> {{ Lang::get('admin_menu.partner_sales') }} <span
                                    class="label label-warning upd">0</span></a></li>
                    @endif
                </ul>
                <div class="panel-body tab-content">
                    <div class="tab-pane active" id="tab-personal">
                        <!-- PAGE CONTENT WRAPPER -->
                        <div class="row">
                            <div class="col-md-3">
                                @component('admin.pages.users.components.edit-profile', ['user' => $user, 'fields' => $fields])
                                @endcomponent
                            </div>
                            <div class="col-md-6">
                                @if($isDriver)
                                    @component('admin.pages.users.components.drivers-license', ['driver' => $driver])
                                    @endcomponent
                                @endif
                            </div>
                            <div class="col-md-3">
                                @component('admin.pages.users.components.user-info', ['driverInfo' => $driverInfo ?? [], 'partnerInfo' => $partnerInfo ?? [], 'userInfo' => $user ?? []])
                                @endcomponent
                                @component('admin.pages.users.components.user-cards', ['methods' => $financials ?? []])
                                @endcomponent
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane" id="tab-withdrawals">
                        @component('admin.pages.components.dataTable', [
                            'panel_title' => $withdrawals['title'] ?? null,
                            'page_title' => $withdrawals['page_title'] ?? null,
                            'addNew' => $withdrawals['addNew'] ?? [],
                            'ajaxUrl' => $withdrawals['ajaxUrl'] ?? null,
                            'ajaxData' => $withdrawals['ajaxData'] ?? [],
                            'columnDefs' => $withdrawals['columnDefs'] ?? [],
                            'dateDefs' => $withdrawals['dateDefs'] ?? null,
                            'columns' => $withdrawals['columns'] ?? []
                        ])
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab-sales">
                        @component('admin.pages.components.dataTable', [
                            'panel_title' => $sales['title'] ?? null,
                            'page_title' => $sales['page_title'] ?? null,
                            'addNew' => $sales['addNew'] ?? [],
                            'ajaxUrl' => $sales['ajaxUrl'] ?? null,
                            'ajaxData' => $sales['ajaxData'] ?? [],
                            'columnDefs' => $sales['columnDefs'] ?? [],
                            'dateDefs' => $sales['dateDefs']  ?? null,
                            'columns' => $sales['columns'] ?? []
                        ])
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab-routes">
                        @component('admin.pages.components.dataTable', [
                            'panel_title' => $routes['title'] ?? null,
                            'page_title' => $routes['page_title'] ?? null,
                            'addNew' => $routes['addNew'] ?? [],
                            'ajaxUrl' => $routes['ajaxUrl'] ?? null,
                            'ajaxData' => $routes['ajaxData'] ?? [],
                            'columnDefs' => $routes['columnDefs'] ?? [],
                            'dateDefs' => $routes['dateDefs'] ?? null,
                            'columns' => $routes['columns'] ?? []
                        ])
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab-vehicles">
                        @component('admin.pages.components.dataTable', [
                            'panel_title' => $vehicles['title'] ?? null,
                            'page_title' => $vehicles['page_title'] ?? null,
                            'addNew' => $vehicles['addNew'] ?? [],
                            'ajaxUrl' => $vehicles['ajaxUrl'] ?? null,
                            'ajaxData' => $vehicles['ajaxData'] ?? [],
                            'columnDefs' => $vehicles['columnDefs'] ?? [],
                            'dateDefs' => $vehicles['dateDefs'] ?? null,
                            'columns' => $vehicles['columns'] ?? []
                        ])
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab-partners-list">
                        @component('admin.pages.components.dataTable', [
                            'panel_title' => $partners['title'] ?? null,
                            'page_title' => $partners['page_title'] ?? null,
                            'addNew' => $partners['addNew'] ?? [],
                            'ajaxUrl' => $partners['ajaxUrl'] ?? null,
                            'ajaxData' => $partners['ajaxData'] ?? [],
                            'columnDefs' => $partners['columnDefs'] ?? [],
                            'dateDefs' => $partners['dateDefs'] ?? null,
                            'columns' => $partners['columns'] ?? []
                        ])
                        @endcomponent
                    </div>
                    <div class="tab-pane" id="tab-partners-sales">
                        @component('admin.pages.components.dataTable', [
                            'panel_title' => $partnerSales['title'] ?? null,
                            'page_title' => $partnerSales['page_title'] ?? null,
                            'addNew' => $partnerSales['addNew'] ?? [],
                            'ajaxUrl' => $partnerSales['ajaxUrl'] ?? null,
                            'ajaxData' => $partnerSales['ajaxData'] ?? [],
                            'columnDefs' => $partnerSales['columnDefs'] ?? [],
                            'dateDefs' => $partnerSales['dateDefs'] ?? null,
                            'columns' => $partnerSales['columns'] ?? []
                        ])
                        @endcomponent
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
