@extends('backend.layouts.master-layouts')
@section('title', 'Edit Page')

@section('content')
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h4>Edit Page</h4>
            <a href="{{ route('pages.index') }}" class="btn btn-secondary">← Back to Page List</a>
        </div>

        <form action="{{ route('pages.update', $page->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label>Title</label>
                <input name="title" class="form-control" value="{{ old('title', $page->title) }}" required>
            </div>

            <div class="form-group">
                <label>Category</label>
                <select name="category_id" class="form-control">
                    <option value="">None</option>
                    @foreach ($pageCategories as $cat)
                        <option value="{{ $cat->id }}"
                            {{ old('category_id', $page->category_id) == $cat->id ? 'selected' : '' }}>
                            {{ $cat->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Content</label>
                <!-- NOTE: id="editor" is required for initialization -->
                <textarea id="editor" name="content" rows="10" class="form-control">{!! old('content', $page->content) !!}</textarea>
            </div>

            <div class="form-check mt-2">
                <input type="checkbox" name="status" class="form-check-input"
                    {{ old('status', $page->status) ? 'checked' : '' }}>
                <label class="form-check-label">Active</label>
            </div>

            <button class="btn btn-primary mt-3">Update Page</button>
        </form>
    </div>

    <!-- Include CKEditor directly here so it always loads -->
    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        // Safe guard: ensure the element exists before creating the editor
        const textarea = document.querySelector('#editor');
        if (textarea) {
            ClassicEditor
                .create(textarea)
                .catch(error => {
                    console.error('CKEditor init error:', error);
                });
        } else {
            console.error('Editor element not found: #editor');
        }
    </script>
@endsection
