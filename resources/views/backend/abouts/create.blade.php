@extends('backend.layouts.master-layouts')
@section('title', __('About Create'))

@section('content')
    <div class="container">
        <h2>Add New About Info</h2>

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

        <form action="{{ route('abouts.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            
            {{-- Name --}}
            <div class="form-group mb-3">
                <label for="name"><strong>Name</strong></label>
                <input type="text" name="name" class="form-control" placeholder="Enter Name" required>
            </div>
            {{-- phone --}}
            <div class="form-group mb-3">
                <label for="phone"><strong>Phone</strong></label>
                <input type="text" name="phone" class="form-control" placeholder="Enter Phone">
            </div>
            {{-- hot_line --}}
            <div class="form-group mb-3">
                <label for="hot_line"><strong>Hot-line</strong></label>
                <input type="text" name="hot_line" class="form-control" placeholder="Enter Hotline">
            </div>
            {{-- whats_app --}}
            <div class="form-group mb-3">
                <label for="whats_app"><strong>whats App Number</strong></label>
                <input type="text" name="whats_app" class="form-control" placeholder="Enter whats app number">
            </div>
            {{-- Address --}}
            <div class="form-group mb-3">
                <label for="address"><strong>Address</strong></label>
                <input type="text" name="address" class="form-control" placeholder="Enter address">
            </div>

            {{-- Description Top --}}
            <div class="form-group mb-3">
                <label for="description_top"><strong>Description Top</strong></label>
                <textarea name="description_top" class="form-control" rows="3" placeholder="Short summary (top)"></textarea>
            </div>

            {{-- Description --}}
            <div class="form-group mb-3">
                <label for="description"><strong>Description</strong></label>
                <textarea name="description" class="form-control" rows="5" placeholder="Full description"></textarea>
            </div>

            {{-- Image Upload --}}
            <div class="form-group mb-3">
                <label for="image"><strong>Image</strong></label>
                <input type="file" name="image" class="form-control">
            </div>

            {{-- Submit --}}
            <div class="form-group mt-3">
                <button type="submit" class="btn btn-primary">Create</button>
                <a href="{{ route('abouts.index') }}" class="btn btn-secondary">Back</a>
            </div>
        </form>
    </div>
@endsection
