@extends('backend.layouts.master-layouts')

@section('title', 'All Supplier Ledgers')

@section('content')
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>All Supplier Ledgers</h4>
        </div>

        <div class="card-body">
            <form method="GET" action="{{ route('supplier.ledger-list') }}" class="row mb-4 g-2">
                <div class="col-md-3">
                    <label for="supplier_id">Supplier</label>
                    <select name="supplier_id" id="supplier_id" class="form-select">
                        <option value="">All Suppliers</option>
                        @foreach (\App\Supplier::all() as $sup)
                            <option value="{{ $sup->id }}" {{ request('supplier_id') == $sup->id ? 'selected' : '' }}>
                                {{ $sup->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="col-md-3">
                    <label for="from_date">From Date</label>
                    <input type="date" name="from_date" id="from_date" class="form-control"
                        value="{{ request('from_date') }}">
                </div>

                <div class="col-md-3">
                    <label for="to_date">To Date</label>
                    <input type="date" name="to_date" id="to_date" class="form-control"
                        value="{{ request('to_date') }}">
                </div>

                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <div class="table-responsive">
                <table class="table table-bordered table-sm">
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Supplier</th>
                            <th>Note</th>
                            <th>Type</th>
                            <th class="text-danger">Debit</th>
                            <th class="text-success">Credit</th>
                            <th>Balance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $balance = 0; @endphp
                        @foreach ($ledgers as $ledger)
                            @php
                                $balance += $ledger->credit - $ledger->debit;
                            @endphp
                            <tr>
                                <td>{{ \Carbon\Carbon::parse($ledger->date)->format('d M Y') }}</td>
                                <td>{{ $ledger->supplier->name ?? 'N/A' }}</td>
                                <td>{{ $ledger->note }}</td>
                                <td>{{ ucfirst($ledger->type) }}</td>
                                <td class="text-danger">{{ $ledger->debit > 0 ? number_format($ledger->debit, 2) : '' }}
                                </td>
                                <td class="text-success">{{ $ledger->credit > 0 ? number_format($ledger->credit, 2) : '' }}
                                </td>
                                <td>{{ number_format($balance, 2) }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="d-flex justify-content-center mt-3">
                {{ $ledgers->appends(request()->query())->links() }}
            </div>
        </div>
    </div>
@endsection
