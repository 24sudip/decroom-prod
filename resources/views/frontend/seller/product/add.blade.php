@extends('frontend.seller.seller_master')
@section('title', 'Seller Product Add')
@section('content')

<!-- seller-menu-top -->
    @include('frontend.include.seller-menu-top')

<div class="quikctech-product-seller mt-5">
    <div class="quikctech-product-seller-head">
        <h5>Your Products</h5>
    </div>
    {{-- <div class="quicktech-product-seller-main">
        <div class="row">
            <div class="col-lg-4">
                <div class="quikctech-p-s-inne">
                    <div class="quikctech-check-box d-flex align-items-center gap-3">
                        <label>
                            <input type="checkbox" name="option" value="1">
                        </label>
                        <img class="rounded-img" src="{{ asset('frontend') }}/images/c1.png" alt="">
                        <h5>Valar murgulis Coin <br>
                            <span style="font-size: 14px; font-weight: 400;">Seller Sku: 1290787283623</span>
                        </h5>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="quicktech-checking-qc">
                    <p>✅ Product will automatically be activated after passing QC. <br>
                        <span> Updated on: {{ date('Y-m-d H:i') }}</span>
                    </p>
                </div>
            </div>
            <div class="col-lg-2">
                <div class="quikctech-edit-product-btn text-center pt-3">
                    <a href="#">Edit more</a>
                </div>
            </div>
        </div>
    </div> --}}
</div>

