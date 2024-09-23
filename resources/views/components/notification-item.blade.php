@foreach($results as $notif)
    <div class="title7">
        <a href="{{ $notif['data']['url'] ?? '#' }}">
            {{ $notif['data']['text_'.config('app.locale')] }}
        </a>
    </div>
@endforeach
