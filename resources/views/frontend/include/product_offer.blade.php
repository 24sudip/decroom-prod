<section id="quicktech-service-offer">
    <div class="container">
        <div class="row quicktech-head-border mt-3">
            <div class="col-lg-12">
                <div class="quicktech-head">
                    <h4>{{ __('messages.offer_on_products') }}</h4>
                    <a href="{{route('vendorproduct.index')}}">{{ __('messages.explore_all_offers') }}</a>
                </div>
            </div>
        </div>
        <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
            @if(isset($products) && $products->count() > 0)
                @foreach($products as $product)
                <div class="col-lg-3 col-6 col-sm-6">
                    <a href="{{ url('product-details/' . $product->id) }}">
                        <div class="quicktech-product">
                            <div class="quikctech-wishlist">
                                <button><i class="fa-solid fa-heart"></i></button>
                            </div>
                            {{-- Sold Count --}}
                            @php
                                $soldCount = $product->orderItems->sum('quantity');
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

                             {{-- Product Image - UPDATED using full logic from product details page --}}
                            <div class="quikctech-img-product text-center">

                                @php
                                    $defaultImage = asset('frontend/images/productmain.png');
                                    $productImage = null;

                                    // 1. Collect product images
                                    $images = \App\ProductImage::where('product_id', $product->id)->get();

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
                                    if (!$productImage && $product->promotion_image) {
                                        if (file_exists(public_path($product->promotion_image))) {
                                            $productImage = asset($product->promotion_image);
                                        }
                                    }

                                    // 4. Final fallback
                                    if (!$productImage) {
                                        $productImage = $defaultImage;
                                    }
                                @endphp

                                <img
                                    src="{{ $productImage }}"
                                    alt="{{ $product->name }}"
                                    class="img-fluid"
                                    style="width: 100%; height: 200px; object-fit: cover;"
                                    onerror="this.src='{{ $defaultImage }}'"
                                >
                            </div>

                            <div class="quicktech-product-text">
                                <h6>{{ Str::limit($product->name, 40) }}</h6>
                                <div class="d-flex justify-content-between quicktech-pp-t">
                                    <p>
                                        @if($product->special_price && $product->special_price < $product->price)
                                            ৳ {{ number_format($product->special_price) }}
                                            <br>
                                            <span style="font-size: 13px;">
                                                <s>৳ {{ number_format($product->price) }}</s>
                                                -{{ number_format((($product->price - $product->special_price) / $product->price) * 100) }}%
                                            </span>
                                        @else
                                            ৳ {{ number_format($product->price) }}
                                            <br>
                                            <span style="font-size: 13px;">{{ __('messages.regular_price') }}</span>
                                        @endif
                                    </p>
                                    @php
                                        $avgRating = $product->averageRating();
                                        $ratingCount = $product->ratingCount();
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
                <!-- Fallback if no products available or $products is null -->
                <!--@for($i = 0; $i < 4; $i++)-->
                <!--<div class="col-lg-3 col-6 col-sm-6">-->
                <!--    <div class="quicktech-product">-->
                <!--        <div class="quikctech-wishlist">-->
                <!--            <button><i class="fa-solid fa-heart"></i></button>-->
                <!--        </div>-->
                <!--        <div class="quicktech-sold">-->
                <!--            <span>0 {{ __('messages.sold') }}</span>-->
                <!--        </div>-->
                <!--        <div class="quikctech-img-product text-center">-->
                <!--            <img src="{{ asset('frontend') }}/images/Architect 1.png" alt="{{ __('messages.no_product_available') }}" class="img-fluid">-->
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
                <!--                    <img src="{{ asset('frontend') }}/images/star.png" alt="{{ __('messages.rating') }}"> 0.0-->
                <!--                </span>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <!--@endfor-->
                <h3 class="text-center">{{ __('messages.no_product') }}</h3>
            @endif
        </div>
    </div>
</section>