<div class="quicktech-add-product mt-4">
    <div class="quikctech-product-seller-head">
        <h5>Add Product</h5>
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

    <form action="{{ route('vendor.products.store') }}" method="POST" enctype="multipart/form-data" id="productForm">
        @csrf

        <div class="quikctech-basic-head">
            <h5>Basic Information</h5>
        </div>

        <div class="quikctech-add-product">
            <!-- Product Name -->
            <div class="mb-3">
                <label class="form-label quikctech-add-product-label">* Product Name</label>
                <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror"
                       placeholder="Ex. Nikon Coolpix A300 Digital Camera" value="{{ old('product_name') }}"
                       maxlength="255" oninput="updateCharCount(this)">
                <small class="quikctech-subtext"><span id="charCount">0</span>/255</small>
                @error('product_name')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Category -->
            <div class="mb-3">
                <label class="form-label quikctech-add-product-label">* Category</label>
                <select name="category_id" class="form-select @error('category_id') is-invalid @enderror">
                    <option value="">Select Category</option>
                    @foreach($categories as $category)
                        <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                            {{ $category->name }}
                        </option>
                    @endforeach
                </select>
                <div class="mt-1">
                    @foreach($categories->take(3) as $category)
                        <span class="badge bg-light text-dark">{{ $category->name }}</span>
                    @endforeach
                </div>
                @error('category_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Product Images -->
            <div class="mb-3">
                <label class="form-label quikctech-add-product-label">* Product Images</label>
                <label class="quikctech-add-product-box" id="mainImageUpload">
                    <input type="file" name="images[]" accept="image/*" multiple onchange="previewImages(this, 'mainImagePreview')">
                    <i class="fa-solid fa-plus"></i>
                    <img>
                    <div id="mainImagePreview" class="image-preview-container"></div>
                </label>
                @error('images')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Buyer Promotion Image -->
            <div class="mb-3 quikctech-bg-light">
                <label class="form-label quikctech-add-product-label">
                    Buyer Promotion Image <span class="quikctech-highlight">More Exposure</span>
                </label>
                <label class="quikctech-add-product-box" id="promotionImageUpload">
                    <input type="file" name="promotion_image" accept="image/*" onchange="previewImage(this, 'promotionImagePreview')">
                    <i class="fa-solid fa-plus"></i>
                    <img>
                    <div id="promotionImagePreview" class="image-preview-container"></div>
                </label>
                <div class="mt-2 quikctech-subtext">White Background Image <a href="#">See Example</a></div>
            </div>

            <!-- Video -->
            <div class="mb-3 quikctech-bg-light">
                <label class="form-label quikctech-add-product-label">Video</label>
                <div class="d-flex quicktech-v-t mb-2">
                    <div class="form-check me-3">
                        <input class="form-check-input" type="radio" name="video_type" value="upload" id="uploadVideo" checked>
                        <label style="color: black;" class="form-check-label" for="uploadVideo">Upload Video</label>
                    </div>
                    <!--<div class="form-check me-3">-->
                    <!--    <input class="form-check-input" type="radio" name="video_type" value="youtube" id="youtubeLink">-->
                    <!--    <label style="color: black;" class="form-check-label" for="youtubeLink">Youtube Link</label>-->
                    <!--</div>-->
                    <!--<div class="form-check">-->
                    <!--    <input class="form-check-input" type="radio" name="video_type" value="media" id="mediaCenter">-->
                    <!--    <label style="color: black;" class="form-check-label" for="mediaCenter">Media Center</label>-->
                    <!--</div>-->
                </div>
                <div id="videoUploadSection">
                    <label class="quikctech-add-product-box">
                        <input type="file" name="video" accept="video/mp4" onchange="previewVideo(this)">
                        <i class="fa-solid fa-plus"></i>
                        <video controls style="display: none;"></video>
                    </label>
                </div>
                <div class="mt-2 quikctech-subtext">
                    * Min size: 480x480 px. max video length: 60 seconds. max file size: 100MB.<br>
                    * Supported Format: mp4<br>
                    * New Video might take up to 36 hrs to be approved
                </div>
            </div>
        </div>

        <div class="quikctech-basic-head">
            <h5>Product Specification</h5>
            <p>Fill more product specification will increase product searchability</p>
        </div>

        <div class="quikctech-spec-container">
            <!-- Brand -->
            <div class="mb-3 quikctech-spec-brand">
                <label class="form-label quikctech-spec-brand-label">* Brand</label>
                <select name="brand_id" class="form-select quikctech-spec-brand-select @error('brand_id') is-invalid @enderror">
                    <option value="">Select Brand</option>
                    @foreach($brands as $brand)
                        <option value="{{ $brand->id }}" {{ old('brand_id') == $brand->id ? 'selected' : '' }}>
                            {{ $brand->name }}
                        </option>
                    @endforeach
                </select>
                @error('brand_id')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <!-- Price, Stock & Variants -->
            <div class="mb-3 quikctech-spec-variant">
                <h5 class="quikctech-spec-section-title">Price, Stock &amp; Variants</h5>
                <p class="text-muted small mb-3">You can add variants to a product that has more than one option, such as size or color.</p>

                <!-- Variants Container -->
                <div class="card mb-4">
                    <div class="card-header bg-light">
                        <h6 class="mb-0">Product Variants</h6>
                    </div>
                    <div class="card-body">
                        <div id="variants-container">
                            <!-- Default variant row -->
                            <div class="variant-row row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Variant Name</label>
                                    <input type="text" class="form-control" name="variants[0][name]" placeholder="e.g., Color, Size">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label small fw-bold">Variant Value</label>
                                    <input type="text" class="form-control" name="variants[0][value]" placeholder="e.g., Red, Large">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Additional Price</label>
                                    <input type="number" step="0.01" class="form-control" name="variants[0][additional_price]" placeholder="0.00" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold">Stock</label>
                                    <input type="number" class="form-control" name="variants[0][stock]" placeholder="0" value="0">
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label small fw-bold" style="visibility: hidden;">Action</label>
                                    <button type="button" class="btn btn-danger remove-variant w-100">
                                        <i class="fa-solid fa-trash"></i> Remove
                                    </button>
                                </div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary" id="add-variant">
                            <i class="fa-solid fa-plus"></i> Add Variant
                        </button>
                    </div>
                </div>
            </div>

            <!-- Price & Stock Table -->
            <div class="mb-3 quikctech-spec-price-stock">
                <h6 class="quikctech-spec-section-title">* Price &amp; Stock</h6>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle quikctech-spec-table">
                        <thead class="table-light">
                            <tr>
                                <th class="quikctech-spec-price">* Price</th>
                                <th class="quikctech-spec-special">Special Price</th>
                                <th class="quikctech-spec-stock">Stock <i class="bi bi-info-circle"></i></th>
                                <th class="quikctech-spec-sku">SellerSKU</th>
                                <th class="quikctech-spec-freeitems">Free Items</th>
                                <th class="quikctech-spec-availability">Availability</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <input type="number" name="price" class="form-control quikctech-spec-price-input @error('price') is-invalid @enderror"
                                           placeholder="৳" step="0.01" min="0" value="{{ old('price') }}">
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="special_price" class="form-control quikctech-spec-price-input"
                                           placeholder="৳" step="0.01" min="0" value="{{ old('special_price') }}">
                                </td>
                                <td>
                                    <input type="number" name="stock" class="form-control quikctech-spec-stock-input @error('stock') is-invalid @enderror"
                                           value="{{ old('stock', 0) }}" min="0">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="text" name="sku" class="form-control quikctech-spec-sku-input @error('sku') is-invalid @enderror"
                                        placeholder="Seller SKU" maxlength="200" value="{{ old('sku') }}">
                                    @error('sku')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </td>
                                <td>
                                    <input type="number" name="free_items" class="form-control quikctech-spec-freeitems-input"
                                        value="{{ old('free_items', 0) }}" min="0">
                                </td>
                                <td class="quikctech-spec-switch">
                                    <div class="form-check form-switch">
                                        <input class="form-check-input quikctech-spec-switch-input" type="checkbox"
                                            name="availability" value="1" {{ old('availability', true) ? 'checked' : '' }}>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="quikctech-basic-head">
            <h5>Product Description</h5>
        </div>

        <div class="quikctech-prodiuct-description">
            <p style="margin-bottom: 0;">Main Description</p>
            <textarea name="description" id="summernote">{{ old('description') }}</textarea>
            @error('description')
                <div class="text-danger small">{{ $message }}</div>
            @enderror
        </div>

        <div class="quikctech-prodiuct-description">
            <p style="margin-bottom: 0;">Highlight</p>
            <textarea name="highlight" id="summerhighlight">{{ old('highlight') }}</textarea>
        </div>

        <div class="quikctech-ship">
            <!-- Heading -->
            <h5 class="quikctech-ship-head mb-3 fw-bold">Shipping &amp; Warranty</h5>

            <!-- Switch -->
            <div class="form-check form-switch mb-3 quikctech-ship-switch">
                <input class="form-check-input" type="checkbox" id="quikctechShipSwitch">
                <label style="color: black;" class="form-check-label" for="quikctechShipSwitch">
                    Switch to enter different package dimensions &amp; weight for variations
                </label>
                <div class="text-muted small">
                    Switch on if you need different dimension &amp; weight for different product variants
                </div>
            </div>

            <!-- Package Weight -->
            <div class="mb-3 quikctech-ship-weight">
                <label class="form-label fw-bold">* Package Weight</label>
                <div class="input-group" style="max-width:300px;">
                    <input type="number" name="weight" class="form-control @error('weight') is-invalid @enderror"
                           placeholder="0.001~300" step="0.001" min="0.001" max="300" value="{{ old('weight') }}">
                    <select class="form-select" style="max-width:86px;" name="weight_unit">
                        <option value="kg">kg</option>
                        <option value="gram">gram</option>
                    </select>
                    @error('weight')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Package Dimensions -->
            <div class="mb-3 quikctech-ship-dimension">
                <label class="form-label fw-bold">* Package Length(cm) * Width(cm) * Height(cm)</label>
                <div class="d-flex align-items-center gap-2 flex-wrap" style="max-width:900px;">
                    <input type="number" name="length" class="form-control @error('length') is-invalid @enderror"
                           placeholder="0.01~300" step="0.01" min="0.01" max="300" value="{{ old('length') }}">
                    <span>x</span>
                    <input type="number" name="width" class="form-control @error('width') is-invalid @enderror"
                           placeholder="0.01~300" step="0.01" min="0.01" max="300" value="{{ old('width') }}">
                    <span>x</span>
                    <input type="number" name="height" class="form-control @error('height') is-invalid @enderror"
                           placeholder="0.01~300" step="0.01" min="0.01" max="300" value="{{ old('height') }}">
                </div>
                <div class="small text-muted mt-1">
                    How to measure my package dimensions? <a href="#">View Example</a>
                </div>
                @error('length')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                @error('width')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
                @error('height')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- Dangerous Goods -->
            <div class="mb-4 quikctech-ship-danger">
                <label class="form-label fw-bold">Dangerous Goods</label>
                <div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="dangerous_goods" id="none"
                               value="none" {{ old('dangerous_goods', 'none') == 'none' ? 'checked' : '' }}>
                        <label style="color: black;" class="form-check-label" for="none">None</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="radio" name="dangerous_goods" id="contains"
                               value="contains" {{ old('dangerous_goods') == 'contains' ? 'checked' : '' }}>
                        <label style="color: black;" class="form-check-label" for="contains">Contains battery / flammables / liquid</label>
                    </div>
                </div>
                @error('dangerous_goods')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>

            <!-- More Warranty Terms Button -->
            <div class="quikctech-ship-danger">
                <label class="form-label fw-bold">Warrenty Terms</label>
                <div class="input-group">
                    <input class="form-control" type="text" name="warranty_term" id="warranty_term" value="{{ old('warranty_term') }}" placeholder="Warranty Terms">
                </div>
                @error('warranty_term')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
            <!--<button type="button" class="btn btn-outline-primary quikctech-ship-btn">More Warranty Terms</button>-->
        </div>

        <div class="quikctech-ship-submit-btn">
            <button type="submit" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<script>
    setTimeout(() => {
        document.querySelectorAll('.alert').forEach(alert => {
            const bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 4000);
</script>

@endsection

@push('scripts')
<script>
// Character counter for product name
function updateCharCount(input) {
    const charCount = input.value.length;
    document.getElementById('charCount').textContent = charCount;
}

// Initialize character count on page load
document.addEventListener('DOMContentLoaded', function() {
    const productNameInput = document.querySelector('input[name="product_name"]');
    if (productNameInput) {
        updateCharCount(productNameInput);
    }
});

// Image preview functionality
function previewImages(input, previewContainerId) {
    const previewContainer = document.getElementById(previewContainerId);
    previewContainer.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            if (file.type.startsWith('image/')) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgDiv = document.createElement('div');
                    imgDiv.className = 'image-preview-item';
                    imgDiv.innerHTML = `
                        <img src="${e.target.result}" class="img-thumbnail">
                        <button type="button" class="btn-remove-image" onclick="removeImagePreview(this)">×</button>
                    `;
                    previewContainer.appendChild(imgDiv);
                }
                reader.readAsDataURL(file);
            }
        });
    }
}

