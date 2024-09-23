<div class="rating-stars-text">
    <div class="rating-stars-text-img">
        <div class="stars-active larger changeable">
            @for($i=1; $i <= 5; $i++)
                <i class="fa @if($current >= $i) fa-star @else fa-star-o @endif" data-rating="{{ $i }}" aria-hidden="true"></i>
            @endfor
        </div>
    </div>
</div>
