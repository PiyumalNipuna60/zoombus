<form class="{{ $class ?? 'form-horizontal' }}" action="{{ $action ?? 'javascript:void(0);' }}"
      id="{{ $form_id ?? null }}" @isset($multipart) enctype="multipart/form-data" @endisset @isset($method) method="{{ $method }}" @endisset
    @isset($ajax) data-type="ajax" data-action="{{ $ajax }}" @endisset
>
    @isset($disabled)
        <fieldset disabled="disabled">
            @endisset
                @isset($row_inside)
                    <div class="row">
                        @endisset
                        @csrf
                        {{ $slot }}
                        @isset($subtitle)
                            <div class="sub-title">{{ $subtitle }}</div>
                        @endisset
                        @isset($row_inside)
                    </div>
                @endisset
            @isset($disabled)
        </fieldset>
    @endisset
</form>
