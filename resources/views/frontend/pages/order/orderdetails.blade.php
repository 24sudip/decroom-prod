@extends('frontend.layouts.master')
@section('title', 'Order Details')
@section('content')

<section id="quicktech-confirm-order">
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-lg-12">
                <div class="quikctech-confirm-order-inner">
                    <h4>Order Details</h4>
                    <div class="row">
                        <div class="col-lg-12">
                            <div class="quikctech-product-order-details-inner">
                                <div class="quikctech-o-d-head d-flex justify-content-between">
                                    <p>{{ $order->user->vendorDetails->shop_name ?? 'N/A' }} #{{ $order->id }}</p>
                                    @if ($order->user)
                                    <a href="{{ route('customer.chat-with-seller', $order->user->id) }}">Chat with Seller</a>
                                    @endif
                                </div>
                            </div>

                            <div class="quikctech-order-details-text-inner">
                                <div class="row mt-4">
                                    <div class="col-lg-6">
                                        <div class="quicktech-order-details-product">
                                            @foreach($order->items as $item)

                                                @php
                                                    $defaultImage = asset('frontend/images/productmain.png');
                                                    $productImages = \App\ProductImage::where('product_id', $item->product_id)->get();
                                                    $productImage = null;

                                                    if($productImages->count() > 0){
                                                        $primaryImage = $productImages->where('is_primary', true)->first();
                                                        $productImage = $primaryImage ? $primaryImage->image_path : $productImages->first()->image_path;
                                                        if ($productImage && !file_exists(public_path($productImage))) $productImage = null;
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

                                                <div class="quikctech-cart-p-main mb-3" id="order-item-{{ $item->id }}">
                                                    <div class="quikctech-check-box d-flex align-items-center gap-3">
                                                        <img src="{{ $displayImage }}" alt="{{ $item->product_name }}" style="width: 70px; height: 70px; object-fit: cover;" onerror="this.src='{{ $defaultImage }}'">

                                                        <h5>{{ $item->product_name ?? 'Product not found' }}
                                                            @if($item->variant_name)
                                                                <br><span>{{ $item->variant_name }}</span>
                                                            @endif
                                                            <span>6 month Warranty</span>
                                                            <div class="quicktech-order-d-price d-flex justify-content-between">
                                                                <p>৳ {{ number_format($item->unit_price, 0) }}</p>
                                                                <p>Qty: {{ $item->quantity }}</p>
                                                            </div>
                                                        </h5>
                                                    </div>
                                                </div>
                                            @endforeach

                                            <div class="quikctech-cancel-order-btn mt-4">
                                                <p>With Standard Delivery &nbsp;৳&nbsp;{{ number_format($order->total_amount, 0) }}</p>
                                                <a href="#">Cancel Order</a>
                                            </div>

                                            <div class="quicktech-order-details-address mt-5">
                                                <div class="order-header mb-3">
                                                    Order #{{ $order->id }} <br>
                                                    Placed on {{ $order->created_at->format('d M Y H:i:s') }} <br>
                                                    Paid by {{ $order->paymentMethod->title ?? 'Unknown Method' }}
                                                </div>

                                                <div class="order-header">
                                                    <div class="d-flex justify-content-between">
                                                        <strong>Delivery Location:</strong>
                                                        <a href="{{ route('customer.profile', 'settings') }}">Edit</a>
                                                    </div>

                                                    Name: {{ $order->name }} <br>
                                                    Phone: {{ $order->phone }} <br>
                                                    Address: {{ $order->address }},
                                                    {{ $order->upazila->name ?? '' }},
                                                    {{ $order->district->name ?? '' }}
                                                </div>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-lg-6">
                                        <div class="quicktech-order-tracking">
                                            <div class="row shop-tracking-status">
                                                <div class="col-lg-12">
                                                    <div class="well">
                                                        <div class="order-status">
                                                            <div class="order-status-timeline">
                                                                <div class="order-status-timeline-completion
                                                                    {{ $order->status == 'completed' ? 'c5' : ($order->status == 'delivered' ? 'c4' : 'c3') }}">
                                                                </div>
                                                            </div>

                                                            <div class="image-order-status image-order-status-new {{ in_array($order->status, ['accepted', 'in_progress', 'shipped', 'delivered', 'completed']) ? 'active' : '' }} img-circle">
                                                                <span class="status">Accepted</span>
                                                                <div class="icon"></div>
                                                            </div>
                                                            <div class="image-order-status image-order-status-active {{ in_array($order->status, ['in_progress', 'shipped', 'delivered', 'completed']) ? 'active' : '' }} img-circle">
                                                                <span class="status">In progress</span>
                                                                <div class="icon"></div>
                                                            </div>
                                                            <div class="image-order-status image-order-status-intransit {{ in_array($order->status, ['shipped', 'delivered', 'completed']) ? 'active' : '' }} img-circle">
                                                                <span class="status">Shipped</span>
                                                                <div class="icon"></div>
                                                            </div>
                                                            <div class="image-order-status image-order-status-delivered {{ in_array($order->status, ['delivered', 'completed']) ? 'active' : '' }} img-circle">
                                                                <span class="status">Delivered</span>
                                                                <div class="icon"></div>
                                                            </div>
                                                            <div class="image-order-status image-order-status-completed {{ $order->status == 'completed' ? 'active' : '' }} img-circle">
                                                                <span class="status">Completed</span>
                                                                <div class="icon"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </div>

                                        <div class="quikctech-total-summary mt-4">
                                            <div class="quicktech-order-de-summary">
                                                <h5>Total Summary</h5>
                                                <div class="quicktech-order-de-subtotal">
                                                    <span>Subtotal ({{ $order->items->count() }} Item)</span>
                                                    <span>৳ {{ number_format($order->items->sum('total_price'), 0) }}</span>
                                                </div>
                                                <div class="quicktech-order-de-shipping-fee">
                                                    <span>Shipping Fee</span>
                                                    <span>৳ {{ number_format($order->shipping_cost ?? 0, 0) }}</span>
                                                </div>
                                                <div class="quicktech-order-de-total">
                                                    <span>Total</span>
                                                    <span>৳ {{ number_format($order->total_amount, 0) }}</span>
                                                </div>
                                                <div class="quicktech-order-de-payment-method">
                                                    <span>Paid by {{ $order->paymentMethod->title ?? 'Cash on Delivery' }}</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div> <!-- end row -->
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
