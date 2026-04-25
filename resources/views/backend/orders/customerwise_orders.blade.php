@extends('backend.layouts.master-layouts')

@section('title', 'Customerwise Orders')

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
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
        <div class="col-md-2">
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

    <div class="table-responsive">
        <table id="customerwiseOrderTable" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Name</th>
                    <th>Phone</th>
                    <th>Type</th>
                    <th>Total Orders</th>
                    <th>Latest Order ID</th>
                    <th>Latest Order Status</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>

    <!-- Order Details Modal -->
    <div class="modal fade" id="orderDetailsModal" tabindex="-1" aria-labelledby="orderDetailsModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Customer Order History</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <table class="table table-striped table-bordered" id="customerOrderList">
                        <thead>
                            <tr>
                                <th>Order ID</th>
                                <th>Date</th>
                                <th>Total</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="orderHistoryBody">
                            <!-- dynamically filled -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.36/vfs_fonts.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        $(function() {
            $('.select2').select2();

            let table = $('#customerwiseOrderTable').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('report.order.customerwise.data') }}",
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.from = $('#fromDate').val();
                        d.to = $('#toDate').val();
                        d.customer_id = $('#customerFilter').val();
                        d.type = $('#customerTypeFilter').val();
                    },
                    error: function(xhr, error) {
                        console.error('DataTables Ajax error:', error);
                        alert('Failed to load data. Check console for details.');
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
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'type',
                        name: 'type'
                    },
                    {
                        data: 'total_orders',
                        name: 'orders_count'
                    },
                    {
                        data: 'latest_order_id',
                        name: 'latest_order_id'
                    },
                    {
                        data: 'latest_order_status',
                        name: 'latest_order_status'
                    },
                    {
                        data: 'id',
                        name: 'id',
                        orderable: false,
                        searchable: false,
                        render: function(data) {
                            return `<button class="btn btn-sm btn-info view-orders-btn" data-id="${data}">View Details</button>`;
                        }
                    }
                ]
            });

            $('#statusFilter, #fromDate, #toDate, #customerFilter, #customerTypeFilter').on('change', function() {
                table.ajax.reload();
            });

            $(document).on('click', '.view-orders-btn', function() {
                const customerId = $(this).data('id');
                $.ajax({
                    url: `/report/order/customer/${customerId}/orders`,
                    type: 'GET',
                    success: function(response) {
                        let tbody = '';
                        if (response.length > 0) {
                            response.forEach(order => {
                                tbody += `
                                <tr>
                                    <td>${order.id}</td>
                                    <td>${order.order_date}</td>
                                    <td>${order.total_amount}</td>
                                    <td>${order.status_text}</td>
                                </tr>`;
                            });
                        } else {
                            tbody =
                                `<tr><td colspan="4" class="text-center">No orders found</td></tr>`;
                        }
                        $('#orderHistoryBody').html(tbody);
                        $('#orderDetailsModal').modal('show');
                    }
                });
            });
        });
    </script>
@endsection
