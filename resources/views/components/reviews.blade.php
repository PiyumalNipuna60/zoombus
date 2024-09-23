<div id="ratings_listing">
    @foreach($reviews as $review)
        <div class="{{ (isset($advanced)) ? 'col-md-12' : 'col-md-6' }} mg-t-10">
            <div class="reviews clearfix">
                <figure>
                    <img src="{{ URL::asset(Controller::userAvatarById($review['user']['id'])) }}"
                         alt="Avatar of {{ $review['user']['name'] }}" class="img-fluid avatar-small">
                </figure>
                <div class="caption">
                    <div class="caption-top clearfix pd-b-0">
                        <div class="txt1">{{ $review['user']['name'] }}</div>
                        @component('components.misc.rating', ['total_votes' => 1, 'rating_sum' => $review['rating']])
                            @slot('parent_class') reviews-stars-wrapper-left @endslot
                        @endcomponent
                    </div>
                    <div class="txt2">
                        {{ (!isset($advanced)) ? (mb_strlen($review['comment']) > 50) ? mb_substr($review['comment'],0,47).'...' : $review['comment'] : $review['comment'] }}
                    </div>
                </div>
            </div>
        </div>
    @endforeach
</div>
@if(isset($advanced) && count($reviews) > config('app.ratings_per_page'))
    <div class="btn btn-form1-submit show_more_mini" data-id="{{ $driver_user_id ?? 0 }}" data-skip="3">
        {{ Lang::get('misc.show_more') }}
    </div>
@endif