function previewImage(input, previewContainerId) {
    const previewContainer = document.getElementById(previewContainerId);
    previewContainer.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            const imgDiv = document.createElement('div');
            imgDiv.className = 'image-preview-item';
            imgDiv.innerHTML = `
                <img src="${e.target.result}" class="img-thumbnail">
                <button type="button" class="btn-remove-image" onclick="removeImagePreview(this)">×</button>
            `;
            previewContainer.appendChild(imgDiv);
        }
        reader.readAsDataURL(input.files[0]);
    }
}

function previewVideo(input) {
    const video = input.parentElement.querySelector('video');
    if (input.files && input.files[0]) {
        const url = URL.createObjectURL(input.files[0]);
        video.src = url;
        video.style.display = 'block';
    }
}

function removeImagePreview(button) {
    button.parentElement.remove();
}

// Variant management
document.addEventListener('DOMContentLoaded', function() {
    let variantCount = 1;

    document.getElementById('add-variant').addEventListener('click', function() {
        const container = document.getElementById('variants-container');
        const newRow = document.createElement('div');
        newRow.className = 'variant-row row mb-3';
        newRow.innerHTML = `
            <div class="col-md-3">
                <label class="form-label small fw-bold">Variant Name</label>
                <input type="text" class="form-control" name="variants[${variantCount}][name]" placeholder="e.g., Color, Size">
            </div>
            <div class="col-md-3">
                <label class="form-label small fw-bold">Variant Value</label>
                <input type="text" class="form-control" name="variants[${variantCount}][value]" placeholder="e.g., Red, Large">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Additional Price</label>
                <input type="number" step="0.01" class="form-control" name="variants[${variantCount}][additional_price]" placeholder="0.00" value="0">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold">Stock</label>
                <input type="number" class="form-control" name="variants[${variantCount}][stock]" placeholder="0" value="0">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold" style="visibility: hidden;">Action</label>
                <button type="button" class="btn btn-danger remove-variant w-100">
                    <i class="fa-solid fa-trash"></i> Remove
                </button>
            </div>
        `;
        container.appendChild(newRow);
        variantCount++;
    });

    // Remove variant
    document.addEventListener('click', function(e) {
        if (e.target.classList.contains('remove-variant') || e.target.closest('.remove-variant')) {
            const removeBtn = e.target.classList.contains('remove-variant') ? e.target : e.target.closest('.remove-variant');
            const variantRow = removeBtn.closest('.variant-row');

            // Don't remove if it's the only row
            const allRows = document.querySelectorAll('.variant-row');
            if (allRows.length > 1) {
                variantRow.remove();
            } else {
                alert('You need at least one variant row. You can clear the fields instead.');
            }
        }
    });
});

