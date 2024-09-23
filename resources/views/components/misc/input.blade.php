<div class="input1_wrapper">
    <label for="{{ $input_id ?? $name }}">{{ $title }}</label>
    <div class="input1_inner {{ $inner_class ?? null }}">
        <input type="text" autocomplete="off" class="form-control input" @isset($required) required
               oninvalid="this.setCustomValidity('{{ $required }}')"
               oninput="setCustomValidity('')" @endisset name="{{ $name }}" placeholder="{{ $placeholder ?? null }}" @empty($noId) id="{{ $input_id ?? $name }}" @endempty value="{{ $value ?? null }}">
    </div>
</div>
