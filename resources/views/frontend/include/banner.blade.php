<section id="quikctech-banner" style="background: url({{ asset('frontend/images/Rectangle 11.png') }}) no-repeat center / cover;">
    <div class="swiper">
        <div class="swiper-wrapper">
            @foreach ($sliders as $slider)
                <div class="swiper-slide quikctech-banner-img">
                    <a href="{{ $slider->link ?? '#' }}">
                        <img src="{{ asset('storage/sliders/' . ($slider->image ?? 'frontend/images/default.png')) }}" class="w-100" alt="{{ __('messages.banner') }}">
                    </a>
                </div>
            @endforeach
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="quikctech-cate-main">
                    <div class="quikctech-cate-head text-center pt-4">
                        <img src="{{ asset('frontend/images/Orange Minimalist Beauty & Skincare Handmade Shop LinkedIn Banner-3 2.png') }}" alt="{{ __('messages.banner_image') }}">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="quikctech-cat-main">
                    @foreach ($serviceCategories as $category)
                        <a href="{{ route('service_category', $category->slug) }}" title="{{ $category->name }}">
                            <div class="quikctech-cate-inner">
                                <img src="{{ asset('storage/service/' . ($category->image ?? 'frontend/images/default.png')) }}" alt="{{ $category->name }}">
                                <h6>{{ $category->name }}</h6>
                            </div>
                        </a>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
</section>