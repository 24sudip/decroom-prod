@extends('frontend.seller.seller_master')
@section('title', 'Order Manage')
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
        <h4>Order Management</h4>
        <a href="{{ route('vendor.products.create') }}">+ New Product</a>
    </div>
</div>

{{-- ===== Order Status Menu ===== --}}
<div class="quicktech-manage-menu">
    <ul>
        <li><a href="{{ route('vendor.orders.list') }}" class="{{ !isset($status) ? 'managemenu-active' : '' }}">All</a></li>
        <li><a href="{{ route('vendor.orders.list', ['status'=>1]) }}" class="{{ isset($status) && $status==1 ? 'managemenu-active' : '' }}">Pending</a></li>
        <li><a href="{{ route('vendor.orders.list', ['status'=>2]) }}" class="{{ isset($status) && $status==2 ? 'managemenu-active' : '' }}">Accepted</a></li>
        <li><a href="{{ route('vendor.orders.list', ['status'=>3]) }}" class="{{ isset($status) && $status==3 ? 'managemenu-active' : '' }}">In Process</a></li>
        <li><a href="{{ route('vendor.orders.list', ['status'=>4]) }}" class="{{ isset($status) && $status==4 ? 'managemenu-active' : '' }}">Picked Up</a></li>
        <li><a href="{{ route('vendor.orders.list', ['status'=>5]) }}" class="{{ isset($status) && $status==5 ? 'managemenu-active' : '' }}">Rescheduled</a></li>
        <li><a href="{{ route('vendor.orders.list', ['status'=>6]) }}" class="{{ isset($status) && $status==6 ? 'managemenu-active' : '' }}">Delivered</a></li>
        <li><a href="{{ route('vendor.orders.list', ['status'=>7]) }}" class="{{ isset($status) && $status==7 ? 'managemenu-active' : '' }}">Cancelled</a></li>
        <li><a href="{{ route('vendor.orders.list', ['status'=>8]) }}" class="{{ isset($status) && $status==8 ? 'managemenu-active' : '' }}">Return</a></li>
    </ul>
</div>

{{-- ===== Order Filter Section ===== --}}
<div class="quikctech-order-filter">
    <div class="quicktech-order-wrapper">
        <div class="d-flex quikctech-mob-flex align-items-center flex-wrap mb-3">
            <span class="me-2 fw-medium">Order Date:</span>
            <div class="quicktech-order-date btn-group me-3">
                <button class="btn">Today</button>
                <button class="btn">Yesterday</button>
                <button class="btn">Last 7 days</button>
                <button class="btn">Last 30 days</button>
                <button class="btn">Custom</button>
            </div>
            <div class="quicktech-order-input me-2">
                <input type="date" class="form-control" placeholder="Start Date">
            </div>
            <span class="me-2">-</span>
            <div class="quicktech-order-input">
                <input type="date" class="form-control" placeholder="End Date">
            </div>
        </div>

        <div class="d-flex align-items-center flex-wrap mb-3">
            <span class="me-2 fw-medium">Order Type:</span>
            <div class="quicktech-order-type btn-group">
                <button class="btn active">All</button>
                <button class="btn">Normal</button>
            </div>
        </div>

        <div class="d-flex quikctech-mob-o align-items-center flex-wrap">
            <div class="quicktech-order-input me-3">
                <input type="text" placeholder="Order Number">
                <button><i class="fa-solid fa-pencil"></i></button>
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>

            <div class="quicktech-order-input me-3">
                <input type="text" placeholder="Tracking Number">
                <button><i class="fa-solid fa-pencil"></i></button>
                <button><i class="fa-solid fa-magnifying-glass"></i></button>
            </div>

            <div class="quicktech-order-sort ms-auto">
                <label class="me-2 fw-medium">Sort By</label>
                <select>
                    <option>Oldest Order Created</option>
                    <option>Newest Order Created</option>
                </select>
            </div>
        </div>
    </div>
</div>

