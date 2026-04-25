@extends('backend.layouts.master-layouts')
@section('title', 'Purchase Return')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4>Return Purchase - Invoice #{{ $purchase->bill_no }}</h4>
            <p><strong>Supplier:</strong> {{ $purchase->supplier->name ?? 'N/A' }}</p>
        </div>
        <div class="card-body">
            <form action="{{ route('purchase-return.store') }}" method="POST">
                @csrf
                <input type="hidden" name="purchase_id" value="{{ $purchase->id }}">
                <input type="hidden" name="supplier_id" value="{{ $purchase->supplier_id }}">

                <div class="mb-3">
                    <label for="return_date">Return Date</label>
                    <input type="date" name="return_date" class="form-control" required>
                </div>

                <table class="table table-bordered" id="returnTable">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Purchased Qty</th>
                            <th>Return Qty</th>
                            <th>Rate</th>
                            <th>Return Amount</th>
                            <th>Reason</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($purchase->purchaseDetails as $index => $item)
                            <tr>
                                <td>{{ $item->product->name ?? 'Unknown Product' }}</td>
                                <td>{{ $item->quantity }}</td>
                                <td>
                                    <input type="number" min="0" max="{{ $item->quantity }}" step="any"
                                        name="returns[{{ $index }}][quantity]" class="form-control returnQty"
                                        data-rate="{{ $item->rate }}" data-index="{{ $index }}">
                                </td>
                                <td>{{ number_format($item->rate, 2) }}</td>
                                <td>
                                    <input type="number" name="returns[{{ $index }}][amount]"
                                        class="form-control returnAmount" step="any" readonly>
                                    <input type="hidden" name="returns[{{ $index }}][product_id]"
                                        value="{{ $item->product_id }}">
                                </td>
                                <td>
                                    <input type="text" name="returns[{{ $index }}][reason]" class="form-control">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Submit Return</button>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Select all inputs with class returnQty
            const qtyInputs = document.querySelectorAll('.returnQty');

            qtyInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const qty = parseFloat(this.value) || 0;
                    const rate = parseFloat(this.dataset.rate) || 0;
                    const idx = this.dataset.index;

                    // Calculate amount
                    const amount = (qty * rate).toFixed(2);

                    // Find the matching amount input field
                    const amountInput = document.querySelector(
                        `input[name="returns[${idx}][amount]"]`
                    );
                    if (amountInput) {
                        amountInput.value = amount;
                    }
                });
            });
        });
    </script>
@endsection
