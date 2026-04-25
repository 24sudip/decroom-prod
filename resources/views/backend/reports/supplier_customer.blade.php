@extends('backend.layouts.master-layouts')

@section('title', 'Supplier & Customer Report')

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
                <h4 class="mb-0">Supplier & Customer Report</h4>
                <small class="text-muted d-block">
                    Date Range:
                    {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
                    to
                    {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
                </small>
            </div>
            <div class="no-print d-flex gap-2">
                <button id="printBtn" class="btn btn-sm btn-primary">Print</button>
                <button id="excelExportBtn" class="btn btn-sm btn-success">Excel</button>
            </div>
        </div>

        <div class="card-body">
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

            <h5 class="mb-3">Supplier Purchases</h5>
            <table class="table table-bordered table-striped" id="supplierPurchaseTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Supplier</th>
                        <th>Total Purchase (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($supplierPurchases as $index => $purchase)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                            <td>{{ number_format($purchase->total_purchase, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="3" class="text-center text-muted">No purchase records found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end" colspan="2">Total Purchase</th>
                        <th>
                            ৳{{ number_format($supplierPurchases->sum('total_purchase'), 2) }}
                        </th>
                    </tr>
                </tfoot>
            </table>

            <h5 class="mt-4 mb-3">Customer Sales</h5>
            <table class="table table-bordered table-striped" id="customerSalesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Customer Name</th>
                        <th>Phone</th>
                        <th>Total Sales (৳)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse ($customerSales as $index => $sale)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $sale->name }}</td>
                            <td>{{ $sale->phone }}</td>
                            <td>{{ number_format($sale->total_sale, 2) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-center text-muted">No sales records found</td>
                        </tr>
                    @endforelse
                </tbody>
                <tfoot>
                    <tr>
                        <th class="text-end" colspan="3">Total Sales</th>
                        <th>
                            ৳{{ number_format($customerSales->sum('total_sale'), 2) }}
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
                <th>Type</th>
                <th>Name</th>
                <th>Phone/Bill No</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($supplierPurchases as $purchase)
                <tr>
                    <td>Purchase</td>
                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                    <td>—</td>
                    <td>{{ $purchase->total_purchase }}</td>
                </tr>
            @endforeach
            @foreach ($customerSales as $sale)
                <tr>
                    <td>Sale</td>
                    <td>{{ $sale->name }}</td>
                    <td>{{ $sale->phone }}</td>
                    <td>{{ $sale->total_sale }}</td>
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
                    title: 'Supplier & Customer Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3]
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
