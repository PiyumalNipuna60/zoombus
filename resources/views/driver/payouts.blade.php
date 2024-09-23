@extends('layouts.app', ['isprofile' => 1])

@section('title', $title ?? Lang::get('seo.driver_payouts'))


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

@section('title1', Lang::get('titles.driver_payouts'))
@section('title2', Lang::get('titles.driver_payouts2'))

@section('content')
    <div class="payout-history">
        @if(Session::get('alert'))
            <div class="response-persistent {{ Session::get('alert') }}">{{ Session::get('text') }}</div>
        @endif
        <div class="span-bold-ge">{{ Lang::get('misc.balance') }}: <span
                class="eng-bold red size20">{{ $balance ?? 0 }}</span>
            <img src="{{ URL::asset('images/currencies/GEL.png') }}" alt="GEL">
            <!-- Future currency possible change this in the far future -->
        </div>
        <div class="response"></div>
        @component('components.misc.form')
            @slot('row_inside') @endslot
            @slot('form_id') PayoutDriverForm @endslot
            @component('components.misc.form-group-col')
                @slot('field') hidden @endslot
                @slot('name') type @endslot
                @slot('hideGroup') @endslot
                @slot('value') driver @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('col') col-md-4 @endslot
                @slot('name') amount @endslot
                @slot('nolabel') @endslot
            @endcomponent
            @component('components.misc.submit-button')
                @slot('col') col-md-3 margin-minus @endslot
                @slot('class') btn-save @endslot
                @slot('form_group') @endslot
                @slot('nolabel') @endslot
                @slot('faicon') fa-floppy-o @endslot
                @slot('anchor') {{ Lang::get('misc.request_amount') }}@endslot
            @endcomponent
        @endcomponent
        <div class="transport-registered-table">
            <table id="driverPayoutsTable" class="table table-striped table-bordered table-sm" cellspacing="0"
                   width="100%">
                <thead>
                <tr>
                    <th class="th-sm">{{ Lang::get('misc.date') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.transaction_id') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.payment_method') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.currency') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('misc.amount') }}
                    </th>
                    <th class="th-sm">{{ Lang::get('driver.status') }}
                    </th>
                </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>

    </div>
@endsection
