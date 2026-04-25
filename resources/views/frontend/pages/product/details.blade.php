@extends('frontend.layouts.master')
@section('title', $product->name . ' - Product Details')
@section('content')
@include('frontend.include.breadcumb')

<section id="quicktech-product-details">
    <div class="container">
        <div class="row quicktexch-mm my-3">
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-6">
                        @php
                            $defaultImage = asset('frontend/images/productmain.png');
                            $productImages = \App\ProductImage::where('product_id', $product->id)->get();
                            $productImage = null;

                            if($productImages->count() > 0){
                                $primaryImage = $productImages->where('is_primary', true)->first();
                                $productImage = $primaryImage ? $primaryImage->image_path : $productImages->first()->image_path;
                                if ($productImage && !file_exists(public_path($productImage))) {
                                    $productImage = $product->promotion_image;
                                }
                            }

                            $displayImage = $productImage ? asset($productImage) : $defaultImage;

                            $validThumbnails = [];
                            if($productImages->count() > 1){
                                foreach($productImages as $image){
                                    if($image->image_path && file_exists(public_path($image->image_path))){
                                        $validThumbnails[] = $image;
                                    }
                                }
                            }
                        @endphp

<div class="quikctech-product-image-main" style="display: none;">
    <img id="main-image" src="{{ asset($product->promotion_image) }}" class="w-100" alt="{{ $product->name }}">
</div>
                        @if ($product->id == 12)
                        <div class="quikctech-product-image-main">
                            <img id="main-image" src="{{ asset($product->promotion_image) }}" class="w-100" alt="{{ $product->name }}">
                        </div>
                        @else
                        <div class="quikctech-product-image-main">
                            <img id="main-image" src="{{ $displayImage }}" class="w-100" alt="{{ $product->name }}" onerror="this.src='{{ $defaultImage }}'">
                        </div>
                        @endif

                        @if(count($validThumbnails) > 1)
                        <div class="quikctech-product-thumblin mt-4" style="overflow-x: hidden;">
                            <div class="swiperthumblin">
                                <div class="swiper-wrapper">
                                    @foreach($validThumbnails as $image)
                                    <div class="swiper-slide">
                                        <div class="quikctech-thublin-img">
                                            <img src="{{ asset($image->image_path) }}" alt="{{ $product->name }}" onclick="changeImage('{{ asset($image->image_path) }}')" onerror="this.src='{{ $defaultImage }}'">
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                <div class="swiper-button-prevv"><i class="fa-solid fa-angle-left"></i></div>
                                <div class="swiper-button-nextt"><i class="fa-solid fa-angle-right"></i></div>
                            </div>
                        </div>
                        @endif
                    </div>

                    <div class="col-lg-6">
                        <div class="quicktech-product-d-details">
                            <h2>{{ $product->name }}</h2>
                            <div class="quikctech-r-s-main mt-2 d-flex justify-content-between">
                                @php
                                    $averageRating = $product->ratings()->avg('rating') ?? 0;
                                    $ratingCount = $product->ratings()->count();
                                @endphp

                                <div class="quikcteh-rating-inner">
                                    <ul class="d-flex align-items-center gap-1">
                                        @for($i=1; $i<=5; $i++)
                                            @if($i <= round($averageRating))
                                                <li><i class="fa-solid fa-star" style="color: #FFD700;"></i></li>
                                            @else
                                                <li><i class="fa-regular fa-star" style="color: #ccc;"></i></li>
                                            @endif
                                        @endfor
                                        <li style="color: #1d71ff; font-weight: 600;">{{ number_format($averageRating,1) }} ({{ $ratingCount }} Reviews)</li>
                                    </ul>
                                </div>

                                <div class="quikctech-share">
                                    <ul class="d-flex gap-2">
                                        <li><a href="#"><i class="fa-solid fa-share-nodes"></i></a></li>
                                        <li><a href="#"><i class="fa-solid fa-heart"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="quicktech-brand mt-2">
                                <ul class="d-flex gap-1">
                                    <li style="color: rgba(0, 0, 0, 0.521);">Brand:</li>
                                    <li style="color: #1d71ff;"><a href="{{ route('brand_wise_product', $product->brand->id) }}">{{ $product->brand->name ?? 'No Brand' }}</a></li>
                                    @if($product->brand)
                                        <li>|</li>
                                        <li style="color: #1d71ff;">More {{ $product->category->name ?? 'Products' }} From {{ $product->brand->name }}</li>
                                    @endif
                                </ul>
                            </div>
                            <hr>

                            <div class="quikctech-product-price">
                                @if($product->special_price && $product->special_price < $product->price)
                                    <h3>৳ {{ number_format($product->special_price) }}</h3>
                                    <span><s>৳ {{ number_format($product->price) }}</s> -{{ number_format((($product->price - $product->special_price)/$product->price)*100) }}%</span>
                                @else
                                    <h3>৳ {{ number_format($product->price) }}</h3>
                                @endif
                            </div>
                            <hr>

                            {{-- Variant Options --}}
                            @if($product->variants && count($product->variants) > 0)
                                @php $groupedVariants = $product->variants->groupBy('name'); @endphp
                                @foreach($groupedVariants as $variantName => $variants)
                                <div class="quikctech-{{ strtolower($variantName) }} mb-3">
                                    <h4>{{ $variantName }}:</h4>
                                    <div class="d-flex gap-2 flex-wrap">
                                        @foreach($variants as $variant)
                                            @php
                                                $variantImage = $defaultImage;
                                                if(isset($variant->image) && file_exists(public_path($variant->image)))
                                                    $variantImage = asset($variant->image);
                                            @endphp

                                            @if(strtolower($variantName) == 'color')
                                                <div class="color-img" style="width:30px;height:30px;border-radius:50%;background-color: {{ $variant->value }};cursor:pointer; border:1px solid #ccc;"
                                                     onclick="selectVariant({{ $variant->id }}, '{{ $variantName }}', '{{ $variant->value }}', {{ $variant->additional_price }}, {{ $variant->stock }}, '{{ $variantImage }}')">
                                                </div>
                                            @elseif(strtolower($variantName) == 'size')
                                                <div class="size-num px-2 py-1 border rounded" style="cursor:pointer;"
                                                     onclick="selectVariant({{ $variant->id }}, '{{ $variantName }}', '{{ $variant->value }}', {{ $variant->additional_price }}, {{ $variant->stock }}, '{{ $variantImage }}')">
                                                    {{ $variant->value }}
                                                </div>
                                            @else
                                                <p class="{{ strtolower($variantName) }}-option"
                                                   onclick="selectVariant({{ $variant->id }}, '{{ $variantName }}', '{{ $variant->value }}', {{ $variant->additional_price }}, {{ $variant->stock }}, '{{ $variantImage }}')">
                                                    {{ $variant->value }}
                                                </p>
                                            @endif
                                        @endforeach
                                    </div>
                                </div>
                                @endforeach
                            @endif

                            <div class="quicktech-quantity mt-4">
                                <div class="quikctech-quan-head"><h4>Quantity</h4></div>
                                <div class="counter-container d-flex align-items-center gap-2">
                                    <button class="counter-btn" id="decrease">-</button>
                                    <span id="number">1</span>
                                    <button class="counter-btn" id="increase">+</button>
                                </div>
                                <div id="stock-message" class="mt-2 small text-muted"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 my-5">
                        <div class="quikctech-pro-btns d-flex gap-2">
                            <a style="background-color: #0AAFCF; color: white;" href="javascript:void(0)" id="add-to-cart-btn">Add To Cart</a>
                            <a style="background-color: #DF9712; color: white;" href="javascript:void(0)" id="buy-now-btn">Order Now</a>
                        </div>
                    </div>
                </div>
            </div>

            @include('frontend.include.delivery_seller_info')

        </div>

        <div class="row">
            @include('frontend.include.product_details', ['product' => $product])
        </div>
    </div>
