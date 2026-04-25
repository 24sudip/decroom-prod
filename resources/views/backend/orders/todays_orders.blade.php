@extends('backend.layouts.master-layouts')
@section('title', 'Today\'s Orders')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
    <div class="container-fluid">
        <h4>Today's Orders</h4>

        <div class="row mb-3">
            <div class="col-md-3">
                <select id="statusFilter" class="form-control">
                    <option value="">All Status</option>
                    @foreach (App\Ordertype::all() as $status)
                        <option value="{{ $status->id }}">{{ $status->name }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <table class="table table-bordered" id="todaysOrderTable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Tracking ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Status</th>
                    <th>Total Amount</th>
                    <th>Payment</th>
                    <th>Date</th>
                    <th>Action</th>
                </tr>
            </thead>
        </table>
    </div>
@endsection

@push('script')
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

    <script>
        $(function() {
            let table = $('#todaysOrderTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('report.order.todays.data') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                    }
                },
                columns: [{
                        data: 'DT_RowIndex',
                        name: 'DT_RowIndex',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'tracking_id',
                        name: 'id'
                    },

                    {
                        data: 'customer',
                        name: 'customer'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'status_dropdown',
                        name: 'ordertype.name',
                        orderable: false,
                        searchable: false
                    },
                    {
                        data: 'total_amount',
                        name: 'total_amount'
                    },
                    {
                        data: 'payment_method',
                        name: 'payment_method'
                    },
                    {
                        data: 'date',
                        name: 'created_at'
                    },
                    {
                        data: 'option',
                        name: 'option',
                        orderable: false,
                        searchable: false
                    }
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

            $('#statusFilter').change(function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
