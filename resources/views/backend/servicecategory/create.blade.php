@extends('backend.layouts.master-layouts')

@section('title')
    @if (isset($serviceCategory))
        {{ __('Update Service Category Details') }}
    @else
        {{ __('Add New Service Category') }}
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
                    @if (isset($serviceCategory))
                        {{ __('Update Service Category Details') }}
                    @else
                        {{ __('Add New Service Category') }}
                    @endif
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('servicecategory.index') }}">{{ __('Service Categories') }}</a></li> 
                        <li class="breadcrumb-item active">
                            @if (isset($serviceCategory))
                                {{ __('Update Category Details') }}
                            @else
                                {{ __('Add New Service Category') }}
                            @endif
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('servicecategory.index') }}"> 
                <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>
                    {{ __('Back to Category List') }}
                </button>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <blockquote>{{ __('Category Information') }}</blockquote>
                    <form action="{{ isset($serviceCategory) ? route('servicecategory.update', $serviceCategory->id) : route('servicecategory.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if (isset($serviceCategory))
                            @method('PATCH')
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">{{ __('Category Name') }} <span
                                        class="text-danger">*</span></label>
                                <input id="name" type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $serviceCategory->name ?? '') }}"
                                    placeholder="{{ __('Enter category name') }}">
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">{{ __('Category Image') }}</label>
                                <input id="image" type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror

                                @if (isset($serviceCategory) && !empty($serviceCategory->image))
                                    <img src="{{ asset('storage/service/' . $serviceCategory->image) }}"
                                        alt="Category Image" class="img-thumbnail mt-2" width="150">
                                @endif
                            </div>

                            <div class="col-md-12 mb-3">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="status" id="status"
                                        value="1" {{ old('status', $serviceCategory->status ?? false) ? 'checked' : '' }}>
                                    <label class="form-check-label" for="status">
                                        {{ __('Active') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    @if (isset($serviceCategory))
                                        {{ __('Update Category') }}
                                    @else
                                        {{ __('Create Category') }}
                                    @endif
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