@extends('backend.layouts.master-layouts')

@section('title', 'Add Supplier Payment')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Add Supplier Payment</h4>
            <a href="{{ route('supplier_payment.index') }}" class="btn btn-secondary btn-sm">Back to List</a>
        </div>
        <div class="card-body">
            @if ($errors->any())
                <div class="alert alert-danger mb-3">
                    <ul class="mb-0">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('supplier_payment.store') }}" method="POST">
                @csrf

                <div class="mb-3">
                    <label for="supplier_id" class="form-label">Supplier <span class="text-danger">*</span></label>
                    <select name="supplier_id" id="supplier_id" class="form-select" required>
                        <option value="">-- Select Supplier --</option>
                        @foreach ($suppliers as $supplier)
                            <option value="{{ $supplier->id }}" {{ old('supplier_id') == $supplier->id ? 'selected' : '' }}>
                                {{ $supplier->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="payment_date" class="form-label">Payment Date <span class="text-danger">*</span></label>
                    <input type="date" name="payment_date" id="payment_date" class="form-control"
                        value="{{ old('payment_date', date('Y-m-d')) }}" required>
                </div>

                <div class="mb-3">
                    <label for="amount" class="form-label">Amount <span class="text-danger">*</span></label>
                    <input type="number" name="amount" id="amount" step="0.01" class="form-control"
                        value="{{ old('amount') }}" required>
                </div>

                <div class="mb-3">
                    <label for="payment_method" class="form-label">Payment Method</label>
                    <input type="text" name="payment_method" id="payment_method" class="form-control"
                        placeholder="e.g., Cash, Bank Transfer" value="{{ old('payment_method') }}">
                </div>

                <div class="mb-3">
                    <label for="note" class="form-label">Note</label>
                    <input type="text" name="note" id="note" class="form-control" placeholder="Optional note"
                        value="{{ old('note') }}">
                </div>

                <button type="submit" class="btn btn-primary">Save Payment</button>
            </form>
        </div>
    </div>
@endsection
