@extends('frontend.seller.seller_master')
@section('title', 'Service Add')
@section('content')

<div class="row">
    <div class="col-lg-12">
        @include('frontend.include.seller-menu-top')

        <div class="quicktech-manage-menu">
            <ul>
                <li><a href="{{ route('services.create') }}" class="managemenu-active">Create New</a></li>
                <li><a href="{{ route('services.index', ['status' => 'in_progress']) }}">In progress</a></li>
                <li><a href="{{ route('services.index', ['status' => 'response']) }}">Response</a></li>
                <li><a href="{{ route('services.index', ['status' => 'on_hold']) }}">On Hold</a></li>
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

<form action="{{ route('services.store') }}" method="POST" class="quikctech-form-wrapper border p-4 rounded-3 shadow-sm" enctype="multipart/form-data">
    @csrf

    <div class="row g-3">
        <!-- Service Title -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Service Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control quikctech-input @error('title') is-invalid @enderror"
                   placeholder="ex. Floor Epoxy" value="{{ old('title') }}" required>
            @error('title')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Service Category -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Service Category</label>
            <select name="category_id" class="form-select quikctech-select @error('category_id') is-invalid @enderror">
                <option value="">Select Category</option>
                @foreach($serviceCategories as $category)
                    <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                        {{ $category->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Organization -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Organization</label>
            <input type="text" name="organization" class="form-control quikctech-input @error('organization') is-invalid @enderror"
                   placeholder="Enter Organization Name" value="{{ old('organization') }}">
            @error('organization')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Client User ID -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Client User ID</label>
            <input type="text" name="client_id" class="form-control quikctech-input @error('client_id') is-invalid @enderror"
                   placeholder="Enter user id" value="{{ old('client_id') }}">
            @error('client_id')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Vendor ID -->
        @php
            $vendor = auth('vendor')->user();
            $isVendorLoggedIn = !empty($vendor);
        @endphp

        <div class="col-md-4">
            <label class="form-label quikctech-label">Provider User ID</label>

            @if($isVendorLoggedIn && $vendor)
                <div class="input-group">
                    <input type="text" class="form-control quikctech-input bg-light"
                           value="{{ $vendor->name ?? $vendor->username ?? $vendor->email }} (ID: {{ $vendor->id }})"
                           readonly>
                    <input type="hidden" name="vendor_id" value="{{ $vendor->id }}">
                    <span class="input-group-text bg-success text-white" style="height: 43px">
                        <i class="fa-solid fa-check"></i> Your Account
                    </span>
                </div>
                <small class="text-muted">Automatically set to your vendor account</small>
            @else
                <select name="vendor_id" class="form-select quikctech-select @error('vendor_id') is-invalid @enderror">
                    <option value="">Select Vendor</option>
                    @foreach($vendors as $vendorOption)
                        <option value="{{ $vendorOption->id }}" {{ old('vendor_id') == $vendorOption->id ? 'selected' : '' }}>
                            {{ $vendorOption->name ?? $vendorOption->username ?? $vendorOption->email }}
                        </option>
                    @endforeach
                </select>
                @error('vendor_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            @endif
        </div>

        <!-- Delivery In -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Delivery in <span class="text-muted">(expected)</span></label>
            <input type="text" name="delivery_duration" class="form-control quikctech-input @error('delivery_duration') is-invalid @enderror"
                   placeholder="ex. next 3months / 45days" value="{{ old('delivery_duration') }}">
            @error('delivery_duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Timeline -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Time Line</label>
            <input type="text" name="time_line" class="form-control quikctech-input @error('time_line') is-invalid @enderror"
                   placeholder="ex. Milestone (in days)" value="{{ old('time_line') }}">
            @error('time_line')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Total Cost -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Total Cost</label>
            <input type="number" step="0.01" name="total_cost" class="form-control quikctech-input @error('total_cost') is-invalid @enderror"
                   placeholder="Tk" value="{{ old('total_cost') }}">
            @error('total_cost')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Material Cost -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Material Cost</label>
            <input type="number" step="0.01" name="material_cost" class="form-control quikctech-input @error('material_cost') is-invalid @enderror"
                   placeholder="Material Cost" value="{{ old('material_cost') }}">
            @error('material_cost')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Service Fee -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Service Fee</label>
            <input type="number" step="0.01" name="service_charge" class="form-control quikctech-input @error('service_charge') is-invalid @enderror"
                   placeholder="Tk" value="{{ old('service_charge') }}">
            @error('service_charge')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Discount -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Discount</label>
            <input type="number" step="0.01" name="discount" class="form-control quikctech-input @error('discount') is-invalid @enderror"
                   placeholder="(Amount)Tk" value="{{ old('discount') }}">
            @error('discount')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Installment -->
        <div class="col-md-3">
            <label class="form-label quikctech-label">Installment</label>
            <select name="installment" class="form-select quikctech-select @error('installment') is-invalid @enderror">
                <option value="0" {{ old('installment') == '0' ? 'selected' : '' }}>No Installment</option>
                <option value="1" {{ old('installment') == '1' ? 'selected' : '' }}>3 Installments</option>
                <option value="2" {{ old('installment') == '2' ? 'selected' : '' }}>6 Installments</option>
                <option value="3" {{ old('installment') == '3' ? 'selected' : '' }}>9 Installments</option>
                <option value="4" {{ old('installment') == '4' ? 'selected' : '' }}>12 Installments</option>
                <option value="5" {{ old('installment') == '5' ? 'selected' : '' }}>15 Installments</option>
                <option value="6" {{ old('installment') == '6' ? 'selected' : '' }}>18 Installments</option>
                <option value="7" {{ old('installment') == '7' ? 'selected' : '' }}>24 Installments</option>
            </select>
            @error('installment')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Advance -->
        {{--<div class="col-md-3">
            <label class="form-label quikctech-label">Advance</label>
            <input type="number" step="0.01" name="advance" class="form-control quikctech-input @error('advance') is-invalid @enderror"
                   placeholder="Tk" value="{{ old('advance') }}">
            @error('advance')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Mid -->
        <div class="col-md-3">
            <label class="form-label quikctech-label">Mid</label>
            <input type="number" step="0.01" name="mid" class="form-control quikctech-input @error('mid') is-invalid @enderror"
                   placeholder="Tk" value="{{ old('mid') }}">
            @error('mid')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>

        <!-- Final -->
        <div class="col-md-3">
            <label class="form-label quikctech-label">Final</label>
            <input type="number" step="0.01" name="final" class="form-control quikctech-input @error('final') is-invalid @enderror"
                   placeholder="Tk" value="{{ old('final') }}">
            @error('final')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>--}}

        <!-- Catalog Upload -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Catalog</label>
            <div class="quikctech-upload-area text-center">
                <input type="file" name="catalog" class="quikctech-file-input @error('catalog') is-invalid @enderror"
                       id="quikctech-catalog" accept=".pdf,.jpg,.jpeg,.png,.docx">
                <label for="quikctech-catalog" class="quikctech-upload-label">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    <span class="d-block mt-2">Upload Catalog</span>
                </label>
            </div>
            @error('catalog')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Attachment Upload -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Attachment</label>
            <div class="quikctech-upload-area text-center">
                <input type="file" name="attachment" class="quikctech-file-input @error('attachment') is-invalid @enderror"
                       id="quikctech-attachment" accept=".pdf,.jpg,.jpeg,.png,.docx">
                <label for="quikctech-attachment" class="quikctech-upload-label">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    <span class="d-block mt-2">Upload Attachment</span>
                </label>
            </div>
            @error('attachment')
                <div class="invalid-feedback d-block">{{ $message }}</div>
            @enderror
        </div>

        <!-- Note -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Note:</label>
            <textarea name="note" class="form-control quikctech-textarea @error('note') is-invalid @enderror"
            rows="5" placeholder="If you have other discussion write here, which will be part of this documentation">{{ old('note') }}</textarea>
            @error('note')
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
        
        <div class="col-md-4">
            <label class="form-label quikctech-label">Expire Duration: (In Days)</label>
            <input type="number" name="expire_duration" class="form-control quikctech-input @error('expire_duration') is-invalid @enderror" placeholder="Days" value="{{ old('expire_duration') }}">
            @error('expire_duration')
                <div class="invalid-feedback">{{ $message }}</div>
            @enderror
        </div>
    </div>
    <!-- Buttons -->
    <div class="d-flex justify-content-end mt-4">
        <button type="submit" name="save_draft" value="1" class="btn btn-secondary quikctech-btn me-2">
            <i class="fa-solid fa-save me-1"></i> Save Draft
        </button>
        <button type="submit" class="btn btn-success quikctech-btn">
            <i class="fa-solid fa-paper-plane me-1"></i> Submit Service
        </button>
    </div>
</form>
<script>
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
</script>
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
