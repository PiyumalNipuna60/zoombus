{{-- Style includes for datepicker --}}
@push('styles')
    <link href="{{ URL::asset('css/bootstrap-datepicker3.min.css') }}" rel="stylesheet">
@endpush

<div class="input1_wrapper {{ $wrapper_class ?? null }}">
    <label for="{{ $input_id ?? $name }}">{{ $title }}</label>
    <div class="input1_inner">
        <input type="text" autocomplete="off" class="form-control input datepicker-element" @isset($required) required @endisset @isset($clear_btn) data-date-clear-btn="true" @endisset  name="{{ $name ?? null }}" value="{{ $value ?? null }}" placeholder="{{ $placeholder ?? null }}" id="{{ $input_id ?? $name }}">
    </div>
</div>

{{-- Script includes for datepicker --}}
@push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.min.js') }}"></script>
    <script type="text/javascript" src="{{ URL::asset('js/moment.js') }}"></script>
@endpush
@if ( Config::get('app.locale') == 'ka')
    @push('scripts')
    <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.ka.min.js') }}"></script>
    @endpush
@elseif ( Config::get('app.locale') == 'ru' )
    @push('scripts')
        <script type="text/javascript" src="{{ URL::asset('js/bootstrap-datepicker.ru.min.js') }}"></script>
    @endpush
@endif

