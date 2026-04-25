@extends('backend.layouts.master-layouts')

@section('title', 'Stock Report')

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
    <div class="card" id="printableArea">
        <div class="card-header d-flex justify-content-between align-items-center">
            <div>
                <h4 class="mb-0">Stock Report</h4>
                <small class="text-muted">As of Date: {{ now()->toDateString() }}</small>
            </div>
            <div class="no-print d-flex gap-2">
                <button id="printBtn" class="btn btn-sm btn-primary">Print</button>
                <button id="excelExportBtn" class="btn btn-sm btn-success">Excel</button>
            </div>
        </div>

        <div class="card-body">
            {{-- Date filter if needed --}}
            <form method="GET" class="row g-3 align-items-center no-print mb-4">
                <div class="col-md-3">
                    <label>From</label>
                    <input type="date" name="from" class="form-control" value="{{ request('from', $from) }}">
                </div>
                <div class="col-md-3">
                    <label>To</label>
                    <input type="date" name="to" class="form-control" value="{{ request('to', $to) }}">
                </div>
                <div class="col-md-3">
                    <label>&nbsp;</label>
                    <button type="submit" class="btn btn-success w-100">Filter</button>
                </div>
            </form>

            <table class="table table-bordered table-striped" id="stockTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product Code</th>
                        <th>Name</th>
                        <th>Total Stock</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($products as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->product_code }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->total_stock }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No products found.</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end" colspan="3">Total Stock</th>
                        <th>
                            {{ $products->sum('total_stock') }}
                        </th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Hidden table for Excel export --}}
    <table id="exportTable" class="d-none">
        <thead>
            <tr>
                <th>Product Code</th>
                <th>Name</th>
                <th>Total Stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($products as $product)
                <tr>
                    <td>{{ $product->product_code }}</td>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->total_stock }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection

@section('script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>

    <script>
        document.addEventListener("DOMContentLoaded", function() {
            // Print button
            document.getElementById('printBtn').addEventListener('click', function() {
                window.print();
            });

            // Setup hidden DataTable for Excel export (no UI)
            const exportTable = $('#exportTable').DataTable({
                dom: 't',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Stock Report',
                    exportOptions: {
                        columns: [0, 1, 2]
                    }
                }],
                paging: false,
                searching: false,
                info: false
            });

            // Trigger Excel export on button click
            document.getElementById('excelExportBtn').addEventListener('click', function() {
                exportTable.button(0).trigger();
            });
        });
    </script>
@endsection
