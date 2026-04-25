@extends('backend.layouts.master-layouts')
@section('title', 'Create Category')

@section('content')
    <div class="container">
        <h4>Create New Category</h4>

        <form action="{{ route('page-categories.store') }}" method="POST">
            @csrf
            <div class="form-group">
                <label>Category Name</label>
                <input name="name" class="form-control" required>
            </div>
            <button class="btn btn-success mt-2">Save</button>
        </form>
    </div>
@endsection
