@extends('backend.layouts.master-layouts')

@section('title')
    {{ $banner ? __('Update Offer Banner') : __('Add New Offer Banner') }}
@endsection

@section('css')
    <link rel="stylesheet" type="text/css" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <style>
        /* Switch Button Design */
        .switch {
            position: relative;
            display: inline-block;
            width: 55px;
            height: 26px;
        }

        .switch input { display:none; }

        .slider {
            position: absolute;
            cursor: pointer;
            top: 0; left: 0; right: 0; bottom: 0;
            background-color: #ccc;
            transition: .4s;
            border-radius: 34px;
        }

        .slider:before {
            position: absolute;
            content: "";
            height: 20px;
            width: 20px;
            left: 3px;
            bottom: 3px;
            background-color: white;
            transition: .4s;
            border-radius: 50%;
        }

        input:checked + .slider {
            background-color: #28a745;
        }

        input:checked + .slider:before {
            transform: translateX(28px);
        }
    </style>
@endsection

@section('content')

<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0 font-size-18">
                {{ $banner ? __('Update Offer Banner') : __('Add New Offer Banner') }}
            </h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                    <li class="breadcrumb-item"><a href="{{ url('offerbanner') }}">{{ __('Banners') }}</a></li>
                    <li class="breadcrumb-item active">
                        {{ $banner ? __('Update Offer Banner') : __('Add New Offer Banner') }}
                    </li>
                </ol>
            </div>
        </div>
    </div>
</div>

<div class="row mb-3">
    <div class="col-12">
        <a href="{{ route('offerbanner.index') }}">
            <button type="button" class="btn btn-primary waves-effect waves-light">
                <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>
                {{ __('Back to Offer Banner') }}
            </button>
        </a>
    </div>
</div>

<div class="row">
    <div class="col-lg-12">
        <div class="card">
            <div class="card-body">

                <blockquote>{{ __('Offer Banner Information') }}</blockquote>

                <form action="{{ $banner ? route('offerbanner.update', $banner->id) : route('offerbanner.store') }}"
                      method="POST" enctype="multipart/form-data">
                    @csrf
                    @if ($banner)
                        @method('PATCH')
                    @endif

                    <div class="row">
                        <!-- Link URL -->
                        <div class="col-md-12 mb-3">
                            <label for="link_url" class="form-label">{{ __('Link URL') }}</label>
                            <input id="link_url" type="text" name="link_url"
                                   class="form-control @error('link_url') is-invalid @enderror"
                                   value="{{ old('link_url', $banner->link_url ?? '') }}"
                                   placeholder="{{ __('Enter link url') }}">
                            @error('link_url')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror
                        </div>

                        <!-- Image -->
                        <div class="col-md-12 mb-3">
                            <label for="image" class="form-label">{{ __('Banner Image') }}</label>
                            <input id="image" type="file" name="image"
                                   class="form-control @error('image') is-invalid @enderror">
                            @error('image')
                            <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                            @enderror

                            @if (!empty($banner->image))
                                <img src="{{ asset('storage/banners/' . $banner->image) }}"
                                     alt="Banner Image" class="img-thumbnail mt-2" width="180">
                            @endif
                        </div>

                        <!-- Status Switch -->
                        <div class="col-md-12 mb-3">
                            <label class="form-label d-block">{{ __('Status') }}</label>

                            <label class="switch">
                                <input type="checkbox" name="status" id="status"
                                       value="1" {{ old('status', $banner->status ?? false) ? 'checked' : '' }}>
                                <span class="slider"></span>
                            </label>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="row">
                        <div class="col-md-12">
                            <button type="submit" class="btn btn-primary">
                                {{ $banner ? __('Update Banner') : __('Create Banner') }}
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
