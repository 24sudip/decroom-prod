@extends('backend.layouts.master-layouts')

@section('title', __('Product Details'))

@section('css')
<style>
    .product-details-card {
        border: none;
        box-shadow: 0 0 15px rgba(0,0,0,0.1);
    }
    .detail-item {
        padding: 12px 0;
        border-bottom: 1px solid #e9ecef;
    }
    .detail-item:last-child {
        border-bottom: none;
    }
    .detail-label {
        font-weight: 600;
        color: #495057;
        min-width: 180px;
    }
    .detail-value {
        color: #212529;
    }
    .status-badge {
        font-size: 0.85rem;
        padding: 0.35rem 0.75rem;
    }
    .price-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
    }
    .image-gallery {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
    .section-header {
        background: linear-gradient(45deg, #6c757d, #495057);
        color: white;
        padding: 12px 20px;
        border-radius: 8px 8px 0 0;
        margin-bottom: 0;
    }
    .info-card {
        border-left: 4px solid #007bff;
    }
    .financial-card {
        border-left: 4px solid #28a745;
    }
    .specs-card {
        border-left: 4px solid #ffc107;
    }
    .gallery-image {
        width: 100%;
        height: 200px;
        object-fit: cover;
        border-radius: 8px;
        margin-bottom: 10px;
    }
    .primary-image {
        border: 3px solid #007bff;
    }
    .dimension-box {
        background: #f8f9fa;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
    }
    .stock-indicator {
        width: 100%;
        height: 8px;
        background: #e9ecef;
        border-radius: 4px;
        overflow: hidden;
    }
    .stock-level {
        height: 100%;
        border-radius: 4px;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ __('Product Details - ') }}{{ $product->name }}</h4>
            <div class="page-title-right">
                <a href="{{ url('admin/product') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>{{ __('Back to List') }}
                </a>
                <a href="{{ route('product.edit', $product->id) }}" class="btn btn-primary">
                    <i class="fas fa-edit me-1"></i>{{ __('Edit Product') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Basic Information -->
    <div class="col-lg-6">
        <div class="card product-details-card info-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-info-circle me-2"></i>{{ __('Basic Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="detail-item d-flex">
                    <span class="detail-label">Product ID:</span>
                    <span class="detail-value fw-bold text-primary">#{{ $product->id }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value fw-semibold">{!! $product->name !!}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">SKU:</span>
                    <span class="detail-value">
                        <code>{{ $product->sku ?? 'N/A' }}</code>
                    </span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Category:</span>
                    <span class="detail-value">
                        <span class="badge bg-light text-dark">{{ $product->category->name ?? 'N/A' }}</span>
                    </span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Brand:</span>
                    <span class="detail-value">
                        <span class="badge bg-light text-dark">{{ $product->brand->name ?? 'N/A' }}</span>
                    </span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Description:</span>
                    <span class="detail-value">{!! $product->description ?? 'N/A' !!}</span>
                </div>
                @if($product->highlight)
                <div class="detail-item">
                    <span class="detail-label d-block mb-2">Highlights:</span>
                    <span class="detail-value bg-light p-3 rounded d-block">
                        {!! $product->highlight !!}
                    </span>
                </div>
                @endif
            </div>
        </div>

        <!-- Status Information -->
        <div class="card product-details-card info-card mb-4">
            <div class="section-header">
                <h5 class="card-title  text-white mb-0">
                    <i class="fas fa-tasks me-2"></i>{{ __('Status Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="detail-item d-flex">
                    <span class="detail-label">Product Status:</span>
                    <span class="detail-value">
                        <span class="badge status-badge 
                            @if($product->status == 1) bg-success
                            @elseif($product->status == 0) bg-danger
                            @elseif($product->status == 2) bg-warning
                            @elseif($product->status == 3) bg-secondary
                            @else bg-light text-dark @endif">
                            {{ $product->status_text }}
                        </span>
                    </span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">QC Status:</span>
                    <span class="detail-value">
                        @if($product->qc_status == 1)
                            <span class="badge bg-success status-badge">✅ Approved</span>
                        @else
                            <span class="badge bg-warning status-badge">⏳ Pending QC</span>
                        @endif
                    </span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Reject Status:</span>
                    <span class="detail-value">
                        @if($product->reject_status == 1)
                            <span class="badge bg-danger status-badge">❌ Rejected</span>
                        @else
                            <span class="badge bg-success status-badge">✅ Active</span>
                        @endif
                    </span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Availability:</span>
                    <span class="detail-value">
                        <span class="badge bg-info status-badge">{{ $product->availability ?? 'Available' }}</span>
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing & Stock Information -->
    <div class="col-lg-6">
        <div class="card product-details-card financial-card mb-4">
            <div class="section-header">
                <h5 class="card-title  text-white mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>{{ __('Pricing Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="price-box mb-3">
                    <h4 class="text-white text-center">৳{{ number_format($product->price, 2) }}</h4>
                    <p class="text-white text-center mb-0">Regular Price</p>
                </div>
                
                @if($product->special_price)
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Special Price:</span>
                    <span class="detail-value text-success fw-bold">৳{{ number_format($product->special_price, 2) }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">You Save:</span>
                    <span class="detail-value text-danger">
                        ৳{{ number_format($product->price - $product->special_price, 2) }}
                        ({{ number_format((($product->price - $product->special_price) / $product->price) * 100, 0) }}%)
                    </span>
                </div>
                @endif

                @if($product->free_items)
                <div class="detail-item d-flex justify-content-between text-info">
                    <span class="detail-label">Free Items:</span>
                    <span class="detail-value">{{ $product->free_items }}</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Stock Information -->
        <div class="card product-details-card financial-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-boxes me-2"></i>{{ __('Stock Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Current Stock:</span>
                    <span class="detail-value fw-bold 
                        @if($product->stock > 10) text-success
                        @elseif($product->stock > 0) text-warning
                        @else text-danger @endif">
                        {{ $product->stock ?? 0 }} units
                    </span>
                </div>
                
                <!-- Stock Level Indicator -->
                <div class="stock-indicator mb-2">
                    @php
                        $stockPercentage = min(100, ($product->stock / 100) * 100);
                        $stockColor = $product->stock > 10 ? 'bg-success' : ($product->stock > 0 ? 'bg-warning' : 'bg-danger');
                    @endphp
                    <div class="stock-level {{ $stockColor }}" style="width: {{ $stockPercentage }}%"></div>
                </div>
                
                <small class="text-muted">
                    @if($product->stock > 10)
                        <i class="fas fa-check-circle text-success"></i> Good stock level
                    @elseif($product->stock > 0)
                        <i class="fas fa-exclamation-triangle text-warning"></i> Low stock
                    @else
                        <i class="fas fa-times-circle text-danger"></i> Out of stock
                    @endif
                </small>

                <!-- Total Stock with Variants -->
                @if($product->variants()->exists())
                <div class="detail-item d-flex justify-content-between mt-3">
                    <span class="detail-label">Total Stock (with variants):</span>
                    <span class="detail-value fw-bold text-primary">{{ $product->total_stock }} units</span>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Vendor Information & Dimensions -->
<div class="row">
    <div class="col-md-6">
        <div class="card product-details-card info-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-building me-2"></i>{{ __('Vendor Information') }}
                </h5>
            </div>
            
            <div class="card-body">
                @if($product->vendor)
                <div class="detail-item d-flex">
                    <span class="detail-label">Shop:</span>
                    <span class="detail-value">{{ optional($product->vendor)->vendorDetails->shop_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $product->vendor->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $product->vendor->email ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $product->vendor->phone ?? 'N/A' }}</span>
                </div>
                
                @else
                <div class="text-center text-muted py-3">
                    <i class="fas fa-building fa-2x mb-2"></i>
                    <p>No vendor information available</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Product Dimensions -->
    <div class="col-md-6">
        <div class="card product-details-card specs-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-ruler-combined me-2"></i>{{ __('Product Dimensions') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    @if($product->weight)
                    <div class="col-6 mb-3">
                        <div class="dimension-box">
                            <i class="fas fa-weight fa-2x text-primary mb-2"></i>
                            <h6>{{ $product->weight }} kg</h6>
                            <small class="text-muted">Weight</small>
                        </div>
                    </div>
                    @endif
                    @if($product->length)
                    <div class="col-6 mb-3">
                        <div class="dimension-box">
                            <i class="fas fa-arrows-alt-h fa-2x text-info mb-2"></i>
                            <h6>{{ $product->length }} cm</h6>
                            <small class="text-muted">Length</small>
                        </div>
                    </div>
                    @endif
                    @if($product->width)
                    <div class="col-6 mb-3">
                        <div class="dimension-box">
                            <i class="fas fa-arrows-alt-v fa-2x text-success mb-2"></i>
                            <h6>{{ $product->width }} cm</h6>
                            <small class="text-muted">Width</small>
                        </div>
                    </div>
                    @endif
                    @if($product->height)
                    <div class="col-6 mb-3">
                        <div class="dimension-box">
                            <i class="fas fa-arrows-alt fa-2x text-warning mb-2"></i>
                            <h6>{{ $product->height }} cm</h6>
                            <small class="text-muted">Height</small>
                        </div>
                    </div>
                    @endif
                </div>

                @if($product->dangerous_goods)
                <div class="alert alert-warning mt-3">
                    <i class="fas fa-exclamation-triangle me-2"></i>
                    <strong>Warning:</strong> This product is classified as dangerous goods.
                </div>
                @endif

                @if(!$product->weight && !$product->length && !$product->width && !$product->height)
                <div class="text-center text-muted py-3">
                    <i class="fas fa-ruler-combined fa-2x mb-2"></i>
                    <p>No dimension information available</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Media & Attachments -->
<div class="row">
    <!-- Product Images -->
    <div class="col-lg-8">
        <div class="card product-details-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-images me-2"></i>{{ __('Product Images') }}
                </h5>
            </div>
            <div class="card-body">
                @if($product->images && $product->images->count() > 0)
                <div class="row">
                    @foreach($product->images as $image)
                    <div class="col-md-4 mb-3">
                        <div class="image-gallery {{ $image->is_primary ? 'primary-image' : '' }}">
                            <img src="{{ asset('public/uploads/products/' . $image->image) }}" 
                                 alt="Product Image" 
                                 class="gallery-image"
                                 onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjhGOUZBIi8+CjxwYXRoIGQ9Ik0xMjAgODBDMTIwIDg4LjgzNjYgMTEyLjgzNyA5NiAxMDQgOTZDOTUuMTYzNCA5NiA4OCA4OC44MzY2IDg4IDgwQzg4IDcxLjE2MzQgOTUuMTYzNCA2NCAxMDQgNjRDMTEyLjgzNyA2NCAxMjAgNzEuMTYzNCAxMjAgODBaIiBmaWxsPSIjOEM5MEE2Ii8+CjxwYXRoIGQ9Ik0xNDIgMTM4SDU4QzUyLjQ3NzIgMTM4IDQ4IDEzMy41MjMgNDggMTI4VjcyQzQ4IDY2LjQ3NzIgNTIuNDc3MiA2MiA1OCA2MkgxNDJDMTQ3LjUyMyA2MiAxNTIgNjYuNDc3MiAxNTIgNzJWMTI4QzE1MiAxMzMuNTIzIDE0Ny41MjMgMTM4IDE0MiAxMzhaIiBzdHJva2U9IiM4QzkwQTYiIHN0cm9rZS13aWR0aD0iNCIvPgo8L3N2Zz4K'">
                            @if($image->is_primary)
                            <span class="badge bg-primary">Primary</span>
                            @endif
                            <div class="mt-2">
                                <a href="{{ asset('public/uploads/products/' . $image->image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-images fa-3x mb-3"></i>
                    <h5>No Images Available</h5>
                    <p>There are no images for this product.</p>
                </div>
                @endif

                <!-- Promotion Image -->
                @if($product->promotion_image)
                <div class="mt-4">
                    <h6>Promotion Image:</h6>
                    <div class="image-gallery">
                        <img src="{{ asset('public/uploads/products/' . $product->promotion_image) }}" 
                             alt="Promotion Image" 
                             class="gallery-image"
                             onerror="this.src='data:image/svg+xml;base64,PHN2ZyB3aWR0aD0iMjAwIiBoZWlnaHQ9IjIwMCIgdmlld0JveD0iMCAwIDIwMCAyMDAiIGZpbGw9Im5vbmUiIHhtbG5zPSJodHRwOi8vd3d3LnczLm9yZy8yMDAwL3N2ZyI+CjxyZWN0IHdpZHRoPSIyMDAiIGhlaWdodD0iMjAwIiBmaWxsPSIjRjhGOUZBIi8+CjxwYXRoIGQ9Ik0xMjAgODBDMTIwIDg4LjgzNjYgMTEyLjgzNyA5NiAxMDQgOTZDOTUuMTYzNCA5NiA4OCA4OC44MzY2IDg4IDgwQzg4IDcxLjE2MzQgOTUuMTYzNCA2NCAxMDQgNjRDMTEyLjgzNyA2NCAxMjAgNzEuMTYzNCAxMjAgODBaIiBmaWxsPSIjOEM5MEE2Ii8+CjxwYXRoIGQ9Ik0xNDIgMTM4SDU4QzUyLjQ3NzIgMTM4IDQ4IDEzMy41MjMgNDggMTI4VjcyQzQ4IDY2LjQ3NzIgNTIuNDc3MiA2MiA1OCA2MkgxNDJDMTQ3LjUyMyA2MiAxNTIgNjYuNDc3MiAxNTIgNzJWMTI4QzE1MiAxMzMuNTIzIDE0Ny41MjMgMTM4IDE0MiAxMzhaIiBzdHJva2U9IiM4QzkwQTYiIHN0cm9rZS13aWR0aD0iNCIvPgo8L3N2Zz4K'">
                        <div class="mt-2">
                            <a href="{{ asset('public/uploads/products/' . $product->promotion_image) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                <i class="fas fa-eye me-1"></i>View Promotion Image
                            </a>
                        </div>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Videos -->
    <div class="col-lg-4">
        <div class="card product-details-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-video me-2"></i>{{ __('Product Videos') }}
                </h5>
            </div>
            <div class="card-body">
                @if($product->video_path)
                <div class="image-gallery mb-3">
                    <i class="fas fa-video fa-3x text-primary mb-2"></i>
                    <h6>Uploaded Video</h6>
                    <div class="mt-2">
                        <a href="{{ asset('public/uploads/products/' . $product->video_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                            <i class="fas fa-play me-1"></i>Play Video
                        </a>
                    </div>
                </div>
                @endif

                @if($product->youtube_url)
                <div class="image-gallery">
                    <i class="fab fa-youtube fa-3x text-danger mb-2"></i>
                    <h6>YouTube Video</h6>
                    <div class="mt-2">
                        <a href="{{ $product->youtube_url }}" target="_blank" class="btn btn-sm btn-outline-danger">
                            <i class="fab fa-youtube me-1"></i>Watch on YouTube
                        </a>
                    </div>
                </div>
                @endif

                @if(!$product->video_path && !$product->youtube_url)
                <div class="text-center text-muted py-3">
                    <i class="fas fa-video fa-2x mb-2"></i>
                    <p>No videos available</p>
                </div>
                @endif
            </div>
        </div>

        <!-- Product Statistics -->
        <div class="card product-details-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-chart-bar me-2"></i>{{ __('Quick Stats') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Total Images:</span>
                    <span class="detail-value">{{ $product->images ? $product->images->count() : 0 }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Variants:</span>
                    <span class="detail-value">{{ $product->variants ? $product->variants->count() : 0 }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Order Items:</span>
                    <span class="detail-value">{{ $product->orderItems ? $product->orderItems->count() : 0 }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Reviews:</span>
                    <span class="detail-value">{{ $product->reviews ? $product->reviews->count() : 0 }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Questions:</span>
                    <span class="detail-value">{{ $product->questions ? $product->questions->count() : 0 }}</span>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Action Buttons Footer -->
<div class="row mt-4">
    <div class="col-12">
        <div class="card">
            <div class="card-body text-center">
                <div class="btn-group" role="group">
                    <a href="{{ url('admin/product') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>{{ __('Back to List') }}
                    </a>
                    
                    <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger delete-btn">
                            <i class="fas fa-trash me-1"></i>{{ __('Delete Product') }}
                        </button>
                    </form>
                    <a href="#" class="btn btn-info" onclick="window.print()">
                        <i class="fas fa-print me-1"></i>{{ __('Print Details') }}
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@section('script')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
$(document).ready(function() {
    // Delete confirmation
    $('.delete-btn').on('click', function(e) {
        e.preventDefault();
        const form = $(this).closest('form');
        const productName = "{{ $product->name }}";
        
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete product: <strong>"${productName}"</strong><br>This action cannot be undone!`,
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'Cancel'
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});
</script>
@endsection