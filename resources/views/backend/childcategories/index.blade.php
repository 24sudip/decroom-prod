@extends('backend.layouts.master-layouts')

@section('content')
    <h4>Child Categories</h4>
    <a href="{{ route('childcategories.create') }}" class="btn btn-primary mb-2">Add Child Category</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>#</th>
                <th>ID</th>
                <th>Subcategory</th>
                <th>Name</th>
                <th>Slug</th>
                <th>Status</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($childcategories as $key => $row)
                <tr>
                    <td>{{ $key + 1 }}</td>
                    <td>{{ $row->id }}</td>
                    <td>{{ $row->subcategory->name ?? '' }}</td>
                    <td>{{ $row->name }}</td>
                    <td>{{ $row->slug }}</td>
                    <td>
                        @if ($row->status)
                            <a href="{{ route('childcategories.status', $row->id) }}"
                                class="btn btn-sm btn-success">Active</a>
                        @else
                            <a href="{{ route('childcategories.status', $row->id) }}"
                                class="btn btn-sm btn-warning">Inactive</a>
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('childcategories.edit', $row->id) }}" class="btn btn-sm btn-info">Edit</a>
                        <form action="{{ route('childcategories.destroy', $row->id) }}" method="POST"
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
