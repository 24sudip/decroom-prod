@extends('backend.layouts.master-layouts')

@section('title', __('Brand Wise Orders'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <h4 class="mb-3">Brand-wise Orders Report</h4>

        <div class="row mb-3">
            <div class="col-md-3">
                <input type="date" id="fromDate" class="form-control" placeholder="From Date">
            </div>
            <div class="col-md-3">
                <input type="date" id="toDate" class="form-control" placeholder="To Date">
            </div>
            <div class="col-md-3">
                <select id="statusFilter" class="form-select">
                    <option value="">{{ __('All Status') }}</option>
                    @foreach ($ordertypes as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table id="brandwise-orders-table" class="table table-bordered table-striped">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>{{ __('Brand Name') }}</th>
                        <th>{{ __('Total Orders') }}</th>
                        <th>{{ __('Latest Order ID') }}</th>
                        <th>{{ __('Latest Order Date') }}</th>
                        <th>{{ __('Status') }}</th>
                        <th>{{ __('Change Status') }}</th>
                        <th>{{ __('Action') }}</th>
                    </tr>
                </thead>

                <tbody></tbody>
            </table>
        </div>
    </div>
@endsection

@push('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let table;

        $(function() {
            table = $('#brandwise-orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('report.order.brandwise.data') }}',
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.from = $('#fromDate').val();
                        d.to = $('#toDate').val();
                    },
                    error: function(xhr, error) {
                        console.error('DataTables Ajax error:', error);
                        alert('Failed to load data. Check console for details.');
                    }
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-sm btn-secondary'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-success'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-danger'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-primary'
                    }
                ],
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'brand_name',
                        name: 'brand_name'
                    },
                    {
                        data: 'total_orders',
                        name: 'total_orders'
                    },
                    {
                        data: 'latest_order_id',
                        name: 'latest_order_id'
                    },
                    {
                        data: 'latest_order_date',
                        name: 'latest_order_date'
                    },
                    {
                        data: 'status_text',
                        name: 'status_text'
                    },
                    {
                        data: 'status_dropdown',
                        name: 'status_dropdown',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'option',
                        name: 'option',
                        orderable: false,
                        searchable: false
                    }
                ],

                order: [
                    [1, 'asc']
                ]
            });

            // Status update event handler
            $(document).on('change', '.status-update', function() {
                const id = $(this).data('id');
                const status = $(this).val();

                $.ajax({
                    url: '{{ route('order.update.status') }}',
                    method: 'POST',
                    data: {
                        id,
                        status
                    },
                    success: function() {
                        alert('Status updated successfully!');
                        table.ajax.reload(null, false);
                    },
                    error: function() {
                        alert('Failed to update status.');
                    }
                });
            });

            $('#statusFilter, #fromDate, #toDate').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
