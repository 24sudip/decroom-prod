@extends('backend.layouts.master-layouts')

@section('content')
    <h4>Add Child Category</h4>

    <form action="{{ route('childcategories.store') }}" method="POST">
        @csrf
        <div class="form-group">
            <label>Subcategory</label>
            <select name="subcategory_id" class="form-control" required>
                <option value="">-- Select Subcategory --</option>
                @foreach ($subcategories as $sub)
                    <option value="{{ $sub->id }}">{{ $sub->name }}</option>
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
