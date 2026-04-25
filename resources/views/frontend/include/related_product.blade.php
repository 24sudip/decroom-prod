<section id="quicktech-service-offer">
    <div class="container">
        <div class="row quicktech-head-border mt-3">
            <div class="col-lg-12">
                <div style="padding-top: 10px;" class="quicktech-head">
                    <h4>{{ __('messages.you_may_also_like') }}</h4>
                </div>
            </div>
        </div>
        <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
            @if(isset($similarProducts) && $similarProducts->count() > 0)
                @foreach($similarProducts->take(4) as $relatedProduct)
                <div class="col-lg-3 col-6 col-sm-6">
                    <a href="{{ route('product.details', $relatedProduct->id) }}">
                        <div class="quicktech-product">
                            <div class="quikctech-wishlist">
                                <button>
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </div>
                            <div class="quicktech-sold">
                                <span>{{ $relatedProduct->total_sold ?? 0 }} {{ __('messages.sold') }}</span>
                            </div>

                            {{-- Product Image - UPDATED using full logic from product details page --}}
                            <div class="quikctech-img-product text-center">

                                @php
                                    $defaultImage = asset('frontend/images/productmain.png');
                                    $productImage = null;

                                    // 1. Collect product images
                                    $images = \App\ProductImage::where('product_id', $relatedProduct->id)->get();

                                    // 2. Try primary image first
                                    if ($images->count() > 0) {
                                        $primary = $images->where('is_primary', true)->first();

                                        if ($primary && file_exists(public_path($primary->image_path))) {
                                            $productImage = asset($primary->image_path);
                                        } else {
                                            $firstImage = $images->first();
                                            if ($firstImage && file_exists(public_path($firstImage->image_path))) {
                                                $productImage = asset($firstImage->image_path);
                                            }
                                        }
                                    }

                                    // 3. If still no image → check promotion image
                                    if (!$productImage && $relatedProduct->promotion_image) {
                                        if (file_exists(public_path($relatedProduct->promotion_image))) {
                                            $productImage = asset($relatedProduct->promotion_image);
                                        }
                                    }

                                    // 4. Final fallback
                                    if (!$productImage) {
                                        $productImage = $defaultImage;
                                    }
                                @endphp

                                <img
                                    src="{{ $productImage }}"
                                    alt="{{ $relatedProduct->name }}"
                                    class="img-fluid"
                                    style="width: 100%; height: 200px; object-fit: cover;"
                                    onerror="this.src='{{ $defaultImage }}'"
                                >
                            </div>

                            <div class="quicktech-product-text">
                                <h6>{{ Str::limit($relatedProduct->name, 40) }}</h6>
                                <div class="d-flex justify-content-between quicktech-pp-t">
                                    <p>
                                        @if($relatedProduct->special_price && $relatedProduct->special_price < $relatedProduct->price)
                                            ৳ {{ number_format($relatedProduct->special_price) }}
                                            <br>
                                            <span style="font-size: 13px;">
                                                <s>৳ {{ number_format($relatedProduct->price) }}</s>
                                                -{{ number_format((($relatedProduct->price - $relatedProduct->special_price) / $relatedProduct->price) * 100) }}%
                                            </span>
                                        @else
                                            ৳ {{ number_format($relatedProduct->price) }}
                                            <br>
                                            <span style="font-size: 13px;">{{ __('messages.regular_price') }}</span>
                                        @endif
                                    </p>
                                    @php
                                        $avgRating = $relatedProduct->averageRating();
                                        $ratingCount = $relatedProduct->ratingCount();
                                    @endphp

                                    <span class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($avgRating))
                                                <i class="fa-solid fa-star" style="color: #FFD700; font-size: 14px;"></i>
                                            @else
                                                <i class="fa-regular fa-star" style="color: #ccc; font-size: 14px;"></i>
                                            @endif
                                        @endfor
                                        <span style="margin-left: 3px; font-size: 13px;">
                                            ({{ number_format($avgRating, 1) }})
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            @elseif(isset($recentlyBrowsed) && $recentlyBrowsed->count() > 0)
                <!-- Fallback to recently browsed products -->
                @foreach($recentlyBrowsed->take(4) as $relatedProduct)
                <div class="col-lg-3 col-6 col-sm-6">
                    <a href="{{ route('product.details', $relatedProduct->id) }}">
                        <div class="quicktech-product">
                            <div class="quikctech-wishlist">
                                <button>
                                    <i class="fa-solid fa-heart"></i>
                                </button>
                            </div>
                            {{-- Sold Count --}}
                            @php
                                $soldCount = $relatedProduct->orderItems->sum('quantity');
                            @endphp
                            <div class="quicktech-sold">
                                <span>
                                    @if($soldCount >= 1000)
                                        {{ number_format($soldCount / 1000, 1) }}k {{ __('messages.sold') }}
                                    @else
                                        {{ $soldCount }} {{ __('messages.sold') }}
                                    @endif
                                </span>
                            </div>
                            <div class="quikctech-img-product text-center">
                                @if($relatedProduct->images && $relatedProduct->images->count() > 0 && $relatedProduct->primaryImage())
                                    <img src="{{ asset($relatedProduct->primaryImage()->image_path) }}" alt="{{ $relatedProduct->name }}" class="img-fluid">
                                @else
                                    <img src="{{ asset('frontend/images/Architect1.png') }}" alt="{{ $relatedProduct->name }}" class="img-fluid">
                                @endif
                            </div>
                            <div class="quicktech-product-text">
                                <h6>{{ Str::limit($relatedProduct->name, 40) }}</h6>
                                <div class="d-flex justify-content-between quicktech-pp-t">
                                    <p>
                                        @if($relatedProduct->special_price && $relatedProduct->special_price < $relatedProduct->price)
                                            ৳ {{ number_format($relatedProduct->special_price) }}
                                            <br>
                                            <span style="font-size: 13px;">
                                                <s>৳ {{ number_format($relatedProduct->price) }}</s>
                                                -{{ number_format((($relatedProduct->price - $relatedProduct->special_price) / $relatedProduct->price) * 100) }}%
                                            </span>
                                        @else
                                            ৳ {{ number_format($relatedProduct->price) }}
                                            <br>
                                            <span style="font-size: 13px;">{{ __('messages.regular_price') }}</span>
                                        @endif
                                    </p>
                                    @php
                                        $avgRating = $relatedProduct->averageRating();
                                        $ratingCount = $relatedProduct->ratingCount();
                                    @endphp

                                    <span class="d-flex align-items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= round($avgRating))
                                                <i class="fa-solid fa-star" style="color: #FFD700; font-size: 14px;"></i>
                                            @else
                                                <i class="fa-regular fa-star" style="color: #ccc; font-size: 14px;"></i>
                                            @endif
                                        @endfor
                                        <span style="margin-left: 3px; font-size: 13px;">
                                            ({{ number_format($avgRating, 1) }})
                                        </span>
                                    </span>
                                </div>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach
            @else
                <!-- Fallback when no related products available -->
                <!--@for($i = 1; $i <= 4; $i++)-->
                <!--<div class="col-lg-3 col-6 col-sm-6">-->
                <!--    <div class="quicktech-product">-->
                <!--        <div class="quikctech-wishlist">-->
                <!--            <button>-->
                <!--                <i class="fa-solid fa-heart"></i>-->
                <!--            </button>-->
                <!--        </div>-->
                <!--        <div class="quicktech-sold">-->
                <!--            <span>0 {{ __('messages.sold') }}</span>-->
                <!--        </div>-->
                <!--        <div class="quikctech-img-product text-center">-->
                <!--            <img src="{{ asset('frontend/images/Architect1.png') }}" alt="{{ __('messages.no_product_available') }}" class="img-fluid">-->
                <!--        </div>-->
                <!--        <div class="quicktech-product-text">-->
                <!--            <h6>{{ __('messages.no_product_available') }}</h6>-->
                <!--            <div class="d-flex justify-content-between quicktech-pp-t">-->
                <!--                <p>-->
                <!--                    ৳ 0-->
                <!--                    <br>-->
                <!--                    <span style="font-size: 13px;">{{ __('messages.no_discount') }}</span>-->
                <!--                </p>-->
                <!--                <span>-->
                <!--                    <img src="{{ asset('frontend/images/star.png') }}" alt="{{ __('messages.rating') }}"> 0.0-->
                <!--                </span>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <!--@endfor-->
                <h3 class="text-center">{{ __('messages.no_product_available') }}</h3>
            @endif
        </div>
    </div>
</section>
