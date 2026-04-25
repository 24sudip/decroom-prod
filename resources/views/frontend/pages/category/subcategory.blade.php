@extends('frontend.layouts.master_for_details')
@section('title', $subcategory->name. 'Product')
@section('content')
    <section id="quicktech-categories-page">
        <div class="container-fluid">
            <div class="row mt-3 mb-3">
                <div class="col-lg-12">
                    
                    <div class="d-flex quikctech-c-headd justify-content-between align-items-center p-3 border rounded bg-white shadow-sm">
                        <h5 class="mb-0 fw-bold">{{$subcategory->name ?? ''}}</h5>
                        <div class="d-flex align-items-center">
                            <span class="text-muted me-2">Sort by:</span>
                            <select class="form-select form-select-sm w-auto" id="sortSelect">
                                <option disabled {{ request('sort') ? '' : 'selected' }}>Select an option</option>
                                <option value="low_high" {{ request('sort') == 'low_high' ? 'selected' : '' }}>Price: Low to High</option>
                                <option value="high_low" {{ request('sort') == 'high_low' ? 'selected' : '' }}>Price: High to Low</option>
                                <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>Newest</option>
                            </select>
                        </div>
                    </div>
                    
                    <script>
                        document.getElementById('sortSelect').addEventListener('change', function() {
                            let sortValue = this.value;
                            let url = new URL(window.location.href);
                            url.searchParams.set('sort', sortValue);
                            window.location.href = url.toString();
                        });
                    </script>
                </div>
            </div>
            <div class="row gapp mb-5">
                @forelse($products as $product)
                    @php
                        $customer = Auth::guard('customer')->user();
                        $isWholesale = $customer && $customer->type == 'wholesale';

                        $defaultVariant = $product->variants->first();

                        if ($defaultVariant) {
                            $price = $isWholesale
                                ? $defaultVariant->varient_wholesale_price ?? $defaultVariant->price
                                : $defaultVariant->price;

                            $oldPrice = $product->old_price;
                        } else {
                            $price = $isWholesale ? $product->wholesale_price : $product->new_price;
                            $oldPrice = $product->old_price;
                        }

                        $discount =
                            $oldPrice && $price && $oldPrice > $price
                                ? round((($oldPrice - $price) / $oldPrice) * 100)
                                : null;
                    @endphp

                    <div class="col-lg-2 col-6 col-sm-4">
                        <div class="quicktech-category-page-product">
                            <div class="quicktech-product-inner">
                                <a href="{{ url('product-details/' . $product->id) }}">
                                    <div class="overlay"></div>

                                    @if ($discount)
                                        <div class="quikctech-offer">
                                            <p>{{ $discount }}% Off</p>
                                        </div>
                                    @endif

                                    <div class="quicktech-product-main">
                                        <div class="quikctech-p-img">
                                            <img class="quikctech-p-im"
                                                src="{{ $product->image ? asset('public/storage/products/' . $product->image) : asset('public/storage/products/default.png') }}"
                                                alt="{{ $product->name }}">
                                        </div>
                                        <div class="quikctech-p-text">
                                            <h5
                                                style="white-space: nowrap; overflow: hidden; text-overflow: ellipsis; display: -webkit-box; -webkit-line-clamp: 1; -webkit-box-orient: vertical;">
                                                {{ $product->name }}
                                            </h5>

                                            <div class="quikctech-p-add">
                                                <h5>
                                                    Tk.{{ number_format($price) }}
                                                    @if ($oldPrice && $oldPrice > $price)
                                                        <s>Tk.{{ number_format($oldPrice) }}</s>
                                                    @endif
                                                </h5>
                                                <a class="quikctech-add-p-btn"
                                                    href="{{ url('cart/add/' . $product->id) }}">Add</a>
                                            </div>
                                        </div>
                                    </div>
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12 text-center">
                        <p>No products available in this category.</p>
                    </div>
                @endforelse
            </div>


        </div>
    </section>
@endsection
