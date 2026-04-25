@extends('backend.layouts.master-layouts')
@section('title', 'Edit Account Entry')

@section('content')
    <div class="container-fluid mt-4">
        <h4>Edit Account Entry</h4>

        <a href="{{ route('account-entries.index') }}" class="btn btn-secondary mb-3">← Back</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('account-entries.update', $accountEntry->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="account_head_id" class="form-label">Account Head</label>
                <select name="account_head_id" id="account_head_id" class="form-select" required>
                    <option value="">Select Head</option>
                    @foreach ($heads as $head)
                        <option value="{{ $head->id }}"
                            {{ (old('account_head_id') ?? $accountEntry->account_head_id) == $head->id ? 'selected' : '' }}>
                            {{ $head->name }} ({{ ucfirst($head->type) }})
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="amount" class="form-label">Amount</label>
                <input type="number" step="0.01" name="amount" id="amount" class="form-control"
                    value="{{ old('amount') ?? $accountEntry->amount }}" required>
            </div>

            <div class="mb-3">
                <label for="entry_date" class="form-label">Entry Date</label>
                <input type="date" name="entry_date" id="entry_date" class="form-control"
                    value="{{ old('entry_date') ?? \Carbon\Carbon::parse($accountEntry->entry_date)->format('Y-m-d') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="note" class="form-label">Note</label>
                <textarea name="note" id="editor" class="form-control">{{ old('note') ?? $accountEntry->note }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary">Update Entry</button>
        </form>
    </div>

    <script src="https://cdn.ckeditor.com/ckeditor5/41.0.0/classic/ckeditor.js"></script>
    <script>
        ClassicEditor
            .create(document.querySelector('#editor'))
            .catch(error => {
                console.error(error);
            });
    </script>
@endsection
