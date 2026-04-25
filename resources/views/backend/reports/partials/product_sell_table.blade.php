<table class="table table-bordered table-striped">
    <thead class="table-light">
        <tr>
            <th>#</th>
            <th>Product Code</th>
            <th>Product Name</th>
            <th>Batch No</th>
            <th>Total Sold Qty</th>
            <th>Total Sales (৳)</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($productSales as $index => $sale)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $sale->product->product_code ?? 'N/A' }}</td>
                <td>{{ $sale->product->name ?? 'N/A' }}</td>
                <td>{{ $sale->product->batch_no ?? 'N/A' }}</td>
                <td>{{ $sale->total_qty }}</td>
                <td>{{ number_format($sale->total_sales, 2) }}</td>
            </tr>
        @empty
            <tr>
                <td colspan="6" class="text-center text-muted">No sales data found.</td>
            </tr>
        @endforelse
    </tbody>
    <tfoot>
        <tr>
            <th colspan="4" class="text-end">Total</th>
            <th>{{ $productSales->sum('total_qty') }}</th>
            <th>৳{{ number_format($productSales->sum('total_sales'), 2) }}</th>
        </tr>
    </tfoot>
</table>
