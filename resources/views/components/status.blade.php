@if(isset($text) || isset($icon) || isset($class))
    @isset($url) <a href="{{ $url }}" class="td-none"> @endisset
        <span class="status-label {{ $status_label ?? null }} {{ $class ?? 'warning' }}" aria-hidden="true">
        @isset($icon) <i class="fa {{ $icon }}"></i> @endisset
            {{ $text ?? null }}
        </span>
    @isset($url) </a> @endisset
@endif
