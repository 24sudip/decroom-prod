@extends('backend.layouts.master-layouts')

@section('title')
    @if ($generic)
        {{ __('Update Generic Details') }}
    @else
        {{ __('Add New Generic') }}
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
                    @if ($generic)
                        {{ __('Update Generic Details') }}
                    @else
                        {{ __('Add New Generic') }}
                    @endif
                </h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ url('/dashboard') }}">{{ __('Dashboard') }}</a></li>
                        <li class="breadcrumb-item"><a href="{{ url('generic') }}">{{ __('generics') }}</a></li>
                        <li class="breadcrumb-item active">
                            @if ($generic)
                                {{ __('Update generic Details') }}
                            @else
                                {{ __('Add New generic') }}
                            @endif
                        </li>
                    </ol>
                </div>
            </div>
        </div>
        <div class="col-12">
            <form action="{{ route('generic.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mb-3 d-flex align-items-center gap-2">
                    <input type="file" name="file" class="form-control" style="width: 200px" required>
                    <button type="submit" class="btn btn-success">Import Generics</button><br>
                    <!-- Download Sample Button -->
                    <a href="{{ asset('storage/generic.xlsx') }}" class="btn btn-info ml-2">
                        Download Excel
                    </a>
                </div>


            </form>
        </div>

    </div>

    <div class="row">
        <div class="col-12">
            <a href="{{ route('generic.index') }}">
                <button type="button" class="btn btn-primary waves-effect waves-light mb-4">
                    <i class="bx bx-arrow-back font-size-16 align-middle me-2"></i>
                    {{ __('Back to generic List') }}
                </button>
            </a>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <blockquote>{{ __('Generic Information') }}</blockquote>
                    <form action="{{ $generic ? route('generic.update', $generic->id) : route('generic.store') }}"
                        method="POST" enctype="multipart/form-data">
                        @csrf
                        @if ($generic)
                            @method('PATCH')
                        @endif

                        <div class="row">
                            <div class="col-md-12 mb-3">
                                <label for="name" class="form-label">{{ __('Generic Name') }} <span
                                        class="text-danger">*</span></label>
                                <input id="name" type="text" name="name"
                                    class="form-control @error('name') is-invalid @enderror"
                                    value="{{ old('name', $generic->name ?? '') }}"
                                    placeholder="{{ __('Enter generic name') }}">
                                @error('name')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror
                            </div>

                            <div class="col-md-12 mb-3">
                                <label for="description" class="form-label">{{ __('Description') }}</label>
                                <textarea id="description" name="description" rows="3" class="form-control">{{ old('description', $generic->description ?? '') }}</textarea>
                            </div>

                            <div class="col-md-12 mb-3 d-none">
                                <label for="image" class="form-label">{{ __('Generic Image') }} @if (!$generic)
                                        <span class="text-danger"></span>
                                    @endif
                                </label>
                                <input id="image" type="file" name="image"
                                    class="form-control @error('image') is-invalid @enderror">
                                @error('image')
                                    <span class="invalid-feedback"><strong>{{ $message }}</strong></span>
                                @enderror

                                @if (!empty($generic->image))
                                    <img src="{{ asset('uploads/generics/' . $generic->image) }}" alt="Generic Image"
                                        class="img-thumbnail mt-2" width="150">
                                @endif
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    {{ $generic ? __('Update Generic') : __('Create Generic') }}
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
