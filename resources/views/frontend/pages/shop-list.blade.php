@extends('frontend.layouts.master')
@section('title', 'Shops')
@section('content')

<section id="quikctech-shop-banner" style="background: url('{{ asset('frontend/images/image 13.png') }}') no-repeat center / cover;">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <div class="text-center text-white py-5">
                    <h1 class="display-5 fw-bold">Discover Amazing Shops</h1>
                    <p class="lead">Browse through our curated list of trusted vendors</p>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="quicktech-shop-list-main" class="py-5">
    <div class="container">
        <!-- Search and Filter Section -->
        <div class="row mb-4">
            <div class="col-lg-12">
                <div class="card shadow-sm">
                    <div class="card-body">
                        <div class="row g-3">
                            <div class="col-lg-4">
                                <form action="{{ route('vendor.shop.list') }}" method="GET">
                                    <div class="input-group">
                                        <input type="text" name="search" class="form-control" 
                                               placeholder="Search shops..." value="{{ request('search') }}">
                                        <button class="btn btn-primary" type="submit">
                                            <i class="fas fa-search"></i>
                                        </button>
                                    </div>
                                </form>
                            </div>
                            <div class="col-lg-3">
                                <select class="form-select" onchange="window.location.href=this.value">
                                    <option value="{{ route('vendor.shop.list', ['sort' => 'latest']) }}"
                                            {{ request('sort') == 'latest' ? 'selected' : '' }}>
                                        Sort by: Latest
                                    </option>
                                    <option value="{{ route('vendor.shop.list', ['sort' => 'popular']) }}"
                                            {{ request('sort') == 'popular' ? 'selected' : '' }}>
                                        Sort by: Most Popular
                                    </option>
                                    <option value="{{ route('vendor.shop.list', ['sort' => 'rating']) }}"
                                            {{ request('sort') == 'rating' ? 'selected' : '' }}>
                                        Sort by: Highest Rating
                                    </option>
                                    <option value="{{ route('vendor.shop.list', ['sort' => 'name']) }}"
                                            {{ request('sort') == 'name' ? 'selected' : '' }}>
                                        Sort by: Name
                                    </option>
                                </select>
                            </div>
                            <div class="col-lg-3">
                                @if($vendorTypes->count() > 0)
                                <select class="form-select" onchange="window.location.href=this.value">
                                    <option value="{{ route('vendor.shop.list') }}">All Types</option>
                                    @foreach($vendorTypes as $type)
                                        <option value="{{ route('vendor.shop.list', ['type' => $type]) }}"
                                                {{ request('type') == $type ? 'selected' : '' }}>
                                            {{ $type }}
                                        </option>
                                    @endforeach
                                </select>
                                @endif
                            </div>
                            <div class="col-lg-2">
                                <a href="{{ route('vendor.shop.list') }}" class="btn btn-outline-secondary w-100">
                                    Reset Filters
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Results Count -->
        @if(request()->has('search') || request()->has('type') || request()->has('sort'))
        <div class="row mb-3">
            <div class="col-lg-12">
                <div class="alert alert-info py-2">
                    <small>
                        <i class="fas fa-info-circle me-1"></i>
                        Showing {{ $vendors->total() }} vendor(s)
                        @if(request('search'))
                            matching "{{ request('search') }}"
                        @endif
                        @if(request('type'))
                            in {{ request('type') }}
                        @endif
                    </small>
                </div>
            </div>
        </div>
        @endif

        <!-- Vendors Grid -->
        <div class="row g-4">
            @forelse($vendors as $vendor)
            <div class="col-lg-4 col-md-6">
                <div class="vendor-card card h-100 shadow-sm hover-shadow">
                    <div class="vendor-card-header position-relative">
                        <!-- Vendor Banner -->
                        <div class="vendor-banner" style="height: 150px; overflow: hidden;">
                            @if($vendor->banner_image)
                                <img src="{{ asset('public/' . $vendor->banner_image) }}" 
                                     alt="{{ $vendor->shop_name }} Banner" 
                                     class="w-100 h-100" style="object-fit: cover;">
                            @else
                                <div class="w-100 h-100 bg-secondary d-flex align-items-center justify-content-center">
                                    <i class="fas fa-store fa-3x text-white"></i>
                                </div>
                            @endif
                        </div>
                        
                        <!-- Vendor Logo -->
                        <div class="vendor-logo position-absolute top-100 start-50 translate-middle">
                            <div class="logo-container rounded-circle border border-4 border-white bg-white shadow">
                                @if($vendor->logo)
                                    <img src="{{ asset('public/' . $vendor->logo) }}" 
                                         alt="{{ $vendor->shop_name }} Logo" 
                                         class="rounded-circle" 
                                         style="width: 80px; height: 80px; object-fit: cover;">
                                @else
                                    <div class="rounded-circle bg-light d-flex align-items-center justify-content-center"
                                         style="width: 80px; height: 80px;">
                                        <i class="fas fa-store fa-2x text-muted"></i>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="card-body text-center pt-5 mt-3">
                        <!-- Vendor Info -->
                        <h5 class="card-title mb-2">{{ $vendor->shop_name }}</h5>
                        <p class="text-muted small mb-2">
                            <i class="fas fa-tag me-1"></i>{{ $vendor->type ?? 'General Store' }}
                        </p>
                        
                        <!-- Rating -->
                        <div class="vendor-rating mb-2">
                            <div class="d-flex justify-content-center align-items-center">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= floor($vendor->rating ?? 0))
                                        <i class="fas fa-star text-warning small"></i>
                                    @elseif($i == ceil($vendor->rating ?? 0) && fmod($vendor->rating ?? 0, 1) != 0)
                                        <i class="fas fa-star-half-alt text-warning small"></i>
                                    @else
                                        <i class="far fa-star text-warning small"></i>
                                    @endif
                                @endfor
                                <span class="ms-1 small text-muted">({{ number_format($vendor->rating ?? 0, 1) }})</span>
                            </div>
                        </div>

                        <!-- Stats -->
                        <div class="vendor-stats d-flex justify-content-around text-center mb-3">
                            <div>
                                <h6 class="mb-0">{{ $vendor->user->total_products ?? 0 }}</h6>
                                <small class="text-muted">Products</small>
                            </div>
                            <div>
                                <h6 class="mb-0 followers-count-{{ $vendor->id }}">{{ number_format($vendor->followers_count) }}</h6>
                                <small class="text-muted">Followers</small>
                            </div>
                            <div>
                                <h6 class="mb-0">
    {{ optional($vendor->user)->products?->where('status', true)->count() ?? 0 }}