{{-- ===== Orders Table ===== --}}
<div class="table-responsive">
    <table class="table quicktech-order-table">
        <thead>
            <tr>
                <th>Product</th>
                <th>Total Amount</th>
                <th>Delivery</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($orders as $order)
            <tr id="order-row-{{ $order->id }}">
                <td>
                    <div class="quicktech-order-product">
                        @php $product = $order->items->first()->product; @endphp

                        @if($product->primaryImage())
                            <img src="{{ asset('public/' . $product->primaryImage()->image_path) }}" 
                                 alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                        @elseif($product->images->count() > 0)
                            <img src="{{ asset('public/' . $product->images->first()->image_path) }}" 
                                 alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/60x60?text=No+Image" 
                                 alt="No Image" style="width: 60px; height: 60px; object-fit: cover;">
                        @endif
                        
                        <div>
                            <h6>{{ $order->items->first()->product->name ?? '-' }}</h6>
                            <small>#{{ $order->id }}</small>
                        </div>
                    </div>
                </td>
                <td>BDT {{ number_format($order->total_amount, 2) }}</td>
                <td>{{ ucfirst($order->shipping->status ?? 'In Progress') }}</td>

                {{-- Status Dropdown --}}
                <td>
                    <select class="form-select order-status-change" data-id="{{ $order->id }}">
                        <option value="1" {{ $order->status==1 ? 'selected' : '' }}>Pending</option>
                        <option value="2" {{ $order->status==2 ? 'selected' : '' }}>Accepted</option>
                        <option value="3" {{ $order->status==3 ? 'selected' : '' }}>In Process</option>
                        <option value="4" {{ $order->status==4 ? 'selected' : '' }}>Picked Up</option>
                        <option value="5" {{ $order->status==5 ? 'selected' : '' }}>Rescheduled</option>
                        <option value="6" {{ $order->status==6 ? 'selected' : '' }}>Delivered</option>
                        <option value="7" {{ $order->status==7 ? 'selected' : '' }}>Cancelled</option>
                        <option value="8" {{ $order->status==8 ? 'selected' : '' }}>Return</option>
                    </select>
                </td>


                <td class="quicktech-order-actions">
                    <a href="{{ route('vendor.orders.show', $order->id) }}" class="btn btn-sm btn-primary text-white">View</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center">No orders found</td>
            </tr>
            @endforelse
        </tbody>
    </table>

@endsection

@push('styles')
<style>
.status-update-container {
    position: relative;
    min-width: 150px;
}

.status-loading {
    position: absolute;
    right: -25px;
    top: 50%;
    transform: translateY(-50%);
}
</style>
@endpush

@push('scripts')
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {

    $('.order-status-change').on('change', function() {
        let select = $(this);
        let orderId = select.data('id');
        let newStatus = select.val();
        let oldStatus = select.data('old') || select.val();

        // Status names for display
        const statusNames = {
            1: 'Pending', 2: 'Accepted', 3: 'In Process',
            4: 'Picked Up', 5: 'Rescheduled', 6: 'Delivered',
            7: 'Cancelled', 8: 'Return'
        };

        Swal.fire({
            title: 'Are you sure?',
            text: `Change status from "${statusNames[oldStatus]}" to "${statusNames[newStatus]}"?`,
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: 'Yes, update!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if(result.isConfirmed){
                $.ajax({
                    url: "{{ route('vendor.orders.updateStatus') }}",
                    type: "POST",
                    data: {
                        _token: "{{ csrf_token() }}",
                        order_id: orderId,
                        status: newStatus
                    },
                    beforeSend: function(){
                        select.prop('disabled', true);
                    },
                    success: function(res){
                        if(res.success){
                            Swal.fire({
                                toast: true,
                                position: 'top-end',
                                icon: 'success',
                                title: 'Status updated!',
                                showConfirmButton: false,
                                timer: 2000
                            });
                            select.data('old', newStatus);
                        } else {
                            Swal.fire('Error', res.message || 'Update failed', 'error');
                            select.val(oldStatus);
                        }
                    },
                    error: function(){
                        Swal.fire('Error', 'Something went wrong', 'error');
                        select.val(oldStatus);
                    },
                    complete: function(){
                        select.prop('disabled', false);
                    }
                });
            } else {
                // Revert on cancel
                select.val(oldStatus);
            }
        });
    });

});
</script>
@endpush

