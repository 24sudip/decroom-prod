@extends('frontend.seller.seller_master')
@section('title', 'Product Manage')
@section('content')

@include('frontend.include.seller-menu-top')

<div class="quikctech-manage-p-inner">
    <div class="quicktech-manage-head">
        <h4>Manage Product</h4>
        <a href="{{ route('vendor.products.create') }}">+ New Product</a>
    </div>
</div>

<div class="quicktech-manage-menu">
    <ul>
        <li><a href="{{ route('vendor.products.manage') }}?status=all" class="{{ $status == 'all' ? 'managemenu-active' : '' }}">All ({{ $counts['all'] }})</a></li>
        <li><a href="{{ route('vendor.products.manage') }}?status=active" class="{{ $status == 'active' ? 'managemenu-active' : '' }}">Active ({{ $counts['active'] }})</a></li>
        <li><a href="{{ route('vendor.products.manage') }}?status=inactive" class="{{ $status == 'inactive' ? 'managemenu-active' : '' }}">Inactive ({{ $counts['inactive'] }})</a></li>
        <li><a href="{{ route('vendor.products.manage') }}?status=pending" class="{{ $status == 'qc_status' ? 'managemenu-active' : '' }}">Pending QC ({{ $counts['pending'] }})</a></li>
        <li><a href="{{ route('vendor.products.manage') }}?status=rejected" class="{{ $status == 'rejected' ? 'managemenu-active' : '' }}">Rejected ({{ $counts['rejected'] }})</a></li>
        <li><a href="{{ route('vendor.products.manage') }}?status=deleted" class="{{ $status == 'deleted' ? 'managemenu-active' : '' }}">Deleted ({{ $counts['deleted'] }})</a></li>
    </ul>
</div>

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
<style>
    .quicktech-manage-table {
    overflow: visible;
}
</style>
<div class="table-responsive">
    <table class="table quicktech-manage-table">
        <thead>
            <tr>
                <th style="width:40px;">
                    <input type="checkbox" class="form-check-input quicktech-manage-checkbox" id="selectAll">
                </th>
                <th>Product Info</th>
                <th>Status Info</th>
                <th>Current Status</th>
                <th class="text-end">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $product)
            <tr>
                <td>
                    <input type="checkbox" class="form-check-input quicktech-manage-checkbox product-checkbox" value="{{ $product->id }}">
                </td>

                <td>
                    <div class="quicktech-manage-product">
                        @if($product->primaryImage())
                            <img src="{{ asset('public/' . $product->primaryImage()->image_path) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                        @elseif($product->images && $product->images->count() > 0)
                            <img src="{{ asset('public/' . $product->images->first()->image_path) }}" alt="{{ $product->name }}" style="width: 60px; height: 60px; object-fit: cover;">
                        @else
                            <img src="https://via.placeholder.com/60x60?text=No+Image" alt="No Image" style="width: 60px; height: 60px; object-fit: cover;">
                        @endif
                        <div>
                            <h6>{{ Str::limit($product->name, 50) }}</h6>
                            <small>Seller SKU: {{ $product->sku }}</small>
                            <br>
                            <small>Price: RM{{ number_format($product->price, 2) }}</small>
                            @if($product->special_price)
                                <small style="color: red;"> Special: RM{{ number_format($product->special_price, 2) }}</small>
                            @endif
                        </div>
                    </div>
                </td>
                <td>
                    <div class="quicktech-manage-status">
                        @if($product->trashed())
                            🗑️ This product is in trash.
                        @elseif($product->status == 2)
                            ⏳ Product will automatically activated after passing QC.
                        @elseif($product->status == 3)
                            ❌ Product rejected during QC check.
                        @elseif($product->status == 1 && $product->availability)
                            ✅ Product is active and available for sale.
                        @else
                            🔒 Product is currently inactive.
                        @endif
                        <br>
                        Updated on: <strong>{{ $product->updated_at->format('Y-m-d H:i') }}</strong>
                    </div>
                </td>
                <td>
                    @if($product->trashed())
                        <span style="color: darkred; font-weight: bold;">Deleted</span>
                    @else
                        <span style="color: {{ $product->status_color }}; font-weight: bold;">
                            {{ $product->status_text }}
                        </span>
                    @endif
                </td>
                <td class="text-end">
                    <div class="btn-group">
                        @if($status == 'deleted')
                            <form action="{{ route('vendor.products.restore', $product->id) }}" method="POST" class="d-inline">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="quicktech-manage-edit">Restore</button>
                            </form>
                            <form action="{{ route('vendor.products.force-delete', $product->id) }}" method="POST" class="d-inline ms-2" onsubmit="return confirm('Permanently delete this product? This cannot be undone!')">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="text-danger" style="border: none; background: none; cursor: pointer;">Delete Permanently</button>
                            </form>
                        @else
                            <a href="{{ route('vendor.products.edit', $product->id) }}" class="quicktech-manage-edit btn btn-sm">Edit</a>

                            <div class="dropdown ms-2">
                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                    ⋮
                                </button>
                                <ul class="dropdown-menu">
                                    @if($product->status == 1 && $product->availability)
                                        <li>
                                            <form action="{{ route('vendor.products.update-status', $product->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="0">
                                                <button type="submit" class="dropdown-item">Deactivate</button>
                                            </form>
                                        </li>
                                    @elseif($product->status != 2 && $product->status != 3)
                                        <li>
                                            <form action="{{ route('vendor.products.update-status', $product->id) }}" method="POST">
                                                @csrf
                                                @method('PATCH')
                                                <input type="hidden" name="status" value="1">
                                                <button type="submit" class="dropdown-item">Activate</button>
                                            </form>
                                        </li>
                                    @endif
                                    <li>
                                        <form action="{{ route('vendor.products.destroy', $product->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to move this product to trash?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="dropdown-item text-danger">Move to Trash</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        @endif
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="text-center py-4">
                    <div class="text-muted">
                        <i class="fas fa-box-open fa-3x mb-3"></i>
                        <p>No products found.</p>
                        @if($status == 'deleted')
                            <p class="small">Your trash is empty.</p>
                        @else
                            <a href="{{ route('vendor.products.create') }}" class="btn btn-primary">Add Your First Product</a>
                        @endif
                    </div>
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($products->hasPages())
<div class="d-flex justify-content-center mt-4">
    {{ $products->links() }}
</div>
@endif

@push('scripts')
<script>
    document.getElementById('selectAll').addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.product-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
</script>
@endpush

@endsection
