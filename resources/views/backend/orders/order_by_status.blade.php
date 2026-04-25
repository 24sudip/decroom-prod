@extends('backend.layouts.master-layouts')

@section('title', 'Orders By Status')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid py-3">
        <h4>Orders ({{ ucfirst($slug) }})</h4>

        {{-- Filter Section --}}
        <div class="row mb-3">
            <div class="col-md-2">
                <label>Status</label>
                <select id="statusFilter" class="form-select">
                    <option value="">All Status</option>
                    @foreach ($ordertypes as $type)
                        <option value="{{ $type->id }}">{{ $type->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>From Date</label>
                <input type="date" id="fromDate" class="form-control">
            </div>
            <div class="col-md-2">
                <label>To Date</label>
                <input type="date" id="toDate" class="form-control">
            </div>
            <div class="col-md-3">
                <label>Customer</label>
                <select id="customerFilter" class="form-select select2">
                    <option value="">All Customers</option>
                    @foreach ($customers as $customer)
                        <option value="{{ $customer->id }}">{{ $customer->name }} ({{ $customer->phone }})</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label>Customer Type</label>
                <select id="customerTypeFilter" class="form-select">
                    <option value="">All Types</option>
                    <option value="retailer">Retailer</option>
                    <option value="wholesale">Wholesale</option>
                </select>
            </div>
        </div>

        {{-- Orders Table --}}
        <div class="card">
            <div class="card-body">
                <table class="table table-bordered table-striped" id="ordersTable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Order ID</th>
                            <th>Customer</th>
                            <th>Phone</th>
                            <th>Amount</th>
                            <th>Status (Text)</th>
                            <th>Status (Update)</th>
                            <th>Date</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody></tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Customer Orders Modal -->
    <div class="modal fade" id="customerOrdersModal" tabindex="-1" aria-labelledby="customerOrdersModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Orders</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-sm table-bordered" id="customerOrdersTable">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Order ID</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody id="customerOrdersBody"></tbody>
                    </table>
                </div>
            </div>
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
        $(function() {
            $('.select2').select2();

            let table = $('#ordersTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('report.order.bytype.data', $slug) }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.from = $('#fromDate').val();
                        d.to = $('#toDate').val();
                        d.customer_id = $('#customerFilter').val();
                        d.customer_type = $('#customerTypeFilter').val();
                    },
                    error: function(xhr) {
                        alert('Failed to load orders. Check backend route or filters.');
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
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'customer',
                        name: 'customer.name'
                    },
                    {
                        data: 'phone',
                        name: 'customer.phone'
                    },
                    {
                        data: 'amount',
                        name: 'amount'
                    },
                    {
                        data: 'status_text',
                        name: 'status_text'
                    }, // New column
                    {
                        data: 'status_dropdown',
                        name: 'status_dropdown',
                        orderable: false,
                        searchable: false
                    }, // New column
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
                        _token: $('meta[name="csrf-token"]').attr('content'),
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

            $('#statusFilter, #fromDate, #toDate, #customerFilter, #customerTypeFilter').on('change', function() {
                table.ajax.reload();
            });
        });
    </script>
@endpush
