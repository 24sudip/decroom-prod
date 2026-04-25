@extends('backend.layouts.master-layouts')

@section('title', __('Service Details'))

@section('css')
<style>
    .service-details-card {
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
    .cost-box {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        border-radius: 10px;
        padding: 20px;
    }
    .attachment-box {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 15px;
        text-align: center;
        transition: all 0.3s ease;
    }
    .attachment-box:hover {
        border-color: #007bff;
        background-color: #f8f9fa;
    }
    .timeline-item {
        position: relative;
        padding-left: 30px;
        margin-bottom: 20px;
    }
    .timeline-item:before {
        content: '';
        position: absolute;
        left: 0;
        top: 5px;
        width: 12px;
        height: 12px;
        border-radius: 50%;
        background: #007bff;
    }
    .timeline-item:after {
        content: '';
        position: absolute;
        left: 5px;
        top: 17px;
        width: 2px;
        height: calc(100% + 3px);
        background: #e9ecef;
    }
    .timeline-item:last-child:after {
        display: none;
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
    .attachment-card {
        border-left: 4px solid #ffc107;
    }
</style>
@endsection

@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ __('Service Details - ') }}{{ $service->title }}</h4>
            <div class="page-title-right">
                <a href="{{ route('admin.service') }}" class="btn btn-secondary">
                    <i class="fas fa-arrow-left me-1"></i>{{ __('Back to List') }}
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Basic Information -->
    <div class="col-lg-6">
        <div class="card service-details-card info-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-info-circle me-2"></i>{{ __('Basic Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="detail-item d-flex">
                    <span class="detail-label">Service ID:</span>
                    <span class="detail-value fw-bold text-primary">#{{ $service->id }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Title:</span>
                    <span class="detail-value fw-semibold">{{ $service->title }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Category:</span>
                    <span class="detail-value">
                        <span class="badge bg-light text-dark">{{ $service->category->name ?? 'N/A' }}</span>
                    </span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Organization:</span>
                    <span class="detail-value">{{ $service->organization ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Delivery Duration:</span>
                    <span class="detail-value">{{ $service->delivery_duration ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Timeline:</span>
                    <span class="detail-value">{{ $service->time_line ?? 'N/A' }}</span>
                </div>
            </div>
        </div>

        <!-- Status Information -->
        <div class="card service-details-card info-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-tasks me-2"></i>{{ __('Status Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="detail-item d-flex">
                    <span class="detail-label">Service Status:</span>
                    <span class="detail-value">
                        <span class="badge status-badge 
                            @if($service->status == 'complete') bg-success
                            @elseif($service->status == 'cancelled') bg-danger
                            @elseif($service->status == 'on_hold') bg-warning
                            @elseif($service->status == 'in_process') bg-primary
                            @elseif($service->status == 'response') bg-info
                            @elseif($service->status == 'draft') bg-secondary
                            @elseif($service->status == 'records') bg-dark
                            @else bg-light text-dark @endif">
                            {{ ucfirst(str_replace('_', ' ', $service->status)) }}
                        </span>
                    </span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Admin Approval:</span>
                    <span class="detail-value">
                        @if($service->admin_approval)
                            <span class="badge bg-success status-badge">✅ Approved</span>
                        @else
                            <span class="badge bg-warning status-badge">⏳ Pending Approval</span>
                        @endif
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Financial Information -->
    <div class="col-lg-6">
        <div class="card service-details-card financial-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-money-bill-wave me-2"></i>{{ __('Financial Information') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="cost-box mb-3">
                    <h4 class="text-white text-center">৳{{ number_format($service->total_cost, 2) }}</h4>
                    <p class="text-white text-center mb-0">Total Cost</p>
                </div>
                
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Material Cost:</span>
                    <span class="detail-value">৳{{ number_format($service->material_cost, 2) }}</span>
                </div>
                <div class="detail-item d-flex justify-content-between">
                    <span class="detail-label">Service Charge:</span>
                    <span class="detail-value">৳{{ number_format($service->service_charge, 2) }}</span>
                </div>
                @if($service->discount > 0)
                <div class="detail-item d-flex justify-content-between text-danger">
                    <span class="detail-label">Discount:</span>
                    <span class="detail-value">-{{ $service->discount }}%</span>
                </div>
                @endif
            </div>
        </div>

        <!-- Installment Plan -->
        @if($service->installment)
        <div class="card service-details-card financial-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-calendar-alt me-2"></i>{{ __('Installment Plan') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="timeline">
                    @if($service->advance)
                    <div class="timeline-item">
                        <strong>Advance Payment:</strong> 
                        <span class="text-success">৳{{ number_format($service->advance, 2) }}</span>
                    </div>
                    @endif
                    @if($service->mid)
                    <div class="timeline-item">
                        <strong>Mid Payment:</strong> 
                        <span class="text-primary">৳{{ number_format($service->mid, 2) }}</span>
                    </div>
                    @endif
                    @if($service->final)
                    <div class="timeline-item">
                        <strong>Final Payment:</strong> 
                        <span class="text-info">৳{{ number_format($service->final, 2) }}</span>
                    </div>
                    @endif
                </div>
            </div>
        </div>
        @endif
    </div>
</div>

<!-- Client & Vendor Information -->
<div class="row">
    <div class="col-md-6">
        <div class="card service-details-card info-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-user me-2"></i>{{ __('Client Information') }}
                </h5>
            </div>
            <div class="card-body">
                @if($service->client)
                <div class="detail-item d-flex">
                    <span class="detail-label">Client ID:</span>
                    <span class="detail-value">#{{ $service->client->id }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $service->client->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $service->client->email ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $service->client->phone ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Address:</span>
                    <span class="detail-value">{{ $service->client->address ?? 'N/A' }}</span>
                </div>
                @else
                <div class="text-center text-muted py-3">
                    <i class="fas fa-user-slash fa-2x mb-2"></i>
                    <p>No client information available</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card service-details-card info-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-building me-2"></i>{{ __('Vendor Information') }}
                </h5>
            </div>
            <div class="card-body">
                @if($service->vendor)
                <div class="detail-item d-flex">
                    <span class="detail-label">Shop:</span>
                    <span class="detail-value">{{ optional($service->vendor)->vendorDetails->shop_name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Name:</span>
                    <span class="detail-value">{{ $service->vendor->name ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Email:</span>
                    <span class="detail-value">{{ $service->vendor->email ?? 'N/A' }}</span>
                </div>
                <div class="detail-item d-flex">
                    <span class="detail-label">Phone:</span>
                    <span class="detail-value">{{ $service->vendor->phone ?? 'N/A' }}</span>
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
</div>

<!-- Attachments & Notes -->
<div class="row">
    <!-- Attachments -->
    <div class="col-lg-8">
        <div class="card service-details-card attachment-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-paperclip me-2"></i>{{ __('Service Attachments') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <!-- Catalog -->
                    <div class="col-md-4 mb-3">
                        <div class="attachment-box h-100">
                            <h6>Service Catalog</h6>
                            @if($service->catalog)
                            <div class="mt-2">
                                <a href="{{ asset($service->catalog) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <a href="{{ asset($service->attachment) }}" download class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </div>
                            @else
                            <p class="text-muted mb-0">No catalog available</p>
                            @endif
                        </div>
                    </div>

                    <!-- Attachment -->
                    <div class="col-md-4 mb-3">
                        <div class="attachment-box h-100">
                            <h6>Service Attachment</h6>
                            @if($service->attachment)
                            <div class="mt-2">
                                <a href="{{ asset($service->attachment) }}" target="_blank" class="btn btn-sm btn-outline-primary mb-1">
                                    <i class="fas fa-eye me-1"></i>View
                                </a>
                                <a href="{{ asset($service->attachment) }}" download class="btn btn-sm btn-outline-success">
                                    <i class="fas fa-download me-1"></i>Download
                                </a>
                            </div>
                            @else
                            <p class="text-muted mb-0">No attachment available</p>
                            @endif
                        </div>
                    </div>

                    <!-- Service Video -->
                    <div class="col-md-4 mb-3">
                        <div class="attachment-box h-100">
                            <i class="fas fa-video fa-3x text-success mb-2"></i>
                            <h6>Service Video</h6>
                            @if($service->service_video)
                            <div class="mt-2">
                                @if(str_contains($service->service_video, 'youtube.com') || str_contains($service->service_video, 'youtu.be'))
                                <a href="{{ asset($service->service_video) }}" target="_blank" class="btn btn-sm btn-outline-danger">
                                    <i class="fab fa-youtube me-1"></i>Watch Video
                                </a>
                                @else
                                <a href="{{ asset($service->service_video) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-external-link-alt me-1"></i>View Video
                                </a>
                                @endif
                            </div>
                            @else
                            <p class="text-muted mb-0">No video available</p>
                            @endif
                        </div>
                    </div>
                </div>

                @if(!$service->catalog && !$service->attachment && !$service->service_video)
                <div class="text-center text-muted py-4">
                    <i class="fas fa-paperclip fa-3x mb-3"></i>
                    <h5>No Attachments Available</h5>
                    <p>There are no files or videos attached to this service.</p>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Notes -->
    <div class="col-lg-4">
        <div class="card service-details-card info-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-sticky-note me-2"></i>{{ __('Service Notes') }}
                </h5>
            </div>
            <div class="card-body">
                @if($service->note)
                <div class="bg-light p-3 rounded" style="min-height: 150px;">
                    {{ $service->note }}
                </div>
                @else
                <div class="text-center text-muted py-4">
                    <i class="fas fa-sticky-note fa-2x mb-2"></i>
                    <p>No notes available for this service</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Service Summary -->
<div class="row">
    <div class="col-12">
        <div class="card service-details-card mb-4">
            <div class="section-header">
                <h5 class="card-title text-white mb-0">
                    <i class="fas fa-chart-bar me-2"></i>{{ __('Service Summary') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-tag fa-2x text-primary mb-2"></i>
                            <h5>{{ $service->category->name ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">Category</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-clock fa-2x text-warning mb-2"></i>
                            <h5>{{ $service->delivery_duration ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">Delivery Duration</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-calendar fa-2x text-info mb-2"></i>
                            <h5>{{ $service->time_line ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">Timeline</p>
                        </div>
                    </div>
                    <div class="col-md-3 mb-3">
                        <div class="border rounded p-3">
                            <i class="fas fa-building fa-2x text-success mb-2"></i>
                            <h5>{{ $service->organization ?? 'N/A' }}</h5>
                            <p class="text-muted mb-0">Organization</p>
                        </div>
                    </div>
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
                    <a href="{{ route('admin.service') }}" class="btn btn-secondary">
                        <i class="fas fa-arrow-left me-1"></i>{{ __('Back to List') }}
                    </a>
                    <form action="{{ route('admin.services.destroy', $service->id) }}" method="POST" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger delete-btn">
                            <i class="fas fa-trash me-1"></i>{{ __('Delete Service') }}
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
        const serviceTitle = "{{ $service->title }}";
        
        Swal.fire({
            title: 'Are you sure?',
            html: `You are about to delete service: <strong>"${serviceTitle}"</strong><br>This action cannot be undone!`,
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