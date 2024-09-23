@extends('layouts.app', ['isprofile' => 1])

@section('title', Lang::get('seo.financial_title'))

@section('body_class')
    @parent
    @if( app()->getLocale() == 'ka')
        language_ge
    @endif
@stop

@push('styles')
    <link href="{{ URL::asset('css/smoothness/jquery-ui-1.10.0.custom.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/datatables.css') }}" rel="stylesheet">
    <link href="{{ URL::asset('css/tables.css') }}" rel="stylesheet">
@endpush

@push('scripts')
    <script src="{{ URL::asset('js/jquery-ui.js') }}"></script>
    <script src="{{ URL::asset('js/datatables.js') }}"></script>

    <script src="{{ URL::asset('js/moment.js') }}"></script>
    <script src="{{ URL::asset('js/moment-with-locales.js') }}"></script>
@endpush

@section('title1', Lang::get('titles.financial_parameters'))
@section('title2', Lang::get('titles.financial_parameters2'))

@section('content')
    <div class="profile-financial">
        <div class="response"></div>
        <div class="profile-financial-btns">
            <a href="{{ route('financial_add') }}" class="btn-save"><i class="fa fa-plus" aria-hidden="true"></i> {{ Lang::get('misc.add_new_financial') }}</a>
        </div>
        <div class="driver-sales-table">
            <table id="financialTable" class="table table-striped table-bordered table-sm" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <th class="th-sm">{{ Lang::get('misc.date_added') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.payment_method') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.account_number') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('status.active') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('auth.delete') }}
                    </th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
@endsection
