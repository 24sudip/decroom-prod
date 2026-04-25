@extends('backend.layouts.master-layouts')

@section('title', __('Purchase List'))

@section('css')
    <link rel="stylesheet" href="{{ asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-6">
            <h4>{{ __('Purchases') }}</h4>
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('purchase.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> {{ __('Add New Purchase') }}
            </a>
        </div>
    </div>

    <div class="row g-2 mb-3 align-items-end">
        <div class="col-md-3">
            <label for="supplier_id">{{ __('Filter by Supplier') }}</label>
            <select name="supplier_id" class="form-control select2" data-placeholder="{{ __('All Suppliers') }}">
                <option value="">{{ __('All Suppliers') }}</option>
                @foreach ($suppliers as $supplier)
                    <option value="{{ $supplier->id }}">{{ $supplier->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-2">
            <label for="from_date">{{ __('From Date') }}</label>
            <input type="date" name="from_date" class="form-control">
        </div>

        <div class="col-md-2">
            <label for="to_date">{{ __('To Date') }}</label>
            <input type="date" name="to_date" class="form-control">
        </div>

        <div class="col-md-3">
            <label for="search">{{ __('Search') }}</label>
            <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name or bill no') }}">
        </div>
        <div class="col-md-2">
            <button id="reset-filters" class="btn btn-outline-secondary">{{ __('Reset Filter') }}</button>
        </div>
    </div>



    <div class="table-responsive">
        <table id="purchases-table" class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('Bill No') }}</th>
                    <th>{{ __('Date') }}</th>
                    <th>{{ __('Supplier') }}</th>
                    <th>{{ __('Bill Amount') }}</th>
                    <th>{{ __('Transport Cost') }}</th>
                    <th>{{ __('Total Amount') }}</th>
                    <th>{{ __('Note') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ asset('build/libs/select2/js/select2.min.js') }}"></script>
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
                width: '100%',
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });

            const table = $('#purchases-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('purchase.index') }}',
                    data: function(d) {
                        d.supplier_id = $('select[name=supplier_id]').val();
                        d.search = $('input[name=search]').val();
                        d.from_date = $('input[name=from_date]').val();
                        d.to_date = $('input[name=to_date]').val();
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
                        data: 'bill_no',
                        name: 'bill_no'
                    },
                    {
                        data: 'purchase_date',
                        name: 'purchase_date'
                    },
                    {
                        data: 'name',
                        name: 'supplier.name'
                    },
                    {
                        data: 'bill_amt',
                        name: 'bill_amt'
                    },
                    {
                        data: 'transport_cost',
                        name: 'transport_cost'
                    },
                    {
                        data: 'total_bill',
                        name: 'total_bill'
                    },
                    {
                        data: 'note',
                        name: 'note'
                    },
                    {
                        data: 'option',
                        name: 'option',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [2, 'desc']
                ]
            });

            $('select[name=supplier_id], input[name=search], input[name=from_date], input[name=to_date]').on(
                'change keyup',
                function() {
                    table.ajax.reload();
                });

            $('#reset-filters').on('click', function() {
                $('select[name=supplier_id]').val(null).trigger('change');
                $('input[name=search]').val('');
                $('input[name=from_date]').val('');
                $('input[name=to_date]').val('');
                table.ajax.reload();
            });
        });
    </script>
@endsection
