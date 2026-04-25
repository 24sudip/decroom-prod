@extends('frontend.seller.seller_master')

@section('title', 'Service Manage')

@section('content')

@include('frontend.include.seller-menu-top')
<div class="row">
    <div class="col-lg-12">
        <div class="quicktech-manage-menu">
            <ul>
                <li><a href="{{ route('services.create') }}" class="managemenu-active">Create New</a></li>
                <li><a href="{{ route('services.index') }}" class="{{ !request('status') ? 'managemenu-active' : '' }}">All Services</a></li>
                <li><a href="{{ route('services.index', ['status' => 'in_progress']) }}" class="{{ request('status') == 'in_progress' ? 'managemenu-active' : '' }}">In progress</a></li>
                <li><a href="{{ route('services.index', ['status' => 'response']) }}" class="{{ request('status') == 'response' ? 'managemenu-active' : '' }}">Response</a></li>
                <li><a href="{{ route('services.index', ['status' => 'on_hold']) }}" class="{{ request('status') == 'on_hold' ? 'managemenu-active' : '' }}">On Hold</a></li>
                <li><a href="{{ route('services.index', ['status' => 'cancelled']) }}" class="{{ request('status') == 'cancelled' ? 'managemenu-active' : '' }}">Cancelled</a></li>
                <li><a href="{{ route('services.index', ['status' => 'complete']) }}" class="{{ request('status') == 'complete' ? 'managemenu-active' : '' }}">Complete</a></li>

                <li><a href="{{ route('services.index', ['status' => 'rejected']) }}" class="{{ request('status') == 'rejected' ? 'managemenu-active' : '' }}">Rejected</a></li>

                <li><a href="{{ route('services.index', ['status' => 'draft']) }}" class="{{ request('status') == 'draft' ? 'managemenu-active' : '' }}">Draft</a></li>
                <li><a href="{{ route('services.index', ['status' => 'record']) }}" class="{{ request('status') == 'record' ? 'managemenu-active' : '' }}">Records</a></li>
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

