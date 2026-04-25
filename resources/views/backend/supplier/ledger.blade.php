@extends('backend.layouts.master-layouts')

@section('title', 'Supplier Ledger')

@section('content')
    <div class="card" id="printableArea">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Ledger for {{ $supplier->name }}</h4>
            <button id="printBtn" class="btn btn-primary btn-sm">Print</button>
        </div>
        <div class="card-body">
            <table class="table table-bordered table-sm">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Note</th>
                        <th>Debit</th>
                        <th>Credit</th>
                        <th>Balance</th>
                    </tr>
                </thead>
                <tbody>
                    @php $balance = 0; @endphp
                    @foreach ($ledgers as $entry)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($entry->date)->format('d M Y') }}</td>
                            <td>{{ ucfirst($entry->type) }}</td>
                            <td>{{ $entry->note }}</td>
                            <td class="text-danger">{{ $entry->debit > 0 ? number_format($entry->debit, 2) : '' }}</td>
                            <td class="text-success">{{ $entry->credit > 0 ? number_format($entry->credit, 2) : '' }}</td>
                            <td>{{ number_format($entry->running_balance, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('printBtn').addEventListener('click', function() {
            const printBtn = document.getElementById('printBtn');

            // Hide the print button
            printBtn.style.display = 'none';

            // Trigger print of the printableArea div only
            window.print();

            // Show the print button again after print dialog
            setTimeout(() => {
                printBtn.style.display = 'inline-block';
            }, 1000);
        });
    </script>
@endsection
