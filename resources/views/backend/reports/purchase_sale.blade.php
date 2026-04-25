@extends('backend.layouts.master-layouts')

@section('title', 'Purchase & Sale Report')

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
                <h4 class="mb-0">Purchase & Sale Report</h4>
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

            <h5 class="mb-3">Purchase Records</h5>
            <table class="table table-bordered table-striped" id="purchaseTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Supplier</th>
                        <th>Bill No</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchases as $index => $purchase)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                            <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                            <td>{{ $purchase->bill_no }}</td>
                            <td>৳{{ number_format($purchase->total_bill, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Purchase</th>
                        <th>৳{{ number_format($totalPurchaseAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>

            <h5 class="mt-4 mb-3">Sale Records</h5>
            <table class="table table-bordered table-striped" id="saleTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Date</th>
                        <th>Customer</th>
                        <th>Phone</th>
                        <th>Total</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($sales as $index => $order)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                            <td>{{ $order->name }}</td>
                            <td>{{ $order->phone }}</td>
                            <td>৳{{ number_format($order->total_amount, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="4" class="text-end">Total Sales</th>
                        <th>৳{{ number_format($totalSaleAmount, 2) }}</th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </div>

    {{-- Hidden Export Table --}}
    <table id="exportTable" class="d-none">
        <thead>
            <tr>
                <th>Type</th>
                <th>Date</th>
                <th>Name</th>
                <th>Phone/Bill</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($purchases as $purchase)
                <tr>
                    <td>Purchase</td>
                    <td>{{ \Carbon\Carbon::parse($purchase->purchase_date)->format('d M Y') }}</td>
                    <td>{{ $purchase->supplier->name ?? 'N/A' }}</td>
                    <td>{{ $purchase->bill_no }}</td>
                    <td>{{ $purchase->total_bill }}</td>
                </tr>
            @endforeach
            @foreach ($sales as $order)
                <tr>
                    <td>Sale</td>
                    <td>{{ \Carbon\Carbon::parse($order->created_at)->format('d M Y') }}</td>
                    <td>{{ $order->name }}</td>
                    <td>{{ $order->phone }}</td>
                    <td>{{ $order->total_amount }}</td>
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
            // Handle Print
            document.getElementById('printBtn').addEventListener('click', function() {
                window.print();
            });

            // Prevent visible Excel button in hidden DataTable
            const exportTable = $('#exportTable').DataTable({
                dom: 't', // Don't render any visible button UI
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Purchase & Sale Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4]
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
