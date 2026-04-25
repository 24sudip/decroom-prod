@extends('backend.layouts.master-layouts')

@section('title', 'Supplier Payments')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>All Supplier Payments</h4>
            <a href="{{ route('supplier_payment.create') }}" class="btn btn-primary btn-sm">Add Payment</a>
        </div>

        <div class="card-body">

            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if ($payments->count())
                <div class="table-responsive">
                    <table class="table table-bordered table-sm table-striped" id="paymentTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Date</th>
                                <th>Supplier</th>
                                <th>Amount</th>
                                <th>Method</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($payments as $index => $payment)
                                <tr>
                                    <td>{{ $index + 1 }}</td>
                                    <td>{{ \Carbon\Carbon::parse($payment->payment_date)->format('d M Y') }}</td>
                                    <td>{{ $payment->supplier->name ?? 'N/A' }}</td>
                                    <td>{{ number_format($payment->amount, 2) }}</td>
                                    <td>{{ $payment->payment_method ?? '-' }}</td>
                                    <td>{{ $payment->note ?? '-' }}</td>
                                    <td>
                                        {{-- Future Edit/Delete Options --}}
                                        {{-- <a href="{{ route('supplier_payment.edit', $payment->id) }}" class="btn btn-sm btn-warning">Edit</a> --}}
                                        {{-- <button class="btn btn-sm btn-danger delete-btn" data-id="{{ $payment->id }}">Delete</button> --}}
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    <div class="mt-3">
                        {{ $payments->links() }}
                    </div>
                </div>
            @else
                <div class="alert alert-info">No supplier payments found.</div>
            @endif

        </div>
    </div>
@endsection
