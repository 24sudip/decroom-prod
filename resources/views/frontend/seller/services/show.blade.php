@extends('frontend.seller.seller_master')
@section('title', 'View Service - ' . $service->title)
@section('content')

<div class="row">
    <div class="col-lg-12">
        <div class="quicktech-seller-menu-top">
            <ul>
                <li><a href="#"><img src="{{ asset('frontend') }}/images/store 1.png" alt="Store"></a></li>
                <li><a href="#"><img src="{{ asset('frontend') }}/images/settings (2).png" alt="Settings"></a></li>
                <li><a href="#"><img src="{{ asset('frontend') }}/images/volunteering.png" alt="Volunteer"></a></li>
                <li><a href="#"><img src="{{ asset('frontend') }}/images/bell.png" alt="Notifications"></a></li>
            </ul>
        </div>

        <div class="quicktech-manage-menu">
            <ul>
                <li><a href="{{ route('services.create') }}">Create New</a></li>
                <li><a href="{{ route('services.index', ['status' => 'in progress']) }}">In progress</a></li>
                <li><a href="{{ route('services.index', ['status' => 'response']) }}">Response</a></li>
                <li><a href="{{ route('services.index', ['status' => 'on hold']) }}">On Hold</a></li>
                <li><a href="{{ route('services.index', ['status' => 'cancelled']) }}">Cancelled</a></li>
                <li><a href="{{ route('services.index', ['status' => 'complete']) }}">Complete</a></li>
                <li><a href="{{ route('services.index', ['status' => 'draft']) }}">Draft</a></li>
                <li><a href="{{ route('services.index', ['status' => 'record']) }}">Records</a></li>
            </ul>
        </div>
    </div>
</div>