// Video type switcher
document.querySelectorAll('input[name="video_type"]').forEach(radio => {
    radio.addEventListener('change', function() {
        const videoUploadSection = document.getElementById('videoUploadSection');
        if (this.value === 'upload') {
            videoUploadSection.innerHTML = `
                <label class="quikctech-add-product-box">
                    <input type="file" name="video" accept="video/mp4" onchange="previewVideo(this)">
                    <i class="fa-solid fa-plus"></i>
                    <video controls style="display: none;"></video>
                </label>
            `;
        } else if (this.value === 'youtube') {
            videoUploadSection.innerHTML = `
                <input type="url" name="youtube_url" class="form-control" placeholder="Enter YouTube URL">
            `;
        } else {
            videoUploadSection.innerHTML = `
                <select name="media_center" class="form-control">
                    <option value="">Select from Media Center</option>
                    <option value="1">Video 1</option>
                    <option value="2">Video 2</option>
                </select>
            `;
        }
    });
});

// Initialize Summernote editors
$(document).ready(function() {
    $('#summernote').summernote({
        height: 200,
        placeholder: 'Write product description here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline', 'clear']],
            ['font', ['strikethrough', 'superscript', 'subscript']],
            ['fontsize', ['fontsize']],
            ['color', ['color']],
            ['para', ['ul', 'ol', 'paragraph']],
            ['height', ['height']],
            ['insert', ['link', 'picture', 'video']],
        ]
    });

    $('#summerhighlight').summernote({
        height: 150,
        placeholder: 'Write product highlights here...',
        toolbar: [
            ['style', ['bold', 'italic', 'underline']],
            ['para', ['ul', 'ol']],
        ]
    });
});
</script>

