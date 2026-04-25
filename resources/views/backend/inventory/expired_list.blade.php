@extends('backend.layouts.master-layouts')

@section('title', 'Expired Product List')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-6">
            <h4>Expired Products (Next 3 Months)</h4>
        </div>
    </div>

    {{-- Filters --}}
    <div class="row g-2 mb-3">

        <div class="col-md-2">
            <select name="category_id" class="form-control select2" data-placeholder="Filter by Category">
                <option value="">All Categories</option>
                @foreach ($categories as $cat)
                    <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="brand_id" class="form-control select2" data-placeholder="Filter by Brand">
                <option value="">All Brands</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <select name="generic_id" class="form-control select2" data-placeholder="Filter by Generic">
                <option value="">All Generics</option>
                @foreach ($generics as $generic)
                    <option value="{{ $generic->id }}">{{ $generic->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <select name="supplier_id" class="form-control select2" data-placeholder="Filter by Supplier">
                <option value="">All Suppliers</option>
                @foreach ($suppliers as $sup)
                    <option value="{{ $sup->id }}">{{ $sup->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-3">
            <input type="text" name="search_term" class="form-control" placeholder="Search name/batch">
        </div>

    </div>

    <div class="row g-2 mb-3">

        <div class="col-md-2">
            <label for="manufacture_date" class="form-label">Manufacture Date</label>
            <input type="date" id="manufacture_date" name="manufacture_date" class="form-control"
                placeholder="Manufacture Date">
        </div>

        <div class="col-md-2">
            <label for="expire_date" class="form-label">Expire Date</label>
            <input type="date" id="expire_date" name="expire_date" class="form-control" placeholder="Expire Date">
        </div>

        <div class="col-md-1 d-flex align-items-end">
            <button id="reset-filters" class="btn btn-outline-secondary w-100" type="button">Reset</button>
        </div>

    </div>

    {{-- Table --}}
    <div class="table-responsive">
        <table id="expired-table" class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Product</th>
                    <th>Batch</th>
                    <th>Category</th>
                    <th>Brand</th>
                    <th>Generic</th>
                    <th>Unit</th>
                    <th>Supplier</th>
                    <th>Expire Date</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $(function() {
            $('.select2').select2({
                allowClear: true,
                width: '100%'
            });

            let table = $('#expired-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('expired.list') }}',
                    data: function(d) {
                        d.category_id = $('select[name=category_id]').val();
                        d.brand_id = $('select[name=brand_id]').val();
                        d.generic_id = $('select[name=generic_id]').val();
                        d.supplier_id = $('select[name=supplier_id]').val();
                        d.search_term = $('input[name=search_term]').val();
                        d.expire_date = $('input[name=expire_date]').val();
                        d.manufacture_date = $('input[name=manufacture_date]').val();
                    }
                },
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'csv', 'pdf', 'print'],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'image',
                        name: 'image',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'product_name',
                        name: 'product.name'
                    },
                    {
                        data: 'batch',
                        name: 'product.batch_no'
                    },
                    {
                        data: 'category',
                        name: 'product.category.name'
                    },
                    {
                        data: 'brand',
                        name: 'product.brand.name'
                    },
                    {
                        data: 'generic',
                        name: 'product.generic.name'
                    },
                    {
                        data: 'unit',
                        name: 'product.unit.name'
                    },
                    {
                        data: 'supplier',
                        name: 'purchase.supplier.name'
                    },
                    {
                        data: 'expire_date',
                        name: 'expire_date'
                    }
                ],
                order: [
                    [9, 'asc']
                ]
            });

            // Filters triggers
            $('select[name=category_id], select[name=brand_id], select[name=generic_id], select[name=supplier_id]')
                .on('change', function() {
                    table.ajax.reload();
                });

            $('input[name=search_term], input[name=expire_date], input[name=manufacture_date]').on('keyup change',
                function() {
                    table.ajax.reload();
                });

            $('#reset-filters').on('click', function() {
                $('select[name=category_id], select[name=brand_id], select[name=generic_id], select[name=supplier_id]')
                    .val(null).trigger('change');
                $('input[name=search_term], input[name=expire_date], input[name=manufacture_date]').val('');
                table.ajax.reload();
            });
        });
    </script>
@endsection
