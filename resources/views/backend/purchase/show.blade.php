@extends('backend.layouts.master-layouts')

@section('title', 'Purchase Details')

@section('content')
    <div class="card" id="printableArea">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Purchase Details - Invoice #{{ $purchase->bill_no }}</h4>
            <button id="printBtn" class="btn btn-primary btn-sm">Print</button>
        </div>
        <div class="card-body">
            <p><strong>Date:</strong> {{ date('d M, Y', strtotime($purchase->purchase_date)) }}</p>
            <p><strong>Supplier:</strong> {{ $purchase->supplier->name ?? 'N/A' }}</p>
            <p><strong>Total Amount:</strong> {{ number_format($purchase->total_bill, 2) }}</p>
            <p><strong>Note:</strong> {{ $purchase->note }}</p>

            <h5 class="mt-4">Purchase Items</h5>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Product</th>
                        <th>Unit</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($purchase->items as $index => $item)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $item->product->name ?? 'N/A' }}</td>
                            <td>{{ $item->product->unit->name ?? '' }}</td>
                            <td>{{ $item->quantity }}</td>
                            <td>{{ number_format($item->rate, 2) }}</td>
                            <td>{{ number_format($item->rate * $item->quantity, 2) }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <a href="{{ route('purchase.index') }}" id="backBtn" class="btn btn-secondary">Back to List</a>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.getElementById('printBtn').addEventListener('click', function() {
            const printBtn = document.getElementById('printBtn');
            const backBtn = document.getElementById('backBtn');

            // Hide buttons
            printBtn.style.display = 'none';
            backBtn.style.display = 'none';

            // Trigger print
            window.print();

            setTimeout(() => {
                printBtn.style.display = 'inline-block';
                backBtn.style.display = 'inline-block';
            }, 1000);
        });
    </script>
@endsection
