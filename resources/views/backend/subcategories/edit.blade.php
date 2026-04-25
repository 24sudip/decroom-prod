@extends('backend.layouts.master-layouts')

@section('content')
    <h4>Edit Subcategory</h4>

    <form action="{{ route('subcategories.update', $subcategory->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Category</label>
            <select name="category_id" class="form-control" required>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $subcategory->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input name="name" value="{{ $subcategory->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $subcategory->description }}</textarea>
        </div>

        <button class="btn btn-primary mt-2">Update</button>
    </form>
@endsection
