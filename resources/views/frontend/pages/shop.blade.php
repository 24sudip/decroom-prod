@extends('frontend.layouts.master')
@section('title', $vendor->shop_name ?? 'Shop')
@section('content')

<section id="quikctech-shop-banner" style="background: url('{{ asset('frontend/images/image 13.png') }}') no-repeat center / cover;">
    <div class="container-fluid">
        <div class="row w-100">
            <div class="col-lg-12">
                <div class="quicktech-shop-details">
                    <div class="quikctech-shop-logo">
                    @if($vendor->logo)
                        <img src="{{ asset($vendor->logo) }}" alt="{{ $vendor->shop_name }} Logo" style="width: 100px; height: 100px; object-fit: cover;">
                    @else
                        <img src="{{ asset('frontend/images/lotto.png') }}" alt="Shop Logo">
                    @endif
                    </div>
                    <div class="quikctech-shop-banner-textt">
                        <div class="quicktech-shop-text-inner">
                            <h4>{{ $vendor->shop_name ?? 'Vendor Shop' }}</h4>
                            @if (auth('customer')->check())
                            <h4>Id: {{ $vendor->id }}</h4>
                            @endif
                            <p>{{ $vendor->type ?? 'Mall / Flagship Store' }} Type |
                                <span class="followers-count">{{ number_format($vendor->followers_count ?? 0) }}
                                    Followers
                                </span>
                            </p>
                            <span>{{ $vendor->rating ?? '0' }}% positive Rating</span>
                        </div>
                    </div>
                    <div class="quikctech-chat-follow-btn d-flex">
                        <div class="quikctect-c-btn text-center">
                            <a href="{{ route('customer.chat-with-seller', $vendor->user_id) }}">
                                <img src="{{ asset('frontend/images/chat 1.png') }}" alt="Chat">
                                <br>
                                Chat Now
                            </a>
                        </div>
                        <div class="quikctect-c-btn text-center">
                            @auth('customer')
                            <a href="#" class="follow-vendor-btn"
                                data-vendor-id="{{ $vendor->id }}"
                                data-following="{{ $isFollowed ? 'true' : 'false' }}">
                                <img src="{{ asset('frontend/images/follow 1.png') }}" alt="Follow" id="follow-icon">
                                <br>
                                <span id="follow-text" class="{{ $isFollowed ? 'following' : '' }}">{{ $isFollowed ? 'Following' : 'Follow Us' }}</span>
                            </a>
                            @else
                            <a href="#" onclick="showLoginAlert()">
                                <img src="{{ asset('frontend/images/follow 1.png') }}" alt="Follow">
                                <br>
                                Follow Us
                            </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<section id="quicktech-shop-main">
    <div class="container">
        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="quicktech-top-shop-bar">
                    <div class="quikctech-shop-menu">
                          <ul>
                            <!--<li><a href="{{ route('vendor.shop.view', $user->id) }}" class="active">Store</a></li>-->
                            <!--<li><a href="#">Products</a></li>-->
                            <!--<li><a href="#">Services</a></li>-->
                            <!--<li><a href="#">Reviews</a></li>-->
                          </ul>
                      </div>
                      <div class="quikctech-search-inner-shop">
                        <form action="{{ route('frontend.searchlist') }}" method="GET" class="d-flex">
                            <input type="text" name="keyword" class="form-control" placeholder="Search in Store" value="{{ request('keyword') }}">

                            <button type="submit" class="btn btn-search">
                                <img src="{{ asset('frontend/images/search icon.png') }}" alt="Search">
                            </button>

                            <div id="searchResultBox"
                                class="search-suggestion-box shadow-sm"
                                style="display:none; position:absolute; z-index:99999; width:100%; background:white; max-height:350px; overflow-y:auto;">
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>

        <!-- Promotional Banner 1 -->
        <div class="row mt-2">
            <div class="col-lg-12">
                <div class="quikctech-shop-ban-img">
                    @if($vendor->banner_image)
                    <img src="{{ asset($vendor->banner_image) }}" class="w-100" alt="{{ $vendor->shop_name }} Banner" style="max-height: 300px; object-fit: cover;">
                    @else
                    <img src="{{ asset('frontend/images/promotion-banner.png') }}" class="w-100" alt="Promotional Banner">
                    @endif
                </div>
            </div>
        </div>

       <!-- Shop Stats -->
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="quikctech-shop-stats">
                    <div class="row text-center">
                        <div class="col-4">
                            <div class="stat-item">
                                <h5>{{ $products->total() }}</h5>
                                <p>Total Products</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h5>{{ $vendor->followers_count ?? 0 }}</h5>
                                <p>Followers</p>
                            </div>
                        </div>
                        <div class="col-4">
                            <div class="stat-item">
                                <h5>{{ $vendor->rating ?? '0' }}%</h5>
                                <p>Positive Rating</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Related Vendors (Sidebar) -->
        @if($relatedVendors->count() > 0)
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="related-vendors">
                    <h5>Similar Shops</h5>
                    <div class="row g-3">
                        @foreach($relatedVendors as $relatedVendor)
                        <div class="col-lg-2 col-md-3 col-4">
                            <a href="{{ route('vendor.shop.view', $relatedVendor->user_id) }}" class="text-decoration-none">
                                <div class="related-vendor-card text-center">
                                    @if($relatedVendor->logo)
                                        <img src="{{ asset($relatedVendor->logo) }}" alt="{{ $relatedVendor->shop_name }}" class="rounded-circle" style="width: 60px; height: 60px; object-fit: cover;">
                                    @else
                                        <div class="rounded-circle bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 60px; height: 60px;">
                                            <i class="fas fa-store text-muted"></i>
                                        </div>
                                   @endif
                                    <p class="small mt-2 mb-0">{{ Str::limit($relatedVendor->shop_name, 15) }}</p>
                                </div>
                            </a>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
        @endif

        <!-- Products Grid -->
        <div class="row gapp mt-5 mb-5">
            @forelse($products as $product)
            <div class="col-lg-3 col-6 col-sm-6 mb-4">
                <a href="{{ route('product.details', $product->id) }}">
                    <div class="quicktech-product">
                        <div class="quikctech-wishlist">
                            <button type="button"><i class="fa-solid fa-heart"></i></button>
                        </div>
                        <div class="quicktech-sold">
                            <span>{{ $product->orderItems ? $product->orderItems->sum('quantity') : 0 }} sold</span>
                        </div>

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
                                <img src="{{ asset('frontend/images/Architect1.png') }}" alt="Default Product Image" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                            @endif
                        </div>

                        <div class="quicktech-product-text">
                            <h6>{{ Str::limit($product->name, 50) }}</h6>
                            <div class="d-flex justify-content-between quicktech-pp-t">
                                <p>
                                    ৳ {{ number_format($product->price) }}
                                    <br>
                                    @if($product->special_price && $product->special_price < $product->price)
                                    <span style="font-size: 13px;">
                                        <s>৳ {{ number_format($product->price) }}</s>
                                        -{{ number_format((($product->price - $product->special_price) / $product->price) * 100, 0) }}%
                                    </span>
                                    @endif
                                </p>
                                @php
                                    $avgRating = $product->averageRating();
                                    $ratingCount = $product->ratingCount();
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
                    <h4>No products found</h4>
                    <p>This shop doesn't have any products yet.</p>
                    <p class="text-muted">Check back later for new products!</p>
                </div>
            </div>
            @endforelse
       </div>

        <!-- Pagination -->
        @if($products->hasPages())
        <div class="row mt-4">
            <div class="col-lg-12">
                <div class="d-flex justify-content-center">
                    {{ $products->links() }}
                </div>
            </div>
        </div>
        @endif

        <!-- Promotional Banner 2 -->
        <div class="row mt-5">
            <div class="col-lg-12">
                <div class="quikctech-shop-ban-img">
                    <img src="{{ asset('frontend/images/image 16 (1).png') }}" class="w-100" alt="Promotional Banner 2">
                </div>
            </div>
        </div>
    </div>
