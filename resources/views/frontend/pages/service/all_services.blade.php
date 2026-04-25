@extends('frontend.layouts.master')
@section('title', 'All Services')
@section('content')
    <section id="quikctech-service-menu">
        <div class="container">
            <div class="row my-3 quicktech-border">
                <div class="col-lg-12">
                    <div class="quikctech-ser-menu">
                        <ul>
                            <li><a href="{{ route('vendorproduct.index') }}">Product</a></li>
                            <li><a class="ser-active" href="{{ route('service.index') }}">Services</a></li>
                            <li><a href="{{ route('vendor.shop.list') }}">View Shop</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>
    
    <section id="quicktech-servicemain">
        <div class="container">
            <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
                @foreach($services as $service)
                <div class="col-lg-3 col-6 col-sm-6 mb-4">
                    <a href="{{ route('service.details', $service->id) }}">
                        <div class="quicktech-product">
                            <div class="quikctech-img-product text-center">
                                {{-- Handle service media: video first, then attachment --}}
                                @if($service->service_video)
                                    <video src="{{ asset('public/' . $service->service_video) }}" class="w-100" controls></video>
                                @elseif($service->attachment)
                                    <img src="{{ asset('public/' . $service->attachment) }}" alt="{{ $service->title }}" class="w-100" style="height: 200px; object-fit: cover;">
                                @else
                                    {{-- Fallback to default video or image --}}
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fa-solid fa-video text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                @endif
                            </div>
                            <div class="quicktech-product-text">
                                <h6>{{ Str::limit($service->title, 50) }}
                                    <br>
                                    <span style="font-size: 13px; font-weight: 700;">
                                        <i class="fa-solid fa-shop"></i> 
                                        {{ $service->vendor->shop_name ?? $service->vendor->name ?? 'Vendor' }}
                                    </span>
                                </h6>
                                <p>
                                    <img src="{{ asset('frontend/images/taka 1.png') }}" alt="Price"> 
                                    @if($service->total_cost)
                                        {{ number_format($service->total_cost) }} Tk
                                    @else
                                        Negotiable
                                    @endif
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                @endforeach

                {{-- Fallback if no services available --}}
                @if($services->count() == 0)
                <div class="col-12 text-center py-5">
                    <div class="mb-3">
                        <i class="fa-solid fa-concierge-bell fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No services available</h4>
                    <p class="text-muted">Check back later for new services.</p>
                </div>
                @endif
            </div>

            {{-- Pagination --}}
            @if($services->hasPages())
            <div class="row">
                <div class="col-12">
                    <div class="quikctech-pagination d-flex justify-content-center">
                        {{ $services->links('pagination::bootstrap-4') }}
                    </div>
                </div>
            </div>
            @endif
        </div>
    </section>
    @include('frontend.include.service_center')
@endsection

@push('styles')
<style>
.quicktech-product {
    transition: transform 0.3s ease, box-shadow 0.3s ease;
    border: 1px solid #e0e0e0;
    border-radius: 8px;
    overflow: hidden;
    background: white;
}

.quicktech-product:hover {
    transform: translateY(-5px);
    box-shadow: 0 5px 15px rgba(0,0,0,0.1);
}

.quikctech-img-product video,
.quikctech-img-product img {
    height: 200px;
    object-fit: cover;
    width: 100%;
}

.quicktech-product-text {
    padding: 15px;
}

.quicktech-product-text h6 {
    font-size: 14px;
    font-weight: 600;
    margin-bottom: 10px;
    color: #333;
    min-height: 60px;
    display: -webkit-box;
    -webkit-line-clamp: 3;
    -webkit-box-orient: vertical;
    overflow: hidden;
}

.quicktech-product-text p {
    margin: 0;
    font-weight: 600;
    color: #e74c3c;
    display: flex;
    align-items: center;
    gap: 5px;
}

.quikctech-pagination .pagination {
    margin: 20px 0 0 0;
}

.quikctech-pagination .page-link {
    color: #333;
    border: 1px solid #ddd;
    margin: 0 2px;
    border-radius: 4px;
}

.quikctech-pagination .page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
    color: white;
}

.quikctech-pagination .page-link:hover {
    background-color: #f8f9fa;
    border-color: #ddd;
}
</style>
@endpush