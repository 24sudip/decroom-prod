@extends('frontend.layouts.master')
@section('title', $category->name . ' - Products')
@section('content')
    <section id="quikctech-service-menu">
        <div class="container">
            <div class="row my-3">
                <div class="col-lg-12">
                    <div class="quikctech-ser-menu">
                        <ul>
                            <li><a class="ser-active" href="{{ route('vendorproduct.index') }}">Product</a></li>
                            <li><a href="{{ route('service.index') }}">Services</a></li>
                            <li><a href="{{ route('vendor.shop.list') }}">View Shop</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Breadcrumb --}}
    <section id="quicktech-breadcrumb">
        <div class="container">
            <div class="row my-3">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('vendorproduct.index') }}">Products</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    
    <section id="quicktech-service-offer">
        <div class="container">
            {{-- Category Header --}}
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="quicktech-head">
                        <h4>{{ $category->name }} Products</h4>
                        <p class="text-muted">{{ $products->total() }} products found</p>
                    </div>
                </div>
            </div>

            <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
                @foreach($products as $product)
                <div class="col-lg-3 col-6 col-sm-6 mb-4">
                    <a href="{{ route('product.details', $product->id) }}">
                        <div class="quicktech-product">
                            <div class="quikctech-wishlist">
                                <button><i class="fa-solid fa-heart"></i></button>
                            </div>
                            
                            {{-- Sold Count --}}
                            @php
                                $soldCount = $product->orderItems->sum('quantity') ?? 0;
                            @endphp
                            @if($soldCount > 0)
                            <div class="quicktech-sold">
                                <span>
                                    @if($soldCount >= 1000)
                                        {{ number_format($soldCount / 1000, 1) }}k sold
                                    @else
                                        {{ $soldCount }} sold
                                    @endif
                                </span>
                            </div>
                            @endif

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
                                            $productImage = asset('public/' . $primary->image_path);
                                        } else {
                                            $firstImage = $images->first();
                                            if ($firstImage && file_exists(public_path($firstImage->image_path))) {
                                                $productImage = asset('public/' . $firstImage->image_path);
                                            }
                                        }
                                    }
                            
                                    // 3. If still no image → check promotion image
                                    if (!$productImage && $product->promotion_image) {
                                        if (file_exists(public_path($product->promotion_image))) {
                                            $productImage = asset('public/' . $product->promotion_image);
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
                                <h6>{{ $product->name }}</h6>
                                <div class="d-flex justify-content-between quicktech-pp-t">
                                    <p>
                                        @if($product->special_price && $product->special_price < $product->price)
                                            ৳ {{ number_format($product->special_price) }}
                                            <br>
                                            <span style="font-size: 13px;">
                                                <s>৳ {{ number_format($product->price) }}</s>
                                                @php
                                                    $discount = (($product->price - $product->special_price) / $product->price) * 100;
                                                @endphp
                                                -{{ round($discount) }}%
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

                {{-- Fallback if no products --}}
                @if($products->count() == 0)
                <div class="col-12 text-center py-5">
                    <div class="mb-3">
                        <i class="fa-solid fa-box-open fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No products found in {{ $category->name }}</h4>
                    <p class="text-muted">Check other categories or come back later.</p>
                    <a href="{{ route('vendorproduct.index') }}" class="btn btn-primary">Browse All Products</a>
                </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if($products->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="quikctech-pagination d-flex justify-content-center">
                        {{ $products->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
@endsection