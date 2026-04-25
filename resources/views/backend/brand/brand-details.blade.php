@extends('backend.layouts.master-layouts')

@section('title')
    @if ($brand)
        {{ __('Update Brand Details') }}
    @else
        {{ __('Add New Brand') }}
    @endif
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0 font-size-18">
                    @if ($brand)
                        {{ __('Update Brand Details') }}
                    @else
                        {{ __('Add New Brand') }}
                    @endif
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('brand') }}">{{ __('brands') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if ($brand)
                                {{ __('Update Brand Details') }}
                            @else
                                {{ __('Add New Brand') }}
                            @endif
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-12">
            <form action="{{ route('brand.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 d-flex align-items-center gap-2">
                    <input type="file" name="file" class="form-control" style="width: 200px" required>
                    <button type="submit" class="btn btn-success">Brand</button><br>
                    <!-- Download Sample Button -->
                    <a href="{{ asset('storage/brand.xlsx') }}" class="btn btn-info ml-2">
                        Download Excel
                    </a>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('brand.index') }}">
                <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>
                    {{ __('Back to brand List') }}
                </button>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <blockquote>{{ __('Brand Information') }}</blockquote>
                    <form action="{{ $brand ? route('brand.update', $brand->id) : route('brand.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($brand)
                            @method('PATCH')
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">{{ __('Brand Name') }} <span
                                        class="text-danger">*</span></label>
                                <input id="name" type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $brand->name ?? '') }}"
                                    placeholder="{{ __('Enter brand name') }}">
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $brand->description ?? '') }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">{{ __('Brand Image') }} @if (!$brand)
                                        <span class="text-danger"></span>
                                    @endif
                                </label>
                                <input id="image" type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror

                                @if (!empty($brand->image))
                                    <img src="{{ asset('uploads/categories/' . $brand->image ?? 'frontend/images/default.png') }}"
                                        alt="Brand Image" class="img-thumbnail mt-2" width="150">
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ $brand ? __('Update Brand') : __('Create Brand') }}
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('build/js/pages/form-advanced.init.js') }}"></script>
@endsection
