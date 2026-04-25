@extends('backend.layouts.master-layouts')

@section('title')
    @if ($method)
        {{ __('Update Payment Method Details') }}
    @else
        {{ __('Add New Payment Method') }}
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
                    @if ($method)
                        {{ __('Update Payment Method Details') }}
                    @else
                        {{ __('Add New Payment Method') }}
                    @endif
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('paymentmethod') }}">{{ __('Methods') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if ($method)
                                {{ __('Update Payment Method Details') }}
                            @else
                                {{ __('Add New Payment Method') }}
                            @endif
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('paymentmethod.index') }}">
                <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>
                    {{ __('Back to Payment Method List') }}
                </button>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <blockquote>{{ __('Payment Method Information') }}</blockquote>
                    <form 
                    action="{{ isset($method) && $method?->id 
                        ? route('paymentmethod.update', $method->id) 
                        : route('paymentmethod.store') }}" 
                    method="POST" 
                    enctype="multipart/form-data"
                >
                    @csrf
                    @if(isset($method) && $method?->id)
                        @method('PATCH')
                    @endif


                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="title" class="form-label">{{ __('Method Title') }} <span
                                        class="text-danger">*</span></label>
                                <input id="title" type="text" name="title"
                                    class="form-control @error('title') is-invalid @enderror"
                                    value="{{ old('title', $method->title ?? '') }}"
                                    placeholder="{{ __('Enter method title') }}">
                                @error('title')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $method->description ?? '') }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="logo" class="form-label">{{ __('Icon') }} @if (!$method)
                                        <span class="text-danger"></span>
                                    @endif
                                </label>
                                <input id="logo" type="file" name="logo"
                                    class="form-control @error('logo') is-invalid @enderror">
                                @error('logo')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror

                                @if (!empty($method->logo))
                                    <img src="{{ asset('uploads/paymentmethod/' . $method->logo ?? 'frontend/images/default.png') }}"
                                        alt="Brand Image" class="img-thumbnail mt-2" width="200">
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ $method ? __('Update Method') : __('Create Method') }}
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
