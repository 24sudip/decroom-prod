@extends('backend.layouts.master-layouts')

@section('title')
    {{ __('Add New Purchase') }}
@endsection

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="{{ URL::asset('build/libs/bootstrap-datepicker/css/bootstrap-datepicker.min.css') }}">
@endsection

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">
                    {{ __('Add New Purchase') }}
                </h4>
            </div>
        </div>
    </div>
    <div class="row mb-3">
        <div class="col-6">
            <form action="{{ route('purchase.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <label for="excel_file">Choose Excel file:</label>
                <input type="file" name="excel_file" id="excel_file" required>
                <button class="btn btn-primary" type="submit">Import</button>
                <a href="{{ route('purchase.demoExcel') }}" class="btn btn-primary">Demo File</a>
            </form>
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('purchase.index') }}" class="btn btn-primary mb-3">
                <i class="bx bx-arrow-back me-2"></i>{{ __('Back to Purchase List') }}
            </a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">

            <form id="userform" class="form-horizontal" method="POST" action="{{ route('purchase.store') }}">

                @csrf
                <input type="hidden" name="unit_id" id="unit_id">

                <div class="card-body">
                    <div class="col-md-12">
                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group row">

                                    <label for="supplier_id" class="col-sm-4 col-form-label">Supplier:
                                        <span class="text-danger"> * </span></label>
                                    <div class="col-sm-8">

                                        <select id="supplier_id" name="supplier_id" class="form-control select2"
                                            value="{{ old('supplier_id') }}" style="width: 100%;">
                                            <option value="">Select Suppliers</option>
                                            @foreach ($suppliers as $v)
                                                <option value="{{ $v->id }}"
                                                    {{ old('supplier_id') == $v->id ? 'selected' : '' }}>
                                                    {{ $v->name }}</option>
                                            @endforeach

                                        </select>

                                        @error('supplier_id')
                                            <span class="error invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="purchase_date" class="col-sm-4 col-form-label">Purchase Date:
                                        <span class="text-danger"> * </span></label>
                                    <div class="col-sm-8">

                                        <div class="input-group date">

                                            <input type="text" name="purchase_date" class="form-control datepicker"
                                                value="{{ old('purchase_date') }}" required>

                                        </div>


                                        @error('purchase_date')
                                            <span class="error invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                        </div>


                        <div class="row mb-2">
                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="bill_no" class="col-sm-4 col-form-label">Bill NO: <span
                                            class="text-danger"> * </span></label>
                                    <div class="col-sm-8">
                                        <input type="text" class="form-control" name="bill_no" id="bill_no"
                                            value="{{ old('bill_no') }}" placeholder="Enter Bill No" required>

                                        @error('bill_no')
                                            <span class="error invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="col-sm-6">
                                <div class="form-group row">
                                    <label for="bill_date" class="col-sm-4 col-form-label">Bill Date:
                                        <span class="text-danger"> * </span></label>
                                    <div class="col-sm-8">

                                        <div class="input-group date">

                                            <input type="text" name="bill_date" class="form-control datepicker"
                                                value="{{ old('bill_date') }}" required>
                                        </div>

                                        @error('bill_date')
                                            <span class="error invalid-feedback" role="alert">
                                                <strong>{{ $message }}</strong>
                                            </span>
                                        @enderror

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th style="width: 10px">Sl</th>
                                        <th style="width:180px">Product</th>
                                        <th style="width:60px">Batch No</th>
                                        <th>Mfg Date</th>
                                        <th>Exp Date</th>
                                        <th>Stock:(<span id="cur_stock"></span>)</th>
                                        <th>Unit</th>
                                        <th>Rate</th>
                                        <th>Tax(%)</th>
                                        <th>Amount</th>
                                        <th style="width:90px;">Action</th>
                                    </tr>

                                    <tr>
                                        <th>#</th>
                                        <th style="width:180px">
                                            <select id="product_id" name="product_id" class="form-control select2"
                                                style="width: 100%;">
                                                <option value="">Select Product</option>
                                                @foreach ($products as $v)
                                                    <option value="{{ $v->id }}" data-pname="{{ $v->name }}"
                                                        data-unitid="{{ $v->unit_id }}"
                                                        data-unit="{{ $v->unit->name }}"
                                                        data-batchno="{{ $v->batch_no }}"
                                                        data-pur="{{ $v->purchase_price }}"
                                                        data-stock="{{ $v->stock }}"
                                                        {{ old('product_id') == $v->id ? 'selected' : '' }}>
                                                        {{ $v->name }}</option>
                                                @endforeach

                                            </select>
                                        </th>

                                        <th>
                                            <input type="text" id="batch_no" class="form-control batch_no"
                                                value="" placeholder="batch_no" style="width:100px">
                                        </th>
                                        <th>
                                            <input style="width:100px" type="text" id="manufacturer_date"
                                                class="form-control datepicker" placeholder="Mfg Date">
                                        </th>
                                        <th>
                                            <input style="width:100px" type="text" id="expire_date"
                                                class="form-control datepicker" placeholder="Exp Date">
                                        </th>
                                        <th>
                                            <input type="number" id="quantity" class="form-control quantity"
                                                value="" placeholder="Quantity">
                                        </th>
                                        <th>
                                            <p id="unitname"></p>
                                        </th>
                                        <th>
                                            <input type="number" id="rate" class="form-control rate"
                                                value="" placeholder="Rate">
                                        </th>
                                        <th>
                                            <input type="number" id="tax_rate" class="form-control tax_rate"
                                                value="" placeholder="Tax(%)">
                                        </th>

                                        <th>
                                            <input type="text" id="total" class="form-control total"
                                                value="" placeholder="Amount" readonly>
                                        </th>
                                        <th>
                                            <button type="button" id="add_row" class="btn btn-info">Add</button>
                                        </th>
                                    </tr>
                                </thead>

                                <tbody id="pur_rows">

                                </tbody>

                                <tfoot>
                                    <tr>
                                        <td style="padding-top: 35px;" colspan="6">
                                            <div class="form-group row">
                                                <label for="pur_note" class="col-sm-4 col-form-label">Purchase
                                                    Note:</label>
                                                <div class="col-sm-8">

                                                    <textarea class="form-control" id="pur_note" rows="3" name="vendorNote" placeholder="Purchase Note"
                                                        style="height: 70px;"></textarea>

                                                </div>
                                            </div>
                                        </td>
                                        <td style="padding-top: 35px;" colspan="5">
                                            <div class="form-group row">
                                                <label for="transport_cost"
                                                    class="col-sm-4 col-form-label text-right">Transport
                                                    Cost:</label>
                                                <div class="col-sm-8">
                                                    <input type="text" class="form-control" name="transport_cost"
                                                        id="transport_cost" placeholder="Transpore Cost..">
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
                <!-- /.card-body -->
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Submit</button>
                    <a href="{{ route('purchase.create') }}" class="btn btn-secondary">Clear All</a>
                </div>
            </form>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="{{ URL::asset('build/libs/bootstrap-datepicker/js/bootstrap-datepicker.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $('.select2').select2();

        $('.datepicker').datepicker({
            format: "yyyy-mm-dd",
            autoclose: true,
            todayHighlight: true
        });

        $("#userform").on("click", ".edit_row", function() {
            if ($(this).data("pid")) {
                let row = $(this).data();
                $('#product_id').val(row.pid).trigger('change');
                $("#quantity").val(row.quantity);
                $("#batch_no").val(row.batch_no);
                $("#rate").val(row.price);
                $("#tax_rate").val(row.taxrate);
                $("#total").val(row.total);
                $("#unitname").html(row.unitname);
                $("#manufacturer_date").val(row.mfg);
                $("#expire_date").val(row.exp);
            }
        });

        $("#userform").on("click", ".delete_row", function() {
            if ($(this).data("pid")) {
                if (confirm('Are you sure to delete this record?')) {
                    let product_id = $(this).data("pid");
                    let route = '{{ route('response.delpurrow') }}';

                    $.post(route, {
                            product_id: product_id
                        })
                        .done(function(data) {
                            $("#pur_rows").html(data);
                        })
                        .fail(function() {
                            alert("Delete failed.");
                        });
                }
            }
        });

        function checkValid(number) {
            return +number <= 0;
        }

        function addRow(product_id, quantity, price, tax_rate, total, unit_name, pname, batch_no, manufacturer_date,
            expire_date) {
            $("#add_row").hide();
            $("#btn_looding").show();

            let route = '{{ route('response.addpurrow') }}';

            $.post(route, {
                    product_id,
                    quantity,
                    price,
                    tax_rate,
                    total,
                    unit_name,
                    pname,
                    batch_no,
                    manufacturer_date,
                    expire_date
                })
                .done(function(data) {
                    $("#pur_rows").html(data);
                    setTimeout(() => {
                        $("#add_row").show();
                        $("#btn_looding").hide();
                    }, 1000);

                    $("#quantity, #rate, #tax_rate, #total").val('');
                    $("#batch_no, #unitname, #cur_stock, #manufacturer_date, #expire_date").html('').val('');
                    $('#product_id').val('').trigger('change');
                })
                .fail(function() {
                    $("#add_row").show();
                    $("#btn_looding").hide();
                    alert("Error adding row.");
                });
        }


        $("#add_row").click(function(e) {
            e.preventDefault();

            let product_id = $("#product_id").val();
            if (!product_id) return alert("Select Product!");

            let quantity = parseFloat($("#quantity").val()) || 0;
            if (checkValid(quantity)) return alert("Please enter a valid quantity");

            let price = parseFloat($("#rate").val()) || 0;
            if (checkValid(price)) return alert("Please enter a valid rate");

            let tax_rate = parseFloat($("#tax_rate").val()) || 0;
            let total = parseFloat($("#total").val()) || 0;
            if (checkValid(total)) return alert("Total amount must not be empty!");

            let unit_name = $("#product_id").find(':selected').data('unit');
            let pname = $("#product_id").find(':selected').data('pname');
            let batch_no = $("#batch_no").val();
            let manufacturer_date = $("#manufacturer_date").val();
            let expire_date = $("#expire_date").val();

            addRow(product_id, quantity, price, tax_rate, total, unit_name, pname, batch_no, manufacturer_date,
                expire_date);
        });


        $("#quantity, #rate, #tax_rate").keyup(function() {
            let quantity = parseFloat($("#quantity").val()) || 0;
            let price = parseFloat($("#rate").val()) || 0;
            let tax_rate = parseFloat($("#tax_rate").val()) || 0;

            if (!$("#product_id").val()) {
                $("#quantity, #rate, #tax_rate").val(0);
                alert("Select Product!");
                return;
            }

            let total = (quantity * price);
            let tax_amt = (total * tax_rate / 100);
            let final_total = (total + tax_amt).toFixed(2);

            $("#total").val(final_total);
        });

        $("#product_id").change(function() {
            let selected = $(this).find(':selected');

            let stock = selected.data('stock') || 0;
            let unitid = selected.data('unitid') || '';
            let unit_name = selected.data('unit') || '';
            let pur_price = selected.data('pur') || 0;
            let batch_no = selected.data('batchno') || '';

            $("#cur_stock").html(stock);
            $("#batch_no").val(batch_no);
            $("#unitname").html(unit_name);
            $("#unit_id").val(unitid);
            $("#rate").val(pur_price);

            $("#quantity, #tax_rate, #total").val('');
        });
    </script>
@endsection
