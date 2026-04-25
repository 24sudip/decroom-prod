@extends('backend.layouts.master-layouts')
@section('title', 'Add Account Head')

@section('content')
    <div class="container-fluid mt-4">
        <h4>Add Account Head</h4>
        <form action="{{ route('account-heads.store') }}" method="POST">
            @csrf
            <div class="mb-3">
                <label for="name" class="form-label">Head Name</label>
                <input type="text" name="name" class="form-control" required>
            </div>
            <div class="mb-3">
                <label for="type" class="form-label">Type</label>
                <select name="type" class="form-select" required>
                    <option value="income">Income</option>
                    <option value="expenditure">Expenditure</option>
                </select>
            </div>
            <button type="submit" class="btn btn-primary">Add Head</button>
        </form>
    </div>
@endsection
