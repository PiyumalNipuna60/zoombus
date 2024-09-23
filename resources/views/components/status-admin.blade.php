@isset($text)
    @isset($url) <a href="{{ $url }}" class="td-none"> @endisset
        <span class="label {{ $status_label ?? null }} label-{{ $class ?? 'warning' }}" aria-hidden="true">
        @isset($icon) <i class="fa {{ $icon }}"></i> @endisset
            {{ $text }}
        </span>
    @isset($url) </a> @endisset
@endisset