</section>

<style>
.quikctech-shop-stats {
    background: #f8f9fa;
    border-radius: 10px;
    padding: 20px;
    border: 1px solid #e9ecef;
}

.stat-item h5 {
    color: #2c3e50;
    font-weight: 700;
    margin-bottom: 5px;
}

.stat-item p {
    color: #6c757d;
    margin-bottom: 0;
    font-size: 0.9rem;
}

.follow-vendor-btn {
    text-decoration: none;
    transition: all 0.3s ease;
}

.follow-vendor-btn:hover {
    transform: translateY(-2px);
}

.following {
    color: #28a745 !important;
    font-weight: 600;
}

.related-vendors {
    background: #fff;
    padding: 20px;
    border-radius: 10px;
    border: 1px solid #e9ecef;
}

.related-vendors h5 {
    color: #2c3e50;
    margin-bottom: 15px;
    font-weight: 600;
}

.related-vendor-card {
    padding: 10px;
    border-radius: 8px;
    transition: all 0.3s ease;
    border: 1px solid transparent;
}

.related-vendor-card:hover {
    border-color: #007bff;
    transform: translateY(-2px);
}

.related-vendor-card p {
    color: #495057;
    line-height: 1.2;
}
</style>

<style>
.search-suggestion-box .search-item:hover {
    background: #f5f5f5;
}

