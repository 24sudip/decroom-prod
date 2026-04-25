@extends('backend.layouts.master-layouts')

@section('title', __('Supplier List'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
    <div class="row mb-3">
        <div class="col-6">
            <h4>{{ __('Suppliers') }}</h4>
        </div>
        <div class="col-6 text-end">
            <a href="{{ route('supplier.create') }}" class="btn btn-primary">
                <i class="bx bx-plus me-1"></i> {{ __('Add New Supplier') }}
            </a>
        </div>
    </div>

    {{-- Filters --}}
    <div class="row g-2 mb-3">
        <div class="col-md-4">
            <input type="text" name="search" class="form-control" placeholder="{{ __('Search by name or phone') }}">
        </div>
        <div class="col-md-2">
            <button id="reset-filters" class="btn btn-outline-secondary w-100" type="button">{{ __('Reset') }}</button>
        </div>
    </div>

    {{-- Supplier Table --}}
    <div class="table-responsive">
        <table id="suppliers-table" class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th>#</th>
                    <th>{{ __('Image') }}</th>
                    <th>{{ __('Name') }}</th>
                    <th>{{ __('Phone') }}</th>
                    <th>{{ __('Email') }}</th>
                    <th>{{ __('Tread Name') }}</th>
                    <th>{{ __('Tread No') }}</th>
                    <th>{{ __('Main Balance') }}</th>
                    <th>{{ __('Due Balance') }}</th>
                    <th>{{ __('Actions') }}</th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <!-- Export Buttons -->
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

            const table = $('#suppliers-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '{{ route('supplier.index') }}',
                    data: function(d) {
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
                        data: 'main_balance',
                        name: 'main_balance'
                    },
                    {
                        data: 'due_balance',
                        name: 'due_balance'
                    },
                    {
                        data: 'option',
                        name: 'option',
                        orderable: false,
                        searchable: false
                    }
                ],
                order: [
                    [2, 'asc']
                ]
            });

            $('input[name=search]').on('keyup', function() {
                table.ajax.reload();
            });

            $('#reset-filters').on('click', function() {
                $('input[name=search]').val('');
                table.ajax.reload();
            });

            // Delete supplier
            $(document).on('click', '.delete-supplier', function() {
                const id = $(this).data('id');
                if (confirm("Are you sure you want to delete this supplier?")) {
                    $.ajax({
                        url: 'supplier/' + id,
                        method: 'DELETE',
                        data: {
                            _token: '{{ csrf_token() }}',
                            id: id
                        },
                        success: function(response) {
                            if (response.isSuccess) {
                                alert(response.message);
                                table.ajax.reload();
                            } else {
                                alert(response.message);
                            }
                        },
                        error: function(xhr) {
                            alert('Error: ' + (xhr.responseJSON?.message ||
                                'Something went wrong.'));
                        }
                    });
                }
            });
        });
    </script>
@endsection
