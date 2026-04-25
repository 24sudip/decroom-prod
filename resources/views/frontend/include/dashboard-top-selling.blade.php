<div class="quikctech-dashboard-top-selling">
    <div class="row gapp">
        <div class="col-lg-6">
            <div class="quikctech-dash-main">
                <div class="quikctech-top-head-dash">
                    <h5>{{ __('messages.top_selling_items') }}</h5>
                    <a href="{{ route('vendor.products.manage') }}">{{ __('messages.view_all') }}</a>
                </div>
                <div class="quikctech-dash-top-pro">
                    <div class="row gapp pt-2">
                        @forelse($topSellingProducts as $product)
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="{{ route('product.details', $product->id) }}">
                                <div class="quicktech-product">
                                    <div class="quikctech-wishlist">
                                        <button><i class="fa-solid fa-heart"></i></button>
                                    </div>
                                    <div class="quicktech-sold">
                                        <span>{{ $product->total_sold ?? 0 }} {{ __('messages.sold') }}</span>
                                    </div>
                                    <div class="quikctech-img-product text-center">
                                        {{-- Use the primaryImage method from Product model --}}
                                        @php
                                            $primaryImage = $product->primaryImage();
                                        @endphp
                                        
                                        @if($primaryImage && $primaryImage->image_path)
                                            <img src="{{ asset($primaryImage->image_path) }}" alt="{{ $product->name }}" style="height: 120px; object-fit: cover;">
                                        @elseif($product->promotion_image)
                                            <img src="{{ asset($product->promotion_image) }}" alt="{{ $product->name }}" style="height: 120px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('frontend/images/placeholder.jpg') }}" alt="{{ __('messages.no_image') }}" style="height: 120px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6>{{ Str::limit($product->name, 50) }}</h6>
                                        <div class="d-flex justify-content-between quicktech-pp-t">
                                            <p>৳ {{ number_format($product->price, 2) }}
                                                <br>
                                                @if($product->special_price)
                                                <span style="font-size: 13px;">
                                                    <s>৳ {{ number_format($product->price, 2) }}</s> 
                                                    -{{ number_format((($product->price - $product->special_price) / $product->price) * 100, 0) }}%
                                                </span>
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
                        @empty
                        <div class="col-12">
                            <div class="text-center py-4">
                                <p>{{ __('messages.no_products_found') }}</p>
                            </div>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="quikctech-dash-main">
                <div class="quikctech-top-head-dash">
                    <h5>{{ __('messages.top_rated_services') }}</h5>
                    <a href="{{route('services.index')}}">{{ __('messages.view_all') }}</a>
                </div>
                <div class="quikctech-dash-top-pro">
                    <div class="row gapp pt-2">
                        @forelse($topRatedServices as $service)
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        @if($service->service_video)
                                            <video src="{{ asset($service->service_video) }}" class="w-100" style="height: 120px; object-fit: cover;" controls></video>
                                        @elseif($service->catalog)
                                            <img src="{{ asset($service->catalog) }}" alt="{{ $service->title }}" style="height: 120px; object-fit: cover;">
                                        @else
                                            <img src="{{ asset('frontend/images/placeholder.jpg') }}" alt="{{ __('messages.no_image') }}" style="height: 120px; object-fit: cover;">
                                        @endif
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6>{{ Str::limit($service->title, 50) }}
                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> {{ $vendor->shop_name ?? __('messages.shop_name') }}
                                            </span>
                                        </h6>
                                        <p>
                                            <img src="{{ asset('frontend/images/taka 1.png') }}" alt="{{ __('messages.currency') }}"> 
                                            @if($service->total_cost)
                                                ৳ {{ number_format($service->total_cost, 2) }}
                                            @else
                                                {{ __('messages.negotiable') }}
                                            @endif
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @empty
                        {{-- Static fallback content when no services placeholder.jpg --}}
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <img src="{{ asset('frontend/images/logo.png') }}" alt="{{ __('messages.service') }}" style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6>{{ __('messages.get_discount_architect') }}
                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> {{ $vendor->shop_name ?? __('messages.shop_name') }}
                                            </span>
                                        </h6>
                                        <p><img src="{{ asset('frontend/images/taka 1.png') }}" alt="{{ __('messages.currency') }}"> {{ __('messages.negotiable') }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <img src="{{ asset('frontend/images/logo.png') }}" alt="{{ __('messages.service') }}" style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6>{{ __('messages.professional_interior_design') }}
                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> {{ $vendor->shop_name ?? __('messages.shop_name') }}
                                            </span>
                                        </h6>
                                        <p><img src="{{ asset('frontend/images/taka 1.png') }}" alt="{{ __('messages.currency') }}"> {{ __('messages.negotiable') }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <img src="{{ asset('frontend/images/logo.png') }}" alt="{{ __('messages.service') }}" style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6>{{ __('messages.home_renovation_consultation') }}
                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> {{ $vendor->shop_name ?? __('messages.shop_name') }}
                                            </span>
                                        </h6>
                                        <p><img src="{{ asset('frontend/images/taka 1.png') }}" alt="{{ __('messages.currency') }}"> {{ __('messages.negotiable') }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <img src="{{ asset('frontend/images/logo.png') }}" alt="{{ __('messages.service') }}" style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6>{{ __('messages.construction_project_management') }}
                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> {{ $vendor->shop_name ?? __('messages.shop_name') }}
                                            </span>
                                        </h6>
                                        <p><img src="{{ asset('frontend/images/taka 1.png') }}" alt="{{ __('messages.currency') }}"> {{ __('messages.negotiable') }}</p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>