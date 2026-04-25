@extends('backend.layouts.master-layouts')

@php
    $supplier = $supplier ?? null;
@endphp

@section('title')
    @if ($supplier)
        {{ __('Update Supplier') }}
    @else
        {{ __('Add New Supplier') }}
    @endif
@endsection

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    {{ $supplier ? __('Update Supplier') : __('Add New Supplier') }}
                </h4>
            </div>
        </div>
    </div>

    <a href="{{ route('supplier.index') }}" class="btn btn-primary mb-3">
        <i class="bx bx-arrow-back me-2"></i>{{ __('Back to Supplier List') }}
    </a>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="card">
        <div class="card-body">
            <form action="{{ $supplier ? route('supplier.update', $supplier->id) : route('supplier.store') }}"
                method="POST" enctype="multipart/form-data">
                @csrf
                @if ($supplier)
                    @method('PATCH')
                @endif

                <div class="row">
                    {{-- Supplier Name --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Supplier Name') }} <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" required
                            value="{{ old('name', $supplier->name ?? '') }}">
                        @error('name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Phone --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Phone') }} <span class="text-danger">*</span></label>
                        <input type="text" name="phone" class="form-control" required pattern="[0-9+ ]+"
                            title="Enter a valid phone number" value="{{ old('phone', $supplier->phone ?? '') }}">
                        @error('phone')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Email') }}</label>
                        <input type="email" name="email" class="form-control"
                            value="{{ old('email', $supplier->email ?? '') }}">
                        @error('email')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tread Name --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Tread Name') }}</label>
                        <input type="text" name="tread_name" class="form-control"
                            value="{{ old('tread_name', $supplier->tread_name ?? '') }}">
                        @error('tread_name')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Tread No --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Tread No') }}</label>
                        <input type="text" name="tread_no" class="form-control"
                            value="{{ old('tread_no', $supplier->tread_no ?? '') }}">
                        @error('tread_no')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Opening Balance --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Opening Balance') }}</label>
                        <input type="number" step="0.01" name="opening_balance" min="0" class="form-control"
                            value="{{ old('opening_balance', $supplier->opening_balance ?? '') }}">
                        @error('opening_balance')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Main Balance --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Main Balance') }}</label>
                        <input type="number" step="0.01" name="main_balance" min="0" class="form-control"
                            value="{{ old('main_balance', $supplier->main_balance ?? '') }}">
                        @error('main_balance')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Due Balance --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Due Balance') }}</label>
                        <input type="number" step="0.01" name="due_balance" min="0" class="form-control"
                            value="{{ old('due_balance', $supplier->due_balance ?? '') }}">
                        @error('due_balance')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>

                    {{-- Image --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Image') }}</label>
                        <input type="file" name="image" class="form-control">
                        @error('image')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror

                        @if (!empty($supplier->image))
                            <img src="{{ Storage::url('suppliers/' . $supplier->image) }}" width="100"
                                class="mt-2 img-thumbnail">
                        @endif
                    </div>

                    {{-- Address --}}
                    <div class="col-md-6 mb-3">
                        <label>{{ __('Address') }}</label>
                        <input type="text" name="address" min="0" class="form-control"
                            value="{{ old('address', $supplier->address ?? '') }}">
                        @error('address')
                            <small class="text-danger">{{ $message }}</small>
                        @enderror
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-success">
                        {{ $supplier ? __('Update Supplier') : __('Create Supplier') }}
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $('.select2').select2();
    </script>
@endsection
