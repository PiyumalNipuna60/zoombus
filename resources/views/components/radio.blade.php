<input type="radio" name="method" id="primary{{ $id ?? 0 }}" data-id="{{ $id ?? 0 }}" @if(isset($checked) && $checked == 1) checked @endif>
<label for="primary{{ $id ?? 0 }}" class="status-label {{ $label['label'] ?? null }} d-inline-block">{{ $label['text'] ?? null }}</label>