</section>

@include('frontend.include.review_rating')
@include('frontend.include.askquestion')
@include('frontend.include.related_product')

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function(){
    let selectedVariantId = null;
    let selectedVariantPrice = {{ $product->special_price ?? $product->price }};
    let selectedVariantStock = {{ $product->stock }};
    let selectedVariantImage = '{{ $displayImage }}';
    let currentQuantity = 1;
    let maxStock = {{ $product->stock }};
    const defaultImage = '{{ $defaultImage }}';

    function changeImage(src){
        const mainImage = document.getElementById('main-image');
        mainImage.src = src;
        mainImage.onerror = function(){ this.src = defaultImage; }
    }

    function selectVariant(variantId, variantName, variantValue, price, stock, image){
        selectedVariantId = variantId;
        selectedVariantPrice = Number(price);
        selectedVariantStock = stock;
        selectedVariantImage = image;
        maxStock = stock;

        changeImage(image);

        // Remove active from all of this variant type
        document.querySelectorAll(`.${variantName.toLowerCase()}-option, .color-img, .size-num`).forEach(el => el.classList.remove('active'));

        // Add active to selected
        document.querySelectorAll(`.${variantName.toLowerCase()}-option, .color-img, .size-num`).forEach(el=>{
            if(el.textContent===variantValue || el.style.backgroundColor===variantValue) el.classList.add('active');
        });

        updateStockMessage();
        updateQuantity();
    }

    function updateQuantity(){
        document.getElementById('number').textContent = currentQuantity;
        updateStockMessage();
    }

    function updateStockMessage(){
        const stockMessage = document.getElementById('stock-message');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const buyNowBtn = document.getElementById('buy-now-btn');
        const increaseBtn = document.getElementById('increase');

        if(maxStock <=0){
            stockMessage.innerHTML='<span class="text-danger">Out of Stock</span>';
            [addToCartBtn,buyNowBtn].forEach(btn=>{ btn.style.opacity=0.6; btn.style.pointerEvents='none'; btn.disabled=true; });
            if(increaseBtn) increaseBtn.disabled=true;
        } else if(maxStock<=5){
            stockMessage.innerHTML=`<span class="text-warning">Only ${maxStock} left in stock!</span>`;
            [addToCartBtn,buyNowBtn].forEach(btn=>{ btn.style.opacity=1; btn.style.pointerEvents='auto'; btn.disabled=false; });
            if(increaseBtn) increaseBtn.disabled = currentQuantity>=maxStock;
        } else {
            stockMessage.innerHTML=`<span class="text-success">In Stock (${maxStock} available)</span>`;
            [addToCartBtn,buyNowBtn].forEach(btn=>{ btn.style.opacity=1; btn.style.pointerEvents='auto'; btn.disabled=false; });
            if(increaseBtn) increaseBtn.disabled = currentQuantity>=maxStock;
        }
    }

    function addToCart(isBuyNow){
        const cartItemId='{{ $product->id }}'+(selectedVariantId?'_'+selectedVariantId:'');
        const price = Number(selectedVariantPrice);

        const formData = new FormData();
        formData.append('_token','{{ csrf_token() }}');
        formData.append('id',cartItemId);
        formData.append('name','{{ $product->name }}');
        formData.append('price',price);
        formData.append('qty',currentQuantity);
        formData.append('variant_id',selectedVariantId||'');
        formData.append('image',selectedVariantImage);

        const addBtn=document.getElementById('add-to-cart-btn');
        const buyBtn=document.getElementById('buy-now-btn');
        const originalAdd=addBtn?addBtn.innerHTML:'';
        const originalBuy=buyBtn?buyBtn.innerHTML:'';

        if(isBuyNow && buyBtn){ buyBtn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Adding...'; buyBtn.disabled=true; }
        else if(addBtn){ addBtn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Adding...'; addBtn.disabled=true; }

        fetch('{{ route("cart.add") }}',{ method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}, body:formData })
        .then(r=>r.ok? r.json() : Promise.reject('HTTP error'))
        .then(data=>{
            if(data.success) {
                updateCartCount(data.cart_count);
                // ✅ BUY NOW → direct redirect, no SweetAlert
                if (isBuyNow) {
                    window.location.href = '{{ route("checkout") }}';
                    return;
                }
                Swal.fire({
                    icon:'success',
                    title:'Success!',
                    text:data.message,
                    showCancelButton:true,
                    confirmButtonText:isBuyNow?'Checkout':'View Cart',
                    cancelButtonText:'Continue Shopping',
                    showDenyButton:!isBuyNow,
                    denyButtonText:'Checkout'
                }).then(result=>{
                    if(result.isConfirmed){ window.location.href=isBuyNow?'{{ route("checkout") }}':'{{ route("cart.view") }}'; }
                    else if(result.isDenied){ window.location.href='{{ route("checkout") }}'; }
                });
            } else { throw new Error(data.message||'Failed to add to cart'); }
        })
        .catch(e=>{ console.error(e); Swal.fire({icon:'error',title:'Error',text:e.message||'Something went wrong'}); })
        .finally(()=>{ if(addBtn){ addBtn.innerHTML=originalAdd; addBtn.disabled=false; } if(buyBtn){ buyBtn.innerHTML=originalBuy; buyBtn.disabled=false; } });
    }

    document.addEventListener('DOMContentLoaded',function(){
        const decreaseBtn=document.getElementById('decrease');
        const increaseBtn=document.getElementById('increase');

        decreaseBtn?.addEventListener('click',()=>{ if(currentQuantity>1){ currentQuantity--; updateQuantity(); } });
        increaseBtn?.addEventListener('click',()=>{ if(currentQuantity<maxStock){ currentQuantity++; updateQuantity(); } });

        document.getElementById('add-to-cart-btn')?.addEventListener('click',()=>addToCart(false));
        document.getElementById('buy-now-btn')?.addEventListener('click',()=>addToCart(true));

        @if($product->variants && count($product->variants)>0)
            const firstVariant = {!! $product->variants->first() !!};
            selectVariant(firstVariant.id, firstVariant.name||'Variant', firstVariant.value, firstVariant.additional_price, firstVariant.stock, firstVariant.image? '{{ asset('public/') }}/'+firstVariant.image : '{{ $displayImage }}');
        @endif

        @if(count($validThumbnails)>1)
        new Swiper('.swiperthumblin',{ slidesPerView:4, spaceBetween:10, navigation:{nextEl:'.swiper-button-nextt', prevEl:'.swiper-button-prevv'}, breakpoints:{320:{slidesPerView:3,spaceBetween:5},768:{slidesPerView:4,spaceBetween:10}} });
        @endif
    });
})();
</script>

<style>
.counter-btn:disabled{ opacity:.5; cursor:not-allowed; }
.quikctech-pro-btns a{ padding:12px 30px; text-decoration:none; border-radius:5px; font-weight:600; transition:all .3s ease; cursor:pointer;}
.quikctech-pro-btns a:hover:not(:disabled){ opacity:.9; transform:translateY(-2px);}
.quikctech-pro-btns a:disabled{ opacity:.6; cursor:not-allowed; transform:none;}
.color-img.active{ border:3px solid #0AAFCF; border-radius:50%; }
.size-num.active{ background-color:#0AAFCF!important; color:white!important; font-weight:600;}
img{ object-fit:cover; }
</style>
@endpush
