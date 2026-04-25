@extends('frontend.seller.seller_master')
@section('title', __('messages.vendor_profile'))
@section('content')

<!-- seller-menu-top -->
@include('frontend.include.seller-menu-top')

<div class="quikctech-profile-inner mt-4 mb-5">
    <!-- Cover Section -->
    <div class="quikctech-profile-cover">
        @if($vendor->banner_image && file_exists(public_path($vendor->banner_image)))
            <img src="{{ asset($vendor->banner_image) }}?t={{ time() }}" class="w-100" alt="{{ __('messages.cover_banner') }}" style="max-height: 300px; object-fit: cover;">
        @else
            <img src="{{ asset('frontend/images/Vector (9).png') }}" class="w-100" alt="{{ __('messages.default_cover') }}" style="max-height: 300px; object-fit: cover;">
        @endif

        <div class="quicktech-profile-pictures">
            @if($vendor->logo && file_exists(public_path($vendor->logo)))
                <img src="{{ asset($vendor->logo) }}?t={{ time() }}" alt="{{ __('messages.profile_picture') }}" style="width: 120px; height: 120px; object-fit: cover;">
            @else
                <img src="https://ui-avatars.com/api/?name={{ urlencode($vendor->user->name) }}&background=random&size=200" alt="{{ __('messages.profile_picture') }}" style="width: 120px; height: 120px; object-fit: cover;">
            @endif
        </div>

    </div>

    <!-- Profile Header -->
    <div class="quikctech-profile-head-text mt-3">
        <div class="quicktech-profile-name">
            <h4>{{ $vendor->user->name }}
                @if($vendor->isActive())
                    @if($vendor->banner_image)
                    <img src="{{ asset($vendor->banner_image) }}?t={{ time() }}" alt="{{ __('messages.verified') }}" title="{{ __('messages.verified_vendor') }}"
                        style="width: 20px; height: 20px;">
                    @else
                    verified
                    @endif
                @endif
            </h4>
            <p class="mb-1">{{ number_format($vendor->followers_count) }} <span class="text-muted">{{ __('messages.followers') }}</span></p>
            <span class="text-warning"><i class="fa-solid fa-star"></i> {{ number_format($vendor->rating, 1) }} {{ __('messages.rating') }}</span>
        </div>

        <!-- Shop Information -->
        <div class="shop-info mb-3">
            <h5 class="text-success mb-2">{{ $vendor->shop_name ?? $vendor->user->name . "'s " . __('messages.shop') }}</h5>
            <p class="text-muted mb-1">
                <i class="fas fa-store"></i>
                {{ ucfirst($vendor->type ?? __('messages.individual')) }} {{ __('Type') }} {{ __('messages.vendor') }}
                @if($vendor->commission)
                    • {{ $vendor->commission }}% {{ __('messages.commission') }}
                @endif
            </p>
            @if($vendor->address)
                <p class="text-muted mb-0">
                    <i class="fas fa-map-marker-alt"></i> {{ $vendor->address }}
                </p>
            @endif
        </div>

        <!-- Vendor Description -->
        <p class="desp mb-4">
            {{ $vendorUser->welcome_description ?? __('messages.default_vendor_description') }}
        </p>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-number text-primary">{{ $stats['total_products'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('messages.total_products') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-number text-success">{{ $stats['active_products'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('messages.active_products') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-number text-info">{{ $stats['total_services'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('messages.services') }}</div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-number text-warning">{{ $stats['total_orders'] ?? 0 }}</div>
                    <div class="stat-label">{{ __('messages.total_orders') }}</div>
                </div>
            </div>
        </div>
        <style>
            @media (min-width: 991.98px) and (max-width: 1199.98px) {
                .quikctech-button-menu-profile ul li a {
                    padding: 4px 25px;
                }
            }
            .ser-active {
                background-color: #1dbf73 !important;
                color: white !important;
            }
            .quikctech-button-menu-profile ul li a {
                border: 2px solid #1dbf73;
            }
        </style>
        <!-- Navigation Menu -->
        <div class="quikctech-button-menu-profile mb-4">
            <ul>
                <li><a href="{{ route('vendor.profile') }}" class="seller-active ser-active">{{ __('messages.profile') }}</a></li>
                <li><a href="{{ route('vendor.products.manage') }}" class="ser-active">{{ __('messages.products') }} ({{ $stats['active_products'] ?? 0 }})</a></li>
                <li><a href="{{ route('vendor.orders.list') }}" class="ser-active">{{ __('messages.orders') }} ({{ $stats['total_orders'] ?? 0 }})</a></li>
                <li><a href="{{route('vendor.message')}}" class="ser-active">{{ __('messages.message') }}</a></li>
                <li><a href="{{ route('vendor.profile.edit') }}" class="ser-active">{{ __('messages.edit_profile') }}</a></li>
                <li>
                    <a href="{{ route('services.index') }}" class="ser-active">
                        {{ __('Services') }}
                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Recent Activities/Posts Section -->
    <div class="row gapp">
        <!-- Recent Products -->
        <div class="col-lg-6">
            <div class="quikctech-profile-post mt-4">
                <div class="quikctech-post-pro-img-head d-flex align-items-center">
                    @if($vendor->logo && file_exists(public_path($vendor->logo)))
                        <img src="{{ asset($vendor->logo) }}?t={{ time() }}" alt="{{ __('messages.profile_picture') }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($vendor->user->name) }}&background=random&size=200" alt="{{ __('messages.profile_picture') }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    @endif
                    <div>
                        <h5 class="mb-0">{{ $vendor->user->name }}
                            <span class="text-muted">{{ __('messages.added_new_products') }}</span>
                        </h5>
                        <p class="text-muted mb-0 small">{{ \Carbon\Carbon::now()->subDays(2)->diffForHumans() }}</p>
                    </div>
                </div>

                <div class="quikctech-post-seller-img mt-3">
                    {{-- Description --}}
                    <p class="quikctech-text quikctech-content" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ __('messages.recently_added_products', ['count' => $stats['active_products'] ?? 0]) }}
                        @if(($vendor->products->count() ?? 0) > 0)
                            {{ __('messages.check_latest_collection') }}
                            {{ $vendor->products->take(3)->pluck('name')->implode(', ') }}
                            @if($vendor->products->count() > 3)
                                {{ __('messages.and_x_more_products', ['count' => $vendor->products->count() - 3]) }}
                            @endif
                        @else
                            {{ __('messages.setting_up_catalog') }}
                        @endif
                    </p>

                    <div class="row gapp mt-5 mb-5">
                    @forelse($vendor->products as $product)
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                        <a href="{{ route('product.details', $product->id) }}">
                            <div class="quicktech-product position-relative">
                                {{-- Wishlist Button --}}
                                <div class="quikctech-wishlist position-absolute top-0 end-0 p-2">
                                    <button type="button"><i class="fa-solid fa-heart"></i></button>
                                </div>

                                {{-- Sold Count --}}
                                <div class="quicktech-sold position-absolute top-0 start-0 p-2 bg-white rounded">
                                    <span>{{ $product->orderItems ? $product->orderItems->sum('quantity') : 0 }} {{ __('messages.sold') }}</span>
                                </div>

                                {{-- Product Image --}}
                                <div class="quikctech-img-product text-center">
                                    @php
                                        $productImage = null;
                                        // Safe check for images
                                        if($product->images && is_object($product->images) && method_exists($product->images, 'count') && $product->images->count() > 0) {
                                            $productImage = $product->images->first();
                                        }
                                    @endphp

                                    @if($productImage && $productImage->image_path)
                                        <img src="{{ asset($productImage->image_path) }}" alt="{{ $product->name }}" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                                    @elseif($product->promotion_image)
                                        <img src="{{ asset($product->promotion_image) }}" alt="{{ $product->name }}" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                                    @else
                                        <img src="{{ asset('frontend/images/Architect1.png') }}" alt="{{ __('messages.default_product_image') }}" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                                    @endif
                                </div>

                                {{-- Product Info --}}
                                <div class="quicktech-product-text mt-2">
                                    <h6 class="text-truncate">{{ Str::limit($product->name, 50) }}</h6>
                                    <div class="d-flex justify-content-between align-items-center quicktech-pp-t">
                                        <p class="mb-0">
                                            ৳ {{ number_format($product->price) }}
                                            @if($product->special_price && $product->special_price < $product->price)
                                                <br>
                                                <span style="font-size: 13px;">
                                                    <s>৳ {{ number_format($product->price) }}</s>
                                                    -{{ number_format((($product->price - $product->special_price) / $product->price) * 100, 0) }}%
                                                </span>
                                            @endif
                                        </p>

                                        {{-- Rating --}}
                                        @php
                                            $avgRating = $product->averageRating();
                                        @endphp
                                        <span class="d-flex align-items-center">
                                            @for($i = 1; $i <= 5; $i++)
                                                @if($i <= round($avgRating))
                                                    <i class="fa-solid fa-star" style="color: #FFD700; font-size: 14px;"></i>
                                                @else
                                                    <i class="fa-regular fa-star" style="color: #ccc; font-size: 14px;"></i>
                                                @endif
                                            @endfor
                                            <span style="margin-left: 3px; font-size: 13px;">
                                                ({{ number_format($avgRating, 1) }})
                                            </span>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </a>
                    </div>
                    @empty
                    <div class="col-lg-12">
                        <div class="text-center py-5">
                            <h4>{{ __('messages.no_products_found') }}</h4>
                            <p>{{ __('messages.no_products_in_shop') }}</p>
                            <p class="text-muted">{{ __('messages.check_back_later') }}</p>
                        </div>
                    </div>
                    @endforelse
                </div>

                </div>

                {{-- JS for See More / See Less --}}
                <script>
                document.querySelectorAll('.quicktech-seemore').forEach(button => {
                    button.addEventListener('click', function() {
                        const content = this.previousElementSibling;
                        if (content.style.webkitLineClamp === 'unset' || !content.style.webkitLineClamp) {
                            content.style.webkitLineClamp = '3';
                            this.textContent = '{{ __('messages.see_more') }}';
                        } else {
                            content.style.webkitLineClamp = 'unset';
                            this.textContent = '{{ __('messages.see_less') }}';
                        }
                    });
                });
                </script>

            </div>
        </div>

        <!-- Store Performance -->
        <div class="col-lg-6">
            <div class="quikctech-profile-post mt-4">
                <div class="quikctech-post-pro-img-head d-flex align-items-center">
                    @if($vendor->logo && file_exists(public_path($vendor->logo)))
                        <img src="{{ asset($vendor->logo) }}?t={{ time() }}" alt="{{ __('messages.profile_picture') }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    @else
                        <img src="https://ui-avatars.com/api/?name={{ urlencode($vendor->user->name) }}&background=random&size=200" alt="{{ __('messages.profile_picture') }}" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    @endif
                    <div>
                        <h5 class="mb-0">{{ $vendor->user->name }}
                            <span class="text-muted">{{ __('messages.store_performance') }}</span>
                        </h5>
                        <p class="text-muted mb-0 small">{{ __('messages.updated_today') }}</p>
                    </div>
                </div>

                <div class="quikctech-post-seller-img mt-3">
                    <div class="performance-stats">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="performance-item">
                                    <div class="performance-value text-success">{{ number_format($vendor->rating, 1) }}/5</div>
                                    <div class="performance-label">{{ __('messages.rating') }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="performance-item">
                                    <div class="performance-value text-primary">{{ $stats['total_followers'] ?? 0 }}</div>
                                    <div class="performance-label">{{ __('messages.followers') }}</div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="performance-item">
                                    <div class="performance-value text-warning">{{ $stats['total_orders'] ?? 0 }}</div>
                                    <div class="performance-label">{{ __('messages.orders') }}</div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="quikctech-text quikctech-content mt-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        {{ __('messages.store_performance_overview', [
                            'rating' => number_format($vendor->rating, 1),
                            'followers' => $stats['total_followers'] ?? 0
                        ]) }}
                        @if($vendor->rating >= 4.5)
                            {{ __('messages.excellent_performance') }}
                        @elseif($vendor->rating >= 4.0)
                            {{ __('messages.good_performance') }}
                        @else
                            {{ __('messages.improving_performance') }}
                        @endif
                    </p>

                    <button class="btn quikctech-toggle quicktech-seemore btn-link p-0 text-decoration-none">{{ __('messages.see_more') }}</button>

                    <div class="quikctech-reaction mt-3 pt-3 border-top">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item me-3">
                                <a href="#" class="text-decoration-none text-dark">
                                    <i style="color: red; font-size: 18px;" class="fa-solid fa-heart"></i>
                                    <span class="small">{{ $totalLikes }}</span>
                                </a>
                            </li>
                            <li class="list-inline-item me-3">
                                <a href="#" class="text-decoration-none text-dark">
                                    <i style="color: #1dbf73; font-size: 18px;" class="fa-regular fa-comment"></i>
                                    <span class="small">{{ $totalComments }}</span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#" class="text-decoration-none text-dark">
                                    <i style="color: #1dbf73; font-size: 18px;" class="fa-solid fa-share"></i>
                                    <span class="small">{{ $totalShares }}</span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div class="modal fade" id="imageUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle">{{ __('messages.upload_image') }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="{{ __('messages.close') }}"></button>
            </div>
            <div class="modal-body">
                <form id="imageUploadForm" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="image_type" id="imageType">
                    <div class="mb-3">
                        <label for="image" class="form-label">{{ __('messages.select_image') }}</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif" required>
                        <div class="form-text">{{ __('messages.supported_formats') }}</div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                            <i class="fas fa-upload me-1"></i>{{ __('messages.upload_image') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden">{{ __('messages.loading') }}</span>
                </div>
                <p class="mb-0">{{ __('messages.uploading_image') }}</p>
            </div>
        </div>
    </div>
</div>

@endsection

@section('styles')
<style>
    .stat-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #007bff;
        transition: transform 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
    }
    .shop-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #28a745;
    }
    .performance-item {
        padding: 15px;
    }
    .performance-value {
        font-size: 20px;
        font-weight: bold;
    }
    .performance-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
    }
    .product-thumbnail {
        transition: transform 0.2s ease;
    }
    .product-thumbnail:hover {
        transform: scale(1.05);
    }
    .product-thumbnail img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    .recent-products {
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
    }
    .quikctech-edit-cover-btn {
        position: absolute;
        bottom: 20px;
        right: 20px;
    }
    .quikctech-profile-cover {
        position: relative;
    }
    .quicktech-profile-pictures {
        position: absolute;
        bottom: -60px;
        left: 30px;
        border: 4px solid white;
        border-radius: 50%;
        background: white;
    }
    .quikctech-button-menu-profile ul {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .quikctech-button-menu-profile ul li a {
        padding: 8px 16px;
        background: #f8f9fa;
        border-radius: 20px;
        text-decoration: none;
        color: #495057;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .quikctech-button-menu-profile ul li a:hover,
    .quikctech-button-menu-profile ul li a.seller-active {
        background: #007bff;
        color: white;
    }
</style>
@endsection

@section('scripts')
<script>
function openImageUpload(type) {
    document.getElementById('imageType').value = type;
    document.getElementById('modalTitle').textContent = type === 'banner' ? '{{ __('messages.upload_cover_banner') }}' : '{{ __('messages.upload_profile_picture') }}';

    // Reset form
    document.getElementById('imageUploadForm').reset();

    var modal = new bootstrap.Modal(document.getElementById('imageUploadModal'));
    modal.show();
}

// Handle image upload form submission
document.getElementById('imageUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = document.getElementById('uploadBtn');
    const originalText = submitBtn.innerHTML;

    // Show loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i>{{ __("messages.uploading") }}...';
    submitBtn.disabled = true;

    // Show loading modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();

    fetch("{{ route('vendor.profile.updateImage') }}", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '{{ csrf_token() }}'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('{{ __("messages.network_error") }}');
        }
        return response.json();
    })
    .then(data => {
        // Hide loading modal
        loadingModal.hide();

        if (data.success) {
            // Close upload modal
            const uploadModal = bootstrap.Modal.getInstance(document.getElementById('imageUploadModal'));
            uploadModal.hide();

            // Show success message
            showToast('{{ __("messages.image_updated_success") }}', 'success');

            // Reload page after short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || '{{ __("messages.upload_failed") }}');
        }
    })
    .catch(error => {
        // Hide loading modal
        loadingModal.hide();

        console.error('Error:', error);
        showToast('{{ __("messages.error_uploading_image") }}: ' + error.message, 'error');

        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// See more functionality
document.querySelectorAll('.quicktech-seemore').forEach(button => {
    button.addEventListener('click', function() {
        const content = this.previousElementSibling;
        if (content.style.webkitLineClamp === 'unset' || !content.style.webkitLineClamp) {
            content.style.webkitLineClamp = '3';
            this.textContent = '{{ __("messages.see_more") }}';
        } else {
            content.style.webkitLineClamp = 'unset';
            this.textContent = '{{ __("messages.see_less") }}';
        }
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `custom-toast alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    toast.innerHTML = `
        <strong>${type === 'success' ? '{{ __("messages.success") }}!' : '{{ __("messages.error") }}!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection
