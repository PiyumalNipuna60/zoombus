<div class="vehicle-scheme-wrapper {{ $route_type }}-scheme-wrapper @if(isset($disabled) && $disabled == true) disabled @endif">
     <div class="@if(isset($editable)) draggable-vehicle @else nondraggable-vehicle @endif">
        <div class="vehicle-scheme {{ $route_type }}-scheme {{ $route_type }}-scheme-start"></div>
        <div class="vehicle-scheme seat-parent {{ $route_type }}-scheme {{ $route_type }}-scheme-pattern @isset($editable) {{ $route_type }}-pattern-edit ui-draggable @endisset">
            @foreach(json_decode($seat_positioning, true) as $i=>$seat)
                @php
                $fs = array_search($seat['value'], array_column($chosen_seats ?? [], 'seat_number'));
                $fsp = array_search($seat['value'], array_column($chosen_pre_seats ?? [], 'seat_number'));
                @endphp
                <div class="vehicle-seat vehicle-seat{{ $seat['value'] }}
                @if(in_array($seat['value'], array_column($chosen_seats ?? [], 'seat_number')))
                        seat-chosen seat-{{ $chosen_seats[$fs]['users']['gender']['key'] }}
                @endif
                @if(in_array($seat['value'], array_column($chosen_pre_seats ?? [], 'seat_number')))
                        seat-chosen-pre seat-chosen seat-{{ $chosen_pre_seats[$fsp]['gender']['key']}}
                @endif
                    " style="top:{{ $seat['top'] }}px;left:{{ $seat['left'] }}px;">
                    <span>
                        @isset($editable)
                            <input type="text" class="transparent-input onchange-actioned"
                                   name="seat_positioning[{{$seat['value']}}][value]"
                                   value="{{ $seat['value'] }}" title="{{ $seat['value'] }}">
                            <input type="hidden" name="seat_positioning[{{$seat['value']}}][top]"
                                   value="{{ $seat['top'] }}" class="fromtop">
                            <input type="hidden" name="seat_positioning[{{$seat['value']}}][left]"
                                   value="{{ $seat['left'] }}" class="fromleft">
                        @endisset
                        @empty($editable)
                            {{ $seat['value'] }}
                        @endempty
                    </span>
                    @isset($editable) <div class="remove_seat @if($route_type == 'bus') top-10 @endif">x</div> @endisset
                </div>
            @endforeach
        </div>
        <div class="{{ $route_type }}-scheme-end"></div>
        <div class="clearfix"></div>
    </div>
</div>
@isset($show_info)
<div class="vehicle-scheme-info @if(isset($disabled) && $disabled == true) disabled @endif">
    @empty($editable)
    <div class="vehicle-scheme-info-item">
        {{ Lang::get('misc.non_available_seats') }} <img src="{{ URL::asset('images/seat-small-chosen.png') }}" alt="{{ Lang::get('misc.non_available_seats') }}" class="img-fluid">
    </div>
    @isset($show_info_preserved)
        <div class="vehicle-scheme-info-item">
            {{ Lang::get('misc.seat_chosen_by_driver') }} <img src="{{ URL::asset('images/seat-small-driver.png') }}" alt="{{ Lang::get('misc.seat_chosen_by_driver') }}" class="img-fluid">
        </div>
    @endisset
    <div class="vehicle-scheme-info-item">
        {{ Lang::get('misc.available_seats') }} <img src="{{ URL::asset('images/seat-small.png') }}" alt="{{ Lang::get('misc.available_seats') }}" class="img-fluid">
    </div>
    <div class="vehicle-scheme-info-item">
        {{ Lang::get('misc.chosen_seats') }} <img src="{{ URL::asset('images/seat-small-active.png') }}" alt="{{ Lang::get('misc.chosen_seats') }}" class="img-fluid">
    </div>
    @endempty
    <div class="vehicle-scheme-info-item">
        {{ Lang::get('misc.delete_seat') }}  <span>x</span>
    </div>
    @isset($editable)
    <div class="vehicle-scheme-info-item">
        <div class="d-inline-block">{{ Lang::get('misc.reposition_seat') }}</div> <i class="move"></i>
    </div>
    @endisset
</div>
@endisset
