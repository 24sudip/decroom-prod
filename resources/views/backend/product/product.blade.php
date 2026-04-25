@extends('backend.layouts.master-layouts')

@section('title', __('Product List'))

@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
<style>
    .status-dropdown {
        min-width: 140px;
        margin-bottom: 5px;
        display: block;
    }
    .action-buttons {
        min-width: 200px;
    }
    .product-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        background-color: #f8f9fa;
    }
    .no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        color: #6c757d;
        font-size: 12px;
        border: 1px solid #dee2e6;
    }
    .status-updating {
        opacity: 0.6;
        pointer-events: none;
    }
    .filter-card .card-body {
        padding: 1.5rem;
    }
    .filter-actions {
        display: flex;
        flex-direction: column;
        gap: 0.5rem;
    }
    @media (max-width: 768px) {
        .filter-actions {
            flex-direction: row;
        }
        .filter-actions .btn {
            flex: 1;
        }
    }
</style>
@endsection

@section('content')
@php
    // Use request() helper instead of $request variable
    $currentRequest = request();
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ __('Product List') }}</h4>
            <div class="page-title-right">
                <a href="{{ route('product.create') }}" class="btn btn-success">
                    <i class="fas fa-plus me-1"></i>{{ __('Add New Product') }}
                </a>
            </div>
        </div>
    </div>
</div>

{{-- Debug Info --}}
@if(env('APP_DEBUG'))
<div class="alert alert-info mb-3" id="debugInfo">
    <div class="row">
        <div class="col-md-3">
            <strong>Total Products:</strong> <span id="productCount">{{ $products->total() }}</span>
        </div>
        <div class="col-md-3">
            <strong>Categories:</strong> {{ $categories->count() }}
        </div>
        <div class="col-md-3">
            <strong>Brands:</strong> {{ $brands->count() }}
        </div>
        <div class="col-md-3">
            <strong>Vendors:</strong> {{ $vendors->count() }}
        </div>
    </div>
    @if($currentRequest->anyFilled(['category_id', 'brand_id', 'vendor_id', 'search']))
    <div class="mt-2">
        <strong>Active Filters:</strong>
        @foreach($currentRequest->all() as $key => $value)
            @if(in_array($key, ['category_id', 'brand_id', 'vendor_id', 'search']) && !empty($value))
                <span class="badge bg-primary me-1">{{ $key }}: {{ $value }}</span>
            @endif
        @endforeach
    </div>
    @endif
</div>
@endif

