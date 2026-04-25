@extends('backend.layouts.master-layouts')
@section('title', 'Account Entries')

@section('content')
    <div class="container-fluid mt-4">
        <h4>Account Entries</h4>

        <a href="{{ route('account-entries.create') }}" class="btn btn-success mb-3">+ Add Entry</a>

        {{-- Filter form --}}
        <form method="GET" action="{{ route('account-entries.index') }}" class="mb-3 row g-3">
            <div class="col-auto">
                <input type="date" name="start_date" class="form-control" value="{{ request('start_date') }}"
                    placeholder="Start Date">
            </div>
            <div class="col-auto">
                <input type="date" name="end_date" class="form-control" value="{{ request('end_date') }}"
                    placeholder="End Date">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Filter</button>
                <a href="{{ route('account-entries.index') }}" class="btn btn-secondary">Reset</a>
            </div>
        </form>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Account Head</th>
                    <th>Amount</th>
                    <th>Entry Date</th>
                    <th>Note</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                @forelse($entries as $entry)
                    <tr>
                        <td>{{ $loop->iteration + ($entries->currentPage() - 1) * $entries->perPage() }}</td>
                        <td>{{ $entry->accountHead->name ?? '-' }}</td>
                        <td>{{ number_format($entry->amount, 2) }}</td>
                        <td>{{ \Carbon\Carbon::parse($entry->entry_date)->format('d M, Y') }}</td>
                        <td>{{ $entry->note ?? '-' }}</td>
                        <td>
                            <a href="{{ route('account-entries.edit', $entry->id) }}"
                                class="btn btn-sm btn-primary">Edit</a>

                            <form action="{{ route('account-entries.destroy', $entry->id) }}" method="POST"
                                class="d-inline" onsubmit="return confirm('Are you sure?');">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-danger">Delete</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6">No entries found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        {{ $entries->links() }}
    </div>
@endsection
