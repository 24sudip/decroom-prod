@extends('backend.layouts.master-layouts')
@section('title', 'Account Heads')

@section('content')
    <div class="container-fluid mt-4">
        <h4>Account Heads</h4>
        <a href="{{ route('account-heads.create') }}" class="btn btn-primary mb-3">+ Add Head</a>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Type</th>
                    <th>Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($heads as $head)
                    <tr>
                        <td>{{ $head->name }}</td>
                        <td>{{ ucfirst($head->type) }}</td>
                        <td>
                            <span class="badge {{ $head->status ? 'bg-success' : 'bg-secondary' }}">
                                {{ $head->status ? 'Active' : 'Inactive' }}
                            </span>
                        </td>
                        <td>
                            <a href="{{ route('account-heads.edit', $head) }}" class="btn btn-sm btn-info">Edit</a>

                            <form action="{{ route('account-heads.toggle', $head->id) }}" method="POST" class="d-inline">
                                @csrf
                                <button type="submit"
                                    class="btn btn-sm {{ $head->status ? 'btn-success' : 'btn-secondary' }}">
                                    {{ $head->status ? 'Active' : 'Inactive' }}
                                </button>
                            </form>

                            <form action="{{ route('account-heads.destroy', $head) }}" method="POST" class="d-inline"
                                onsubmit="return confirm('Are you sure?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4">No account heads found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $heads->links() }}
    </div>
@endsection
