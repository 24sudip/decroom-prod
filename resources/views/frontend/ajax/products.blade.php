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
                                {{ number_format($soldCount / 1000, 1) }}k sold
                            @else
                                {{ $soldCount }} sold
                            @endif
                        </span>
                    </div>

            <div class="quikctech-img-product text-center">
                <img src="{{ asset('frontend/images/productmain.png') }}"
                     class="img-fluid"
                     style="width:100%;height:200px;object-fit:cover;">
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
                                                    <span style="font-size: 13px;">Regular Price</span>
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
