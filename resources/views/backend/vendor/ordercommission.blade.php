@extends('backend.layouts.master-layouts')

@section('title', __('Order Commission'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 p-2">

            <div class="d-flex justify-content-between align-items-center py-2">
                <h5 class="title">Order Commission</h5>
            </div>

            <div id="VisitorDt_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">

                        <table id="VisitorDt" 
                            class="table table-striped table-sm table-bordered dataTable no-footer" 
                            width="100%" role="grid">

                            <thead>
                                <tr>
                                    <th class="text-center py-2" style="width: 10px">NO</th>
                                    <th class="py-2">Vendor</th>
                                    <th class="py-2">Product/Service</th>
                                    <th class="py-2">Total Price</th>
                                    <th class="py-2">Admin Commission</th>
                                    <th class="py-2">Vendor Earning</th>
                                    <th class="text-center py-2">Order Time</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($orders as $order)
                                    @foreach ($order->items as $item)
                                        <tr>
                                            <td class="text-center py-2">{{ $loop->parent->iteration }}</td>

                                            <!-- Vendor -->
                                            <td class="py-2">
                                                {{ optional($item->vendor)->name 
                                                   ?? optional($order->user)->name 
                                                   ?? 'N/A' }}
                                            </td>

                                            <!-- Product or service name -->
                                            <td class="py-2">
                                                @if($item->product_name)
                                                    {{ $item->product_name }}
                                                @elseif($item->service_name)
                                                    {{ $item->service_name }}
                                                @else 
                                                    N/A
                                                @endif
                                                <br>
                                                @if($item->quantity)
                                                    Quantity: {{ $item->quantity }}
                                                @endif
                                            </td>

                                            <!-- Total Price -->
                                            <td class="py-2">
                                                {{ number_format($item->total_price, 2) }}
                                            </td>

                                            <!-- Admin Commission -->
                                            <td class="py-2 text-danger fw-bold">
                                                {{ number_format($item->admin_commission, 2) }}
                                            </td>

                                            <!-- Vendor Earning -->
                                            <td class="py-2 text-success fw-bold">
                                                {{ number_format($item->vendor_earning, 2) }}
                                            </td>

                                            <!-- Order Date -->
                                            <td class="text-center py-2">
                                                {{ $order->created_at->format('d M, Y h:i A') }}
                                            </td>
                                        </tr>
                                    @endforeach
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            <h4>No Orders Found!</h4>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