{{-- ✅ Improved Filter Form --}}
<div class="card mb-4 filter-card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i>{{ __('Filter Products') }}
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ url('admin/product') }}" id="filterForm">
            <div class="row g-3">
                {{-- Category Filter --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ __('Category') }}</label>
                    <select name="category_id" class="form-control select2" data-placeholder="{{ __('Select Category') }}">
                        <option value="">{{ __('All Categories') }}</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}" 
                                {{ (string)$currentRequest->category_id === (string)$category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Brand Filter --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">{{ __('Brand') }}</label>
                    <select name="brand_id" class="form-control select2" data-placeholder="{{ __('Select Brand') }}">
                        <option value="">{{ __('All Brands') }}</option>
                        @foreach ($brands as $brand)
                            <option value="{{ $brand->id }}" 
                                {{ (string)$currentRequest->brand_id === (string)$brand->id ? 'selected' : '' }}>
                                {{ $brand->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                
                {{-- Vendor Filter --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">{{ __('Vendor') }}</label>
                    <select name="vendor_id" class="form-control select2" data-placeholder="{{ __('Select Vendor') }}">
                        <option value="">{{ __('All Vendors') }}</option>
                        @foreach ($vendors as $vendor)
                            <option value="{{ $vendor->id }}" 
                                {{ (string)$currentRequest->vendor_id === (string)$vendor->id ? 'selected' : '' }}>
                                {{ $vendor->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ __('Search Products') }}</label>
                    <div class="input-group">
                        <input type="text" name="search" value="{{ $currentRequest->search }}" class="form-control"
                               placeholder="{{ __('Search by product name...') }}"
                               aria-label="Search products">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-1">
                    <div class="filter-actions mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-search me-1"></i>{{ __('Apply Filters') }}
                        </button>
                    </div>
                </div>
                <div class="col-md-1">
                    <div class="filter-actions mt-4">
                        <a href="{{ url('admin/product') }}" class="btn btn-outline-secondary">
                            <i class="fas fa-refresh me-1"></i>{{ __('Reset All') }}
                        </a>

                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Results Summary --}}
@if($currentRequest->anyFilled(['category_id', 'brand_id', 'vendor_id', 'search']))
<div class="alert alert-light mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <strong>Filtered Results:</strong> 
            Showing {{ $products->firstItem() ?? 0 }} - {{ $products->lastItem() ?? 0 }} of {{ $products->total() }} products
        </div>
        <div>
            <a href="{{ url('admin/product') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-times me-1"></i>Clear Filters
            </a>
        </div>
    </div>
</div>
@endif

{{-- ✅ Product Table --}}
<div class="card">
    <div class="card-body">
        @if($products->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th width="80">{{ __('Image') }}</th>
                        <th>{{ __('Vendor') }}</th>
                        <th>{{ __('Product Name') }}</th>
                        <th>{{ __('Category') }}</th>
                        <th>{{ __('Brand') }}</th>
                        <th width="120">{{ __('Price') }}</th>
                        <th width="100">{{ __('Stock') }}</th>
                        <th width="200">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $key => $product)
                        <tr>
                            <td class="text-center">{{ $products->firstItem() + $key }}</td>
                            <td class="text-center">
                               
                                @if (!empty($product->promotion_image))
                                    <img src="{{ asset($product->promotion_image) }}" 
                                         class="product-image" alt="Product Image" >
                                @else
                                    <div class="product-image no-image" title="No Image">
                                        <i class="fas fa-image"></i>
                                    </div>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $product->vendor->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($product->name, 30) }}</div>
                                <small class="text-muted">ID: {{ $product->id }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $product->category->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $product->brand->name ?? 'N/A' }}</span>
                            </td>
                            <td class="fw-bold text-success">
                                ৳{{ number_format($product->price, 2) }}
                            </td>
                            <td>
                                <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }}">
                                    {{ $product->stock ?? 0 }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                <div class="d-flex flex-column gap-1">
                                    <!-- View Button -->
                                    <a href="{{ route('admin.product.view', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>
                                    
                                    <!-- Edit Button -->
                                    <a href="{{ route('product.edit', $product->id) }}" 
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-edit me-1"></i>Edit
                                    </a>
                                
                                    <!-- Delete Button -->
                                    <form action="{{ route('product.destroy', $product->id) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 delete-btn">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                
                                    <!-- QC Status Dropdown -->
                                    <select class="form-select form-select-sm qc-status status-dropdown" 
                                            data-id="{{ $product->id }}"
                                            data-current="{{ $product->qc_status }}">
                                        <option value="0" {{ $product->qc_status == 0 ? 'selected' : '' }}>⏳ Pending QC</option>
                                        <option value="1" {{ $product->qc_status == 1 ? 'selected' : '' }}>✅ Approved</option>
                                    </select>
                                
                                    <!-- Reject Status Dropdown -->
                                    <select class="form-select form-select-sm reject-status status-dropdown" 
                                            data-id="{{ $product->id }}"
                                            data-current="{{ $product->reject_status }}">
                                        <option value="0" {{ $product->reject_status == 0 ? 'selected' : '' }}>✅ Active</option>
                                        <option value="1" {{ $product->reject_status == 1 ? 'selected' : '' }}>❌ Rejected</option>
                                    </select>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ✅ Pagination --}}
        @if($products->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $products->firstItem() }} to {{ $products->lastItem() }} of {{ $products->total() }} entries
            </div>
            <div>
                {{ $products->appends($currentRequest->all())->links() }}
            </div>
        </div>
        @endif

        @else
        {{-- No Products Found --}}
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-box-open fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">{{ __('No products found') }}</h4>
            <p class="text-muted mb-4">
                @if($currentRequest->anyFilled(['category_id', 'brand_id', 'vendor_id', 'search']))
                    {{ __('Try adjusting your search filters or') }}
                @else
                    {{ __('Get started by adding your first product or') }}
                @endif
            </p>
            <div class="d-flex justify-content-center gap-2">
                @if($currentRequest->anyFilled(['category_id', 'brand_id', 'vendor_id', 'search']))
                    <a href="{{ url('admin/product') }}" class="btn btn-outline-primary">
                        <i class="fas fa-refresh me-1"></i>{{ __('Clear Filters') }}
                    </a>
                @endif
                <a href="{{ route('product.create') }}" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i>{{ __('Add New Product') }}
                </a>
            </div>
        </div>
        @endif
    </div>
