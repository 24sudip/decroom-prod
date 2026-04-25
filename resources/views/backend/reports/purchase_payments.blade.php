@extends('backend.layouts.master-layouts')

@section('title', 'Supplier Purchase Payments')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
    <style>
        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Supplier Ledger Report</h2>
            <div class="no-print">
                <button class="btn btn-sm btn-primary" id="printBtn">Print</button>
                <button class="btn btn-sm btn-success" id="excelExportBtn">Excel</button>
            </div>
        </div>

        {{-- Filter --}}
        <form method="GET" class="row g-3 align-items-end mb-4 border p-3 bg-light rounded no-print">
            <div class="col-md-3">
                <label for="from">From</label>
                <input type="date" name="from" class="form-control" value="{{ request('from', $from) }}">
            </div>
            <div class="col-md-3">
                <label for="to">To</label>
                <input type="date" name="to" class="form-control" value="{{ request('to', $to) }}">
            </div>
            <div class="col-md-3">
                <label for="supplier_id">Supplier</label>
                <select name="supplier_id" class="form-control">
                    <option value="">All Suppliers</option>
                    @foreach ($allSuppliers as $supplier)
                        <option value="{{ $supplier->id }}"
                            {{ request('supplier_id', $supplierId) == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100">Filter</button>
            </div>
        </form>


        <p><small class="text-muted d-block">
                Date Range:
                {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
                to
                {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
            </small></p>

        <div id="printableArea">
            <table class="table table-bordered table-striped">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Type</th>
                        <th>Reference</th>
                        <th>Debit (৳)</th>
                        <th>Credit (৳)</th>
                        <th>Note</th>
                    </tr>
                </thead>
                <tbody>
                    @php
                        $totalDebit = 0;
                        $totalCredit = 0;
                    @endphp
                    @forelse ($ledgers as $index => $entry)
                        @php
                            $totalDebit += $entry->debit;
                            $totalCredit += $entry->credit;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($entry->date)->format('Y-m-d') }}</td>
                            <td>{{ $entry->supplier->name ?? 'N/A' }}</td>
                            <td>{{ ucfirst($entry->type) }}</td>
                            <td>{{ $entry->ref_id ?? '—' }}</td>
                            <td>{{ number_format($entry->debit, 2) }}</td>
                            <td>{{ number_format($entry->credit, 2) }}</td>
                            <td>{{ $entry->note }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="text-center text-muted">No ledger records found for the selected date
                                range.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="5" class="text-end">Total</th>
                        <th>৳{{ number_format($totalDebit, 2) }}</th>
                        <th>৳{{ number_format($totalCredit, 2) }}</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>

        {{-- Hidden export table --}}
        <table id="exportTable" class="d-none">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Supplier</th>
                    <th>Type</th>
                    <th>Ref</th>
                    <th>Debit</th>
                    <th>Credit</th>
                    <th>Note</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($ledgers as $entry)
                    <tr>
                        <td>{{ \Carbon\Carbon::parse($entry->date)->format('Y-m-d') }}</td>
                        <td>{{ $entry->supplier->name ?? 'N/A' }}</td>
                        <td>{{ ucfirst($entry->type) }}</td>
                        <td>{{ $entry->ref_id }}</td>
                        <td>{{ $entry->debit }}</td>
                        <td>{{ $entry->credit }}</td>
                        <td>{{ $entry->note }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            document.getElementById('printBtn').addEventListener('click', function() {
                window.print();
            });

            const exportTable = $('#exportTable').DataTable({
                dom: 't',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Supplier Ledger Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5, 6]
                    }
                }],
                paging: false,
                searching: false,
                info: false
            });

            document.getElementById('excelExportBtn').addEventListener('click', function() {
                exportTable.button(0).trigger();
            });
        });
    </script>
@endsection