<style>
.image-preview-container {
    display: flex;
    flex-wrap: wrap;
    gap: 10px;
    margin-top: 10px;
}

.image-preview-item {
    position: relative;
    display: inline-block;
}

.image-preview-item img {
    width: 100px;
    height: 100px;
    object-fit: cover;
    border-radius: 8px;
}

.btn-remove-image {
    position: absolute;
    top: -5px;
    right: -5px;
    background: #dc3545;
    color: white;
    border: none;
    border-radius: 50%;
    width: 20px;
    height: 20px;
    font-size: 12px;
    cursor: pointer;
}

.quikctech-add-product-box {
    position: relative;
}

.quikctech-add-product-box input[type="file"] {
    position: absolute;
    width: 100%;
    height: 100%;
    opacity: 0;
    cursor: pointer;
}

.variant-row {
    padding: 15px;
    border: 1px solid #e9ecef;
    border-radius: 8px;
    background-color: #f8f9fa;
    margin-bottom: 15px;
}

.variant-row .form-label {
    font-size: 12px;
    margin-bottom: 5px;
}

.variant-row .form-control {
    font-size: 14px;
}

.remove-variant {
    font-size: 12px;
    padding: 8px 12px;
}

#variants-container {
    margin-bottom: 20px;
}

.quikctech-spec-variant .card {
    border: 1px solid #dee2e6;
    border-radius: 8px;
}

.quikctech-spec-variant .card-header {
    background-color: #f8f9fa !important;
    border-bottom: 1px solid #dee2e6;
    padding: 12px 20px;
}

.quikctech-spec-variant .card-body {
    padding: 20px;
}
</style>
@endpush