</h6>

                                <small class="text-muted">Active</small>
                            </div>
                        </div>

                        <!-- Address -->
                        @if($vendor->address)
                        <p class="small text-muted mb-3">
                            <i class="fas fa-map-marker-alt me-1"></i>
                            {{ Str::limit($vendor->address, 50) }}
                        </p>
                        @endif
                    </div>

                    <div class="card-footer bg-transparent border-top-0 pb-3">
                        <div class="row g-2">
                            <div class="col-6">
                                @if($vendor->user)
                                <a href="{{ route('vendor.shop.view', $vendor->user->id) }}" 
                                   class="btn btn-outline-primary w-100 btn-sm">
                                    Visit Shop
                                </a>
                                @endif
                            </div>
                            <div class="col-6">
                                @auth('customer')
                                    <button class="btn w-100 btn-sm follow-vendor {{ $vendor->isFollowedBy() ? 'btn-success' : 'btn-outline-secondary' }}" 
                                            data-vendor-id="{{ $vendor->id }}"
                                            data-following="{{ $vendor->isFollowedBy() ? 'true' : 'false' }}">
                                        <i class="fas {{ $vendor->isFollowedBy() ? 'fa-check' : 'fa-plus' }} me-1"></i>
                                        <span class="follow-text">{{ $vendor->isFollowedBy() ? 'Following' : 'Follow' }}</span>
                                    </button>
                                @else
                                    <button class="btn btn-outline-secondary w-100 btn-sm" 
                                            onclick="showLoginAlert()">
                                        <i class="fas fa-plus me-1"></i> Follow
                                    </button>
                                @endauth
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-lg-12">
                <div class="text-center py-5">
                    <div class="empty-state">
                        <i class="fas fa-store fa-4x text-muted mb-3"></i>
                        <h4>No Vendors Found</h4>
                        <p class="text-muted">No vendors match your search criteria. Try adjusting your filters.</p>
                        <a href="{{ route('vendor.shop.list') }}" class="btn btn-primary">Clear Filters</a>
                    </div>
                </div>
            </div>
            @endforelse
        </div>

        <!-- Pagination -->
        @if($vendors->hasPages())
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="d-flex justify-content-center">
                    {{ $vendors->links() }}
                </div>
            </div>
        </div>
        @endif
    </div>
