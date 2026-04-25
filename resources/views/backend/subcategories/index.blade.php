@extends('backend.layouts.master-layouts')

@section('content')
    <h4>Subcategories</h4>
    <a href="{{ route('subcategories.create') }}" class="btn btn-primary mb-2">Add Subcategory</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Category</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($subcategories as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->category->name ?? '' }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->slug }}</td>
                    <td>
                        @if ($row->status)
                            <a href="{{ route('subcategories.status', $row->id) }}" class="btn btn-sm btn-success">Active</a>
                        @else
                            <a href="{{ route('subcategories.status', $row->id) }}"
                                class="btn btn-sm btn-warning">Inactive</a>
                        @endif

                        <a href="{{ route('subcategories.edit', $row->id) }}" class="btn btn-sm btn-info">Edit</a>

                        <form action="{{ route('subcategories.destroy', $row->id) }}" method="POST"
                            style="display:inline-block;">
                            @csrf @method('DELETE')
                            <button onclick="return confirm('Are you sure?')" class="btn btn-sm btn-danger">Del</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
