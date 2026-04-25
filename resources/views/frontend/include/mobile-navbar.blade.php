<section id="quikctech-mobile-navbar">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="quikctech-mobile-logo text-center">
                    <a href="{{ route('home') }}"> 
                        <img src="{{ asset('frontend/images/logo-removebg-preview(3).png') }}" alt="{{ __('messages.logo') }}">
                    </a>
                </div>
                <div class="quikctech-search-mobile mt-2">
                    <input type="text" placeholder="{{ __('messages.search_placeholder') }}" class="w-100">
                    <div class="quikctech-mob-search-icon">
                        <a href="#"><img src="{{ asset('frontend/images/search icon.png') }}" alt="{{ __('messages.search') }}"></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>