@extends('backend.layouts.master-layouts')

@php
    $product = $product ?? null;
@endphp

@section('title')
    @if ($product)
        {{ __('Update Product') }}
    @else
        {{ __('Add New Product') }}
    @endif
@endsection

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
    <!-- Summernote CSS -->
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.css" rel="stylesheet">
    <style>
        .note-editor {
            border: 1px solid #dee2e6 !important;
            border-radius: 0.375rem !important;
        }
        .note-toolbar {
            background-color: #f8f9fa !important;
            border-bottom: 1px solid #dee2e6 !important;
        }
        .note-statusbar {
            background-color: #f8f9fa !important;
            border-top: 1px solid #dee2e6 !important;
        }
    </style>
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    {{ $product ? __('Update Product') : __('Add New Product') }}
                </h4>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('product.index') }}" class="btn btn-primary mb-3 float-end">
                <i class="bx bx-arrow-back me-2"></i>{{ __('Back to Product List') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            <form action="{{ $product ? route('product.update', $product->id) : route('product.store') }}" method="POST"
                enctype="multipart/form-data" id="productForm">
                @csrf
                @if ($product)
                    @method('PATCH')
                @endif

                <div class="row">
                    <!-- Product Basic Information -->
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Product Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required
                            value="{{ old('name', $product->name ?? '') }}">
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>{{ __('SKU') }} <span class="text-danger">*</span></label>
                        <input type="text" name="sku" class="form-control" required
                            value="{{ old('sku', $product->sku ?? '') }}">
                        @error('sku')
                            <span class="text-danger">{{ $message }}</span>
                        @enderror
                    </div>

                    <!-- Category and Brand -->
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Category') }} <span class="text-danger">*</span></label>
                        <select name="category_id" class="form-control select2" required>
                            <option value="">{{ __('Select Category') }}</option>
                            @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ old('category_id', $product->category_id ?? '') == $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>{{ __('Brand') }} <span class="text-danger">*</span></label>
                        <select name="brand_id" class="form-control select2" required>
                            <option value="">{{ __('Select Brand') }}</option>
                            @foreach ($brands as $brand)
                                <option value="{{ $brand->id }}"
                                    {{ old('brand_id', $product->brand_id ?? '') == $brand->id ? 'selected' : '' }}>
                                    {{ $brand->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Vendor') }} <span class="text-danger">*</span></label>
                        <select name="vendor_id" class="form-control select2" required>
                            <option value="">{{ __('Select Vendor') }}</option>
                            @foreach ($users as $user)
                                <option value="{{ $user->id }}"
                                    {{ old('vendor_id', $product->vendor_id ?? '') == $user->id ? 'selected' : '' }}>
                                    {{ $user->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Pricing -->
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Price') }} <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" step="0.01" name="price" class="form-control" required
                                value="{{ old('price', $product->price ?? '') }}" min="0">
                        </div>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>{{ __('Special Price') }}</label>
                        <div class="input-group">
                            <span class="input-group-text">৳</span>
                            <input type="number" step="0.01" name="special_price" class="form-control"
                                value="{{ old('special_price', $product->special_price ?? '') }}" min="0">
                        </div>
                    </div>

                    <!-- Stock -->
                    <div class="col-md-4 mb-3">
                        <label>{{ __('Stock Quantity') }} <span class="text-danger">*</span></label>
                        <input type="number" name="stock" class="form-control" required
                            value="{{ old('stock', $product->stock ?? '') }}" min="0">
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>{{ __('Free Items') }}</label>
                        <input type="number" name="free_items" class="form-control"
                            value="{{ old('free_items', $product->free_items ?? 0) }}" min="0">
                    </div>

                    <!-- Product Dimensions -->
                    <div class="col-md-3 mb-3">
                        <label>{{ __('Weight (kg)') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.001" name="weight" class="form-control" required
                            value="{{ old('weight', $product->weight ?? '') }}" min="0.001" max="300">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>{{ __('Length (cm)') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="length" class="form-control" required
                            value="{{ old('length', $product->length ?? '') }}" min="0.01" max="300">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>{{ __('Width (cm)') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="width" class="form-control" required
                            value="{{ old('width', $product->width ?? '') }}" min="0.01" max="300">
                    </div>

                    <div class="col-md-3 mb-3">
                        <label>{{ __('Height (cm)') }} <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" name="height" class="form-control" required
                            value="{{ old('height', $product->height ?? '') }}" min="0.01" max="300">
                    </div>

                    <!-- Dangerous Goods -->
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Dangerous Goods') }} <span class="text-danger">*</span></label>
                        <select name="dangerous_goods" class="form-control select2" required>
                            <option value="none" {{ old('dangerous_goods', $product->dangerous_goods ?? 'none') == 'none' ? 'selected' : '' }}>None</option>
                            <option value="contains" {{ old('dangerous_goods', $product->dangerous_goods ?? '') == 'contains' ? 'selected' : '' }}>Contains Dangerous Goods</option>
                        </select>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>{{ __('Availability') }}</label>
                        <div class="form-check form-switch mt-2">
                            <input type="checkbox" class="form-check-input" id="availability" name="availability" 
                                {{ old('availability', $product->availability ?? false) ? 'checked' : '' }}>
                            <label class="form-check-label" for="availability">Available</label>
                        </div>
                    </div>

                    <!-- Media Uploads -->
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Product Images') }} <span class="text-danger">*</span></label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*" {{ $product ? '' : 'required' }}>
                        
                        @if(isset($product) && $product->images && $product->images->count() > 0)
                            <div class="mt-2">
                                <small class="text-muted">Existing Images:</small>
                                <div class="d-flex flex-wrap mt-1">
                                    @foreach($product->images as $image)
                                        <div class="position-relative me-2 mb-2">
                                            <img src="{{ asset($image->image_path) }}" width="80" class="img-thumbnail" alt="Product Image" onerror="this.style.display='none'">
                                            <div class="form-check mt-1">
                                                <input type="checkbox" class="form-check-input" name="existing_images[]" value="{{ $image->id }}" checked>
                                                <label class="form-check-label">Keep</label>
                                            </div>
                                            @if($image->is_primary)
                                                <span class="badge bg-primary position-absolute top-0 start-0">Primary</span>
                                            @else
                                                <div class="form-check">
                                                    <input type="radio" class="form-check-input" name="primary_image" value="{{ $image->id }}">
                                                    <label class="form-check-label">Set Primary</label>
                                                </div>
                                            @endif
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        @elseif(isset($product))
                            <div class="mt-2">
                                <small class="text-muted">No existing images</small>
                            </div>
                        @endif
                    </div>
                    

                    <div class="col-md-6 mb-3">
                        <label>{{ __('Promotion Image') }}</label>
                        <input type="file" name="promotion_image" class="form-control" accept="image/*">
                        @if (!empty($product->promotion_image))
                            <div class="mt-2">
                                <img src="{{ asset($product->promotion_image) }}" width="100" class="img-thumbnail">
                                <small class="d-block text-muted">Current promotion image</small>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>{{ __('Product Video') }}</label>
                        <input type="file" name="video" class="form-control" accept="video/mp4">
                        @if (!empty($product->video_path))
                            <div class="mt-2">
                                <small class="text-muted">Current video:</small>
                                <div class="mt-1">
                                    <video width="150" controls>
                                        <source src="{{ asset($product->video_path) }}" type="video/mp4">
                                    </video>
                                </div>
                            </div>
                        @endif
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>{{ __('YouTube URL') }}</label>
                        <input type="url" name="youtube_url" class="form-control"
                            value="{{ old('youtube_url', $product->youtube_url ?? '') }}"
                            placeholder="https://www.youtube.com/watch?v=...">
                    </div>

                    <!-- Product Description with Summernote -->
                    <div class="col-md-12 mb-3">
                        <label>{{ __('Product Description') }} <span class="text-danger">*</span></label>
                        <textarea name="description" id="description" class="form-control summernote" rows="7">{{ old('description', $product->description ?? '') }}</textarea>
                    </div>
                    
                    <!-- Product Highlights with Summernote -->
                    <div class="col-md-12 mb-3">
                        <label>{{ __('Product Highlights') }}</label>
                        <textarea name="highlight" id="highlight" class="form-control summernote" rows="3">{{ old('highlight', $product->highlight ?? '') }}</textarea>
                    </div>  

                    <!-- Variants Section -->
                    <div id="variant-section" class="col-12 mb-3">
                        <label class="form-label">Product Variants</label>
                        <div class="variant-container">
                            @php $variantIndex = 0; @endphp

                            @foreach ($product->variants ?? [] as $variant)
                                <div class="row variant-group mb-2">
                                    <div class="col-md-3">
                                        <input type="text" name="variants[{{ $variantIndex }}][name]"
                                            class="form-control" placeholder="Variant Name (e.g. Color)"
                                            value="{{ old("variants.$variantIndex.name", $variant->name) }}">
                                    </div>
                                    <div class="col-md-3">
                                        <input type="text" name="variants[{{ $variantIndex }}][value]"
                                            class="form-control" placeholder="Variant Value (e.g. Red)"
                                            value="{{ old("variants.$variantIndex.value", $variant->value) }}">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="variants[{{ $variantIndex }}][additional_price]"
                                            class="form-control" placeholder="Additional Price"
                                            value="{{ old("variants.$variantIndex.additional_price", $variant->additional_price) }}" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <input type="number" name="variants[{{ $variantIndex }}][stock]"
                                            class="form-control" placeholder="Stock"
                                            value="{{ old("variants.$variantIndex.stock", $variant->stock) }}" min="0">
                                    </div>
                                    <div class="col-md-2">
                                        <button type="button" class="btn btn-danger remove-variant">Remove</button>
                                    </div>
                                </div>
                                @php $variantIndex++; @endphp
                            @endforeach
                        </div>
                        <button type="button" id="add-variant" class="btn btn-sm btn-primary mt-2">+ Add Variant</button>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        {{ $product ? __('Update Product') : __('Create Product') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    
    <!-- Summernote JS -->
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-bs4.min.js"></script>
    
    <script>
        // Initialize Select2
        $(document).ready(function() {
            $('.select2').select2();
            
            // Initialize Summernote for both textareas
            $('.summernote').summernote({
                height: 300,
                toolbar: [
                    ['style', ['style']],
                    ['font', ['bold', 'italic', 'underline', 'clear']],
                    ['fontname', ['fontname']],
                    ['color', ['color']],
                    ['para', ['ul', 'ol', 'paragraph']],
                    ['table', ['table']],
                    ['insert', ['link', 'picture', 'video']],
                    ['view', ['fullscreen', 'codeview', 'help']]
                ],
                fontNames: ['Arial', 'Arial Black', 'Comic Sans MS', 'Courier New', 'Helvetica', 'Impact', 'Tahoma', 'Times New Roman', 'Verdana'],
                fontSizes: ['8', '9', '10', '11', '12', '14', '16', '18', '20', '24', '28', '32', '36'],
                callbacks: {
                    onInit: function() {
                        console.log('Summernote initialized');
                    },
                    onChange: function(contents, $editable) {
                        // Content changed
                    }
                }
            });

            // Variant Management
            let variantIndex = {{ $variantIndex ?? 0 }};

            $('#add-variant').on('click', function() {
                const container = $('.variant-container');

                const row = $(`
                    <div class="row variant-group mb-2">
                        <div class="col-md-3">
                            <input type="text" name="variants[${variantIndex}][name]" class="form-control" placeholder="Variant Name (e.g. Color)">
                        </div>
                        <div class="col-md-3">
                            <input type="text" name="variants[${variantIndex}][value]" class="form-control" placeholder="Variant Value (e.g. Red)">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="variants[${variantIndex}][additional_price]" class="form-control" placeholder="Additional Price" min="0">
                        </div>
                        <div class="col-md-2">
                            <input type="number" name="variants[${variantIndex}][stock]" class="form-control" placeholder="Stock" min="0">
                        </div>
                        <div class="col-md-2">
                            <button type="button" class="btn btn-danger remove-variant">Remove</button>
                        </div>
                    </div>
                `);
                
                container.append(row);
                variantIndex++;
            });

            $(document).on('click', '.remove-variant', function(e) {
                $(this).closest('.variant-group').remove();
            });

            // Form submit handler - Summernote automatically syncs content
            $('#productForm').on('submit', function(e) {
                // Summernote automatically syncs content with textarea
                console.log('Form submitting with Summernote content');
            });
        });
    </script>
@endsection