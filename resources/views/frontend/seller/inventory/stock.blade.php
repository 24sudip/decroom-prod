@extends('frontend.seller.seller_master')
@section('title', 'Stock Manage')
@section('content')

@include('frontend.include.seller-menu-top')

<div class="quikctech-manage-p-inner">
    <div class="quicktech-manage-head">
        <h4>Stock Manage</h4>
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
                <th>Action</th>
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
                <td>
                    <!-- Button trigger modal -->
                    <button type="button" class="btn btn-primary" data-bs-toggle="modal"
                        data-bs-target="#exampleModal{{ $product->id }}">
                        Change Stock
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="exampleModal{{ $product->id }}" tabindex="-1"
                        aria-labelledby="exampleModal{{ $product->id }}Label" aria-hidden="true">
                        <div class="modal-dialog modal-xl">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h1 class="modal-title fs-5" id="exampleModal{{ $product->id }}Label">Edit Stock</h1>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="{{ route('vendor.inventory.stock.update', $product->id) }}" method="post">
                                        @csrf
                @if($product->variants && $product->variants->count() > 0)
                <div id="variants-container">
                    @foreach($product->variants as $index => $variant)
                    <div class="variant-row row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="variants[{{ $index }}][name]"
                                placeholder="Variant Name" value="{{ $variant->name }}">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="variants[{{ $index }}][value]"
                                placeholder="Variant Value" value="{{ $variant->value }}">
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" class="form-control"
                                name="variants[{{ $index }}][additional_price]"
                                placeholder="Additional Price" value="{{ $variant->additional_price }}">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="variants[{{ $index }}][stock]"
                                placeholder="Stock" value="{{ $variant->stock }}">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-variant">Remove</button>
                        </div>
                    </div>
                    @endforeach
                </div>
                <button type="button" class="btn btn-secondary" id="add-variant">Add Variant</button>
                @else
                <div class="mb-3">
                    <label for="stock" class="form-label">Stock</label>
                    <input type="number" class="form-control" id="stock" name="stock" value="{{ $product->total_stock }}" required>
                </div>
                @endif
                <button type="submit" class="btn btn-primary">Save changes</button>
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
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
    @if($products instanceof \Illuminate\Pagination\LengthAwarePaginator)
        <div class="d-flex justify-content-center mt-4">
            {{ $products->links() }}
        </div>
    @endif
<script>
    document.addEventListener('DOMContentLoaded', function() {
        let variantCount = {{ ($product->variants && $product->variants->count() > 0) ? $product->variants->count() : 1 }};

        document.getElementById('add-variant').addEventListener('click', function() {
            const container = document.getElementById('variants-container');
            const newRow = document.createElement('div');
            newRow.className = 'variant-row row mb-3';
            newRow.innerHTML = `
                <div class="col-md-3">
                    <input type="text" class="form-control" name="variants[${variantCount}][name]" placeholder="Variant Name">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="variants[${variantCount}][value]" placeholder="Variant Value">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" class="form-control" name="variants[${variantCount}][additional_price]" placeholder="Additional Price">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="variants[${variantCount}][stock]" placeholder="Stock">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-variant">Remove</button>
                </div>
            `;
            container.appendChild(newRow);
            variantCount++;
        });

        // Remove variant
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variant')) {
                e.target.closest('.variant-row').remove();
            }
        });
    });
</script>
@endsection
