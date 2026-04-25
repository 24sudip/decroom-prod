@extends('frontend.layouts.master')
@section('title', 'Checkout')

@section('content')
<section id="quicktech-confirm-order">
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-lg-12">
                <div class="quikctech-confirm-order-inner">
                    <h4>Confirm Order</h4>
                    <div class="row gapp">

                        {{-- ================= LEFT SECTION ================= --}}
                        <div class="col-lg-8">
                            <div class="quicktech-conter-order-left">
                                <div class="quikctech-confirm-order-head">
                                    <h5>Package Details</h5>
                                    <p>Shop: {{ config('app.name', 'Shop') }}</p>
                                </div>

                                <div class="quikctech-confirm-order-product">
                                    {{-- DELIVERY OPTIONS --}}
                                    <div class="quikctech-delivery-option">
                                        <h5 class="pt-4">Delivery Option:</h5>
                                        <div class="delivery-options">
                                            @foreach ($shippingCharges as $index => $shipping)
                                                <div class="delivery-option" id="option{{ $index + 1 }}">
                                                    <input type="radio" name="shipping_method_radio"
                                                        id="radio{{ $index + 1 }}"
                                                        value="{{ $shipping->charge }}"
                                                        data-id="{{ $shipping->id }}"
                                                        {{ $loop->first ? 'checked' : '' }}
                                                        onchange="updateShippingCharge({{ $shipping->charge }})">
                                                    <label for="radio{{ $index + 1 }}">
                                                        <span>
                                                            <img src="{{ asset('frontend/images/checked.png') }}" style="height: 15px;" alt=""> 
                                                            ৳ {{ number_format($shipping->charge, 2) }}
                                                        </span>
                                                        <p>{{ $shipping->location ?? 'Standard Delivery' }}</p>
                                                        <p>{{ $shipping->type ?? '' }}</p>
                                                    </label>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>

                                    {{-- CART ITEMS --}}
                                    @foreach ($cartItems as $item)
                                        <div class="quikctech-cart-p-main mt-4">
                                            <div class="quikctech-check-box d-flex align-items-center gap-3">
                                                <img 
                                                    src="{{ Str::startsWith($item['attributes']['image'], ['http://', 'https://']) ? $item['attributes']['image'] : asset('public/' . $item['attributes']['image']) }}" 
                                                    alt="{{ $item['name'] }}" 
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                                <h5>
                                                    {{ $item['name'] }}<br>
                                                    <span>{{ $item['attributes']['variant_name'] ?? 'Default' }}</span>
                                                </h5>
                                            </div>
                                            <div class="quikctech-cart-p-price text-center">
                                                <p>৳ {{ number_format($item['price'], 2) }}</p>
                                            </div>
                                            <div class="counter-container counter quikctech-cart-quantity">
                                                <p>Qty: {{ $item['quantity'] }}</p>
                                            </div>
                                        </div>
                                    @endforeach

                                </div>
                            </div>
                        </div>

                        {{-- ================= RIGHT SECTION ================= --}}
                        <div class="col-lg-4">
                            <div class="quickcktech-order-main">
                                <div class="quikctech-order-inner">
                                    <div class="d-flex justify-content-between">
                                        <h5>Order Delivery location</h5>
                                    </div>
                        
                                    @if ($customer)
                                        <div class="d-flex justify-content-between">
                                            <p><strong>Name:</strong> {{ $customer->name }}</p>
                                            <p>{{ $customer->phone }}</p>
                                        </div>
                                        <p><strong>Address:</strong> {{ $customer->address ?? 'Not provided' }}</p>
                                    @else
                                        <p>Please log in to select your delivery address.</p>
                                    @endif
                                </div>
                        
                                {{-- ORDER SUMMARY --}}
                                <div class="order-summary mt-4">
                                    <h5>Order Summary</h5>
                        
                                    <div class="quikctech-price-inner d-flex justify-content-between">
                                        <p>Sub total ({{ $cartCount }} item{{ $cartCount > 1 ? 's' : '' }})</p>
                                        <span id="subtotal">৳ {{ number_format($cartTotal, 2) }}</span>
                                    </div>
                        
                                    <div class="quikctech-price-inner d-flex justify-content-between">
                                        <p>Shipping Fee</p>
                                        <span id="shippingFee">৳ {{ number_format($shipping_method, 2) }}</span>
                                    </div>
                        
                                    @if (session('coupon_discount', 0) > 0)
                                        <div class="quikctech-price-inner d-flex justify-content-between">
                                            <p>Coupon Discount</p>
                                            <span>-৳ {{ number_format(session('coupon_discount'), 2) }}</span>
                                        </div>
                                    @endif
                        
                                    <div class="quikctech-total-price mt-4 d-flex justify-content-between">
                                        <p>Total Price</p>
                                        <span id="totalPrice">৳ {{ number_format($finalTotal, 2) }}</span>
                                    </div>
                                    
                                    {{-- PROCEED BUTTON --}}
                                    <div class="quikctech-order-btn text-center mt-3">
                                        <form id="checkoutForm" action="{{ route('proceed-to-pay') }}" method="POST">    
                                            @csrf
                                            <input type="hidden" name="shipping_method" id="shipping_method" value="{{ $shipping_method }}">
                                            <input type="hidden" name="coupon_code" value="{{ session('coupon_code') }}">
                                            <button type="submit" class="btn btn-success w-100">
                                                Proceed to Pay
                                            </button>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- JS for dynamic shipping total --}}
<script>
    function updateShippingCharge(charge) {
        const cartTotal = parseFloat({{ $cartTotal }});
        const discount = parseFloat({{ session('coupon_discount', 0) }});
        const newTotal = (cartTotal - discount) + parseFloat(charge);
    
        // Update text
        document.getElementById('shippingFee').textContent = '৳ ' + parseFloat(charge).toFixed(2);
        document.getElementById('totalPrice').textContent = '৳ ' + newTotal.toFixed(2);
    
        // Update hidden input
        document.getElementById('shipping_method').value = parseFloat(charge);
    }
</script>
@endsection
