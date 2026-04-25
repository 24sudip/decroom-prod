@extends('backend.layouts.master-layouts')
@section('title', __('About'))

@section('content')
    <h1>Abouts</h1>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    <table class="table table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Phone</th>
                <th>Address</th>
                <th>Description Top</th>
                <th>Description</th>
                <th>Image</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($aboutInfo as $about)
                <tr>
                    <td>{{ $about->name ?? '' }}</td>
                    <td>{{ $about->phone ?? '' }}</td>
                    <td>{{ $about->address ?? '' }}</td>
                    <td>{{ Str::limit($about->description_top, 50) }}</td>
                    <td>{{ Str::limit($about->description, 100) }}</td>
                    <td>
                        @if ($about->image)
                            <img src="{{ asset('build/images/abouts/' . $about->image ?? 'frontend/images/default.png') }}"
                                alt="{{ $about->name }}" height="50">
                        @endif
                    </td>
                    <td>
                        <a href="{{ route('abouts.edit', $about) }}" class="btn btn-sm btn-warning">Edit</a>

                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

    {{ $aboutInfo->links() }}
@endsection
