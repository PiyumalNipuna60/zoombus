@if(isset($total_votes) && $total_votes > 0 || isset($average_rating))
    <div class="{{ $parent_class ?? 'rating-stars-text' }} {{ (isset($advanced)) ? 'w-50' : null }}">
        @if(isset($total_votes) && isset($average_rating))
            <div class="hidden" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                <span itemprop="ratingValue">{{ $average_rating }}</span>
                <span itemprop="bestRating">5</span>
                <span itemprop="ratingCount">{{ $total_votes }}</span>
            </div>
        @endif
        <div class="{{ $child_class ?? 'rating-stars-text-img' }}">
            <span class="stars-active" style="width:{{ round($average_rating ?? $rating_sum/$total_votes, 1)*20 }}%">
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
                <i class="fa fa-star" aria-hidden="true"></i>
            </span>
            <span class="stars-inactive">
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
                <i class="fa fa-star-o" aria-hidden="true"></i>
            </span>
        </div>
        <div class="rating-stars-text-txt {{ (!isset($advanced)) ? 'd-inline-block' : null }}">
            {{ (isset($advanced)) ? Lang::get('misc.average_rating_text', ['rating' => round($average_rating ?? $rating_sum/$total_votes, 1, 2), 'count' => $total_votes ?? 0]) : round($average_rating ?? $rating_sum/$total_votes, 1, 2).'/5' }}
        </div>
    </div>
@endif