</section>

<style>
.vendor-card {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
}

.vendor-card:hover {
    transform: translateY(-5px);
}

.hover-shadow:hover {
    box-shadow: 0 1rem 3rem rgba(0, 0, 0, 0.175) !important;
}

.vendor-logo .logo-container {
    width: 90px;
    height: 90px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sample-product-img {
    border: 1px solid #e9ecef;
    transition: transform 0.2s ease;
}

.sample-product-img:hover {
    transform: scale(1.05);
}

.empty-state {
    max-width: 400px;
    margin: 0 auto;
}

.vendor-stats h6 {
    font-size: 1.1rem;
    font-weight: 600;
}

.vendor-stats small {
    font-size: 0.75rem;
}

.alert-info {
    background-color: #e3f2fd;
    border-color: #b3e0ff;
}
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Follow vendor functionality
    document.querySelectorAll('.follow-vendor').forEach(button => {
        button.addEventListener('click', function() {
            const vendorId = this.getAttribute('data-vendor-id');
            const isFollowing = this.getAttribute('data-following') === 'true';
            const button = this;
            
            // Show loading state
            button.disabled = true;
            const originalHTML = button.innerHTML;
            button.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i> Loading...';
            
            fetch(`{{ route('vendor.shop.follow', '') }}/${vendorId}`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Content-Type': 'application/json',
                },
            })
            .then(response => {
                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }
                return response.json();
            })
            .then(data => {
                if (data.success) {
                    // Update button state
                    if (data.following) {
                        button.classList.remove('btn-outline-secondary');
                        button.classList.add('btn-success');
                        button.innerHTML = '<i class="fas fa-check me-1"></i> <span class="follow-text">Following</span>';
                    } else {
                        button.classList.remove('btn-success');
                        button.classList.add('btn-outline-secondary');
                        button.innerHTML = '<i class="fas fa-plus me-1"></i> <span class="follow-text">Follow</span>';
                    }
                    
                    // Update followers count
                    const followersCount = document.querySelector(`.followers-count-${vendorId}`);
                    if (followersCount) {
                        followersCount.textContent = data.followers_count.toLocaleString();
                    }
                    
                    // Update data attribute
                    button.setAttribute('data-following', data.following);
                    
                    // Show success message
                    showToast(data.message, 'success');
                } else {
                    if (data.login_required) {
                        showLoginAlert();
                    } else {
                        showToast(data.message, 'error');
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Something went wrong. Please try again.', 'error');
                button.innerHTML = originalHTML; 
            })
            .finally(() => {
                button.disabled = false;
            });
        });
    });
});

function showLoginAlert() {
    Swal.fire({
        title: 'Login Required',
        text: 'Please login as a customer to follow vendors',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Login as Customer',
        cancelButtonText: 'Cancel',
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33'
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = '{{ route("customer.login") }}';
        }
    });
}

function showToast(message, type = 'success') {
    const Toast = Swal.mixin({
        toast: true,
        position: 'top-end',
        showConfirmButton: false,
        timer: 3000,
        timerProgressBar: true,
        didOpen: (toast) => {
            toast.addEventListener('mouseenter', Swal.stopTimer)
            toast.addEventListener('mouseleave', Swal.resumeTimer)
        }
    });

    Toast.fire({
        icon: type,
        title: message
    });
}
</script>
    
@endsection