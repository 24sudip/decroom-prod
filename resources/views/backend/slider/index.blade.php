@extends('backend.layouts.master-layouts')

@section('content')
    <a href="{{ route('slider.create') }}" class="btn btn-success mb-3">Add New Slider</a>

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Image</th>
                <th>Title</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($sliders as $slider)
                <tr>
                    <td><img src="{{ asset('storage/sliders/' . $slider->image) }}" width="100"></td>
                    <td>{{ $slider->title }}</td>
                    <td>{{ $slider->status ? 'Active' : 'Inactive' }}</td>
                    <td class="d-flex gap-2">
                        <!-- Edit Button -->
                        <a href="{{ route('slider.edit', $slider->id) }}" class="btn btn-primary btn-sm">Edit</a>

                        <!-- Toggle Status Button -->
                        <form action="{{ route('slider.toggleStatus', $slider->id) }}" method="POST"
                            onsubmit="return confirm('Are you sure you want to {{ $slider->status ? 'deactivate' : 'activate' }} this slider?')">
                            @csrf
                            @method('PATCH')
                            <button type="submit" class="btn btn-sm {{ $slider->status ? 'btn-warning' : 'btn-success' }}">
                                {{ $slider->status ? 'Deactivate' : 'Activate' }}
                            </button>
                        </form>

                        <!-- Delete Button -->
                        <form action="{{ route('slider.destroy', $slider->id) }}" method="POST"
                            onsubmit="return confirm('Delete this slider?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
