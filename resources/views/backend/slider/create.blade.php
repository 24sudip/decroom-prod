@extends('backend.layouts.master-layouts')

@section('title', 'Add New Slider')

@section('content')
    <div class="row mb-3">
        <div class="col-12 d-flex justify-content-between align-items-center">
            <h4>Add New Slider</h4>
            <a href="{{ route('slider.index') }}" class="btn btn-primary">Back to Slider List</a>
        </div>
    </div>

    <form action="{{ route('slider.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="mb-3">
            <label>Title</label>
            <input type="text" name="title" class="form-control">
        </div>

        <div class="mb-3">
            <label>Description</label>
            <textarea name="description" id="editor" class="form-control"></textarea>
        </div>

        <div class="mb-3">
            <label>Link (optional)</label>
            <input type="url" name="link" class="form-control">
        </div>

        <div class="mb-3">
            <label>Image <span class="text-danger">*</span></label>
            <input type="file" name="image" class="form-control" required>
        </div>

        <button class="btn btn-primary">Create</button>
    </form>

    <!-- CKEditor script directly here so it always loads -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