{{-- Flash Messages --}}
@if (session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-circle-check me-2"></i>
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="quikctech-form-wrapper border p-4 rounded-3 shadow-sm">
            <!-- Service Header -->
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h4 class="mb-1">{{ $service->title }}</h4>
                    <p class="text-muted mb-0">Service ID: #{{ $service->id }}</p>
                </div>
                <div class="text-end">
                    <span class="badge 
                        @if($service->status == 'in progress') bg-primary
                        @elseif($service->status == 'on hold') bg-warning
                        @elseif($service->status == 'response') bg-info
                        @elseif($service->status == 'cancelled') bg-danger
                        @elseif($service->status == 'complete') bg-success
                        @elseif($service->status == 'draft') bg-secondary
                        @elseif($service->status == 'record') bg-dark
                        @endif fs-6">
                        {{ ucfirst($service->status) }}
                    </span>
                    <div class="mt-2">
                        <small class="text-muted">Created: {{ $service->created_at->format('M d, Y h:i A') }}</small>
                    </div>
                </div>
            </div>

            <div class="row g-4">
                <!-- Basic Information -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fa-solid fa-info-circle me-2"></i>Basic Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Service Title</label>
                                    <p class="form-control-plaintext border-bottom pb-2">{{ $service->title }}</p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Service Category</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        {{ $service->category->name ?? 'N/A' }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Organization</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        {{ $service->organization ?? 'Not specified' }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Client User ID</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        {{ $service->client_id ?? 'Not assigned' }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Provider User ID</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        {{ $service->vendor->name ?? $service->vendor->username ?? $service->vendor->email ?? 'N/A' }}
                                        <small class="text-muted">(ID: {{ $service->vendor_id }})</small>
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Timeline & Delivery -->
                <div class="col-lg-6">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fa-solid fa-calendar me-2"></i>Timeline & Delivery</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Delivery Duration</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        {{ $service->delivery_duration ?? 'Not specified' }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Time Line</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        {{ $service->time_line ?? 'Not specified' }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Created Date</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        {{ $service->created_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Last Updated</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        {{ $service->updated_at->format('M d, Y h:i A') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Cost Breakdown -->
                <div class="col-lg-8">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fa-solid fa-money-bill-wave me-2"></i>Cost Breakdown</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-3">
                                    <label class="form-label quikctech-label">Total Cost</label>
                                    <p class="form-control-plaintext border-bottom pb-2 fw-bold text-primary">
                                        ৳{{ number_format($service->total_cost, 2) }}
                                    </p>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label quikctech-label">Material Cost</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        ৳{{ number_format($service->material_cost, 2) }}
                                    </p>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label quikctech-label">Service Fee</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        ৳{{ number_format($service->service_charge, 2) }}
                                    </p>
                                </div>
                                
                                <div class="col-md-3">
                                    <label class="form-label quikctech-label">Discount</label>
                                    <p class="form-control-plaintext border-bottom pb-2 text-danger">
                                        ৳{{ number_format($service->discount, 2) }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Installment Plan</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        @if($service->installment == 0)
                                            No Installment
                                        @elseif($service->installment == 1)
                                            3 Installments
                                        @elseif($service->installment == 2)
                                            6 Installments
                                        @elseif($service->installment == 3)
                                            12 Installments
                                        @else
                                            Not specified
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Payment Schedule -->
                <div class="col-lg-4">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fa-solid fa-credit-card me-2"></i>Payment Schedule</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Advance Payment</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        ৳{{ number_format($service->advance, 2) }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Mid Payment</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        ৳{{ number_format($service->mid, 2) }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Final Payment</label>
                                    <p class="form-control-plaintext border-bottom pb-2">
                                        ৳{{ number_format($service->final, 2) }}
                                    </p>
                                </div>
                                
                                @php
                                    $paidAmount = $service->advance + $service->mid + $service->final;
                                    $remainingAmount = max(0, $service->total_cost - $paidAmount);
                                @endphp
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Total Paid</label>
                                    <p class="form-control-plaintext border-bottom pb-2 text-success">
                                        ৳{{ number_format($paidAmount, 2) }}
                                    </p>
                                </div>
                                
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Remaining Amount</label>
                                    <p class="form-control-plaintext border-bottom pb-2 text-warning">
                                        ৳{{ number_format($remainingAmount, 2) }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Files & Notes -->
                <div class="col-12">
                    <div class="card border-0 shadow-sm">
                        <div class="card-header bg-light">
                            <h6 class="mb-0"><i class="fa-solid fa-file me-2"></i>Files & Additional Information</h6>
                        </div>
                        <div class="card-body">
                            <div class="row g-4">
                                <!-- Catalog -->
                                <div class="col-md-4">
                                    <label class="form-label quikctech-label">Catalog</label>
                                    <div class="quikctech-upload-area text-center">
                                        @if($service->catalog)
                                            <div class="py-3">
                                                <i class="fa-solid fa-file-alt fa-2x text-primary mb-2"></i>
                                                <p class="mb-2">Catalog File Available</p>
                                                <a href="{{ asset($service->catalog) }}" target="_blank" class="btn btn-primary btn-sm">
                                                    View Catalog
                                                </a>
                                            </div>
                                        @else
                                            <div class="py-3">
                                                <i class="fa-solid fa-file-arrow-up fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No Catalog Uploaded</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Attachment -->
                                <div class="col-md-4">
                                    <label class="form-label quikctech-label">Attachment</label>
                                    <div class="quikctech-upload-area text-center">
                                        @if($service->attachment)
                                            <div class="py-3">
                                                <i class="fa-solid fa-file-alt fa-2x text-primary mb-2"></i>
                                                <p class="mb-2">Attachment File Available</p>
                                                <a href="{{ asset($service->attachment) }}" target="_blank" class="btn btn-primary btn-sm">
                                                    View Attachment
                                                </a>
                                            </div>
                                        @else
                                            <div class="py-3">
                                                <i class="fa-solid fa-file-arrow-up fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No Attachment Uploaded</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="col-md-4">
                                    <label class="form-label quikctech-label">Service Video</label>
                                    <div class="quikctech-upload-area text-center">
                                        @if($service->service_video)
                                            <div class="py-3">
                                                <i class="fa-solid fa-file-alt fa-2x text-primary mb-2"></i>
                                                <p class="mb-2">Video File Available</p>
                                                <a href="{{ asset($service->service_video) }}" target="_blank" class="btn btn-primary btn-sm">
                                                    View Video 
                                                </a>
                                            </div>
                                        @else
                                            <div class="py-3">
                                                <i class="fa-solid fa-file-arrow-up fa-2x text-muted mb-2"></i>
                                                <p class="text-muted mb-0">No Video Uploaded</p>
                                            </div>
                                        @endif
                                    </div>
                                </div>

                                <!-- Note -->
                                <div class="col-12">
                                    <label class="form-label quikctech-label">Note</label>
                                    <div class="border rounded p-3 bg-light">
                                        @if($service->note)
                                            <p class="mb-0">{{ $service->note }}</p>
                                        @else
                                            <p class="text-muted mb-0">No additional notes provided.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex justify-content-between align-items-center mt-4 pt-4 border-top">
                <div>
                    <a href="{{ route('services.index') }}" class="btn btn-secondary quikctech-btn me-2">
                        <i class="fa-solid fa-arrow-left me-1"></i> Back to List
                    </a>
                </div>
                <div>
                    <a href="{{ route('services.edit', $service->id) }}" class="btn btn-primary quikctech-btn me-2">
                        <i class="fa-solid fa-edit me-1"></i> Edit Service
                    </a>
                    <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger quikctech-btn" 
                                onclick="return confirm('Are you sure you want to delete this service? This action cannot be undone.')">
                            <i class="fa-solid fa-trash me-1"></i> Delete Service
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('styles')
<style>
.quikctech-form-wrapper {
    background: #fff;
}

.quikctech-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.form-control-plaintext {
    min-height: 20px;
    padding: 0;
    margin: 0;
}

.quikctech-upload-area {
    border: 2px dashed #ddd;
    border-radius: 6px;
    padding: 20px;
    background: #f8f9fa;
    transition: all 0.3s ease;
    min-height: 120px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.quikctech-btn {
    padding: 10px 24px;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
}

.quikctech-btn:hover {
    transform: translateY(-1px);
}

.quicktech-manage-menu {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.quicktech-manage-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.quicktech-manage-menu li a {
    text-decoration: none;
    color: #333;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.quicktech-manage-menu li a:hover {
    background: #007bff;
    color: white;
}

.managemenu-active {
    background: #007bff !important;
    color: white !important;
}

.quicktech-seller-menu-top {
    background: #fff;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.quicktech-seller-menu-top ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px;
    justify-content: flex-end;
}

.quicktech-seller-menu-top li a {
    display: block;
    transition: transform 0.3s ease;
}

.quicktech-seller-menu-top li a:hover {
    transform: scale(1.1);
}

.card {
    border-radius: 8px;
}

.card-header {
    border-radius: 8px 8px 0 0 !important;
    font-weight: 600;
}

.border-bottom {
    border-bottom: 1px solid #e9ecef !important;
}

/* Status badge styles */
.badge {
    font-size: 0.75em;
    padding: 0.5em 0.75em;
}
</style>
@endpush