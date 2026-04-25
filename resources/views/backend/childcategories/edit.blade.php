@extends('backend.layouts.master-layouts')

@section('content')
    <h4>Edit Child Category</h4>

    <form action="{{ route('childcategories.update', $childcategory->id) }}" method="POST">
        @csrf @method('PUT')
        <div class="form-group">
            <label>Subcategory</label>
            <select name="subcategory_id" class="form-control" required>
                @foreach ($subcategories as $sub)
                    <option value="{{ $sub->id }}" {{ $childcategory->subcategory_id == $sub->id ? 'selected' : '' }}>
                        {{ $sub->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="form-group">
            <label>Name</label>
            <input name="name" value="{{ $childcategory->name }}" class="form-control" required>
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ $childcategory->description }}</textarea>
        </div>

        <button class="btn btn-primary mt-2">Update</button>
    </form>
@endsection
