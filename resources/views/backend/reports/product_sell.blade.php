@extends('backend.layouts.master-layouts')

@section('title', 'Product Sell Report')

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
            <h2>Product Sell Report</h2>
            <div class="no-print">
                <button class="btn btn-sm btn-primary" id="printBtn">Print</button>
                <button class="btn btn-sm btn-success" id="excelExportBtn">Excel</button>
            </div>
        </div>

        {{-- Filters --}}
        <form method="GET" class="row g-3 align-items-end mb-3 border p-3 bg-light rounded no-print">
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
                <label for="searchInput">Search Product</label>
                <input type="text" id="searchInput" class="form-control" placeholder="Search by name or batch no"
                    value="{{ request('search') }}">
            </div>
            <div class="col-md-3">
                <button type="submit" class="btn btn-success w-100">Filter</button>
            </div>
        </form>

        <p><small class="text-muted d-block">
                Date Range:
                {{ \Carbon\Carbon::parse($from)->format('d M Y') }} to
                {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
            </small></p>

        {{-- Dynamic Report Table --}}
        <div id="productSellTable">
            @include('reports.partials.product_sell_table', ['productSales' => $productSales])
        </div>

        {{-- Export Table --}}
        <table id="exportTable" class="d-none">
            <thead>
                <tr>
                    <th>Product Code</th>
                    <th>Product Name</th>
                    <th>Batch No</th>
                    <th>Quantity</th>
                    <th>Sales (৳)</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($productSales as $sale)
                    <tr>
                        <td>{{ $sale->product->product_code ?? 'N/A' }}</td>
                        <td>{{ $sale->product->name ?? 'N/A' }}</td>
                        <td>{{ $sale->product->batch_no ?? 'N/A' }}</td>
                        <td>{{ $sale->total_qty }}</td>
                        <td>{{ $sale->total_sales }}</td>
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
            // Print
            document.getElementById('printBtn').addEventListener('click', function() {
                window.print();
            });

            // Excel export
            const exportTable = $('#exportTable').DataTable({
                dom: 't',
                buttons: [{
                    extend: 'excelHtml5',
                    title: 'Product Sell Report',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 5]
                    }
                }],
                paging: false,
                searching: false,
                info: false
            });

            document.getElementById('excelExportBtn').addEventListener('click', function() {
                exportTable.button(0).trigger();
            });

            // AJAX search
            let timer;
            document.getElementById('searchInput').addEventListener('keyup', function() {
                clearTimeout(timer);
                const search = this.value;
                const from = document.getElementById('from').value;
                const to = document.getElementById('to').value;

                timer = setTimeout(() => {
                    fetch(`{{ route('reports.productSell') }}?from=${from}&to=${to}&search=${search}`, {
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest'
                            }
                        })
                        .then(response => response.text())
                        .then(html => {
                            document.getElementById('productSellTable').innerHTML = html;
                        });
                }, 300);
            });
        });
    </script>
@endsection
