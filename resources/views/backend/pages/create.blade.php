@extends('backend.layouts.master-layouts')
@section('title', 'Create Page')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Create New Page</h4>
            <a href="{{ route('pages.index') }}" class="btn btn-secondary">← Back to Page List</a>
        </div>

        <form action="{{ route('pages.store') }}" method="POST">
            @csrf

            <div class="form-group">
                <label>Title</label>
                <input name="title" class="form-control" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <option value="">Select Category</option>
                    @foreach ($pageCategories as $cat)
                        <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Content</label>
                <textarea id="editor" name="content" rows="6" class="form-control"></textarea>
            </div>

            <div class="form-check mt-2">
                <input type="checkbox" name="status" class="form-check-input" checked>
                <label class="form-check-label">Active</label>
            </div>

            <button class="btn btn-success mt-3">Save Page</button>
        </form>
    </div>

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
