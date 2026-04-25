@extends('backend.layouts.master-layouts')

@section('title')
    {{ __('Wholesale Customer') }}
@endsection

@section('css')
    <!-- DataTables & Bootstrap 5 -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/5.3.0/css/bootstrap.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css" rel="stylesheet" />
    <link href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.bootstrap5.min.css" rel="stylesheet" />

    <style>
        .img-thumbnail {
            object-fit: cover;
            width: 60px;
            height: 60px;
            border-radius: 5px;
        }
    </style>
@endsection

@section('content')
    @component('backend.components.breadcrumb')
        @slot('title')
            {{ __('Wholesale Customer List') }}
        @endslot
        @slot('li_1')
            {{ __('Dashboard') }}
        @endslot
        @slot('li_2')
            {{ __('Wholesale Customer') }}
        @endslot
    @endcomponent

    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-body">

                    <div class="table-responsive">
                        <table id="customerList" class="table table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>{{ __('Sr. No') }}</th>
                                    <th>{{ __('Image') }}</th>
                                    <th>{{ __('Name') }}</th>
                                    <th>{{ __('Phone') }}</th>
                                    <th>{{ __('Email') }}</th>
                                    <th>{{ __('Tread Name') }}</th>
                                    <th>{{ __('Tread No') }}</th>
                                    <th>{{ __('Address') }}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('script')
    <!-- DataTables and plugins -->
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/dataTables.buttons.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.2/js/buttons.print.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
    <script src="https://cdn.datatables.net/responsive/2.5.0/js/responsive.bootstrap5.min.js"></script>
    <script src="{{ asset('build/libs/jszip/jszip.min.js') }}"></script>
    <script src="{{ asset('build/libs/pdfmake/build/pdfmake.min.js') }}"></script>
    <script src="{{ asset('build/libs/pdfmake/build/vfs_fonts.js') }}"></script>

    <script>
        $(document).ready(function() {
            $('#customerList').DataTable({
                responsive: true,
                processing: true,
                serverSide: true,
                ajax: "{{ route('customer.wholesale_list') }}",
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
                        data: 'phone',
                        name: 'phone'
                    },
                    {
                        data: 'email',
                        name: 'email'
                    },
                    {
                        data: 'tread_name',
                        name: 'tread_name'
                    },
                    {
                        data: 'tread_no',
                        name: 'tread_no'
                    },
                    {
                        data: 'address',
                        name: 'address'
                    }

                ],
                pagingType: 'full_numbers',
                drawCallback: function() {
                    $('.dataTables_paginate > .pagination').addClass('justify-content-end');
                    $('.dataTables_filter').addClass('d-flex justify-content-end');
                }
            });
        });

        // Delete customer
        $(document).on('click', '#delete-customer', function() {
            const id = $(this).data('id');

            if (confirm('{{ __('Are you sure you want to delete this customer?') }}')) {
                $.ajax({
                    url: 'customer/' + id,
                    method: 'DELETE',
                    data: {
                        _token: '{{ csrf_token() }}',
                        id: id
                    },
                    beforeSend: function() {
                        $('#pageloader').show();
                    },
                    success: function(response) {
                        toastr.success(response.message, '{{ __('Success Alert') }}', {
                            timeOut: 1000
                        });
                        $('#customerList').DataTable().ajax.reload();
                    },
                    error: function(xhr) {
                        toastr.error(xhr.responseJSON?.message ||
                            '{{ __('Something went wrong.') }}', {
                                timeOut: 2000
                            });
                    },
                    complete: function() {
                        $('#pageloader').hide();
                    }
                });
            }
        });
    </script>
@endsection