@if (session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <i class="fa-solid fa-triangle-exclamation me-2"></i>
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table quicktech-manage-table">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            <input type="checkbox" class="form-check-input quicktech-manage-checkbox" id="selectAll">
                        </th>
                        <th>Service Info</th>
                        <th>Status Details</th>
                        <th>Current Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($services as $service)
                    <tr>
                        <td>
                            <input type="checkbox" class="form-check-input quicktech-manage-checkbox service-checkbox" value="{{ $service->id }}">
                        </td>
                        <td>
                            <div class="quicktech-manage-product">
                                @if($service->catalog)
                                    <img src="{{ asset($service->catalog) }}" alt="{{ $service->title }}" onerror="this.src='https://i.ibb.co/6HF7Q7j/coin.jpg'">
                                @else
                                    <img src="https://i.ibb.co/6HF7Q7j/coin.jpg" alt="Default Service Image">
                                @endif
                                <div>
                                    <h6>{{ $service->title }}</h6>
                                    <small>Service ID: {{ $service->id }}</small>
                                    <br>
                                    <small>Category: {{ $service->category->name ?? 'N/A' }}</small>
                                    <br>
                                    <small>Client: {{ $service->client_id ?? 'Not assigned' }}</small>
                                    <br>
                                    <small>Vendor: {{ $service->vendor->name ?? $service->vendor->username ?? 'N/A' }}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <div class="quicktech-manage-status">
                                @if($service->status == 'draft')
                                    📝 <strong>Draft Mode</strong> - Ready for submission
                                @elseif($service->status == 'in_progress')
                                    🔄 <strong>In Progress</strong> - Service is currently being processed
                                @elseif($service->status == 'response')
                                    💬 <strong>Response Required</strong> - Waiting for client response
                                @elseif($service->status == 'on_hold')
                                    ⏸️ <strong>On Hold</strong> - Temporarily paused
                                @elseif($service->status == 'cancelled')
                                    ❌ <strong>Cancelled</strong> - Service has been cancelled
                                @elseif($service->status == 'complete')
                                    ✅ <strong>Completed</strong> - Service successfully delivered
                                @elseif($service->status == 'record')
                                    📁 <strong>Archived</strong> - Service record kept for reference
                                @endif
                                <br>
                                Created: <strong>{{ $service->created_at->format('Y-m-d H:i') }}</strong>
                                @if($service->delivery_duration)
                                    <br>
                                    Delivery: <strong>{{ $service->delivery_duration }}</strong>
                                @endif
                                @if($service->total_cost > 0)
                                    <br>
                                    Cost: <strong>৳{{ number_format($service->total_cost, 2) }}</strong>
                                @endif
                            </div>
                        </td>
                        <td class="status-cell">
                            @if($service->status == 'in_progress')
                                <span style="color: Green; font-weight: bold;">In Progress</span>
                            @elseif($service->status == 'on_hold')
                                <span style="color: red; font-weight: bold;">On Hold</span>
                            @elseif($service->status == 'response')
                                <span style="color: orange; font-weight: bold;">Response</span>
                            @elseif($service->status == 'cancelled')
                                <span style="color: #dc3545; font-weight: bold;">Cancelled</span>
                            @elseif($service->status == 'complete')
                                <span style="color: #28a745; font-weight: bold;">Complete</span>
                            @elseif($service->status == 'draft')
                                <span style="color: #6c757d; font-weight: bold;">Draft</span>
                            @elseif($service->status == 'record')
                                <span style="color: #6610f2; font-weight: bold;">Record</span>
                            @endif
                        </td>
                        <td class="text-end">
                            <div class="btn-group">
                                <a href="{{ route('services.edit', $service->id) }}" class="quicktech-manage-edit me-2">
                                    Edit
                                </a>
                                <a href="{{ route('services.show', $service->id) }}" class="quicktech-manage-view me-2">
                                    View
                                </a>
                                @if($service->delete_access)
                                <form action="{{ route('services.destroy', $service->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="quicktech-manage-delete" onclick="return confirm('Are you sure you want to delete this service?')">
                                        Delete
                                    </button>
                                </form>
                                @endif
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="quicktech-empty-state">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>No services found</h5>
                                <p class="text-muted">You haven't created any services yet.</p>
                                <a href="{{ route('services.create') }}" class="btn btn-primary">
                                    <i class="fa-solid fa-plus me-2"></i>Create Your First Service
                                </a>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        @if($services->hasPages())
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing {{ $services->firstItem() }} to {{ $services->lastItem() }} of {{ $services->total() }} services
            </div>
            <nav>
                {{ $services->links() }}
            </nav>
        </div>
        @endif
    </div>
</div>

@endsection

@push('styles')
<style>
.quicktech-manage-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.quicktech-manage-table thead {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.quicktech-manage-table th {
    font-weight: 600;
    color: #333;
    padding: 15px 12px;
    border: none;
}

.quicktech-manage-table td {
    padding: 15px 12px;
    vertical-align: middle;
    border-color: #f1f1f1;
}

.quicktech-manage-product {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.quicktech-manage-product img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.quicktech-manage-product h6 {
    margin: 0 0 5px 0;
    font-weight: 600;
    color: #333;
}

.quicktech-manage-product small {
    color: #6c757d;
    font-size: 12px;
}

.quicktech-manage-status {
    font-size: 13px;
    color: #555;
    line-height: 1.4;
}

.quicktech-manage-edit,
.quicktech-manage-view,
.quicktech-manage-delete {
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    background: none;
}

.quicktech-manage-edit {
    color: #007bff;
    border: 1px solid #007bff;
}

.quicktech-manage-edit:hover {
    background: #007bff;
    color: white;
}

.quicktech-manage-view {
    color: #28a745;
    border: 1px solid #28a745;
}

.quicktech-manage-view:hover {
    background: #28a745;
    color: white;
}

.quicktech-manage-delete {
    color: #dc3545;
    border: 1px solid #dc3545;
}

.quicktech-manage-delete:hover {
    background: #dc3545;
    color: white;
}

.quicktech-manage-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.quicktech-empty-state {
    padding: 40px 20px;
    text-align: center;
}

.quicktech-empty-state h5 {
    color: #6c757d;
    margin-bottom: 10px;
}

/* Status colors */
.status-cell {
    font-weight: 600;
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

/* Pagination styling */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border-radius: 6px;
    margin: 0 2px;
    border: 1px solid #dee2e6;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Select all checkbox functionality
    const selectAll = document.getElementById('selectAll');
    const serviceCheckboxes = document.querySelectorAll('.service-checkbox');

    if (selectAll) {
        selectAll.addEventListener('change', function() {
            serviceCheckboxes.forEach(checkbox => {
                checkbox.checked = selectAll.checked;
            });
        });
    }

    // Update individual checkboxes to update "Select All" status
    serviceCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(serviceCheckboxes).every(cb => cb.checked);
            const anyChecked = Array.from(serviceCheckboxes).some(cb => cb.checked);

            if (selectAll) {
                selectAll.checked = allChecked;
                selectAll.indeterminate = anyChecked && !allChecked;
            }
        });
    });

    // Add confirmation for status changes
    const statusCells = document.querySelectorAll('.status-cell');
    statusCells.forEach(cell => {
        cell.addEventListener('click', function() {
            const serviceId = this.closest('tr').querySelector('.service-checkbox').value;
            const currentStatus = this.textContent.trim();

            // You can implement status change modal here
            console.log('Change status for service:', serviceId, 'from:', currentStatus);
        });
    });
});
</script>
@endpush
