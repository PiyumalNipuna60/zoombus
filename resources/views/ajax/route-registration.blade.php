@if(isset($submit) && $submit == 'continue')
    <div class="response"></div>
@endif
<div class="marg-label hidden">
    <span class="label label-info eng">{{ $incremented_id ?? null }}</span>
</div>
<div class="transport-registration-road-section" data-id="{{ $incremented_id ?? null }}">
    @component('components.misc.form')
        @slot('class') form-horizontal form-default @if(isset($editing)) routeEditForm @else
            routeRegistrationForm @endif @endslot
        @slot('row_inside') @endslot
        @if(isset($editing))
            @component('components.misc.form-group-col')
                @slot('field') hidden @endslot
                @slot('hideGroup') @endslot
                @slot('name') id @endslot
                @slot('value') {{ $id ?? null }} @endslot
            @endcomponent
        @endif
        @component('components.misc.form-group-col')
            @slot('field') hidden @endslot
            @slot('hideGroup') @endslot
            @slot('name') vehicle_id @endslot
            @slot('value') {{ $vehicle_id }} @endslot
        @endcomponent
        @component('components.misc.form-group-col')
            @slot('name') from @endslot
            @slot('addon') typeahead @endslot
            @slot('label') {{ Lang::get('driver.travel_from') }}@endslot
            @slot('faicon') fa-map-marker @endslot
            @slot('col') col-md-2 @endslot
            @slot('value') {{ $cities_from['translated']['name'] ?? null }} @endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
        @endcomponent
        @if(isset($editing))
            @component('components.misc.form-group-col')
                @slot('name') departure_date @endslot
                @slot('label') {{ Lang::get('driver.from_date') }} @endslot
                @slot('faicon') fa-calendar @endslot
                @slot('col') col-md-2 @endslot
                @slot('addon') datepicker @endslot
                @slot('value') {{ \Carbon\Carbon::parse($departure_date)->translatedFormat('j\ F Y') ?? null}} @endslot
                @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
            @endcomponent
        @else
            @component('components.misc.form-group-col')
                @slot('name') departure_date @endslot
                @slot('label') {{ Lang::get('driver.from_date') }}@endslot
                @slot('faicon') fa-calendar @endslot
                @slot('col') col-md-2 @endslot
                @slot('addon') datepicker @endslot
                @slot('field_id') departure_date_multiple @endslot
            @endcomponent
        @endif
        @component('components.misc.form-group-col')
            @slot('name') departure_time @endslot
            @slot('type') time @endslot
            @slot('label') {{ Lang::get('driver.departure_time') }}@endslot
            @slot('faicon') fa-clock-o @endslot
            @slot('col') col-md-2 @endslot
            @slot('value') {{ $departure_time ?? null }} @endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
        @endcomponent
        @component('components.misc.form-group-col')
            @slot('name') from_address @endslot
            @slot('label') {{ Lang::get('driver.from_address') }}@endslot
            @slot('faicon') fa-map-marker @endslot
            @slot('col') col-md-6 @endslot
            @slot('value') {{ $address_from['translated']['name'] ?? null }} @endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
        @endcomponent
        @component('components.misc.rowbreak') @endcomponent
        @component('components.misc.form-group-col')
            @slot('name') to @endslot
            @slot('addon') typeahead @endslot
            @slot('label') {{ Lang::get('driver.travel_to') }}@endslot
            @slot('faicon') fa-map-marker @endslot
            @slot('col') col-md-2 @endslot
            @slot('value') {{ $cities_to['translated']['name'] ?? null }} @endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
        @endcomponent

        @component('components.misc.form-group-col')
            @slot('name') stopping_time @endslot
            @slot('label') {{ Lang::get('driver.stopping_time') }}@endslot
            @slot('faicon') fa-clock-o @endslot
            @slot('explanation') {{ Lang::get('explanations.stopping_time') }} @endslot
            @slot('col') col-md-2 @endslot
            @slot('value') {{ $stopping_time ?? null }} @endslot
            @slot('placeholder') {{ Lang::get('misc.hour_min') }}@endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
        @endcomponent


        @component('components.misc.form-group-col')
            @slot('name') route_duration_hour @endslot
            @slot('label') {{ Lang::get('driver.route_duration') }} @endslot
            @slot('faicon') fa-clock-o @endslot
            @slot('value') {{ $route_duration_hour ?? null }} @endslot
            @slot('col') col-md-2 @endslot
            @slot('placeholder') {{ Lang::get('misc.hour_min') }}@endslot
            @slot('explanation') {{ Lang::get('explanations.route_duration_hour') }} @endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
        @endcomponent
        @component('components.misc.form-group-col')
            @slot('name') to_address @endslot
            @slot('label') {{ Lang::get('driver.to_address') }}@endslot
            @slot('faicon') fa-map-marker @endslot
            @slot('col') col-md-6 @endslot
            @slot('value') {{ $address_to['translated']['name'] ?? null }} @endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
        @endcomponent
        @component('components.misc.rowbreak') @endcomponent
        @component('components.misc.form-group-col', ['values' => $currencies ?? $currencies_all, 'value' => (isset($editing)) ? $currency_id ?? null : $current_currency ?? $current_currency_route])
            @slot('name') currency_id @endslot
            @slot('field') select @endslot
            @slot('label') {{ Lang::get('driver.currency') }}@endslot
            @slot('faicon') fa-money @endslot
            @slot('col') col-md-2 @endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
        @endcomponent
        @component('components.misc.form-group-col')
            @slot('name') price @endslot
            @slot('label') {{ Lang::get('driver.price') }}@endslot
            @slot('faicon') fa-money @endslot
            @slot('col') col-md-2 @endslot
            @slot('value') {{ $price ?? null }} @endslot
        @endcomponent
        <div class="col-md-2"></div>
        @component('components.misc.form-group-col', ['values' => $types ?? [], 'value' => (!isset($editing)) ? 2 : 1])
            @slot('name') type @endslot
            @slot('field') select @endslot
            @slot('explanation') {{ Lang::get('explanations.route_type') }} @endslot
            @slot('label') {{ Lang::get('driver.route_type') }}@endslot
            @slot('faicon') fa-car @endslot
            @slot('col') col-md-6 @endslot
            @isset($atLeastOneSale) @slot('disabled') @endslot @endisset
            @isset($editing) @slot('disabled') @endslot @endisset
        @endcomponent
        @if(isset($submit) && $submit == 'continue')
            @component('components.misc.form-group-col')
                @slot('field') hidden @endslot
                @slot('hideGroup') @endslot
                @slot('name') continue @endslot
                @slot('value') yes @endslot
            @endcomponent
            @component('components.misc.form-group-col')
                @slot('field') button @endslot
                @slot('col') col-md-2 @endslot
                @slot('class') btn-save wizard-back @endslot
                @slot('name') back @endslot
                @slot('faicon') fa-chevron-left @endslot
                @slot('label') &nbsp; @endslot
                @slot('anchor') {{ Lang::get('auth.back') }} @endslot
            @endcomponent
            <div class="col-md-7"></div>
            @component('components.misc.form-group-col')
                @slot('field') button @endslot
                @slot('col') col-md-3 @endslot
                @slot('class') btn-save @endslot
                @slot('name') save @endslot
                @slot('endIcon') true @endslot
                @slot('faicon') fa-chevron-right @endslot
                @slot('label') &nbsp; @endslot
                @slot('anchor') {{ Lang::get('auth.finish') }} @endslot
            @endcomponent
        @else
            @if(isset($editing))
                @component('components.misc.form-group-col')
                    @slot('field') button @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('class') btn-road @endslot
                    @slot('name') save @endslot
                    @slot('faicon') fa-floppy-o @endslot
                    @slot('label') &nbsp; @endslot
                    @slot('anchor') {{ Lang::get('auth.save') }} @endslot
                @endcomponent
            @else
                @component('components.misc.form-group-col')
                    @slot('field') button @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('class') btn-road @endslot
                    @slot('name') save @endslot
                    @slot('faicon') fa-floppy-o @endslot
                    @slot('label') &nbsp; @endslot
                    @slot('anchor') {{ Lang::get('auth.registration') }} @endslot
                @endcomponent
            @endif

            @if(isset($sales) && count($sales) > 0)
                @component('components.misc.form-group-col', ['alertify' => $deleteAlertify ?? []])
                    @slot('field') button @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('type') button @endslot
                    @slot('class') btn-cancel route_cancel @endslot
                    @slot('name') cancel @endslot
                    @slot('faicon') fa-trash @endslot
                    @slot('label') &nbsp; @endslot
                    @slot('anchor') {{ Lang::get('misc.cancel_route') }} @endslot
                @endcomponent
            @else
                @component('components.misc.form-group-col', ['alertify' => $deleteAlertify ?? []])
                    @slot('field') button @endslot
                    @slot('col') col-md-6 @endslot
                    @slot('type') button @endslot
                    @slot('class') btn-cancel route_delete @endslot
                    @slot('name') cancel @endslot
                    @isset($disableDelete) @slot('disabled') @endslot @endisset
                    @slot('faicon') fa-trash @endslot
                    @slot('label') &nbsp; @endslot
                    @slot('anchor') {{ Lang::get('auth.delete') }} @endslot
                @endcomponent
            @endif
        @endif
    @endcomponent
</div>
<div class="divider2"></div>
