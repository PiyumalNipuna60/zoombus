@foreach($actions as $act)
    @if(isset($act['url']))
        <a href="{{ $act['url'] }}"
           @isset($act['ajaxData']) data-action="ajax" data-post-data="{{ json_encode($act['ajaxData']) }}" @endisset
        class="{{ $act['url_class'] ?? 'blue' }}"
            @isset($act['color']) style="color: {{ $act['color'] }}" @endisset
            @isset($act['alertify'])
               @foreach($act['alertify'] as $key => $val)
                    data-{{$key}}="{{ $val }}"
               @endforeach
            @endisset >

            @if(isset($act['faicon']))
                <i class="fa {{ $act['faicon'] }} {{ $act['fa_class'] ?? null }}"></i>
            @endif
            @if(isset($act['anchor']))
                {{ $act['anchor'] }}
            @endif
        </a>
    @endif
@endforeach
