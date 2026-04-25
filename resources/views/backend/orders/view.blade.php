@extends('backend.layouts.master-layouts')

@section('title', 'Order Invoice - #' . $order->id)

@section('css')
    <style>
        .invoice-box {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table th {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .invoice-box .title {
            font-size: 30px;
            color: #333;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="invoice-box mt-4">

        {{-- Header --}}
        <table>
            <tr>
                <td>
                    <img src="{{ asset('build/images/' . AppSetting('logo_lg')) }}" alt="Logo" style="width: 120px;">
                </td>
                <td style="text-align: right;">
                    <button onclick="window.print()" class="btn btn-sm btn-primary no-print">🖨 Print</button>
                </td>
            </tr>
        </table>

        <hr>

        <table>
            <tr>
                <td class="title">
                    <h2>Order Invoice</h2>
                </td>

                <td style="text-align: right;">
                    Order #: {{ $order->id }}<br>
                    Created: {{ $order->created_at->format('d M Y, h:i A') }}<br>
                    Status: {{ $order->ordertype->name ?? 'N/A' }}
                </td>
            </tr>
        </table>

        <hr>

        <table>
            <tr>
                <td>
                    <strong>Customer Details</strong><br>
                    Name: {{ $order->customer->name ?? ($order->name ?? 'N/A') }}<br>
                    Phone: {{ $order->customer->phone ?? ($order->phone ?? 'N/A') }}<br>
                    Email: {{ $order->customer->email ?? ($order->email ?? '-') }}<br>
                    Address: {{ $order->address }}<br>
                    District: {{ optional($order->district)->name ?? '-' }}<br>
                    Upazila: {{ optional($order->upazila)->name ?? '-' }}
                </td>

                <td>
                    <strong>Shipping Address</strong><br>
                    @php
                        $shipping = $order->customer->shippingAddresses->first() ?? null;
                    @endphp
                    @if ($shipping)
                        {{ $shipping->address }}<br>
                        District: {{ optional($shipping->district)->name ?? '-' }}<br>
                        Upazila: {{ optional($shipping->upazila)->name ?? '-' }}
                    @else
                        N/A
                    @endif
                </td>
            </tr>
        </table>

        <hr>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Variant</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($order->items as $item)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $item->product_name }}</td>
                        <td>{{ $item->variant_name ?? '-' }}</td>
                        <td>{{ $item->quantity }}</td>
                        <td>{{ number_format($item->unit_price, 2) }}</td>
                        <td>{{ number_format($item->total_price, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <table style="margin-top: 20px;">
            <tr>
                <td></td>
                <td style="text-align: right; width: 300px;">
                    <table>
                        <tr>
                            <td>Subtotal:</td>
                            <td>{{ number_format($order->items->sum('total_price'), 2) }}</td>
                        </tr>
                        <tr>
                            <td>Shipping Cost:</td>
                            <td>{{ number_format($order->shipping_cost, 2) }}</td>
                        </tr>
                        @if ($order->coupon_code)
                            <tr>
                                <td>Coupon:</td>
                                <td>{{ $order->coupon_code }}</td>
                            </tr>
                        @endif
                        <tr class="total">
                            <td><strong>Total Amount:</strong></td>
                            <td><strong>{{ number_format($order->total_amount, 2) }}</strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <hr>

        <table>
            <tr>
                <td><strong>Payment Method:</strong> {{ ucfirst($order->payment_method) }}</td>
            </tr>
            <tr>
                <td><strong>Order Note:</strong> {{ $order->order_note ?? '-' }}</td>
            </tr>
        </table>

        <div class="mt-4 no-print">
            <a href="{{ url()->previous() }}" class="btn btn-secondary">← Back</a>
        </div>
    </div>
@endsection
