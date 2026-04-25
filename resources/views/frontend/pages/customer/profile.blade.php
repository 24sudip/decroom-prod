@extends('frontend.layouts.master')
@section('title', 'Customer Profile - ' . ucfirst($active_tab))
@section('content')

<section id="quicktech-customer-profile" style="background: url('{{ asset('frontend/images/profile-bg.png') }}') no-repeat center / cover;">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="profile-header text-center text-white mb-5">
                    <div class="profile-avatar mb-3">
                        <div class="avatar-placeholder rounded-circle bg-white d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h2 class="mb-2">{{ $customer->name }}</h2>
                    <p class="mb-0">{{ $customer->email ?? $customer->phone }}</p>
                    <p class="mb-0">{{ $customer->address }}</p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                <div class="profile-sidebar card border-0 shadow-sm">
                    <div class="card-body">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link {{ $active_tab == 'dashboard' ? 'active' : '' }}"
                                   href="{{ route('customer.profile', 'dashboard') }}">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $active_tab == 'orders' ? 'active' : '' }}"
                                   href="{{ route('customer.profile', 'orders') }}">
                                    <i class="fas fa-shopping-bag me-2"></i> My Orders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link {{ $active_tab == 'service-orders' ? 'active' : '' }}"
                                   href="{{ route('customer.profile', 'service-orders') }}">
                                    <i class="fas fa-shopping-bag me-2"></i> My Services
                                </a>
                            </li>
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link {{ $active_tab == 'addresses' ? 'active' : '' }}" -->
                            <!--       href="{{ route('customer.profile', 'addresses') }}">-->
                            <!--        <i class="fas fa-map-marker-alt me-2"></i> Addresses-->
                            <!--    </a>-->
                            <!--</li>-->
                            @if(method_exists($customer, 'followedVendors'))
                            <li class="nav-item">
                                <a class="nav-link {{ $active_tab == 'followed-vendors' ? 'active' : '' }}"
                                   href="{{ route('customer.profile', 'followed-vendors') }}">
                                    <i class="fas fa-heart me-2"></i> Followed Shops
                                </a>
                            </li>
                            @endif
                            <li class="nav-item">
                                <a class="nav-link {{ $active_tab == 'chat' ? 'active' : '' }}"
                                   href="{{ route('customer.message') }}">
                                    <i class="fas fa-comments me-2"></i> Message
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link {{ $active_tab == 'settings' ? 'active' : '' }}"
                                   href="{{ route('customer.profile', 'settings') }}">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="{{ route('customer.logout') }}" method="POST" class="d-none">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="profile-content card border-0 shadow-sm">
                    <div class="card-body">

                        @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                {{ session('error') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif

                        <!-- Dashboard Tab -->
                        @if($active_tab == 'dashboard')
                            <div class="dashboard-welcome">
                                <h4 class="mb-4">Welcome back, {{ $customer->name }}!</h4>

                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <div class="stat-card text-center p-4 border rounded">
                                            <i class="fas fa-shopping-bag fa-2x text-primary mb-3"></i>
                                            <h5>Total Orders</h5>
                                            <h3 class="text-primary">{{ isset($orders) ? $orders->count() : 0 }}</h3>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="stat-card text-center p-4 border rounded">
                                            <i class="fas fa-map-marker-alt fa-2x text-success mb-3"></i>
                                            <h5>Saved Addresses</h5>
                                            <h3 class="text-success">{{ isset($shippingAddresses) ? $shippingAddresses->count() : 0 }}</h3>
                                        </div>
                                    </div>
                                    @if(isset($followedVendors))
                                    <div class="col-md-4 mb-4">
                                        <div class="stat-card text-center p-4 border rounded">
                                            <i class="fas fa-heart fa-2x text-danger mb-3"></i>
                                            <h5>Followed Shops</h5>
                                            <h3 class="text-danger">{{ $followedVendors->count() }}</h3>
                                        </div>
                                    </div>
                                    @endif
                                </div>

                                <div class="recent-activity mt-5">
                                    <h5 class="mb-3">Recent Activity</h5>
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <small class="text-muted">Just now</small>
                                            <p class="mb-1">You logged into your account</p>
                                        </div>
                                        @if(isset($orders) && $orders->count() > 0)
                                        <div class="list-group-item">
                                            <small class="text-muted">{{ $orders->first()->created_at->diffForHumans() }}</small>
                                            <p class="mb-1">You placed order #{{ $orders->first()->id }}</p>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>

                        <!-- Orders Tab -->
                        @elseif($active_tab == 'orders')
                            <h4 class="mb-4">My Orders</h4>

                            @if(isset($orders) && $orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($orders as $order)
                                            <tr>
                                                <td>#{{ $order->id }}</td>
                                                <td>{{ $order->created_at->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $order->status == 6 ? 'success' : 'warning' }}">
                                                        {{ ucfirst($order->ordertype->name) }}
                                                    </span>
                                                </td>
                                                <td>৳{{ number_format($order->total_amount, 2) }}</td>

                                                <td>
                                                    <a href="{{ route('order.details', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                        View
                                                    </a>

                                                    {{-- ⭐ Rate Now Button (only when completed) --}}
                                                    @if($order->status == 6)
                                                        <button class="btn btn-sm btn-outline-success"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ratingModal{{ $order->id }}">
                                                            Rate Now
                                                        </button>
                                                    @endif
                                                </td>
                                            </tr>

                                            {{-- ⭐⭐⭐ Rating Modal for This Order ⭐⭐⭐ --}}
                                            <div class="modal fade" id="ratingModal{{ $order->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Rate Your Order #{{ $order->id }}</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <form action="{{ route('product.rate') }}" method="POST">
                                                            @csrf

                                                            <div class="modal-body">

                                                                @if($order->items && $order->items->count() > 0)

                                                                    @foreach($order->items as $item)
                                                                        <div class="mb-3 p-2 border rounded">

                                                                            <strong>{{ $item->product->name ?? 'Product removed' }}</strong>

                                                                            {{-- product_id array --}}
                                                                            <input type="hidden" name="product_id[]" value="{{ $item->product_id }}">

                                                                            @php
                                                                                $myRating = $item->product->ratings()
                                                                                    ->where('customer_id', auth('customer')->id())
                                                                                    ->first()->rating ?? 0;
                                                                            @endphp

                                                                            <div class="rating-stars mt-2" data-item="{{ $item->id }}">
                                                                                @for($i = 1; $i <= 5; $i++)
                                                                                    <label style="cursor: pointer; font-size: 22px;">
                                                                                        <input type="radio"
                                                                                               name="rating[{{ $item->product_id }}]"
                                                                                               value="{{ $i }}"
                                                                                               class="d-none star-input"
                                                                                               {{ $i == $myRating ? 'checked' : '' }}>
                                                                                        <i class="fa fa-star star-icon {{ $i <= $myRating ? 'text-warning' : 'text-secondary' }}"
                                                                                           data-value="{{ $i }}"
                                                                                           data-product="{{ $item->product_id }}">
                                                                                        </i>
                                                                                    </label>
                                                                                @endfor
                                                                            </div>

                                                                            {{-- Review per product --}}
                                                                            <textarea name="review[{{ $item->product_id }}]"
                                                                                      class="form-control mt-2"
                                                                                      rows="2"
                                                                                      placeholder="Write your review (optional)">
                                                                                {{ $item->product->ratings()->where('customer_id', auth('customer')->id())->first()->review ?? '' }}
                                                                            </textarea>

                                                                        </div>
                                                                    @endforeach

                                                                @else
                                                                    <p class="text-danger">No items found for this order.</p>
                                                                @endif

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Submit Rating</button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                            </div>

                                                        </form>


                                                    </div>
                                                </div>
                                            </div>
                                            {{-- ⭐ END Rating Modal --}}

                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $orders->links() }}

                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5>No orders yet</h5>
                                    <p class="text-muted">You haven't placed any orders yet.</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary">Start Shopping</a>
                                </div>
                            @endif
                        <!-- Service Orders Tab -->
                        @elseif($active_tab == 'service-orders')
                            <h4 class="mb-4">My Services</h4>

                            @if(isset($service_orders) && $service_orders->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Service Order ID</th>
                                                <th>Expire Date</th>
                                                <th>Installment Status</th>
                                                <th>Installment Number</th>
                                                <th>Paid Amount</th>
                                                <th>Due Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            @foreach($service_orders as $service_order)
                                            <tr>
                                                <td>#{{ $service_order->id }}</td>
                                                <td>{{ Carbon\Carbon::parse($service_order->expired_at)->format('M d, Y') }}</td>
                                                <td>
                                                    <span class="badge bg-{{ $service_order->installment_status == 1 ? 'success' : 'warning' }}">
                                                        {{ $service_order->installment_status == 1 ? 'Confirmed' : 'Pending' }}
                                                    </span>
                                                </td>
                                                <td>{{ $service_order->installment_number }}</td>
                                                <td>৳ {{ number_format($service_order->paid_amount, 2) }}</td>
                                                <td>৳ {{ number_format($service_order->due_amount, 2) }}</td>

                                                <td>
                                                    <a href="{{ route('service-draft.details', $service_order->id) }}" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        View
                                                    </a>
                                                    @if ($service_order->installment_status == 0)
                                                    Confirming 
                                                    @else
                                                    <button class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addPayment{{ $service_order->id }}">
                                                        Add Payment
                                                    </button>
                                                    @endif
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="addPayment{{ $service_order->id }}" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Add Installment</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <form action="{{ route('customer.add.payment',  $service_order->id) }}" method="POST">
                                                                @csrf
                                                                <div class="mb-3">
                                                                    <label for="form-label">Payment Amount</label>
                                                                    <input type="number" class="form-control" id="form-label" name="amount" required placeholder="Amount">
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $service_orders->links() }}

                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5>No Services yet</h5>
                                    <p class="text-muted">You haven't ordered any services yet.</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary">Start Shopping</a>
                                </div>
                            @endif

                        <!-- Follow Tab -->
                        @elseif($active_tab == 'followed-vendors')
                            <h4 class="mb-4">Followed Vendors</h4>
                            @if(isset($followedVendors) && $followedVendors->count() > 0)
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Shop Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @foreach($followedVendors as $shop)
                                            <tr>
                                                <td>#{{ $shop->id }}</td>
                                                <td>{{ $shop->created_at->format('M d, Y') }}</td>
                                                <td>{{ $shop->shop_name ?? "N/A" }}</td>
                                                <td>
                                                    <a href="{{ route('vendor.shop.view', $shop->user_id) }}" class="btn btn-sm btn-outline-primary">View</a>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                                {{ $followedVendors->links() }}
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5>No followed vendors yet</h5>
                                    <p class="text-muted">You haven't followed vendors yet.</p>
                                    <a href="{{ route('home') }}" class="btn btn-primary">Start Shopping</a>
                                </div>
                            @endif

                        <!-- Addresses Tab -->
                        @elseif($active_tab == 'addresses')
                            <h4 class="mb-4">My Addresses</h4>
                            @if(isset($shippingAddresses) && $shippingAddresses->count() > 0)
                                <div class="row">
                                    @foreach($shippingAddresses as $address)
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-title">{{ $address->address_title ?? 'Primary Address' }}</h6>
                                                <p class="card-text mb-1">{{ $address->address }}</p>
                                                <p class="card-text mb-1">
                                                    {{ $address->upazila->name ?? '' }},
                                                    {{ $address->district->name ?? '' }}
                                                </p>
                                                <p class="card-text mb-1">Phone: {{ $address->phone }}</p>
                                                <div class="mt-3">
                                                    <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            @else
                                <div class="text-center py-5">
                                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                    <h5>No addresses saved</h5>
                                    <p class="text-muted">You haven't saved any shipping addresses yet.</p>
                                    <a href="{{ route('shipping.create') }}" class="btn btn-primary">Add Address</a>
                                </div>
                            @endif

                        <!-- Settings Tab -->
                        @elseif($active_tab == 'settings')
                            <h4 class="mb-4">Profile Settings</h4>
                            <form method="POST" action="{{ route('customer.profile.update') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                   value="{{ old('name', $customer->name) }}" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number *</label>
                                            <input type="text" name="phone" class="form-control"
                                                   value="{{ old('phone', $customer->phone) }}" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control"
                                           value="{{ old('email', $customer->email) }}">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3">{{ old('address', $customer->address) }}</textarea>
                                </div>

                                <hr class="my-4">
                                <h6 class="mb-3">Change Password</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="new_password" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>

                        @else
                            <div class="text-center py-5">
                                <h4>Coming Soon</h4>
                                <p class="text-muted">This section is under development.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    console.log("jQuery loaded!");

    $(".rating-stars .star-icon").on("click", function () {

        let value = $(this).data("value");
        let wrapper = $(this).closest(".rating-stars");

        wrapper.find("input[value='" + value + "']").prop("checked", true);

        wrapper.find(".star-icon").each(function () {
            if ($(this).data("value") <= value) {
                $(this).removeClass("text-secondary").addClass("text-warning");
            } else {
                $(this).removeClass("text-warning").addClass("text-secondary");
            }
        });
    });

});
</script>



<style>
.profile-sidebar .nav-link {
    color: #333;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

.profile-sidebar .nav-link:hover,
.profile-sidebar .nav-link.active {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
}

.profile-content {
    min-height: 500px;
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.avatar-placeholder {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.profile-header {
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}
</style>

@endsection
