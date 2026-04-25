@extends('backend.layouts.master-layouts')

@section('title')
    @if ($unit)
        {{ __('Update Unit Details') }}
    @else
        {{ __('Add New Unit') }}
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
                    @if ($unit)
                        {{ __('Update Unit Details') }}
                    @else
                        {{ __('Add New Unit') }}
                    @endif
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('unit') }}">{{ __('units') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if ($unit)
                                {{ __('Update Unit Details') }}
                            @else
                                {{ __('Add New Unit') }}
                            @endif
                        </li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('unit.index') }}">
                <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>
                    {{ __('Back to Unit List') }}
                </button>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <blockquote>{{ __('Unit Information') }}</blockquote>
                    <form action="{{ $unit ? route('unit.update', $unit->id) : route('unit.store') }}" method="POST"
                        enctype="multipart/form-data">
                        @csrf
                        @if ($unit)
                            @method('PATCH')
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">{{ __('Unit Name') }} <span
                                        class="text-danger">*</span></label>
                                <input id="name" type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $unit->name ?? '') }}"
                                    placeholder="{{ __('Enter Unit name') }}">
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $unit->description ?? '') }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="image" class="form-label">{{ __('Unit Image') }} @if (!$unit)
                                        <span class="text-danger"></span>
                                    @endif
                                </label>
                                <input id="image" type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror

                                @if (!empty($unit->image))
                                    <img src="{{ asset('uploads/unit/' . $unit->image) }}" alt="Unit Image"
                                        class="img-thumbnail mt-2" width="150">
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ $unit ? __('Update Unit') : __('Create Unit') }}
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
