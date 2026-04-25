@extends('backend.layouts.master-layouts')
@section('title', 'Page Categories')

@section('content')
    <div class="container">
        <h4>Page Categories</h4>
        <a href="{{ route('page-categories.create') }}" class="btn btn-primary mb-3">Add New</a>

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Category Name</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pageCategories as $cat)
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $cat->name }}</td>
                        <td>
                            <a href="{{ route('page-categories.edit', $cat->id) }}" class="btn btn-sm btn-warning">Edit</a>
                            <form action="{{ route('page-categories.destroy', $cat->id) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection
