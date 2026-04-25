@extends('frontend.seller.seller_master')
@section('title', 'Edit Product')
@section('content')

@include('frontend.include.seller-menu-top')

<div class="quikctech-manage-p-inner">
    <div class="quicktech-manage-head">
        <h4>Edit Product</h4>
        <a href="{{ route('vendor.products.manage') }}">← Back to Products</a>
    </div>
</div>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        {{ session('error') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

<form action="{{ route('vendor.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Basic Information</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        {{--<label for="product_name" class="form-label">Product Name *</label>
                        <input type="text" class="form-control @error('product_name') is-invalid @enderror"
                           id="product_name" name="product_name"
                           value="{{ old('product_name') }}" required>--}}

                        <label class="form-label quikctech-add-product-label">* Product Name</label>
                        <input type="text" name="product_name" class="form-control @error('product_name') is-invalid @enderror"
                           placeholder="Ex. Nikon Coolpix A300 Digital Camera" value="{{ old('product_name', $product->name) }}"
                           maxlength="255" oninput="updateCharCount(this)">
                        <small class="quikctech-subtext"><span id="charCount">0</span>/255</small>
                        @error('product_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="sku" class="form-label">SKU *</label>
                        <input type="text" class="form-control @error('sku') is-invalid @enderror"
                               id="sku" name="sku"
                               value="{{ old('sku', $product->sku) }}" required>
                        @error('sku')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category *</label>
                        <select class="form-select @error('category_id') is-invalid @enderror"
                                id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            @foreach($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id) == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('category_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="brand_id" class="form-label">Brand *</label>
                        <select class="form-select @error('brand_id') is-invalid @enderror"
                                id="brand_id" name="brand_id" required>
                            <option value="">Select Brand</option>
                            @foreach($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id) == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}
                                </option>
                            @endforeach
                        </select>
                        @error('brand_id')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="description" class="form-label">Description *</label>
                <textarea class="form-control @error('description') is-invalid @enderror"
                    id="summernote" name="description" rows="4" required>{{ old('description', $product->description) }}</textarea>
                @error('description')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="highlight" class="form-label">Product Highlights</label>
                <textarea class="form-control @error('highlight') is-invalid @enderror"
                    id="summerhighlight" name="highlight" rows="3">{{ old('highlight', $product->highlight) }}</textarea>
                @error('highlight')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Pricing & Inventory</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="price" class="form-label">Price (RM) *</label>
                        <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                               id="price" name="price"
                               value="{{ old('price', $product->price) }}" required>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="special_price" class="form-label">Special Price (RM)</label>
                        <input type="number" step="0.01" class="form-control @error('special_price') is-invalid @enderror"
                               id="special_price" name="special_price"
                               value="{{ old('special_price', $product->special_price) }}">
                        @error('special_price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="stock" class="form-label">Stock *</label>
                        <input type="number" class="form-control @error('stock') is-invalid @enderror"
                               id="stock" name="stock"
                               value="{{ old('stock', $product->stock) }}" required>
                        @error('stock')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="mb-3">
                        <label for="stock" class="form-label">Free Items </label>
                        <input type="number" name="free_items" class="form-control quikctech-spec-freeitems-input"
                            value="{{ old('free_items', $product->free_items) }}" min="0">
                        @error('free_items')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Product Images</h5>
        </div>
        <div class="card-body">
            <!-- Existing Images -->
            @if($product->images && $product->images->count() > 0)
                <div class="mb-4">
                    <label class="form-label">Existing Images</label>
                    <div class="row">
                        @foreach($product->images as $image)
                            <div class="col-md-3 mb-3">
                                <div class="image-checkbox-wrapper">
                                    <img src="{{ asset('storage/' . $image->image_path) }}"
                                         alt="Product Image"
                                         class="img-thumbnail"
                                         style="height: 150px; object-fit: cover; width: 100%;">
                                    <div class="form-check mt-2">
                                        <input type="checkbox" class="form-check-input"
                                               name="existing_images[]"
                                               value="{{ $image->id }}"
                                               id="image_{{ $image->id }}" checked>
                                        <label class="form-check-label" for="image_{{ $image->id }}">
                                            Keep Image
                                        </label>
                                    </div>
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input"
                                               name="primary_image"
                                               value="{{ $image->id }}"
                                               id="primary_{{ $image->id }}"
                                               {{ $image->is_primary ? 'checked' : '' }}>
                                        <label class="form-check-label" for="primary_{{ $image->id }}">
                                            Set as Primary
                                        </label>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @else
                <div class="alert alert-info">
                    No existing images found for this product.
                </div>
            @endif

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

            <!-- New Images -->
            {{--<div class="mb-3">
                <label for="images" class="form-label">Add New Images</label>
                <input type="file" class="form-control @error('images') is-invalid @enderror"
                       id="images" name="images[]" multiple accept="image/*">
                @error('images')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
                <div class="form-text">You can select multiple images. First image will be set as primary if no primary is selected.</div>
            </div>--}}

            <!-- Promotion Image -->
            <div class="mb-3">
                <label for="promotion_image" class="form-label">Promotion Image</label>
                @if($product->promotion_image)
                    <div class="mb-2">
                        <img src="{{ asset('storage/' . $product->promotion_image) }}"
                             alt="Promotion Image"
                             class="img-thumbnail"
                             style="height: 150px; object-fit: cover;">
                    </div>
                @endif
                <input type="file" class="form-control @error('promotion_image') is-invalid @enderror"
                       id="promotion_image" name="promotion_image" accept="image/*">
                @error('promotion_image')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Product Variants</h5>
        </div>
        <div class="card-body">
            <div id="variants-container">
                @if($product->variants && $product->variants->count() > 0)
                    @foreach($product->variants as $index => $variant)
                        <div class="variant-row row mb-3">
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="variants[{{ $index }}][name]"
                                    placeholder="Variant Name" value="{{ $variant->name }}">
                            </div>
                            <div class="col-md-3">
                                <input type="text" class="form-control" name="variants[{{ $index }}][value]"
                                    placeholder="Variant Value" value="{{ $variant->value }}">
                            </div>
                            <div class="col-md-2">
                                <input type="number" step="0.01" class="form-control"
                                    name="variants[{{ $index }}][additional_price]"
                                    placeholder="Additional Price" value="{{ $variant->additional_price }}">
                            </div>
                            <div class="col-md-2">
                                <input type="number" class="form-control" name="variants[{{ $index }}][stock]"
                                    placeholder="Stock" value="{{ $variant->stock }}">
                            </div>
                            <div class="col-md-2">
                                <button type="button" class="btn btn-danger remove-variant">Remove</button>
                            </div>
                        </div>
                    @endforeach
                @else
                    <div class="variant-row row mb-3">
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="variants[0][name]" placeholder="Variant Name">
                        </div>
                        <div class="col-md-3">
                            <input type="text" class="form-control" name="variants[0][value]" placeholder="Variant Value">
                        </div>
                        <div class="col-md-2">
                            <input type="number" step="0.01" class="form-control" name="variants[0][additional_price]" placeholder="Additional Price">
                        </div>
                        <div class="col-md-2">
                            <input type="number" class="form-control" name="variants[0][stock]" placeholder="Stock">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-variant">Remove</button>
                        </div>
                    </div>
                @endif
            </div>
            <button type="button" class="btn btn-secondary" id="add-variant">Add Variant</button>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Shipping & Dimensions</h5>
        </div>
        <div class="card-body">
            <div class="row">
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="weight" class="form-label">Weight (kg) *</label>
                        <input type="number" step="0.001" class="form-control @error('weight') is-invalid @enderror"
                               id="weight" name="weight"
                               value="{{ old('weight', $product->weight) }}" required>
                        @error('weight')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="length" class="form-label">Length (cm) *</label>
                        <input type="number" step="0.01" class="form-control @error('length') is-invalid @enderror"
                               id="length" name="length"
                               value="{{ old('length', $product->length) }}" required>
                        @error('length')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="width" class="form-label">Width (cm) *</label>
                        <input type="number" step="0.01" class="form-control @error('width') is-invalid @enderror"
                               id="width" name="width"
                               value="{{ old('width', $product->width) }}" required>
                        @error('width')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="height" class="form-label">Height (cm) *</label>
                        <input type="number" step="0.01" class="form-control @error('height') is-invalid @enderror"
                               id="height" name="height"
                               value="{{ old('height', $product->height) }}" required>
                        @error('height')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="dangerous_goods" class="form-label">Dangerous Goods *</label>
                        <select class="form-select @error('dangerous_goods') is-invalid @enderror"
                                id="dangerous_goods" name="dangerous_goods" required>
                            <option value="none" {{ old('dangerous_goods', $product->dangerous_goods) == 'none' ? 'selected' : '' }}>None</option>
                            <option value="contains" {{ old('dangerous_goods', $product->dangerous_goods) == 'contains' ? 'selected' : '' }}>Contains Dangerous Goods</option>
                        </select>
                        @error('dangerous_goods')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                <div class="col-md-6">
                    <label for="stock" class="form-label">Availability </label>
                    <div class="form-check form-switch">
                        <input class="form-check-input quikctech-spec-switch-input" type="checkbox"
                            name="availability" value="1" {{ old('availability', $product->availability) ? 'checked' : '' }}>
                    </div>
                    @error('availability')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror

                    {{--<div class="mb-3">
                        <div class="form-check form-switch mt-4">
                            <label class="form-check-label" for="availability">Product Available</label>
                            <input class="form-check-input" type="checkbox"
                               id="availability" name="availability" value="1"
                               {{ old('availability', $product->availability) ? 'checked' : '' }}>
                        </div>
                    </div>--}}
                </div>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header">
            <h5 class="mb-0">Media</h5>
        </div>
        <div class="card-body">
            @if($product->video_path)
                <div class="mb-2">
                    <video controls style="max-width: 300px; max-height: 200px;">
                        <source src="{{ asset('storage/' . $product->video_path) }}" type="video/mp4">
                        Your browser does not support the video tag.
                    </video>
                </div>
            @endif
            <div class="mb-3">
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

                {{--<label for="video" class="form-label">Product Video</label>
                <input type="file" class="form-control @error('video') is-invalid @enderror"
                    id="video" name="video" accept="video/mp4">--}}
                @error('video')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            {{--<div class="mb-3">
                <label for="youtube_url" class="form-label">YouTube URL</label>
                <input type="url" class="form-control @error('youtube_url') is-invalid @enderror"
                       id="youtube_url" name="youtube_url"
                       value="{{ old('youtube_url', $product->youtube_url) }}">
                @error('youtube_url')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>--}}

            <div class="mb-3 quikctech-ship-danger">
                <label class="form-label fw-bold">Warrenty Terms</label>
                <div class="input-group">
                    <input class="form-control" type="text" name="warranty_term" id="warranty_term" value="{{ old('warranty_term', $product->warranty_term) }}" placeholder="Warranty Terms">
                </div>
                @error('warranty_term')
                    <div class="text-danger small">{{ $message }}</div>
                @enderror
            </div>
        </div>
    </div>

    <div class="text-end">
        <button type="submit" class="btn btn-primary">Update Product</button>
        <a href="{{ route('vendor.products.manage') }}" class="btn btn-secondary">Cancel</a>
    </div>
</form>

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
        let variantCount = {{ ($product->variants && $product->variants->count() > 0) ? $product->variants->count() : 1 }};

        document.getElementById('add-variant').addEventListener('click', function() {
            const container = document.getElementById('variants-container');
            const newRow = document.createElement('div');
            newRow.className = 'variant-row row mb-3';
            newRow.innerHTML = `
                <div class="col-md-3">
                    <input type="text" class="form-control" name="variants[${variantCount}][name]" placeholder="Variant Name">
                </div>
                <div class="col-md-3">
                    <input type="text" class="form-control" name="variants[${variantCount}][value]" placeholder="Variant Value">
                </div>
                <div class="col-md-2">
                    <input type="number" step="0.01" class="form-control" name="variants[${variantCount}][additional_price]" placeholder="Additional Price">
                </div>
                <div class="col-md-2">
                    <input type="number" class="form-control" name="variants[${variantCount}][stock]" placeholder="Stock">
                </div>
                <div class="col-md-2">
                    <button type="button" class="btn btn-danger remove-variant">Remove</button>
                </div>
            `;
            container.appendChild(newRow);
            variantCount++;
        });

        // Remove variant
        document.addEventListener('click', function(e) {
            if (e.target.classList.contains('remove-variant')) {
                e.target.closest('.variant-row').remove();
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

@endsection
