@extends('backend.layouts.master-layouts')
@section('title', 'Edit Account Head')

@section('content')
    <div class="container-fluid mt-4">
        <h4>Edit Account Head</h4>

        <a href="{{ route('account-heads.index') }}" class="btn btn-secondary mb-3">← Back</a>

        @if ($errors->any())
            <div class="alert alert-danger">
                <ul class="mb-0">
                    @foreach ($errors->all() as $err)
                        <li>{{ $err }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('account-heads.update', $accountHead->id) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="name" class="form-label">Head Name</label>
                <input type="text" name="name" class="form-control" value="{{ old('name', $accountHead->name) }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="income" {{ $accountHead->type === 'income' ? 'selected' : '' }}>Income</option>
                    <option value="expenditure" {{ $accountHead->type === 'expenditure' ? 'selected' : '' }}>Expenditure
                    </option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Update Head</button>
        </form>
    </div>
@endsection
