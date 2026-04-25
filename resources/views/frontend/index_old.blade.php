@extends('frontend.layouts.master')
@section('title', 'Home')
@section('content')
    <!-- search-content -->
    @include('frontend.include.search_content')
    <!-- search-content -->

    <!-- banner -->
    @include('frontend.include.slider')
    <!-- banner -->
    
    <style>
.quicktech-cater-inner {
	width: 100%;
	height: 192px;
	position: relative;
	border-radius: 31px;
	overflow: hidden;
}
        .quicktech-cater-inner img{
            width:100%;
            height:100%;
            
        }
        
        .quicktech-category-text{
            position: absolute;
          bottom: 0;
          left: 50%;
          transform: translateX(-50%);
          background-color: #ffffffde;
          width: 82%;
          text-align: center;
          padding-top: 9px;
          border-top-left-radius: 19px;
          border-top-right-radius: 17px;
        }
    </style>

    <!-- categories -->
    <section id="quicktech-categories">
        <div class="container-fluid">
            <div class="row mb-3 mt-5">
                <div class="col-lg-12">
                    <div class="quicktech-head text-center">
                        <h3>Browse by Category</h3>
                    </div>
                </div>
            </div>
           
            <div class="row gapp mb-5">
                  @php
                    use App\ProductCategory;
                    $categories = ProductCategory::with(['subcategories.childcategories'])->where('is_home', 1)
                                    ->orderByRaw("id = 13 DESC")
                                    ->orderBy('id', 'ASC')
                                    ->get();
                @endphp
                @foreach ($categories as $category)
                <div class="col-lg-2 col-sm-6 col-6 col-md-4 mb-4">
                 <a href="{{ url('category/' . $category->slug) }}">
                    <div class="quicktech-cater-inner">
                       <img src="{{ $category->image ? asset('public/storage/categories/' . $category->image) : asset('public/storage/categories/default.png') }}" alt="{{ $category->name }}">
                        <div class="quicktech-category-text">
                          <h4>{{ $category->name }}</h4>
                        </div>
                    </div>
                 </a>
                </div>
                  @endforeach
            </div>
            
        </div>
    </section>
    <!-- categories -->


    <!-- especially -->
    <section id="quicktech-especially">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="quicktech-main-head text-center">
                        <h3>Especially For you</h3>
                    </div>
                </div>
            </div>
            <div class="row quicktech-inn-main">
                <div class="col-lg-3 col-6">
                        <div class="quicktech-especially-inner">
                            <div class="quikctech-especially-text">
                                <div>
                                    <img class="quicktech-es-img" src="{{ asset('frontend') }}/images/whatsapp(3).png"
                                        alt="">
                                </div>
                                <div class="quikctech-t">
                                    <h5>Order <span> Via WhatsApp</span> <br>
                                        01911671514 </h5>
                                    <a href="tel:01911671514">CALL NOW</a>
                                </div>

                            </div>
                        </div>
                </div>
                <div class="col-lg-3 col-6">
                    <div class="quicktech-especially-innertwo">
                            <div class="quikctech-especially-text">
                                <div>
                                    <img class="quicktech-es-img" src="{{ asset('frontend') }}/images/cash-back(1).png"
                                        alt="">
                                </div>
                                <div class="quikctech-t">
                                    <h5>UPTO <span> 10% OFF </span> <br>
                                        + Cashback </h5>
                                    <a href="{{ route('frontend.prescriptions.create') }}">Upload Prescription</a>
                                </div>
                            </div>
                        </div>
                </div>
                <div class="col-lg-3 col-6">
                     <div class="quicktech-especially-innerthree">
                            <div class="quikctech-especially-text">
                                <div>
                                    <img class="quicktech-es-img" src="{{ asset('frontend') }}/images/health-insurance.png"
                                        alt="">
                                </div>
                                <div class="quikctech-t">
                                    <h5>UPTO <span> 16% OFF </span> <br>
                                        + Cashback </h5>
                                    <a href="">HealthCare</a>
                                </div>

                            </div>
                        </div>
                </div>
                <div class="col-lg-3 col-6">
                      <div class="quicktech-especially-inner">
                            <div class="quikctech-especially-text">
                                <div>
                                    <img class="quicktech-es-img"
                                        src="{{ asset('frontend') }}/images/customer-service(3).png" alt="">
                                </div>
                                <div class="quikctech-t">
                                    <h5>UPTO <span> 16% OFF </span> <br>
                                        + Cashback </h5>
                                    <a href="tel:01812989321">Call to order</a>
                                </div>

                            </div>
                        </div>
                </div>
            </div>
        </div>
    </section>
    <!-- especially -->

    <!-- New Arival -->
    <section id="quicktech-flash-sale">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="quicketch-flashsale-head">
                        <h2>New Arrival</h2>
                    </div>
                </div>
            </div>
            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="swiper quikctech-p-slider">
                        <div class="swiper-wrapper">
                            @foreach ($newArivalProduct as $product)
                                <div class="swiper-slide">
                                    <a href="{{ url('product-details/' . $product->id) }}">
                                        <div class="quicktech-product-inner">
                                            <div class="overlay"></div>

                                            @php
                                                $customer = Auth::guard('customer')->user();
                                                $isWholesale = $customer && $customer->type == 'wholesale';

                                                $defaultVariant = $product->variants->first();

                                                if ($defaultVariant) {
                                                    $price = $isWholesale
                                                        ? $defaultVariant->varient_wholesale_price ??
                                                            $defaultVariant->price
                                                        : $defaultVariant->price;

                                                    $oldPrice = $product->old_price;
                                                } else {
                                                    $price = $isWholesale
                                                        ? $product->wholesale_price
                                                        : $product->new_price;
                                                    $oldPrice = $product->old_price;
                                                }

                                                $discount = $oldPrice
                                                    ? round((($oldPrice - $price) / $oldPrice) * 100)
                                                    : null;
                                            @endphp

                                            @if ($discount)
                                                <div class="quikctech-offer">
                                                    <p>{{ $discount }}% Off</p>
                                                </div>
                                            @endif

                                            <div class="quicktech-product-main">
                                                <div class="quikctech-p-img">
                                                    <img class="quikctech-p-im"
                                                        src="{{ $product->image ? asset('public/storage/products/' . $product->image) : asset('public/storage/products/default.png') }}"
                                                        alt="{{ $product->name }}">

                                                </div>
                                                <div class="quikctech-p-text">
                                                    <h5
                                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;">
                                                        {{ $product->name }}
                                                    </h5>

                                                    <div class="quikctech-p-add">
                                                        <h5>
                                                            Tk.{{ number_format($price) }}
                                                            @if ($product->old_price && $product->old_price > $price)
                                                                <s>Tk.{{ number_format($product->old_price) }}</s>
                                                            @endif
                                                        </h5>
                                                        <a class="quikctech-add-p-btn"
                                                            href="{{ url('cart/add/' . $product->id) }}">Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </section>


    <!-- New Arival -->


    <!-- best selling -->
    <section id="quikctech-best-selling">
        <div class="container">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="quicketch-flashsale-head">
                        <h2>Best Selling Products</h2>
                    </div>
                </div>
            </div>

            <div class="row mt-3">
                <div class="col-lg-12">
                    <div class="swiper quikctech-p-slider">
                        <!-- Swiper Wrapper -->
                        <div class="swiper-wrapper">
                            @foreach ($bestSaleProduct as $product)
                                <div class="swiper-slide">
                                    <a href="{{ url('product-details/' . $product->id) }}">
                                        <div class="quicktech-product-inner">
                                            <div class="overlay"></div>

                                            @php
                                                $customer = Auth::guard('customer')->user();
                                                $isWholesale = $customer && $customer->type == 'wholesale';

                                                $defaultVariant = $product->variants->first();

                                                if ($defaultVariant) {
                                                    $price = $isWholesale
                                                        ? $defaultVariant->varient_wholesale_price ??
                                                            $defaultVariant->price
                                                        : $defaultVariant->price;

                                                    $oldPrice = $product->old_price;
                                                } else {
                                                    $price = $isWholesale
                                                        ? $product->wholesale_price
                                                        : $product->new_price;
                                                    $oldPrice = $product->old_price;
                                                }

                                                $discount =
                                                    $oldPrice && $price
                                                        ? round((($oldPrice - $price) / $oldPrice) * 100)
                                                        : null;
                                            @endphp

                                            @if ($discount)
                                                <div class="quikctech-offer">
                                                    <p>{{ $discount }}% Off</p>
                                                </div>
                                            @endif

                                            <div class="quicktech-product-main">
                                                <div class="quikctech-p-img">
                                                    <img class="quikctech-p-im"
                                                        src="{{ $product->image ? asset('public/storage/products/' . $product->image) : asset('public/storage/products/default.png') }}">
                                                </div>
                                                <div class="quikctech-p-text">
                                                    <h5
                                                        style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;">
                                                        {{ $product->name }}
                                                    </h5>
                                                    <div class="quikctech-p-add">
                                                        <h5>
                                                            Tk.{{ number_format($price) }}
                                                            @if ($product->old_price && $product->old_price > $price)
                                                                <s>Tk.{{ number_format($product->old_price) }}</s>
                                                            @endif
                                                        </h5>
                                                        <a class="quikctech-add-p-btn"
                                                            href="{{ url('cart/add/' . $product->id) }}">Add</a>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- best selling -->

    <!-- Summer Must-Haves -->
    @foreach ($categories->where('is_home', 1) as $category)
        @php
            $homeProducts = $category->products()->latest()->take(10)->get();
            $customer = Auth::guard('customer')->user();
            $isWholesale = $customer && $customer->type == 'wholesale';
        @endphp

        @if ($homeProducts->isNotEmpty())
            <section id="quikctech-category-{{ $category->id }}">
                <div class="container">
                    <!-- Section Header -->
                    <div class="row mt-5">
                        <div class="col-lg-12 d-flex justify-content-between align-items-center">
                            <div class="quicketch-flashsale-head">
                                <h2>{{ $category->name }}</h2>
                            </div>
                            <a href="{{ url('category/' . $category->slug) }}" class="text-primary">
                                View All <i class="fa-solid fa-arrow-right"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Product Slider -->
                    <div class="row mt-3">
                        <div class="col-lg-12">
                            <div class="swiper quikctech-p-slider">
                                <div class="swiper-wrapper">
                                    @foreach ($homeProducts as $product)
                                        @php
                                            $defaultVariant = $product->variants->first();

                                            if ($defaultVariant) {
                                                $price = $isWholesale
                                                    ? $defaultVariant->varient_wholesale_price ?? $defaultVariant->price
                                                    : $defaultVariant->price;

                                                $oldPrice = $product->old_price;
                                            } else {
                                                $price = $isWholesale ? $product->wholesale_price : $product->new_price;
                                                $oldPrice = $product->old_price;
                                            }

                                            $discount =
                                                $oldPrice && $price && $oldPrice > $price
                                                    ? round((($oldPrice - $price) / $oldPrice) * 100)
                                                    : null;
                                        @endphp

                                        <div class="swiper-slide">
                                            <a href="{{ url('product-details/' . $product->id) }}">
                                                <div class="quicktech-product-inner">
                                                    <div class="overlay"></div>

                                                    @if ($discount)
                                                        <div class="quikctech-offer">
                                                            <p>{{ $discount }}% Off</p>
                                                        </div>
                                                    @endif

                                                    <div class="quicktech-product-main">
                                                        <div class="quikctech-p-img">
                                                            <img class="quikctech-p-im"
                                                                src="{{ $product->image ? asset('public/storage/products/' . $product->image) : asset('public/storage/products/default.png') }}"
                                                                alt="{{ $product->name }}">
                                                        </div>
                                                        <div class="quikctech-p-text">
                                                            <h5 class="text-truncate" title="{{ $product->name }}">
                                                                {{ $product->name }}
                                                            </h5>
                                                            <div class="quikctech-p-add">
                                                                <h5>
                                                                    Tk.{{ number_format($price) }}
                                                                    @if ($product->old_price && $product->old_price > $price)
                                                                        <s>Tk.{{ number_format($product->old_price) }}</s>
                                                                    @endif
                                                                </h5>
                                                                <a class="quikctech-add-p-btn"
                                                                    href="{{ url('cart/add/' . $product->id) }}">
                                                                    Add
                                                                </a>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </a>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        @endif
    @endforeach
    <!-- Summer Must-Haves -->


    <!-- Brand -->
    <section id="quikctech-categories">
        <div class="container">
            <div class="row mt-5">
                <div class="col-lg-12">
                    <div class="quicketch-flashsale-head">
                        <h3 style="font-weight: 600;">Popular Brands</h3>
                       <a href="{{ route('allbrand') }}">View All</a>
                    </div>
                </div>
            </div>
            <div class="row gapp mb-5 mt-3">
                <div class="col-lg-12">
                    <!-- Slider main container -->
                <div class="swiperbrands">
                  <!-- Additional required wrapper -->
                  <div class="swiper-wrapper">
                    <!-- Slides -->
                      @forelse ($brands as $brand)
                    <div class="swiper-slide">
                               <div class="quicktech-category-main">
                            <a href="{{ url('brand-product/' . $brand->id) }}">
                                <div class="quikctech-category-img">
                                    <img src="{{ asset('public/storage/brands/' . ($brand->image ?? 'public/storage/brands/default.png')) }}"
                                        alt="{{ $brand->name }}">
                                </div>
                                
                                <!--<div class="quikctech-category-name text-center mt-2">-->
                                <!--    <h5 class="q">{{ $brand->name }}</h5>-->
                                <!--</div>-->
                            </a>
                        </div>      
                    </div>
                     @empty
                    <div class="col-12 text-center">
                        <p>No brands available.</p>
                    </div>
                @endforelse
                  
                  </div>
               
                </div>

                </div>
                
                
                <!--@forelse ($brands as $brand)-->
                <!--    <div class="col-lg-2 col-6 col-sm-4">-->
                <!--        <div class="quicktech-category-main">-->
                <!--            <a href="{{ url('brand-product/' . $brand->id) }}">-->
                <!--                <div class="quikctech-category-img">-->
                <!--                    <img src="{{ asset('public/storage/brands/' . ($brand->image ?? 'public/storage/brands/default.png')) }}"-->
                <!--                        alt="{{ $brand->name }}">-->
                <!--                </div>-->
                                
                <!--                <div class="quikctech-category-name text-center mt-2">-->
                <!--                    <h5 class="q">{{ $brand->name }}</h5>-->
                <!--                </div>-->
                <!--            </a>-->
                <!--        </div>-->
                <!--    </div>-->
                    
                <!--@empty-->
                <!--    <div class="col-12 text-center">-->
                <!--        <p>No brands available.</p>-->
                <!--    </div>-->
                <!--@endforelse-->
            </div>
        </div>
    </section>

    <!-- Brand -->

    <!-- refer banner -->
    <section id="quicktech-refer-banner"
        style="background: url(https://t4.ftcdn.net/jpg/05/12/05/07/360_F_512050720_raHVOBRGefjx7sprIA1yAh5dJonKlHRn.jpg) no-repeat center / cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-12">
                    <div class="quicktech-refer-ban-text">
                        <h1>Want to Register Your Pharmacy?</h1>
                        <h3> </h3>
                        <div class="quikctech-refer-btn mt-5">
                            <a href="{{ route('customer.wholesellregister') }}">Register Here</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- refer banner -->
    


@endsection

    
