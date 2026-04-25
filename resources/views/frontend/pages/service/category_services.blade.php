@extends('frontend.layouts.master')
@section('title', $category->name . ' - Services')
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

    {{-- Breadcrumb --}}
    <section id="quicktech-breadcrumb">
        <div class="container">
            <div class="row my-3">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ url('/') }}">Home</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('service.index') }}">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page">{{ $category->name }}</li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    
    <section id="quicktech-servicemain">
        <div class="container">
            {{-- Category Header --}}
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="quicktech-head">
                        <h4>{{ $category->name }} Services</h4>
                        <p class="text-muted">{{ $services->total() }} services found</p>
                    </div>
                </div>
            </div>

            <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
                @foreach($services as $service)
                <div class="col-lg-3 col-6 col-sm-6 mb-4">
                    <a href="{{ route('service.details', $service->id) }}">
                        <div class="quicktech-product">
                            <div class="quikctech-img-product text-center">
                                {{-- Handle service media --}}
                                @if($service->service_video && file_exists(public_path($service->service_video)))
                                    <video src="{{ asset($service->service_video) }}" class="w-100" style="height: 200px; object-fit: cover;"></video>
                                @elseif($service->attachment && file_exists(public_path($service->attachment)))
                                    <img src="{{ asset($service->attachment) }}" alt="{{ $service->title }}" class="w-100" style="height: 200px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fa-solid fa-concierge-bell text-muted" style="font-size: 2rem;"></i>
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

                {{-- Fallback if no services --}}
                @if($services->count() == 0)
                <div class="col-12 text-center py-5">
                    <div class="mb-3">
                        <i class="fa-solid fa-concierge-bell fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No services found in {{ $category->name }}</h4>
                    <p class="text-muted">Check other categories or come back later.</p>
                    <a href="{{ route('service.index') }}" class="btn btn-primary">Browse All Services</a>
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
@endsection