.quikctech-dropdown-btn { border: none; background: transparent; padding: 4px; border-radius: 50%; transition: all 0.3s ease; }
.quikctech-dropdown-btn:hover { background: rgba(0, 123, 255, 0.1); transform: scale(1.05); }
.quikctech-login-btn:hover { background: #007bff !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3); }
.quikctech-login-btn:hover span { color: white !important; }
.quikctech-login-btn:hover img { filter: brightness(0) invert(1); }
.customer-avatar-inline { transition: all 0.3s ease; }
.quikctech-dropdown-btn:hover .customer-avatar-inline { transform: scale(1.1); }
@keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
.position-absolute.bg-success { animation: pulse 2s infinite; }
</style>

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    const input = document.getElementById("searchInput");
    const category = document.getElementById("searchCategory");
    const box = document.getElementById("searchResultBox");

    let searchTimer;

    input.addEventListener("keyup", function () {
        clearTimeout(searchTimer);

        const keyword = input.value.trim();
        const categoryId = category.value;

        if (keyword.length < 2) {
            box.style.display = "none";
            box.innerHTML = "";
            return;
        }

        searchTimer = setTimeout(() => {
            fetch(`{{ route('frontend.search') }}?q=${keyword}&category_id=${categoryId}`)
                .then(res => res.json())
                .then(data => {
                    if (data.length === 0) {
                        box.innerHTML = `<div class="p-2 text-muted">No results found</div>`;
                        box.style.display = "block";
                        return;
                    }

                    let html = '';
                    data.forEach(item => {
                        html += `
                            <div class="search-item p-2 border-bottom"
                                 style="cursor:pointer;"
                                 onclick="window.location.href='/product/${item.slug ?? item.id}'">
                                <strong>${item.name}</strong><br>
                                <small class="text-muted">${item.description?.substring(0, 60) || ''}...</small>
                            </div>
                        `;
                    });

                    box.innerHTML = html;
                    box.style.display = "block";
                });
        }, 300);
    });

    // Hide suggestion box when clicking outside
    document.addEventListener("click", function (e) {
        if (!box.contains(e.target) && e.target !== input) {
            box.style.display = "none";
        }
    });

});
</script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Follow vendor functionality
    const followBtn = document.querySelector('.follow-vendor-btn');
    if (followBtn) {
        followBtn.addEventListener('click', function(e) {
            e.preventDefault();

            const vendorId = this.getAttribute('data-vendor-id');
            const isFollowing = this.getAttribute('data-following') === 'true';
            const button = this;
            const followIcon = document.getElementById('follow-icon');
            const followText = document.getElementById('follow-text');
            const followersCount = document.querySelector('.followers-count');

            // Show loading state
            button.style.opacity = '0.7';
            const originalText = followText.textContent;
            followText.textContent = 'Loading...';

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
                        followText.textContent = 'Following';
                        followText.classList.add('following');
                        button.setAttribute('data-following', 'true');
                    } else {
                        followText.textContent = 'Follow Us';
                        followText.classList.remove('following');
                        button.setAttribute('data-following', 'false');
                    }

                    // Update followers count
                    if (followersCount) {
                        followersCount.textContent = data.followers_count.toLocaleString() + ' Followers';
                    }

                    // Update stats
                    const followerStat = document.querySelector('.stat-item:nth-child(2) h5');
                    if (followerStat) {
                        followerStat.textContent = data.followers_count;
                    }

                    // Show success message
                    showToast(data.message, 'success');
                } else {
                    if (data.login_required) {
                        showLoginAlert();
                        followText.textContent = originalText;
                    } else {
                        showToast(data.message, 'error');
                        followText.textContent = originalText;
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
                showToast('Something went wrong. Please try again.', 'error');
                followText.textContent = originalText;
            })
            .finally(() => {
                button.style.opacity = '1';
            });
        });
    }
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
