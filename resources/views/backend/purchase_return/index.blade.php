@extends('backend.layouts.master-layouts')

@section('title', __('Purchase Return List'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-6">
            <h4>{{ __('Purchase Returns') }}</h4>
        </div>
    </div>

    {{-- Filters --}}
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <select name="supplier_id" class="form-control select2" data-placeholder="{{ __('Filter by Supplier') }}">
                <option value="">{{ __('All Suppliers') }}</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="col-md-6">
            <input type="text" name="search" class="form-control"
                placeholder="{{ __('Search by bill no or supplier') }}">
        </div>
        <div class="col-md-2">
            <button id="reset-filters" class="btn btn-outline-secondary w-100" type="button">{{ __('Reset') }}</button>
        </div>
    </div>

    <div class="table-responsive">
        <table id="purchaseReturnTable" class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>Return Date</th>
                    <th>Purchase Bill No</th>
                    <th>Supplier</th>
                    <th>Return Amount</th>
                    <th>Action</th>
                </tr>
            </thead>
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

            var table = $('#purchaseReturnTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('purchase-return.index') }}',
                    data: function(d) {
                        d.supplier_id = $('select[name=supplier_id]').val();
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
                        data: 'return_date',
                        name: 'return_date'
                    },
                    {
                        data: 'bill_no',
                        name: 'purchase.bill_no'
                    },
                    {
                        data: 'supplier_name',
                        name: 'supplier.name'
                    },
                    {
                        data: 'total_return_amt',
                        name: 'total_return_amt'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [1, 'desc']
                ]
            });

            $('select[name=supplier_id], input[name=search]').on('change keyup', function() {
                table.ajax.reload();
            });

            $('#reset-filters').on('click', function() {
                $('select[name=supplier_id]').val(null).trigger('change');
                $('input[name=search]').val('');
                table.ajax.reload();
            });
        });
    </script>
@endsection
