<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">

<style>
.quicktech-social a i {
    transition: 0.3s;
}
.quicktech-social a:hover i {
    color: #ff6a00;
    transform: scale(1.2);
}
</style>

<section id="quicktech-footer" style="background-color: white;">
    <div class="container">
        <div class="row gapp pt-4">

            <!-- Customer Care -->
            <div class="col-lg-3 col-6">
                <div class="quikctech-footer-inner">
                    <h5>{{ __('messages.customer_care') }}</h5>
                    <ul>
                        @php
                            $isCustomerLoggedIn = auth('customer')->check();
                            $isVendorLoggedIn = auth('vendor')->check();

                            $profileUrl = $isCustomerLoggedIn ? route('customer.profile', ['tab' => 'profile']) : route('customer.login');
                            $sellerLoginUrl = $isVendorLoggedIn ? route('vendor.dashboard') : route('vendor.login');
                            $sellerDashboardUrl = $isVendorLoggedIn ? route('vendor.dashboard') : route('vendor.login');
                            
                            $contactUrl = route('contact_us');
                            $aboutUrl = route('about_us');
                            $returnPolicyUrl = route('return_policy');
                            $termsConditionsUrl = route('terms_and_condition');
                            $privacyPolicyUrl = route('privacy_policy');
                            $warrantyUrl = route('warranty');
                            $deliveryUrl = route('delivery');
                            $helpCenterUrl = route('help_center');

                            $customerCareLinks = [
                                ['title' => __('messages.contact'), 'url' => $contactUrl],
                                ['title' => __('messages.delivery'), 'url' => $deliveryUrl],
                                ['title' => __('messages.return_refund'), 'url' => $returnPolicyUrl],
                                ['title' => __('messages.warranty'), 'url' => $warrantyUrl],
                                ['title' => __('messages.help_center'), 'url' => $helpCenterUrl],
                                ['title' => __('messages.seller_login'), 'url' => $sellerLoginUrl],
                                ['title' => __('messages.seller'), 'url' => $sellerDashboardUrl]
                            ];
                        @endphp

                        @foreach($customerCareLinks as $link)
                            <li><a href="{{ $link['url'] }}">{{ $link['title'] }}</a></li>
                        @endforeach
                    </ul>
                </div>
            </div>

            <!-- Product & Service Categories -->
            <div class="col-lg-3 col-6">
                <div class="quikctech-footer-inner">

                    <h5>{{ __('messages.product_categories') }}</h5>
                    <ul>
                        @foreach($categories as $cat)
                            <li>
                                <a href="{{ route('product_category', $cat->slug) }}">
                                    {{ $cat->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                    <hr>

                    <h5>{{ __('messages.service_categories') }}</h5>
                    <ul>
                        @foreach($serviceCategories as $sc)
                            <li>
                                <a href="{{ route('service_category', $sc->slug) }}">
                                    {{ $sc->name }}
                                </a>
                            </li>
                        @endforeach
                    </ul>

                </div>
            </div>

            <!-- App & Social -->
            <div class="col-lg-3 col-md-6">
                <div class="quikctech-footer-inner">

                    <!-- App Download -->
                    <div class="quicktech-app-inner d-flex gap-2">
                        <div class="quikctech-app-img">
                            <img src="{{ asset('frontend/images/appDownloadf.png') }}" alt="">
                        </div>

                        <div class="quikctech-app-img">
                            <a href="#">
                                <img src="{{ asset('frontend/images/playstore_888923 1.png') }}" alt="">
                                {{ __('messages.available_playstore') }}
                            </a>
                            <a href="#">
                                <img src="{{ asset('frontend/images/app-store_5968492 1.png') }}" alt="">
                                {{ __('messages.available_appstore') }}
                            </a>
                        </div>
                    </div>

                    <!-- Social Icons -->
                    <div class="quicktech-social d-flex gap-3 mt-4">
                        <h4 style="padding-top: 10px;">{{ __('messages.follow_us') }}:</h4>

                        <ul class="d-flex gap-3" style="list-style: none; padding-left:0; padding-top:10px;">
                            @foreach($socialmedia as $social)
                                <li>
                                    <a href="{{ $social->link }}" target="_blank" title="{{ $social->name }}" style="font-size: 25px; color: #000;">
                                        <i class="{{ $social->icon }}"></i>
                                    </a>
                                </li>
                            @endforeach
                        </ul>
                    </div>

                </div>
            </div>

            <!-- Payment Methods -->
            <div class="col-lg-3 col-md-6">
                <div class="quikctech-footer-inner quicktech-pay">
                    <h5>{{ __('messages.payment_methods') }}</h5>
                    <ul>
                        <li><img src="{{ asset('frontend/images/payment.png') }}" class="w-100" alt=""></li>
                    </ul>
                </div>
            </div>

        </div>
    </div>
</section>
