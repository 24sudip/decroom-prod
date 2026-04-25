<!-- I begin to speak only when I am certain what I will say is not better left unsaid. - Cato the Younger -->
@extends('backend.layouts.master-layouts')

@section('title', __('Post Service List'))

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
    .service-image {
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
        gap: 0.5rem;
    }
    .cost-badge {
        font-size: 0.8rem;
    }
    @media (max-width: 768px) {
        .filter-actions {
            flex-direction: column;
        }
    }
</style>
@endsection

@section('content')
@php
    $currentRequest = request();
@endphp

<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">{{ __('Post Service List') }}</h4>

        </div>
    </div>
</div>

{{-- Debug Info --}}
@if(env('APP_DEBUG'))
<div class="alert alert-info mb-3" id="debugInfo">
    <div class="row">
        <div class="col-md-4">
            <strong>Total Post Services:</strong> <span id="serviceCount">{{ $service_drafts->total() }}</span>
        </div>
        {{-- <div class="col-md-4">
            <strong>Categories:</strong> {{ $categories->count() }}
        </div> --}}
        <div class="col-md-4">
            <strong>Vendors:</strong> {{ $vendors->count() }}
        </div>
    </div>
    @if($currentRequest->anyFilled(['category_id', 'vendor_id', 'search', 'status']))
    <div class="mt-2">
        <strong>Active Filters:</strong>
        @foreach($currentRequest->all() as $key => $value)
            @if(in_array($key, ['category_id', 'vendor_id', 'search', 'status']) && !empty($value))
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
            <i class="fas fa-filter me-2"></i>{{ __('Filter Post Services') }}
        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="{{ route('admin.service-draft.all') }}" id="filterForm">
            <div class="row g-3 align-items-end">
                {{-- <div class="col-md-3">
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
                </div> --}}

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

                {{-- Status Filter --}}
                <div class="col-md-2">
                    <label class="form-label fw-semibold">{{ __('Status') }}</label>
                    <select name="status" class="form-control select2" data-placeholder="{{ __('Select Status') }}">
                        <option value="">{{ __('All Status') }}</option>
                        <option value="0" {{ $currentRequest->status == 0 ? 'selected' : '' }}>Pending</option>
                        <option value="1" {{ $currentRequest->status == 1 ? 'selected' : '' }}>Confirmed</option>
                    </select>
                </div>

                {{-- Search --}}
                <div class="col-md-3">
                    <label class="form-label fw-semibold">{{ __('Search Post Services') }}</label>
                    <div class="input-group">
                        <input type="text" name="search" value="{{ $currentRequest->search }}" class="form-control"
                            placeholder="{{ __('Search by service title...') }}"
                            aria-label="Search services">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                {{-- Action Buttons --}}
                <div class="col-md-1">
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i>{{ __('Filter') }}
                        </button>
                        <a href="{{ route('admin.service-draft.all') }}" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-refresh me-1"></i>{{ __('Reset') }}
                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>

{{-- Results Summary --}}
@if($currentRequest->anyFilled(['category_id', 'vendor_id', 'search', 'status']))
<div class="alert alert-light mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <strong>Filtered Results:</strong>
            Showing {{ $service_drafts->firstItem() ?? 0 }} - {{ $service_drafts->lastItem() ?? 0 }} of
            {{ $service_drafts->total() }} Post services
        </div>
        <div>
            <a href="{{ route('admin.service-draft.all') }}" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-times me-1"></i>Clear Filters
            </a>
        </div>
    </div>
</div>
@endif

@if (session('success'))
<div class="alert alert-success">
    <strong class="text-success">{{ session('success') }}</strong>
</div>
@endif

