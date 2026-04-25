<section id="quicktech-review-rating">
    <div class="container">
        <h4>{{ __('messages.review_rating') }}</h4>

        @php
            $totalReviews = $product->ratings()->count();
            $avgRating = $totalReviews > 0 ? $product->ratings()->avg('rating') : 0;
        
            // Count reviews by rating
            $ratingDistribution = [
                5 => $product->ratings()->where('rating',5)->count(),
                4 => $product->ratings()->where('rating',4)->count(),
                3 => $product->ratings()->where('rating',3)->count(),
                2 => $product->ratings()->where('rating',2)->count(),
                1 => $product->ratings()->where('rating',1)->count(),
            ];
        @endphp
        
        <div class="mb-4">
            <h3>{{ number_format($avgRating,1) }} / 5</h3>
            <div class="rating-stars mb-2">
                @for($i=1; $i<=5; $i++)
                    @if($i <= floor($avgRating))
                        <i class="fas fa-star" style="color:#FFD700;"></i>
                    @elseif($i - 0.5 <= $avgRating)
                        <i class="fas fa-star-half-alt" style="color:#FFD700;"></i>
                    @else
                        <i class="far fa-star" style="color:#ccc;"></i>
                    @endif
                @endfor
                <span class="ms-2">{{ $totalReviews }} {{ __('messages.review', ['count' => $totalReviews]) }}</span>
            </div>
        
            <div class="rating-distribution">
                @foreach($ratingDistribution as $star=>$count)
                    @php $percent = $totalReviews>0?($count/$totalReviews)*100:0; @endphp
                    <div class="d-flex align-items-center mb-1">
                        <span class="me-2">{{ $star }} <i class="fas fa-star" style="color:#FFD700;"></i></span>
                        <div class="progress flex-grow-1" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: {{ $percent }}%; background-color:#FFD700;"></div>
                        </div>
                        <span class="ms-2">{{ $count }}</span>
                    </div>
                @endforeach
            </div>
        </div>
        
        <div class="reviews-list">
            @forelse($reviews as $review)
                <div class="review-item mb-3 border-bottom pb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            @for($i=1;$i<=5;$i++)
                                @if($i <= $review->rating)
                                    <i class="fas fa-star" style="color:#FFD700;"></i>
                                @else
                                    <i class="far fa-star" style="color:#ccc;"></i>
                                @endif
                            @endfor
                            <strong class="ms-2">{{ $review->user->name ?? __('messages.anonymous') }}</strong>
                            @if($review->is_verified_purchase)
                                <span class="text-success ms-2"><i class="fa-solid fa-circle-check"></i> {{ __('messages.verified_purchase') }}</span>
                            @endif
                        </div>
                        <small class="text-muted">{{ $review->created_at->diffForHumans() }}</small>
                    </div>
                    <p>{{ $review->comment }}</p>
                    @if($review->variant_info)
                        <small>{{ __('messages.color') }}: {{ $review->variant_info['color'] ?? __('messages.not_available') }}, {{ __('messages.size') }}: {{ $review->variant_info['size'] ?? __('messages.not_available') }}</small>
                    @endif
                </div>
            @empty
                <p>{{ __('messages.no_reviews_yet') }}</p>
            @endforelse
        </div>
        
        @if(method_exists($reviews,'links'))
            <div class="mt-3">
                {{ $reviews->links() }}
            </div>
        @endif

    </div>
</section>

<style>
.rating-stars i { font-size: 18px; }
.rating-distribution .progress { background-color: #e0e0e0; border-radius: 4px; }
.rating-distribution .progress-bar { height: 8px; }
.review-item p { margin-bottom: 0.5rem; }
</style>