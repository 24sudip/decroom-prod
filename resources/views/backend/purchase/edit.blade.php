@extends('backend.layouts.master-layouts')

@section('title', 'Edit Purchase')

@php
    use Carbon\Carbon;
@endphp

@section('content')
    <div class="container-fluid">
        <h3>Edit Purchase #{{ $purchase->id }}</h3>

        <form method="POST" action="{{ route('purchase.update', $purchase->id) }}" id="purchaseForm">
            @csrf
            @method('PUT')

            <div class="mb-3">
                <label for="supplier_id" class="form-label">Supplier</label>
                <select name="supplier_id" id="supplier_id" class="form-control select2" required>
                    <option value="">-- Select Supplier --</option>
                    @foreach ($suppliers as $supplier)
                        <option value="{{ $supplier->id }}" {{ $purchase->supplier_id == $supplier->id ? 'selected' : '' }}>
                            {{ $supplier->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-3">
                <label for="purchase_date" class="form-label">Purchase Date</label>
                <input type="text" name="purchase_date" id="purchase_date" class="form-control datepicker"
                    value="{{ old('purchase_date', $purchase->purchase_date ? Carbon::parse($purchase->purchase_date)->format('Y-m-d') : '') }}"
                    required>
            </div>

            <div class="mb-3">
                <label for="bill_no" class="form-label">Bill No</label>
                <input type="text" name="bill_no" id="bill_no" class="form-control"
                    value="{{ old('bill_no', $purchase->bill_no) }}" required>
            </div>

            <div class="mb-3">
                <label for="bill_date" class="form-label">Bill Date</label>
                <input type="text" name="bill_date" id="bill_date" class="form-control datepicker"
                    value="{{ old('bill_date', $purchase->bill_date ? Carbon::parse($purchase->bill_date)->format('Y-m-d') : '') }}"
                    required>
            </div>

            <hr>

            <h5>Purchase Items</h5>
            <table class="table table-bordered" id="purchaseDetailsTable">
                <thead>
                    <tr>
                        <th>Product</th>
                        <th>Batch No</th>
                        <th>Quantity</th>
                        <th>Rate</th>
                        <th>Tax Rate (%)</th>
                        <th>Total</th>
                        <th>Manufacturer Date</th>
                        <th>Expire Date</th>
                        <th>Unit</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="pur_rows">
                    @foreach ($purchase->purchaseDetails as $detail)
                        <tr data-id="{{ $detail->id }}">
                            <td>
                                <select name="purchase_details[{{ $detail->id }}][product_id]"
                                    class="form-control select2 product_select" required>
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-unit="{{ $product->unit->name ?? '' }}"
                                            data-batchno="{{ $product->batch_no }}"
                                            data-pur="{{ $product->purchase_price }}"
                                            data-stock="{{ $product->stock ?? 0 }}"
                                            {{ $detail->product_id == $product->id ? 'selected' : '' }}>
                                            {{ $product->name }} (Batch: {{ $product->batch_no }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td><input type="text" name="purchase_details[{{ $detail->id }}][batch_no]"
                                    class="form-control batch_no" step="any"
                                    value="{{ old('batch_no', $detail->product->batch_no) }}"></td>
                            <td><input type="number" name="purchase_details[{{ $detail->id }}][quantity]"
                                    class="form-control quantity" step="any"
                                    value="{{ old('quantity', $detail->quantity) }}" required></td>
                            <td><input type="number" name="purchase_details[{{ $detail->id }}][rate]"
                                    class="form-control rate" step="any" value="{{ old('rate', $detail->rate) }}"
                                    required></td>
                            <td><input type="number" name="purchase_details[{{ $detail->id }}][tax_rate]"
                                    class="form-control tax_rate" step="any"
                                    value="{{ old('tax_rate', $detail->tax_rate) }}"></td>
                            <td><input type="text" name="purchase_details[{{ $detail->id }}][total]"
                                    class="form-control total" value="{{ number_format($detail->total, 2) }}" readonly>
                            </td>
                            <td><input type="text" name="purchase_details[{{ $detail->id }}][manufacturer_date]"
                                    class="form-control datepicker manufacturer_date"
                                    value="{{ $detail->manufacturer_date ? Carbon::parse($detail->manufacturer_date)->format('Y-m-d') : '' }}">
                            </td>
                            <td><input type="text" name="purchase_details[{{ $detail->id }}][expire_date]"
                                    class="form-control datepicker expire_date"
                                    value="{{ $detail->expire_date ? Carbon::parse($detail->expire_date)->format('Y-m-d') : '' }}">
                            </td>
                            <td><span class="unitname">{{ $detail->product->unit->name ?? '' }}</span></td>
                            <td>
                                <button type="button" class="btn btn-danger btn-sm delete_row">Delete</button>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <h5>Add New Item</h5>
            <div class="row g-3 align-items-center mb-3">
                <table>
                    <tbody>
                        <tr>
                            <td>
                                <select id="product_id" name="new_product_id" class="form-control select2">
                                    <option value="">-- Select Product --</option>
                                    @foreach ($products as $product)
                                        <option value="{{ $product->id }}" data-unit="{{ $product->unit->name ?? '' }}"
                                            data-batchno="{{ $product->batch_no }}"
                                            data-pur="{{ $product->purchase_price }}"
                                            data-stock="{{ $product->stock ?? 0 }}" data-pname="{{ $product->name }}">
                                            {{ $product->name }} (Batch: {{ $product->batch_no }})
                                        </option>
                                    @endforeach
                                </select>
                            </td>
                            <td>
                                <input type="text" id="batch_no" placeholder="batch_no" class="form-control batch_no"
                                    step="any">
                            </td>
                            <td>
                                <input type="number" id="quantity" placeholder="Qty" class="form-control"
                                    step="any">
                            </td>
                            <td>
                                <input type="number" id="rate" placeholder="Rate" class="form-control"
                                    step="any">
                            </td>
                            <td>
                                <input type="number" id="tax_rate" placeholder="Tax %" class="form-control"
                                    step="any">
                            </td>
                            <td>
                                <input type="text" id="total" placeholder="Total" class="form-control" readonly>
                            </td>
                            <td>
                                <input type="text" id="manufacturer_date" placeholder="Mfg Date"
                                    class="form-control datepicker">
                            </td>
                            <td>
                                <input type="text" id="expire_date" placeholder="Expire Date"
                                    class="form-control datepicker">
                            </td>
                            <td>
                                <button type="button" class="btn btn-success" id="add_row">Add</button>
                            </td>
                        </tr>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td>
                                <button type="submit" class="btn btn-primary">Update Purchase</button>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </form>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $(document).ready(function() {
            $('.select2').select2();
            $('.datepicker').datepicker({
                format: "yyyy-mm-dd",
                autoclose: true,
                todayHighlight: true
            });

            // Calculate row total when quantity, rate or tax is changed
            $('#purchaseDetailsTable').on('input', '.quantity, .rate, .tax_rate', function() {
                let row = $(this).closest('tr');
                let qty = parseFloat(row.find('.quantity').val()) || 0;
                let rate = parseFloat(row.find('.rate').val()) || 0;
                let tax = parseFloat(row.find('.tax_rate').val()) || 0;
                let total = qty * rate;
                let tax_amt = total * tax / 100;
                row.find('.total').val((total + tax_amt).toFixed(2));
            });

            // Update unit & batch no on product change inside existing row
            $('#purchaseDetailsTable').on('change', '.product_select', function() {
                let row = $(this).closest('tr');
                let selected = $(this).find(':selected');
                let unit = selected.data('unit') || '';
                let batch_no = selected.data('batchno') || '';
                row.find('.unitname').text(unit);
                row.find('.batch_no').val(batch_no);
            });

            // Auto calculate total in new add row inputs
            function calculateAddTotal() {
                let qty = parseFloat($('#quantity').val()) || 0;
                let rate = parseFloat($('#rate').val()) || 0;
                let tax = parseFloat($('#tax_rate').val()) || 0;
                let total = qty * rate;
                let tax_amt = total * tax / 100;
                $('#total').val((total + tax_amt).toFixed(2));
            }

            $('#quantity, #rate, #tax_rate').on('input', calculateAddTotal);

            // Add new row
            $('#add_row').click(function() {
                let product_id = $('#product_id').val();
                if (!product_id) return alert('Select Product!');

                let exists = false;
                $('#pur_rows tr').each(function() {
                    let existingProductId = $(this).find('input[name$="[product_id]"]').val();
                    if (existingProductId == product_id) {
                        exists = true;
                        return false;
                    }
                });
                if (exists) return alert('This product is already added!');

                let quantity = parseFloat($('#quantity').val()) || 0;
                if (quantity <= 0) return alert('Enter a valid quantity');

                let rate = parseFloat($('#rate').val()) || 0;
                if (rate <= 0) return alert('Enter a valid rate');

                let tax_rate = parseFloat($('#tax_rate').val()) || 0;
                let total = parseFloat($('#total').val()) || 0;
                if (total <= 0) return alert('Total must be greater than zero');

                let selected = $('#product_id').find(':selected');
                let productName = selected.data('pname');
                let batch_no = selected.data('batchno') || '';
                let unit = selected.data('unit') || '';
                let manufacturer_date = $('#manufacturer_date').val();
                let expire_date = $('#expire_date').val();

                let newId = 'new_' + Date.now();

                let newRow = `
                    <tr data-id="${newId}">
                        <td>
                            <input type="hidden" name="purchase_details[${newId}][product_id]" value="${product_id}">
                            ${productName} (Batch: ${batch_no})
                        </td>
                        <td><input type="number" name="purchase_details[${newId}][batch_no]" class="form-control batch_no" step="any" value="${batch_no}"></td>
                        <td><input type="number" name="purchase_details[${newId}][quantity]" class="form-control quantity" step="any" value="${quantity}" required></td>
                        <td><input type="number" name="purchase_details[${newId}][rate]" class="form-control rate" step="any" value="${rate}" required></td>
                        <td><input type="number" name="purchase_details[${newId}][tax_rate]" class="form-control tax_rate" step="any" value="${tax_rate}"></td>
                        <td><input type="text" name="purchase_details[${newId}][total]" class="form-control total" value="${total.toFixed(2)}" readonly></td>
                        <td><input type="text" name="purchase_details[${newId}][manufacturer_date]" class="form-control manufacturer_date datepicker" value="${manufacturer_date}"></td>
                        <td><input type="text" name="purchase_details[${newId}][expire_date]" class="form-control expire_date datepicker" value="${expire_date}"></td>
                        <td><span class="unitname">${unit}</span></td>
                        <td><button type="button" class="btn btn-danger btn-sm delete_row">Delete</button></td>
                    </tr>`;

                $('#pur_rows').append(newRow);

                // Reset input fields
                $('#product_id').val(null).trigger('change');
                $('#quantity, #rate, #tax_rate, #total').val('');
                $('#manufacturer_date, #expire_date').val('');

                // Rebind datepicker for new inputs
                $('.datepicker').datepicker({
                    format: "yyyy-mm-dd",
                    autoclose: true,
                    todayHighlight: true
                });
            });

            // Delete row
            $('#purchaseDetailsTable').on('click', '.delete_row', function() {
                if (confirm('Are you sure to delete this row?')) {
                    $(this).closest('tr').remove();
                }
            });
        });
    </script>
@endsection
