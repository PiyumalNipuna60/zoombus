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
    <script src="{{ URL::asset('admin/js/plugins/summernote/summernote.js') }}"></script>
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
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h3 class="panel-title"><span class="fa fa-comment"></span> {{ Lang::get('admin.messages') }}</h3>
                </div>
                <div class="panel-body">
                    <div class="messages messages-img">
                        @foreach($ticket['messages_asc'] as $m)
                            <div class="item @if($m['admin'] > 0) in @endif">
                                <div class="image">
                                    <img src="@if($m['admin'] == 0) {{ $userAvatar }} @else {{ URL::asset('images/logo.svg') }} @endif"
                                         alt="{{ $ticket['name'] }}">
                                </div>
                                <div class="text">
                                    <div class="heading">
                                        <a href="@if(isset($ticket['user_id']) && $ticket['user_id'] > 0) {{ route('admin_user_edit', ['id' => $ticket['user_id']]) }} @else # @endif" target="_blank">
                                            @if($m['admin'] == 0)
                                                {{ $ticket['user']['name'] }}
                                            @else
                                                {{ $m['user']['name'] }}
                                            @endif
                                        </a>
                                        <span class="date">{{ \Carbon\Carbon::parse($m['created_at'])->translatedFormat('j\ F Y - H:i:s') }}</span>
                                    </div>
                                    @if($m['admin'] == 0) {{ $m['message'] }} @else {!! $m['message'] !!} @endif
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                <div class="panel-footer">
                    @component('components.misc.form')
                        @slot('ajax') {{ route('admin_support_reply') }} @endslot
                        @component('components.misc.form-group-col')
                            @slot('field') hidden @endslot
                            @slot('name') ticket_id @endslot
                            @slot('value') {{ $ticket['id'] }} @endslot
                            @slot('hideGroup') @endslot
                        @endcomponent
                        @component('components.misc.form-group-col')
                            @slot('field') textarea @endslot
                            @slot('class') form-control summernote w-100 @endslot
                            @slot('name') message @endslot
                            @slot('placeholder') {{ Lang::get('admin.type_a_message') }} @endslot
                        @endcomponent
                        @component('components.misc.submit-button')
                            @slot('class') btn btn-default @endslot
                            @slot('anchor') {{ Lang::get('admin.send') }} @endslot
                        @endcomponent
                        @component('components.table-actions', ['actions' => $actions])
                        @endcomponent
                    @endcomponent
                </div>
            </div>
        </div>
    </div>
@endsection
