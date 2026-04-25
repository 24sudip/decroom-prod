@extends('backend.layouts.master-layouts')

@section('title', 'Edit Purchase Return')

@section('content')
    <div class="card" id="printableArea">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h4>Edit Purchase Return - Invoice #{{ $purchaseReturn->purchase->bill_no ?? 'N/A' }}</h4>
        </div>

        <div class="card-body">
            <form action="{{ route('purchase-return.update', $purchaseReturn->id) }}" method="POST">
                @csrf
                @method('PUT')

                <input type="hidden" name="purchase_id" value="{{ $purchaseReturn->purchase_id }}">
                <input type="hidden" name="supplier_id" value="{{ $purchaseReturn->supplier_id }}">

                <div class="mb-3">
                    <label for="return_date">Return Date</label>
                    <input type="date" name="return_date" class="form-control"
                        value="{{ \Carbon\Carbon::parse($purchaseReturn->return_date)->format('Y-m-d') }}" required>
                </div>

                <h5 class="mt-4">Return Items</h5>
                <table class="table table-bordered">
                    <thead>
                        <tr>
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
                                <td>{{ $item->product->name ?? 'N/A' }}</td>
                                <td>
                                    <input type="number" name="returns[{{ $index }}][quantity]"
                                        class="form-control returnQty" value="{{ $item->quantity }}" step="any"
                                        min="0" data-rate="{{ $item->rate }}" data-index="{{ $index }}">
                                </td>
                                <td>{{ number_format($item->rate, 2) }}</td>
                                <td>
                                    <input type="number" name="returns[{{ $index }}][amount]"
                                        class="form-control returnAmount" value="{{ $item->total }}" readonly
                                        step="any">
                                    <input type="hidden" name="returns[{{ $index }}][product_id]"
                                        value="{{ $item->product_id }}">
                                </td>
                                <td>
                                    <input type="text" name="returns[{{ $index }}][reason]" class="form-control"
                                        value="{{ $item->reason }}">
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

                <button type="submit" class="btn btn-primary">Update Return</button>
                <a href="{{ route('purchase-return.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const qtyInputs = document.querySelectorAll('.returnQty');

            qtyInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const qty = parseFloat(this.value) || 0;
                    const rate = parseFloat(this.dataset.rate) || 0;
                    const idx = this.dataset.index;
                    const amount = (qty * rate).toFixed(2);
                    const amountInput = document.querySelector(
                        `input[name="returns[${idx}][amount]"]`);
                    if (amountInput) {
                        amountInput.value = amount;
                    }
                });
            });
        });
    </script>
@endsection
