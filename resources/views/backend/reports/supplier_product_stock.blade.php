@extends('backend.layouts.master-layouts')

@section('title', 'Supplier Wise Product Stock Report')

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
            <h2>Supplier Wise Product Stock Report</h2>
            <div class="no-print">
                <button class="btn btn-sm btn-primary" id="printBtn">Print</button>
                <button class="btn btn-sm btn-success" id="excelExportBtn">Excel</button>
            </div>
        </div>

        {{-- Date range filter --}}
        <form method="GET" class="row g-3 align-items-end mb-4 border p-3 bg-light rounded no-print">
            <div class="col-md-3">
                <label for="from">From Date</label>
                <input type="date" name="from" id="from" class="form-control"
                    value="{{ request('from', $from) }}">
            </div>

            <div class="col-md-3">
                <label for="to">To Date</label>
                <input type="date" name="to" id="to" class="form-control" value="{{ request('to', $to) }}">
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
            @forelse ($supplierProducts as $item)
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="mb-0">{{ $item['supplier']->name ?? 'N/A Supplier' }}</h5>
                    </div>
                    <div class="card-body p-0">
                        <table class="table table-bordered mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th style="width: 20%">Product Code</th>
                                    <th>Product Name</th>
                                    <th style="width: 15%">Current Stock</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($item['products'] as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->product_code }}</td>
                                        <td>{{ $product->name }}</td>
                                        <td>{{ $product->stock }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            @empty
                <div class="alert alert-warning">No supplier product stock data found for the selected date range.</div>
            @endforelse
        </div>

        {{-- Hidden export table --}}
        <table id="exportTable" class="d-none">
            <thead>
                <tr>
                    <th>Supplier</th>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Stock</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($supplierProducts as $item)
                    @foreach ($item['products'] as $product)
                        <tr>
                            <td>{{ $item['supplier']->name ?? 'N/A' }}</td>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->stock }}</td>
                        </tr>
                    @endforeach
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
            // Print
            document.getElementById('printBtn').addEventListener('click', function() {
                window.print();
            });

            // Excel export
            const exportTable = $('#exportTable').DataTable({
                dom: 't',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Supplier Wise Product Stock Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
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
