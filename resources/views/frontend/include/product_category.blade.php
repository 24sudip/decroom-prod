<section id="quicktech-product-c">
    <div class="container">
        <div class="row mb-4">

            <!-- Categories Sidebar -->
            <!--<div class="col-lg-3">-->
            <!--    <div class="row quicktech-head-border">-->
            <!--        <div class="col-lg-12">-->
            <!--            <div class="quikctech-category-n-head p-2 border-bottom text-center">-->
            <!--                <h4>{{ __('messages.categories') }}</h4>-->
            <!--            </div>-->
            <!--            <div class="quikctech-category-n-n p-2 border-bottom text-center">-->
            <!--                <h4>{{ __('messages.product') }}</h4>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="row quicktech-border py-3">-->
            <!--        <div class="col-lg-12">-->
            <!--            <div class="quick-c-p">-->
            <!--                @if(isset($productCategories) && $productCategories->count() > 0)-->
            <!--                    @foreach($productCategories->take(10) as $category)-->
            <!--                    <div class="c-inner">-->
            <!--                        <a href="{{ route('product_category', $category->slug) }}">-->
            <!--                            <div class="quicktech-categories-side" style="height: 160px;">-->
            <!--                                @if($category->image)-->
            <!--                                    <img src="{{ asset('public/storage/categories/' . $category->image) }}" alt="{{ $category->name }}" style="height: 75px; width: 75px">-->
            <!--                                @else-->
            <!--                                    <img src="{{ asset('frontend') }}/images/wedding-dress 1.png" alt="{{ $category->name }}" style="height: 75px; width: 75px">-->
            <!--                                @endif-->
            <!--                                <p>{{ Str::limit($category->name, 20) }}</p>-->
            <!--                            </div>-->
            <!--                        </a>-->
            <!--                    </div>-->
            <!--                    @endforeach-->
            <!--                @else-->
            <!--                    @for($i = 0; $i < 10; $i++)-->
            <!--                    <div class="c-inner">-->
            <!--                        <a href="#">-->
            <!--                            <div class="quicktech-categories-side">-->
            <!--                                <img src="{{ asset('frontend') }}/images/wedding-dress 1.png" alt="{{ __('messages.category') }}">-->
            <!--                                <p>{{ __('messages.category') }} {{ $i + 1 }}</p>-->
            <!--                            </div>-->
            <!--                        </a>-->
            <!--                    </div>-->
            <!--                    @endfor-->
            <!--                @endif-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->

            <!-- Products Main Content -->
            <div class="col-lg-12">
                <!-- Products Section -->
                <div class="row quicktech-head-border quikctech-margin">
                    <div class="col-lg-12">
                        <div class="quicktech-head">
                            <!--<h4>{{ __('messages.offer_on_products') }}</h4>-->
                            <h4>All Product</h4>
                            <!--<a href="{{route('vendorproduct.index')}}">{{ __('messages.explore_all_offers') }}</a>-->
                            <a href="{{route('vendorproduct.index')}}">Explore All Products</a>
                        </div>
                    </div>
                </div>

                <div class="row py-4 quikctech-margin quicktech-border gapp" id="productArea">

                    @if(isset($allProducts) && $allProducts->count() > 0)
                        @foreach($allProducts->take(6) as $product)
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

                                    <div class="quikctech-img-product text-center">
                                        @php
                                            $defaultImage = asset('frontend/images/productmain.png');
                                            $productImage = null;
                                            $images = \App\ProductImage::where('product_id', $product->id)->get();

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

                                            if (!$productImage && $product->promotion_image) {
                                                if (file_exists(public_path($product->promotion_image))) {
                                                    $productImage = asset($product->promotion_image);
                                                }
                                            }

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
                        <h3 class="text-center">{{ __('messages.no_product') }}</h3>
                    @endif

                    <div class="col-lg-12 mt-4 text-center">
                        <div class="quikctech-load-more text-center">
                            <a href="#" id="loadMoreProducts" class="quikctech-load-more">{{ __('messages.load_more') }}</a>
                        </div>
                    </div>
                </div>

                <br>

                <!-- Services Section -->
                <div class="row quicktech-head-border quikctech-margin">
                    <div class="col-lg-12">
                        <div class="quicktech-head">
                            <h4>{{ __('messages.services_for_you') }}</h4>
                            <a href="{{ route('service.index') }}">Explore all Services</a>
                        </div>
                    </div>
                </div>

                <div class="row py-4 quikctech-margin quicktech-border gapp" id="serviceArea">
                    @if(isset($services) && $services->count() > 0)
                        @foreach($services->take(6) as $service)
                        <div class="col-lg-4 col-6 col-sm-6">
                            <a href="{{ url('service-details/' . $service->id) }}">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        @if($service->catalog && Str::contains($service->catalog, ['.mp4', '.mov', '.avi']))
                                            <video src="{{ asset('public/storage/' . $service->catalog) }}" class="w-100" controls></video>
                                        @elseif($service->attachment && Str::contains($service->attachment, ['.mp4', '.mov', '.avi']))
                                            <video src="{{ asset('public/storage/' . $service->attachment) }}" class="w-100" controls></video>
                                        @elseif($service->catalog && Str::contains($service->catalog, ['.webp', '.png', '.jpg', '.jpeg', '.svg', '.gif']))
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <img src="{{ asset($service->catalog) }}" class="w-100" alt="{{ $service->title }}" style="height: 150px; object-fit: cover;">
                                            </div>
                                        @else
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <img src="{{ asset($service->attachment) }}" class="w-100" alt="{{ $service->title }}" style="height: 150px; object-fit: cover;">
                                            </div>
                                        @endif
                                    </div>

                                    <div class="quicktech-product-text">
                                        <h6>
                                            {{ Str::limit($service->title, 40) }}
                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i>
                                                {{ $service->vendor->name ?? __('messages.vendor_name') }}
                                            </span>
                                        </h6>
                                        <p>
                                            <img src="{{ asset('frontend') }}/images/taka 1.png" alt="{{ __('messages.price') }}">
                                            @if($service->discount > 0)
                                                <span class="text-danger">
                                                    {{ number_format($service->total_cost - $service->discount) }}
                                                    <small class="text-muted text-decoration-line-through">{{ number_format($service->total_cost) }}</small>
                                                </span>
                                            @else
                                                {{ $service->total_cost ? '৳ ' . number_format($service->total_cost) : __('messages.negotiable') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    @else
                        <h3 class="text-center">{{ __('messages.no_service_available') }}</h3>
                    @endif

                    <div class="col-lg-12 mt-4 text-center">
                        <div class="quikctech-load-more text-center">
                            <a href="#" id="loadMoreServices" class="quikctech-load-more">{{ __('messages.load_more') }}</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let productSkip = 6;
let serviceSkip = 6;

// Create a loading element
const loadingHtml = '<div class="text-center my-3" id="loadingSpinner"><span>{{ __("messages.loading") }}</span></div>';

// Load More Products
$('#loadMoreProducts').click(function(e){
    e.preventDefault();

    // Show loading
    $('#loadMoreProducts').after(loadingHtml);

    $.get("{{ route('load.more.products') }}", { skip: productSkip }, function(response){
        $('#productArea').append(response.html);

        // Hide loading
        $('#loadingSpinner').remove();

        if(response.count < 6){
            $('#loadMoreProducts').hide();
        }
        productSkip += 6;
    }).fail(function(){
        $('#loadingSpinner').remove();
        alert('{{ __("messages.something_went_wrong") }}');
    });
});

// Load More Services
$('#loadMoreServices').click(function(e){
    e.preventDefault();

    // Show loading
    $('#loadMoreServices').after(loadingHtml);

    $.get("{{ route('load.more.services') }}", { skip: serviceSkip }, function(response){
        $('#serviceArea').append(response.html);

        // Hide loading
        $('#loadingSpinner').remove();

        if(response.count < 6){
            $('#loadMoreServices').hide();
        }
        serviceSkip += 6;
    }).fail(function(){
        $('#loadingSpinner').remove();
        alert('{{ __("messages.something_went_wrong") }}');
    });
});
</script>
