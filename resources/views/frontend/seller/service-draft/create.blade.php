<!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
@extends('frontend.seller.seller_master')
@section('title', 'Reel Of Service Add')
@section('content')

<div class="row">
    <div class="col-lg-12">
        @include('frontend.include.seller-menu-top')
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

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                Please fix the following errors:
            </h5>
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

<form action="{{ route('service-draft.store') }}" method="POST" class="quikctech-form-wrapper border p-4 rounded-3 shadow-sm" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <!-- Service Title -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Service <span class="text-danger">*</span></label>
            <select name="service_id" class="form-control quikctech-input @error('service_id') is-invalid @enderror">
                <option value="">Choose Service</option>
                @foreach ($services as $service)
                <option value="{{ $service->id }}">{{ $service->title }}</option>
                @endforeach
            </select>
            @error('service_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
        
        <div class="col-md-4">
            <label class="form-label quikctech-label">Service Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control quikctech-input @error('title') is-invalid @enderror"
            placeholder="ex. Floor Epoxy" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Delivery In -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Delivery in <span class="text-muted">(expected)</span></label>
            <input type="text" name="delivery_duration" class="form-control quikctech-input @error('delivery_duration') is-invalid @enderror" placeholder="ex. next 3months / 45days" value="{{ old('delivery_duration') }}">
            @error('delivery_duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Total Cost -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Total Project Cost</label>
            <input type="number" step="0.01" name="project_cost" class="form-control quikctech-input @error('project_cost') is-invalid @enderror" placeholder="Tk" value="{{ old('project_cost') }}">
            @error('project_cost')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Material Cost -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Material Cost</label>
            <input type="number" step="0.01" name="material_cost" class="form-control quikctech-input @error('material_cost') is-invalid @enderror" placeholder="Material Cost" value="{{ old('material_cost') }}">
            @error('material_cost')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Service Fee -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Service Charge</label>
            <input type="number" step="0.01" name="service_charge" class="form-control quikctech-input @error('service_charge') is-invalid @enderror" placeholder="Tk" value="{{ old('service_charge') }}">
            @error('service_charge')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Discount -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Discount</label>
            <input type="number" step="0.01" name="discount" class="form-control quikctech-input @error('discount') is-invalid @enderror" placeholder="(Amount)Tk" value="{{ old('discount') }}">
            @error('discount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Video -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Video:</label>
            <input type="file" id="video" name="service_video" accept="video/*">
            @error('service_video')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <!-- Buttons -->
    <div class="d-flex justify-content-end mt-4">
        {{-- <button type="submit" name="save_draft" value="1" class="btn btn-secondary quikctech-btn me-2">
            <i class="fa-solid fa-save me-1"></i> Save Draft
        </button> --}}
        <button type="submit" class="btn btn-success quikctech-btn">
            <i class="fa-solid fa-paper-plane me-1"></i> Submit Post
        </button>
    </div>
</form>
{{-- <script>
document.getElementById('video').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const video = document.createElement('video');
    video.preload = 'metadata';

    video.onloadedmetadata = function () {
        window.URL.revokeObjectURL(video.src);

        const duration = video.duration; // seconds
        console.log('Duration:', duration);

        if (duration > 30) {
            alert('Video must be less than 30 seconds');
            e.target.value = ''; // reset input
        }
    };

    video.src = URL.createObjectURL(file);
});
</script> --}}
@endsection

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File upload preview
        const catalogInput = document.getElementById('quikctech-catalog');
        const attachmentInput = document.getElementById('quikctech-attachment');

        catalogInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = catalogInput.nextElementSibling;
                label.innerHTML = `<i class="fa-solid fa-file"></i><span class="d-block mt-2">${fileName}</span>`;
            }
        });

        attachmentInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = attachmentInput.nextElementSibling;
                label.innerHTML = `<i class="fa-solid fa-file"></i><span class="d-block mt-2">${fileName}</span>`;
            }
        });

        // Auto-calculate total cost
        const materialCostInput = document.querySelector('input[name="material_cost"]');
        const serviceChargeInput = document.querySelector('input[name="service_charge"]');
        const discountInput = document.querySelector('input[name="discount"]');
        const totalCostInput = document.querySelector('input[name="total_cost"]');

        function calculateTotalCost() {
            const materialCost = parseFloat(materialCostInput.value) || 0;
            const serviceCharge = parseFloat(serviceChargeInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            const total = materialCost + serviceCharge - discount;
            totalCostInput.value = total > 0 ? total.toFixed(2) : '';
        }

        if (materialCostInput && serviceChargeInput && discountInput && totalCostInput) {
            materialCostInput.addEventListener('input', calculateTotalCost);
            serviceChargeInput.addEventListener('input', calculateTotalCost);
            discountInput.addEventListener('input', calculateTotalCost);
        }

        // Form submission confirmation for draft
        const saveDraftBtn = document.querySelector('button[name="save_draft"]');
        const submitBtn = document.querySelector('button[type="submit"]:not([name])');

        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function(e) {
                // Optional: Add confirmation for draft save
                const title = document.querySelector('input[name="title"]').value;
                if (!title.trim()) {
                    if (!confirm('You are saving without a service title. Continue?')) {
                        e.preventDefault();
                    }
                }
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                const title = document.querySelector('input[name="title"]').value;
                if (!title.trim()) {
                    e.preventDefault();
                    alert('Please enter a service title before submitting.');
                    document.querySelector('input[name="title"]').focus();
                }
            });
        }
    });
</script>
@endpush

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

.quikctech-input, .quikctech-select, .quikctech-textarea {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 14px;
}

.quikctech-input:focus, .quikctech-select:focus, .quikctech-textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.quikctech-upload-area {
    border: 2px dashed #ddd;
    border-radius: 6px;
    padding: 20px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.quikctech-upload-area:hover {
    border-color: #007bff;
    background: #f0f8ff;
}

.quikctech-file-input {
    display: none;
}

.quikctech-upload-label {
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}

.quikctech-upload-label:hover {
    color: #007bff;
}

.quikctech-btn {
    padding: 10px 24px;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 140px;
}

.quikctech-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-secondary.quikctech-btn {
    background: #6c757d;
    border-color: #6c757d;
}

.btn-secondary.quikctech-btn:hover {
    background: #5a6268;
    border-color: #545b62;
}

.btn-success.quikctech-btn {
    background: #28a745;
    border-color: #28a745;
}

.btn-success.quikctech-btn:hover {
    background: #218838;
    border-color: #1e7e34;
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

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.justify-content-end {
        justify-content: center !important;
    }

    .d-flex.justify-content-end .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
@endpush
