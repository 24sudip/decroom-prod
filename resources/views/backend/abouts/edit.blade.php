@extends('backend.layouts.master-layouts')
@section('title', __('About Edit'))

@section('content')
    <div class="container">
        <h2>Edit About Info</h2>
        <a href="{{ route('abouts.index') }}" class="btn btn-primary mb-3">Manage</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <strong>Whoops!</strong> Please fix the following issues:<br><br>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('abouts.update', $about->id) }}" method="POST" enctype="multipart/form-data">
            @csrf
            @method('PUT')

            {{-- Name --}}
            <div class="form-group mb-3">
                <label for="name"><strong>Name</strong></label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $about->name) }}" required>
            </div>

            {{-- phone --}}
            <div class="form-group mb-3">
                <label for="phone"><strong>Phone</strong></label>
                <input type="text" name="phone" class="form-control" value="{{ old('phone', $about->phone) }}">
            </div>
            {{-- hot_line --}}
            <div class="form-group mb-3">
                <label for="hot_line"><strong>Hot-line</strong></label>
                <input type="text" name="hot_line" class="form-control" value="{{ old('hot_line', $about->hot_line) }}">
            </div>
            {{-- whats_app --}}
            <div class="form-group mb-3">
                <label for="whats_app"><strong>Whats App Number</strong></label>
                <input type="text" name="whats_app" class="form-control"
                    value="{{ old('whats_app', $about->whats_app) }}">
            </div>
            {{-- Address --}}
            <div class="form-group mb-3">
                <label for="address"><strong>Address</strong></label>
                <input type="text" name="address" class="form-control" value="{{ old('address', $about->address) }}">
            </div>

            {{-- Description Top --}}
            <div class="form-group mb-3">
                <label for="description_top"><strong>Description Top</strong></label>
                <textarea name="description_top" class="form-control" rows="6">{{ old('description_top', $about->description_top) }}</textarea>
            </div>

            {{-- Description --}}
            <div class="form-group mb-3">
                <label for="description"><strong>Description</strong></label>
                <textarea name="description" class="form-control" rows="7">{{ old('description', $about->description) }}</textarea>
            </div>

            {{-- Existing Image Preview --}}
            @if ($about->image)
                <div class="mb-3">
                    <label><strong>Current Image:</strong></label><br>
                    <img src="{{ asset('build/images/abouts/' . $about->image) }}" alt="Image" height="100">
                </div>
            @endif

            {{-- Upload New Image --}}
            <div class="form-group mb-3">
                <label for="image"><strong>Change Image</strong></label>
                <input type="file" name="image" class="form-control">
            </div>

            {{-- Submit --}}
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('abouts.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
@endsection
