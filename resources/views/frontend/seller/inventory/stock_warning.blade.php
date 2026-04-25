@extends('frontend.seller.seller_master')
@section('title', 'Warning Stock Manage')
@section('content')

@include('frontend.include.seller-menu-top')

<div class="quikctech-manage-p-inner">
    <div class="quicktech-manage-head">
        <h4>Warning Stock Manage</h4>
    </div>
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

<form action="{{ route('vendor.inventory.warning-limit.update') }}" method="post">
    @csrf
    <div class="form-group mb-3">
        <label class="form-label" for="product_warning_limit">Product Warning Limit</label>
        <input type="number" class="form-control" name="product_warning_limit" id="product_warning_limit"
        value="{{ old('product_warning_limit', $real_vendor->product_warning_limit) }}">
    </div>
    <button type="submit" class="btn btn-primary mb-3">Save</button>
</form>

<div class="table-responsive">
    <table class="table quicktech-manage-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Product</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($products as $key => $product)
                <tr>
                    <td>{{ $key + 1 }}</td>

                    <td>
                        @if($product->primaryImage())
                            <img
                                src="{{ asset($product->primaryImage()->image_path) }}"
                                width="50"
                                height="50"
                                class="img-thumbnail"
                            >
                        @else
                            <img
                                src="{{ asset('frontend/images/no-image.png') }}"
                                width="50"
                                height="50"
                            >
                        @endif
                    </td>

                    <td>{{ $product->name }}</td>
                    <td>{{ $product->category->name ?? '-' }}</td>
                    <td>{{ $product->brand->name ?? '-' }}</td>

                    <td>
                        <span class="badge bg-{{ $product->total_stock > 0 ? 'success' : 'danger' }}">
                            {{ $product->total_stock }}
                        </span>
                    </td>

                    <td>
                        <span style="color: {{ $product->status_color }}">
                            {{ $product->status_text }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center">No products found</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
