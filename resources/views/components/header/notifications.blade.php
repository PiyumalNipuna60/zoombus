<div class="my_notifications_wrapper">
    <div class="my_notifications_button @if(isset($number_of_items) && $number_of_items == 0 || count($items) == 0) clicked @endif">
        <a href="{{ route('notifications') }}">
            <i class="" aria-hidden="true"></i>
            <span @if(isset($number_of_items) && $number_of_items > 0) class="new" @endif>{{ $number_of_items ?? 0 }}</span>
        </a>
    </div>
    @if(count($items) > 0)
        <div class="my_notifications_popup">
            @foreach($items as $item)
                <a href="{{ $item['data']['url'] ?? '#' }}" class="my_notification_item clearfix {{ ($item['read_at']) ? 'old' : 'new' }}">
                    <div class="row h-100 m-t-b-10">
                        <div class="col-sm-2">
                            <div class="image-area">
                                <img src="{{ URL::asset('images/notification-types/'.$item['data']['type'].'.svg') }}"
                                     alt="{{ $item['data']['type'] }}" class="img-fluid">
                            </div>
                        </div>
                        <div class="col-sm-10 ml-0">
                            <div class="caption">
                                {{ $item['data']['text_'.$current_locale] }}
                            </div>
                        </div>
                    </div>
                </a>
            @endforeach
            <div class="my_cart_buttons">
                <a href="{{ route('notifications') }}"
                   class="my_cart_button2">{{ Lang::get('notifications.view_all') }}</a>
            </div>
        </div>
    @endif

</div>
