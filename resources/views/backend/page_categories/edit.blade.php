@extends('backend.layouts.master-layouts')
@section('title', 'Edit Category')

@section('content')
    <div class="container">
        <h4>Edit Category</h4>

        <form action="{{ route('page-categories.update', $pageCategory->id) }}" method="POST">
            @csrf @method('PUT')
            <div class="form-group">
                <label>Category Name</label>
                <input name="name" class="form-control" value="{{ $pageCategory->name }}" required>
            </div>
            <button class="btn btn-primary mt-2">Update</button>
        </form>
    </div>
@endsection
