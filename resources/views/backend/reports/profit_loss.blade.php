@extends('backend.layouts.master-layouts')

@section('title', 'Profit / Loss Report')

@section('css')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
    <style>
        @media print {
            .no-print {
                display: none !important;
            }

            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }

            .card {
                border: none !important;
            }

            .card-header {
                text-align: center;
                border-bottom: 1px solid #ccc !important;
            }
        }
    </style>
@endsection

@section('content')
    <div class="container-fluid">
        <!-- Title & Actions -->
        <div class="d-flex justify-content-between align-items-center mb-3">
            <h2>Profit / Loss Report</h2>
            <div class="no-print">
                <button class="btn btn-sm btn-primary" id="printBtn">Print</button>
                <button class="btn btn-sm btn-success" id="excelExportBtn">Excel</button>
            </div>
        </div>

        <!-- Filter -->
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
                <button type="submit" class="btn btn-success w-100">Filter</button>
            </div>
        </form>

        <p><small class="text-muted d-block">
                Date Range:
                {{ \Carbon\Carbon::parse($from)->format('d M Y') }}
                to
                {{ \Carbon\Carbon::parse($to)->format('d M Y') }}
            </small></p>

        <!-- Report -->
        <div class="card" id="printableArea">
            <div class="card-body">
                <table class="table table-bordered">
                    <tr>
                        <th>Total Sales</th>
                        <td>৳{{ number_format($totalSales, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Total Purchases</th>
                        <td>৳{{ number_format($totalPurchases, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Purchase Returns</th>
                        <td>৳{{ number_format($totalPurchaseReturns, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Other Incomes</th>
                        <td>৳{{ number_format($income, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Expenses</th>
                        <td>৳{{ number_format($expense, 2) }}</td>
                    </tr>
                    <tr>
                        <th>Net Profit / Loss</th>
                        <td>
                            <strong class="{{ $profitOrLoss < 0 ? 'text-danger' : 'text-success' }}">
                                ৳{{ number_format($profitOrLoss, 2) }}
                            </strong>
                        </td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Hidden Export Table -->
        <table id="exportTable" class="d-none">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Total Sales</td>
                    <td>{{ $totalSales }}</td>
                </tr>
                <tr>
                    <td>Total Purchases</td>
                    <td>{{ $totalPurchases }}</td>
                </tr>
                <tr>
                    <td>Purchase Returns</td>
                    <td>{{ $totalPurchaseReturns }}</td>
                </tr>
                <tr>
                    <td>Other Incomes</td>
                    <td>{{ $income }}</td>
                </tr>
                <tr>
                    <td>Expenses</td>
                    <td>{{ $expense }}</td>
                </tr>
                <tr>
                    <td>Net Profit / Loss</td>
                    <td>{{ $profitOrLoss }}</td>
                </tr>
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
                    title: 'Profit Loss Report',
                    exportOptions: {
                        columns: [0, 1]
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
