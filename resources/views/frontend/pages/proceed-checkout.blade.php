@extends('frontend.layouts.master')
@section('title', 'Proceed Checkout')

@section('content')
<section id="quicktech-confirm-order">
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-lg-12">
                <div class="quikctech-confirm-order-inner">
                    <h4>Select Payment Method</h4>

                    <div class="row gapp my-4">
                        {{-- ========== LEFT SECTION ========== --}}
                        <div class="col-lg-8">
                            <div class="quikctech-payment-tabs">
                                
                                {{-- Nav Tabs --}}
                                <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                                    @foreach ($methods as $index => $method)
                                        <li class="nav-item" role="presentation">
                                            {{-- add data-method-id so JS can easily read the id --}}
                                            <a class="nav-link {{ $loop->first ? 'active' : '' }}" 
                                               id="tab-{{ $method->id }}" 
                                               data-method-id="{{ $method->id }}"
                                               data-bs-toggle="tab" 
                                               href="#method-{{ $method->id }}" 
                                               role="tab" 
                                               aria-controls="method-{{ $method->id }}" 
                                               aria-selected="{{ $loop->first ? 'true' : 'false' }}">
                                                <img class="quikctech-tabs-pay-img" 
                                                     src="{{ asset('uploads/paymentmethod/' . $method->logo) }}" 
                                                     alt="{{ $method->title }}" 
                                                     title="{{ $method->title }}">
                                            </a>
                                        </li>
                                    @endforeach
                                </ul>

                                {{-- Tab Contents --}}
                                <div class="tab-content" id="paymentTabsContent">
                                    @foreach ($methods as $method)
                                        <div class="tab-pane quikctech-payemnt-inner fade {{ $loop->first ? 'show active' : '' }}" 
                                             id="method-{{ $method->id }}" 
                                             role="tabpanel" 
                                             aria-labelledby="tab-{{ $method->id }}">
                                            
                                            <div class="quikctech-cash-on-text mt-3">
                                                <h5>{{ $method->title }}</h5>
                                                <p>{!! nl2br(e($method->description ?? 'No description available.')) !!}</p>

                                                {{-- NOTE: no submit button here anymore; selection is handled by the right-side form --}}
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        {{-- ========== RIGHT SECTION (Order Summary) ========== --}}
                        <div class="col-lg-4">
                            <div class="quickcktech-order-main">
                                <div class="order-summary">
                                    <h5>Order Summary</h5>

                                    <div class="quikctech-price-inner d-flex justify-content-between">
                                        <p>Subtotal (including shipping):</p>
                                        <span>৳ {{ number_format(($cartTotal ?? 0) + ($shipping_method ?? 0), 2) }}</span>
                                    </div>

                                    @if (session('coupon_discount', 0) > 0)
                                        <div class="quikctech-price-inner d-flex justify-content-between">
                                            <p>Coupon Discount:</p>
                                            <span>-৳ {{ number_format(session('coupon_discount'), 2) }}</span>
                                        </div>
                                    @endif

                                    <div class="quikctech-total-price mt-4 d-flex justify-content-between">
                                        <p style="font-size: 20px;">Total Price</p>
                                        <span style="font-size: 20px;">৳ {{ number_format($finalTotal ?? (($cartTotal ?? 0) + ($shipping_method ?? 0)), 2) }}</span>
                                    </div>

                                    {{-- single form: submits payment_method + shipping_method --}}
                                    <form id="checkoutFormSubmit" action="{{ route('checkout.placeOrder') }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="payment_method" id="payment_method" value="{{ $methods->first()->id ?? '' }}">
                                        <input type="hidden" name="shipping_method" value="{{ $shipping_method ?? '' }}">
                                        <input type="hidden" name="order_note" value="{{ $request->order_note ?? '' }}">
                                    
                                        <div class="quikctech-order-btn text-center mt-3">
                                            <button class="btn btn-primary w-100" type="submit" id="proceedPaymentBtn">
                                                Proceed Payment - ৳ {{ number_format($finalTotal, 2) }}
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

@endsection

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Set initial payment method
    function setSelectedMethodFromActiveTab() {
        const activeTab = document.querySelector('#paymentTabs .nav-link.active');
        const methodInput = document.getElementById('payment_method');
        if (activeTab && methodInput) {
            const id = activeTab.getAttribute('data-method-id') || activeTab.id.replace('tab-', '');
            methodInput.value = id;
        }
    }

    setSelectedMethodFromActiveTab();

    // Update payment method when tabs change
    const tabs = document.querySelectorAll('#paymentTabs .nav-link');
    tabs.forEach(function(tabEl) {
        tabEl.addEventListener('shown.bs.tab', function (e) {
            const methodId = e.target.getAttribute('data-method-id') || e.target.id.replace('tab-', '');
            const methodInput = document.getElementById('payment_method');
            if (methodInput) methodInput.value = methodId;
        });
    });

    // Form validation
    const checkoutFormSubmit = document.getElementById('checkoutFormSubmit');
    if (checkoutFormSubmit) {
        checkoutFormSubmit.addEventListener('submit', function (e) {
            const methodInput = document.getElementById('payment_method');
            if (!methodInput || !methodInput.value) {
                e.preventDefault();
                alert('Please select a payment method.');
                return false;
            }
            
            // Optional: Show loading state
            const submitBtn = document.getElementById('proceedPaymentBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
            }
        });
    }
});
</script>
@endpush
