@extends('backend.layouts.master-layouts')

@section('title', 'Edit Slider')

@section('content')
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Edit Slider</h4>
            <a href="{{ route('slider.index') }}" class="btn btn-primary">Back to Slider List</a>
        </div>
    </div>

    <form action="{{ route('slider.update', $slider->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PATCH')

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control" value="{{ old('title', $slider->title) }}">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" id="editor" class="form-control">{{ old('description', $slider->description) }}</textarea>
        </div>

        <div class="mb-3">
            <label>Link (optional)</label>
            <input type="url" name="link" class="form-control" value="{{ old('link', $slider->link) }}">
        </div>

        <div class="mb-3">
            <label>Image</label>
            <input type="file" name="image" class="form-control">
            @if ($slider->image)
                <img src="{{ asset('storage/sliders/' . $slider->image) }}" alt="Slider Image" class="mt-2 img-thumbnail"
                    width="150">
            @endif
        </div>

        <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" id="status" name="status"
                style="width: 20px !important;
        height: 20px !important;
        position: absolute !important;
        left: 9% !important;
        top: 0% !important;
        margin: 0 !important;"
                {{ old('status', $slider->status) ? 'checked' : '' }}>
            <label class="form-check-label" for="status">Active</label>
        </div>

        <button class="btn btn-success">Update</button>
    </form>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