{{-- ✅ Service Table --}}
<div class="card">
    <div class="card-body">
        @if($service_drafts->count() > 0)
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th width="80">{{ __('Video') }}</th>
                        <th>{{ __('Vendor') }}</th>
                        <th>{{ __('Post Service Title') }}</th>
                        <th>{{ __('Service Charge') }}</th>
                        <th>{{ __('Delivery Duration') }}</th>
                        <th width="120">{{ __('Project Cost') }}</th>
                        <th width="100">{{ __('Status') }}</th>
                        <th width="200">{{ __('Actions') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($service_drafts as $key => $service_draft)
                        <tr>
                            <td class="text-center">{{ $service_drafts->firstItem() + $key }}</td>
                            <td class="text-center">
                                <a href="{{ asset($service_draft->service_video) }}" target="_blank">View Video</a>
                                {{-- <div class="service-image no-image" title="No Image">
                                    <i class="fas fa-concierge-bell"></i>
                                </div> --}}
                            </td>
                            <td>
                                <span class="badge bg-info">{{ $service_draft->vendor->name ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ Str::limit($service_draft->title, 200) }}</div>
                                <small class="text-muted">ID: {{ $service_draft->id }}</small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark">{{ $service_draft->service_charge ?? 'N/A' }}</span>
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $service_draft->delivery_duration ?? 'N/A' }}</span>
                            </td>
                            <td class="fw-bold text-success">
                                ৳{{ number_format($service_draft->project_cost, 2) }}
                                @if($service_draft->discount > 0)
                                    <br><small class="text-danger cost-badge">-{{ $service_draft->discount }}%</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge
                                    @if($service_draft->status == 1) bg-success
                                    @elseif($service_draft->status == 0) bg-danger
                                    @else bg-light text-dark @endif">
                                    {{ $service_draft->status == 0 ? 'Pending' : 'Confirmed' }}
                                </span>
                            </td>
                            <td class="action-buttons">
                                <div class="d-flex flex-column gap-1">
                                    <form action="{{ route('admin.service-draft.status', $service_draft->id) }}" method="post">
                                        @csrf
                                        <div class="dropdown">
                                            <button class="btn btn-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Change Status
                                            </button>
                                            <div class="dropdown-menu">
                                                <button name="status" value="0" class="dropdown-item" type="submit">
                                                    Pending
                                                </button>
                                                <button name="status" value="1" class="dropdown-item" type="submit">
                                                    Confirmed
                                                </button>
                                            </div>
                                        </div>
                                    </form>
                                    {{-- <select class="form-select form-select-sm service-status status-dropdown"
                                            data-id="{{ $service_draft->id }}"
                                            data-type="status"
                                            data-current="{{ $service_draft->status }}">
                                        <option value="0" {{ $service_draft->status == 0 ? 'selected' : '' }}>⏳ Pending</option>
                                        <option value="1" {{ $service_draft->status == 1 ? 'selected' : '' }}>💬 Confirmed</option>
                                    </select> --}}
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        {{-- ✅ Pagination --}}
        @if($service_drafts->hasPages())
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing {{ $service_drafts->firstItem() }} to {{ $service_drafts->lastItem() }} of
                {{ $service_drafts->total() }} entries
            </div>
            <div>
                {{ $service_drafts->appends($currentRequest->all())->links() }}
            </div>
        </div>
        @endif

        @else
        {{-- No Services Found --}}
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-concierge-bell fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted">{{ __('No Post services found') }}</h4>
            <p class="text-muted mb-4">
                @if($currentRequest->anyFilled(['category_id', 'vendor_id', 'search', 'status']))
                    {{ __('Try adjusting your search filters or clear them to see all services.') }}
                @else
                    {{ __('Get started by adding your first service.') }}
                @endif
            </p>
            <div class="d-flex justify-content-center gap-2">
                @if($currentRequest->anyFilled(['category_id', 'vendor_id', 'search', 'status']))
                    <a href="{{ route('admin.service-draft.all') }}" class="btn btn-outline-primary">
                        <i class="fas fa-refresh me-1"></i>{{ __('Clear Filters') }}
                    </a>
                @endif
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
    console.log('🚀 Service List Initialized');

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
            const serviceTitle = form.closest('tr').find('.fw-semibold').text().trim();

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

        // Service Status change
        $(document).on('change', '.service-status', function() {
            const serviceId = $(this).data('id');
            const status = $(this).val();
            const previousValue = $(this).data('current');
            const dropdown = $(this);

            console.log(`Service Status Change: Service ${serviceId}, Status: ${status}`);

            // Show updating state
            dropdown.addClass('status-updating');

            $.ajax({
                url: "{{ route('admin.service-draft.updateStatus') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: serviceId,
                    type: 'status',
                    value: status
                },
                success: function(response) {
                    console.log('Service Status Update SUCCESS:', response);

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: response.message || 'Post Service status updated successfully!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Update current value
                        dropdown.data('current', status);
                        // Update status badge in the table
                        const statusBadge = dropdown.closest('tr').find('td:nth-child(8) .badge');
                        statusBadge.removeClass('bg-success bg-danger bg-warning bg-primary bg-info bg-secondary bg-dark bg-light');
                        statusBadge.addClass(
                            status == 1 ? 'bg-success' :
                            status == 0 ? 'bg-danger' :
                            // status == 'on_hold' ? 'bg-warning' :
                            // status == 'in_process' ? 'bg-primary' :
                            // status == 'response' ? 'bg-info' :
                            // status == 'draft' ? 'bg-secondary' :
                            // status == 'records' ? 'bg-dark' : 'bg-light'
                        );
                        statusBadge.text(status == 1 ? 'Confirmed' : 'Pending');
                        // statusBadge.text(status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()));
                    } else {
                        throw new Error(response.message || 'Update failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Post Service Status Update ERROR:', error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Failed to update post service status. Please try again.',
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

        // Admin Approval change
        $(document).on('change', '.admin-approval', function() {
            const serviceId = $(this).data('id');
            const approvalStatus = $(this).val();
            const previousValue = $(this).data('current');
            const dropdown = $(this);

            console.log(`Admin Approval Change: Service ${serviceId}, Status: ${approvalStatus}`);

            // Show updating state
            dropdown.addClass('status-updating');

            $.ajax({
                url: "{{ route('admin.services.updateStatus') }}",
                method: 'POST',
                data: {
                    _token: "{{ csrf_token() }}",
                    id: serviceId,
                    type: 'admin_approval',
                    value: approvalStatus
                },
                success: function(response) {
                    console.log('Admin Approval Update SUCCESS:', response);

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approval Updated',
                            text: response.message || 'Admin approval status updated successfully!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Update current value
                        dropdown.data('current', approvalStatus);
                        // Update approval badge in the table
                        const approvalBadge = dropdown.closest('tr').find('td:nth-child(8) .text-success');
                        if (approvalStatus == '1') {
                            if (approvalBadge.length === 0) {
                                dropdown.closest('tr').find('td:nth-child(8)').append('<br><small class="text-success cost-badge">✓ Approved</small>');
                            }
                        } else {
                            approvalBadge.remove();
                        }
                    } else {
                        throw new Error(response.message || 'Update failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Admin Approval Update ERROR:', error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Failed to update admin approval. Please try again.',
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

        console.log('✅ All service list features initialized');

    } catch (error) {
        console.error('❌ Service list initialization error:', error);
    }
});
</script>
@endsection
