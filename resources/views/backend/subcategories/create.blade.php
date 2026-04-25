@extends('backend.layouts.master-layouts')

@section('content')
    <h4>Add Subcategory</h4>

    <form action="{{ route('subcategories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                <option value="">-- Select Category --</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input name="name" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control"></textarea>
        </div>

        <button class="btn btn-primary mt-2">Save</button>
    </form>
@endsection
