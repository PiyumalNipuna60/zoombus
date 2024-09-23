@isset($title)<div class="footer-block-title">{{ $title }}</div>@endisset
<div class="owl-carousel owl-carousel-reviews">
    @isset($reviews)
        @foreach(array_chunk($reviews, 3) as $chunk)
            <div class="item">
                <div class="reviews-wrapper">
                    @foreach($chunk as $review)
                        <div class="reviews clearfix">
                            <figure>
                                <img src="{{ $review['image'] }}" alt="{{ $review['name'] }}" class="img-fluid">
                            </figure>
                            <div class="caption">
                                <div class="caption-top clearfix">
                                    <div class="txt1">{{ $review['name'] }}</div>
                                    <div class="reviews-stars-wrapper">
                                        <div class="reviews-stars">
                                            @for($c = 0; $c < 5; $c++)
                                                <i class="fa fa-star @if($c < $review['rating']) active @endif"></i>
                                            @endfor
                                        </div>
                                        <div class="reviews-stars-txt">{{ $review['rating'] }}/5</div>
                                    </div>
                                </div>
                                <div class="txt2">{{ $review['comment'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    @endisset
</div>