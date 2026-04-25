{{-- resources/views/frontend/ajax/ajax-shop-items.blade.php --}}
<div class="row g-3">

    @forelse($products as $product)
        <div class="col-lg-3 col-md-4 col-6">
            <a href="{{ route('product.details', $product->id) }}">
                <div class="quicktech-product shadow-sm rounded">

                    {{-- Wishlist / Heart --}}
                    <div class="quikctech-wishlist">
                        <button type="button"><i class="fa-solid fa-heart"></i></button>
                    </div>

                    {{-- Sold Count --}}
                    <div class="quicktech-sold">
                        <span>{{ $product->orderItems ? $product->orderItems->sum('quantity') : 0 }} sold</span>
                    </div>

                    {{-- Product Image --}}
                    <div class="quikctech-img-product text-center">
                        @php
                            $image = $product->images->first()->image_path ?? $product->promotion_image ?? 'frontend/images/Architect1.png';
                        @endphp
                        <img src="{{ asset($image) }}" class="img-fluid" style="height:200px; object-fit:cover;">
                    </div>

                    {{-- Product Info --}}
                    <div class="quicktech-product-text">
                        <h6>{{ Str::limit($product->name, 50) }}</h6>
                        <div class="d-flex justify-content-between quicktech-pp-t">
                            <p>
                                ৳ {{ number_format($product->price) }}
                                @if($product->special_price && $product->special_price < $product->price)
                                    <br>
                                    <span style="font-size: 13px;">
                                        <s>৳ {{ number_format($product->price) }}</s> 
                                        -{{ number_format((($product->price - $product->special_price) / $product->price) * 100, 0) }}%
                                    </span>
                                @endif
                            </p>
                            <span>
                                <img src="{{ asset('frontend/images/star.png') }}" alt="Rating"> 
                                {{ number_format($product->rating ?? 4.5, 1) }}
                            </span>
                        </div>
                    </div>

                </div>
            </a>
        </div>

    @empty
        <div class="col-12 text-center py-5">
            <h5>No products found</h5>
            <p class="text-muted">Check back later for new products!</p>
        </div>
    @endforelse

</div>

{{-- Pagination --}}
@if($products->hasPages())
    <div class="row mt-3">
        <div class="col-12 d-flex justify-content-center">
            {{ $products->links() }}
        </div>
    </div>
@endif