</div>
@endsection

@section('script')
<!-- Load existing app.min.js FIRST to avoid MetisMenu conflict -->
<script src="{{ URL::asset('build/js/app.min.js') }}"></script>

<!-- Then load jQuery and other libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    console.log('🚀 Product List Initialized');
    
    try {
        // Initialize Select2 with better configuration
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap-5',
                dropdownParent: $('.filter-card .card-body'),
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });
            console.log('✅ Select2 initialized');
        }

        // Clear search input
        $('#clearSearch').on('click', function() {
            $('input[name="search"]').val('').focus();
        });

        // Auto-submit form on select change (optional)
        $('select[name="category_id"], select[name="brand_id"], select[name="vendor_id"]').on('change', function() {
            // Uncomment below line for auto-submit on filter change
            // $('#filterForm').submit();
        });

        // Enter key in search input submits form
        $('input[name="search"]').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                $('#filterForm').submit();
            }
        });

        // Delete confirmation
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const productName = form.closest('tr').find('.fw-semibold').text().trim();
            
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

        // QC Status change
        $(document).on('change', '.qc-status', function() {
            const productId = $(this).data('id');
            const status = $(this).val();
            const previousValue = $(this).data('current');
            const dropdown = $(this);

            console.log(`QC Status Change: Product ${productId}, Status: ${status}`);

            // Show updating state
            dropdown.addClass('status-updating');

            $.ajax({
                url: "{{ route('admin.product.updateStatus') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: productId,
                    type: 'qc',
                    value: status
                },
                success: function(response) {
                    console.log('QC Status Update SUCCESS:', response);
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'QC Status Updated',
                            text: response.message || 'Product QC status updated successfully!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Update current value
                        dropdown.data('current', status);
                    } else {
                        throw new Error(response.message || 'Update failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('QC Status Update ERROR:', error);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Failed to update QC status. Please try again.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    
                    // Revert dropdown to previous value
                    dropdown.val(previousValue);
                },
                complete: function() {
                    dropdown.removeClass('status-updating');
                }
            });
        });

        // Reject Status change
        $(document).on('change', '.reject-status', function() {
            const productId = $(this).data('id');
            const status = $(this).val();
            const previousValue = $(this).data('current');
            const dropdown = $(this);

            console.log(`Reject Status Change: Product ${productId}, Status: ${status}`);

            // Show updating state
            dropdown.addClass('status-updating');

            $.ajax({
                url: "{{ route('admin.product.updateStatus') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: productId,
                    type: 'reject',
                    value: status
                },
                success: function(response) {
                    console.log('Reject Status Update SUCCESS:', response);
                    
                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: response.message || 'Product reject status updated successfully!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Update current value
                        dropdown.data('current', status);
                    } else {
                        throw new Error(response.message || 'Update failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Reject Status Update ERROR:', error);
                    
                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Failed to update reject status. Please try again.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });
                    
                    // Revert dropdown to previous value
                    dropdown.val(previousValue);
                },
                complete: function() {
                    dropdown.removeClass('status-updating');
                }
            });
        });

        console.log('✅ All product list features initialized');

    } catch (error) {
        console.error('❌ Product list initialization error:', error);
    }
});
</script>
@endsection