@extends('backend.layouts.master-layouts')

@section('title', __('Stock Manage'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css">
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-6">
            <h4>{{ __('Stock Manage') }}</h4>
        </div>
    </div>

    {{-- Filters --}}
    <div class="row g-2 mb-3">
        <div class="col-md-3">
            <select name="category_id" class="form-control select2" data-placeholder="{{ __('Filter by Category') }}">
                <option value="">{{ __('All Categories') }}</option>
                @foreach ($categories as $category)
                    <option value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="brand_id" class="form-control select2" data-placeholder="{{ __('Filter by Brand') }}">
                <option value="">{{ __('All Brands') }}</option>
                @foreach ($brands as $brand)
                    <option value="{{ $brand->id }}">{{ $brand->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-3">
            <select name="generic_id" class="form-control select2" data-placeholder="{{ __('Filter by Generic') }}">
                <option value="">{{ __('All Generics') }}</option>
                @foreach ($generics as $generic)
                    <option value="{{ $generic->id }}">{{ $generic->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-2">
            <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name or batch no') }}">
        </div>
        <div class="col-md-1">
            <button id="reset-filters" class="btn btn-outline-secondary w-100" type="button">{{ __('Reset') }}</button>
        </div>
    </div>

    {{-- Product Table --}}
    <div class="table-responsive">
        <table id="products-table" class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Batch No') }}</th>
                    <th>{{ __('Unit') }}</th>
                    <th>{{ __('Price') }}</th>
                    <th>{{ __('Stock') }}</th>
                    <th>{{ __('Generic') }}</th>
                    <th>{{ __('Expire Date') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- DataTables Buttons Scripts -->
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
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            var table = $('#products-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('product.stock') }}',
                    data: function(d) {
                        d.category_id = $('select[name=category_id]').val();
                        d.brand_id = $('select[name=brand_id]').val();
                        d.generic_id = $('select[name=generic_id]').val();
                        d.search = $('input[name=search]').val();
                    }
                },
                dom: 'Bfrtip',
                buttons: ['copy', 'excel', 'pdf', 'print'],
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'batch_no',
                        name: 'batch_no'
                    },
                    {
                        data: 'unit',
                        name: 'unit'
                    },
                    {
                        data: 'new_price',
                        name: 'new_price'
                    },
                    {
                        data: 'stock',
                        name: 'stock'
                    },
                    {
                        data: 'generic',
                        name: 'generic'
                    },
                    {
                        data: 'expire_date',
                        name: 'expire_date',
                        render: function(data) {
                            if (!data) return '';
                            const date = new Date(data);
                            const month = String(date.getMonth() + 1).padStart(2, '0');
                            const year = date.getFullYear();
                            return `${month}-${year}`;
                        }
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });

            $('select[name=category_id], select[name=brand_id], select[name=generic_id]').on('change', function() {
                table.ajax.reload();
            });

            $('input[name=search]').on('keyup', function() {
                table.ajax.reload();
            });

            $('#reset-filters').on('click', function() {
                $('select[name=category_id], select[name=brand_id], select[name=generic_id]').val(null)
                    .trigger('change');
                $('input[name=search]').val('');
                table.ajax.reload();
            });
        });
    </script>
@endsection
