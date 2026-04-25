@extends('frontend.seller.seller_master')
@section('title', 'Order Invoice')
@section('content')

<div class="quicktech-seller-menu-top">
    <ul>
        <li><a href="#"><img src="{{ asset('frontend') }}/images/store 1.png" alt="Store"></a></li>
        <li><a href="setting.html"><img src="{{ asset('frontend') }}/images/settings (2).png" alt="Settings"></a></li>
        <li><a href="#"><img src="{{ asset('frontend') }}/images/volunteering.png" alt="Volunteer"></a></li>
        <li><a href="#"><img src="{{ asset('frontend') }}/images/bell.png" alt="Notifications"></a></li>
    </ul>
</div>

<div class="quikctech-manage-p-inner">
    <div class="quicktech-manage-head">
        <h4>Order Invoice</h4>
        <div>
            <button onclick="window.print()" class="btn btn-primary me-2">
                <i class="fa-solid fa-print"></i> Print Invoice
            </button>
            <a href="{{ route('vendor.orders.list') }}" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left"></i> Back to Orders
            </a>
        </div>
    </div>
</div>

<div class="invoice-container">
    <div class="invoice-card">
        <!-- Invoice Header -->
        <div class="invoice-header">
            <div class="row align-items-center">
                <div class="col-md-6">
                    <div class="company-info">
                        <h2 class="company-name">QuickTech</h2>
                        <p class="company-address">
                            123 Business Street<br>
                            Dhaka, Bangladesh<br>
                            Phone: +880 1234-567890<br>
                            Email: info@quicktech.com
                        </p>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="invoice-meta">
                        <h1 class="invoice-title">INVOICE</h1>
                        <p class="invoice-number">Invoice #{{ $order->id }}</p>
                        <p class="invoice-date">
                            Date: {{ $order->created_at->format('F d, Y') }}<br>
                            Time: {{ $order->created_at->format('h:i A') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order & Customer Info -->
        <div class="invoice-info-section">
            <div class="row">
                <div class="col-md-6">
                    <div class="info-box">
                        <h5 class="info-title">Bill To</h5>
                        <div class="info-content">
                            <strong>{{ $order->customer->name ?? $order->name }}</strong><br>
                            Phone: {{ $order->customer->phone ?? $order->phone }}<br>
                            Email: {{ $order->customer->email ?? $order->email ?? 'N/A' }}<br>
                            Address: {{ $order->customer->address ?? $order->address }},
                            {{ $order->upazila->name ?? '' }},
                            {{ $order->district->name ?? '' }}
                        </div>
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="info-box">
                        <h5 class="info-title">Order Details</h5>
                        <div class="info-content">
                            <table class="order-meta-table">
                                <tr>
                                    <td><strong>Order Status:</strong></td>
                                    <td>
                                        <span class="status-badge status-{{ $order->status }}">
                                            @switch($order->status)
                                                @case(1) Pending @break
                                                @case(2) Accepted @break
                                                @case(3) In Process @break
                                                @case(4) Picked Up @break
                                                @case(5) Rescheduled @break
                                                @case(6) Delivered @break
                                                @case(7) Cancelled @break
                                                @case(8) Return @break
                                                @default Unknown
                                            @endswitch
                                        </span>
                                    </td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Method:</strong></td>
                                    <td>{{ $order->paymentMethod->title ?? $order->payment_method }}</td>
                                </tr>
                                <tr>
                                    <td><strong>Payment Status:</strong></td>
                                    <td>
                                        <span class="badge {{ $order->payment_status ? 'bg-success' : 'bg-warning' }}">
                                            {{ $order->payment_status ? 'Paid' : 'Pending' }}
                                        </span>
                                    </td>
                                </tr>
                                <!--<tr>-->
                                <!--    <td><strong>Coupon Code:</strong></td>-->
                                <!--    <td>{{ $order->coupon_code ?? 'None' }}</td>-->
                                <!--</tr>-->
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Order Items Table -->
        <div class="invoice-items-section">
            <h5 class="section-title">Order Items</h5>
            <div class="table-responsive">
                <table class="table invoice-items-table">
                    <thead>
                        <tr>
                            <th class="product-col">Product</th>
                            <th class="price-col">Unit Price</th>
                            <th class="qty-col">Qty</th>
                            <th class="total-col">Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                            @php $product = $item->product; @endphp
                            <tr>
                                <td class="product-col">
                                    <div class="product-info d-flex align-items-center">
                                        @if($product && method_exists($product, 'primaryImage') && $product->primaryImage())
                                            <img src="{{ asset('public/' . $product->primaryImage()->image_path) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="product-image" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @elseif($product && $product->images && $product->images->count() > 0)
                                            <img src="{{ asset('public/' . $product->images->first()->image_path) }}" 
                                                 alt="{{ $product->name }}" 
                                                 class="product-image" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @else
                                            <img src="https://via.placeholder.com/60x60?text=No+Image" 
                                                 alt="No Image" 
                                                 class="product-image" 
                                                 style="width: 60px; height: 60px; object-fit: cover;">
                                        @endif
                    
                                        <div class="product-details ms-2">
                                            <h6 class="product-name mb-0">{{ $product->name ?? 'N/A' }}</h6>
                                            <small class="product-sku">SKU: {{ $product->product_code ?? 'N/A' }}</small>
                                        </div>
                                    </div>
                                </td>
                    
                                <td class="price-col">BDT {{ number_format($item->unit_price, 2) }}</td>
                                <td class="qty-col">{{ $item->quantity }}</td>
                                <td class="total-col">BDT {{ number_format($item->unit_price * $item->quantity, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>

                </table>
            </div>
        </div>

        <!-- Order Summary -->
        <div class="invoice-summary-section">
            <div class="row justify-content-end">
                <div class="col-md-6 col-lg-4">
                    <table class="summary-table">
                        <tr>
                            <td class="summary-label">Subtotal:</td>
                            <td class="summary-value">BDT {{ number_format($order->subtotal ?? $order->total_amount, 2) }}</td>
                        </tr>
                        @if($order->discount && $order->discount > 0)
                        <tr>
                            <td class="summary-label">Discount:</td>
                            <td class="summary-value text-danger">- BDT {{ number_format($order->discount, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->shipping_cost && $order->shipping_cost > 0)
                        <tr>
                            <td class="summary-label">Shipping:</td>
                            <td class="summary-value">+ BDT {{ number_format($order->shipping_cost, 2) }}</td>
                        </tr>
                        @endif
                        @if($order->tax_amount && $order->tax_amount > 0)
                        <tr>
                            <td class="summary-label">Tax:</td>
                            <td class="summary-value">+ BDT {{ number_format($order->tax_amount, 2) }}</td>
                        </tr>
                        @endif
                        <tr class="grand-total">
                            <td class="summary-label"><strong>Grand Total:</strong></td>
                            <td class="summary-value"><strong>BDT {{ number_format($order->total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Footer Notes -->
        <div class="invoice-footer">
            <div class="row">
                <div class="col-md-6">
                    <div class="terms-section">
                        <h6>Terms & Conditions</h6>
                        <p>Goods once sold will not be taken back. This is a computer generated invoice.</p>
                    </div>
                </div>
                <div class="col-md-6 text-end">
                    <div class="signature-section">
                        <div class="signature-line"></div>
                        <p class="signature-label">Authorized Signature</p>
                    </div>
                </div>
            </div>
            <div class="invoice-thankyou text-center mt-4">
                <p class="thankyou-text">Thank you for your business!</p>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.invoice-container {
    background: #f8f9fa;
    padding: 20px;
    min-height: 100vh;
}

.invoice-card {
    background: white;
    border-radius: 12px;
    box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    padding: 40px;
    max-width: 1000px;
    margin: 0 auto;
}

/* Header Styles */
.invoice-header {
    border-bottom: 2px solid #e9ecef;
    padding-bottom: 30px;
    margin-bottom: 30px;
}

.company-name {
    color: #2c3e50;
    font-weight: 700;
    font-size: 2rem;
    margin-bottom: 10px;
}

.company-address {
    color: #6c757d;
    line-height: 1.6;
}

.invoice-title {
    color: #3498db;
    font-weight: 700;
    font-size: 2.5rem;
    margin-bottom: 5px;
}

.invoice-number {
    font-size: 1.1rem;
    color: #6c757d;
    margin-bottom: 5px;
}

.invoice-date {
    color: #6c757d;
}

/* Info Section */
.invoice-info-section {
    margin-bottom: 30px;
}

.info-box {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 8px;
    height: 100%;
}

.info-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 15px;
    border-bottom: 1px solid #dee2e6;
    padding-bottom: 8px;
}

.info-content {
    color: #495057;
    line-height: 1.6;
}

.order-meta-table td {
    padding: 4px 0;
    vertical-align: top;
}

.order-meta-table td:first-child {
    width: 140px;
}

/* Status Badges */
.status-badge {
    padding: 4px 12px;
    border-radius: 20px;
    font-size: 0.85rem;
    font-weight: 600;
}

.status-1 { background: #fff3cd; color: #856404; }
.status-2 { background: #d1ecf1; color: #0c5460; }
.status-3 { background: #d4edda; color: #155724; }
.status-4 { background: #cce7ff; color: #004085; }
.status-5 { background: #e2e3e5; color: #383d41; }
.status-6 { background: #d1e7dd; color: #0f5132; }
.status-7 { background: #f8d7da; color: #721c24; }
.status-8 { background: #fff3cd; color: #856404; }

/* Items Table */
.invoice-items-section {
    margin-bottom: 30px;
}

.section-title {
    color: #2c3e50;
    font-weight: 600;
    margin-bottom: 20px;
    border-bottom: 2px solid #3498db;
    padding-bottom: 8px;
}

.invoice-items-table {
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.invoice-items-table thead {
    background: #3498db;
    color: white;
}

.invoice-items-table th {
    border: none;
    padding: 15px;
    font-weight: 600;
}

.invoice-items-table td {
    padding: 15px;
    vertical-align: middle;
    border-color: #dee2e6;
}

.product-col { width: 50%; }
.price-col, .qty-col, .total-col { width: 16.66%; text-align: center; }

.product-info {
    display: flex;
    align-items: center;
    gap: 15px;
}

.product-image {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #dee2e6;
}

.product-name {
    margin-bottom: 5px;
    color: #2c3e50;
}

.product-sku {
    color: #6c757d;
    font-size: 0.85rem;
}

/* Summary Section */
.invoice-summary-section {
    margin-bottom: 30px;
}

.summary-table {
    width: 100%;
    border: 1px solid #dee2e6;
    border-radius: 8px;
    overflow: hidden;
}

.summary-table tr {
    border-bottom: 1px solid #dee2e6;
}

.summary-table tr:last-child {
    border-bottom: none;
}

.summary-table td {
    padding: 12px 20px;
}

.summary-label {
    color: #495057;
    font-weight: 500;
}

.summary-value {
    text-align: right;
    font-weight: 500;
}

.grand-total {
    background: #f8f9fa;
    border-top: 2px solid #dee2e6;
}

.grand-total .summary-label,
.grand-total .summary-value {
    font-size: 1.1rem;
    color: #2c3e50;
}

/* Footer */
.invoice-footer {
    border-top: 2px solid #e9ecef;
    padding-top: 30px;
}

.terms-section h6 {
    color: #2c3e50;
    margin-bottom: 10px;
}

.terms-section p {
    color: #6c757d;
    font-size: 0.9rem;
}

.signature-section {
    display: inline-block;
    text-align: center;
}

.signature-line {
    width: 200px;
    height: 1px;
    background: #495057;
    margin-bottom: 5px;
}

.signature-label {
    color: #6c757d;
    font-size: 0.9rem;
}

.invoice-thankyou {
    border-top: 1px solid #e9ecef;
    padding-top: 20px;
}

.thankyou-text {
    color: #3498db;
    font-size: 1.2rem;
    font-weight: 600;
    font-style: italic;
}

/* Print Styles */
@media print {
    .quicktech-seller-menu-top,
    .quikctech-manage-p-inner .btn {
        display: none !important;
    }
    
    .invoice-container {
        background: white;
        padding: 0;
    }
    
    .invoice-card {
        box-shadow: none;
        padding: 20px;
    }
}

/* Responsive */
@media (max-width: 768px) {
    .invoice-card {
        padding: 20px;
    }
    
    .product-info {
        flex-direction: column;
        text-align: center;
        gap: 8px;
    }
    
    .product-image {
        width: 50px;
        height: 50px;
    }
    
    .invoice-header .text-end {
        text-align: left !important;
        margin-top: 20px;
    }
}
</style>
@endpush