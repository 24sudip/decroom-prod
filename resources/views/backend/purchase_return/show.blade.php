@extends('backend.layouts.master-layouts')

@section('title', 'Purchase Return Details')

@section('content')
    <div class="card" id="printableArea">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Purchase Return - Invoice #{{ $purchaseReturn->purchase->bill_no ?? 'N/A' }}</h4>
            <button id="printBtn" class="btn btn-primary btn-sm">Print</button>
        </div>
        <div class="card-body">
            <p><strong>Return Date:</strong> {{ \Carbon\Carbon::parse($purchaseReturn->return_date)->format('d M, Y') }}</p>
            <p><strong>Supplier:</strong> {{ $purchaseReturn->supplier->name ?? 'N/A' }}</p>
            <p><strong>Return Amount:</strong> {{ number_format($purchaseReturn->total_return_amt, 2) }}</p>

            <h5 class="mt-4">Returned Items</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Total</th>
                        <th>Reason</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchaseReturn->details as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->rate, 2) }}</td>
                            <td>{{ number_format($item->total, 2) }}</td>
                            <td>{{ $item->reason ?? '-' }}</td>
                        </tr>
                    @endforeach
                    @if ($purchaseReturn->details->isEmpty())
                        <tr>
                            <td colspan="6" class="text-center text-muted">No return items found.</td>
                        </tr>
                    @endif
                </tbody>
            </table>

            <a href="{{ route('purchase-return.index') }}" id="backBtn" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('printBtn').addEventListener('click', function() {
            const printBtn = document.getElementById('printBtn');
            const backBtn = document.getElementById('backBtn');

            // Hide buttons for print
            printBtn.style.display = 'none';
            backBtn.style.display = 'none';

            window.print();

            // Restore buttons after printing
            setTimeout(() => {
                printBtn.style.display = 'inline-block';
                backBtn.style.display = 'inline-block';
            }, 1000);
        });
    </script>
@endsection
