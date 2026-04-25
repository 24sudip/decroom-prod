<div class="col-lg-3">
    <div class="quicktech-delivery-option">
        <div class="quicktech-delivey-address d-flex justify-content-between">
            <h5>{{ __('messages.delivery_option') }}</h5>
            <p>
                <i class="fa-solid fa-circle-info"></i>
            </p>
        </div>
        <div class="quikctech-address-inner">
            <div class="quikctech-address">
                <div class="quicktech-address-text" style="display: flex; align-items: baseline; gap: 8px;">
                    <i class="fa-solid fa-location-dot"></i>
                    <p>{{ __('messages.delivery_address') }}</p>
                </div>
            </div>
        </div>
        <hr>
        <div class="quikctech-address-inner">
            <div class="quikctech-address align-items-center">
                <div class="quicktech-address-text" style="display: flex; align-items: baseline; gap: 8px;">
                    <i class="fa-solid fa-box-open"></i>
                    <p>{{ __('messages.standard_delivery') }}
                        <br>
                        <span>{{ __('messages.guaranteed_by_date') }}</span>
                    </p>
                </div>
                <div class="quicktech-ad-chan-btn">
                    <p style="font-weight: 600; font-size: 18px;">৳ {{ __('messages.delivery_charge') }}</p>
                </div>
            </div>
        </div>
        <div class="quikctech-address-inner">
            <div class="quikctech-address align-items-center">
                <div class="quicktech-address-text" style="display: flex; align-items: baseline; gap: 8px;">
                    <i class="fa-solid fa-money-bill"></i>
                    <p>
                        {{ __('messages.cash_on_delivery') }}
                    </p>
                </div>
            </div>
        </div>
        <hr>
        <div class="quicktech-delivey-address d-flex justify-content-between">
            <h5>{{ __('messages.return_warranty') }}</h5>
            <p>
                <i class="fa-solid fa-circle-info"></i>
            </p>
        </div>
        <div class="quikctech-address-inner">
            <div class="quikctech-address align-items-center">
                <div class="quicktech-address-text" style="display: flex; align-items: baseline; gap: 8px;">
                    <i class="fa-solid fa-money-bill"></i>
                    <p>
                        {{ __('messages.return_policy_days') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="quikctech-seller-details mt-3">
        <div class="qikctech-sold-inner">
            <div class="quikctech-sold-by d-flex justify-content-between align-items-center">
                <h5>
                    {{ __('messages.sold_by') }}
                    <br>
                    {{-- ?? __('messages.vendor') --}}
                    <span>{{ $product->vendor ? ($product->vendor->vendorDetails->shop_name ?? $product->vendor->name) : 'Admin' }}</span>
                </h5>
                @if ($product->vendor)
                <a href="{{ route('customer.chat-with-seller', $product->vendor_id) }}">
                    <i class="fa-solid fa-message"></i> {{ __('messages.chat_now') }}
                </a>
                @endif
            </div>
        </div>
        <div class="quikctech-sold-rate">
            <div class="quicktech-sold-rate-main">
                <h5>{{ __('messages.positive_seller_rating') }}</h5>
                <h4>70%</h4>
            </div>
            <div class="quicktech-sold-rate-main">
                <h5>{{ __('messages.ship_on_time') }}</h5>
                <h4>100%</h4>
            </div>
            <div class="quicktech-sold-rate-main">
                <h5>{{ __('messages.chat_response_rate') }}</h5>
                <h4>{{ __('messages.null') }}</h4>
            </div>
        </div>
        <div class="quicktech-visit-btn text-center my-4">
            <a href="{{ route('vendor.shop.view', $product->vendor_id) }}">{{ __('messages.visit_shop') }}</a>
        </div>
    </div>
</div>

