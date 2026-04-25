@extends('backend.layouts.master-layouts')

@section('title', __('Permission List'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
@endsection

@section('content')
    <div class="container">
        <div class="row">
            <div class="col-md-12 p-2">
                <div class="d-flex justify-content-between align-items-center py-2">
                    <h5 class="title">Permission List</h5>
                    <a href="{{ route('permission.create') }}" class="btn btn-sm btn-info">Add New</a>
                </div>
                <div class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="VisitorDt" class="table table-striped table-sm table-bordered dataTable no-footer"
                                cellspacing="0" width="100%" role="grid" aria-describedby="VisitorDt_info"
                                style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th class="th-sm sorting_asc py-2 text-center" aria-controls="VisitorDt"
                                            aria-sort="ascending" aria-label="NO: activate to sort column descending"
                                            style="width: 10px">NO
                                        </th>

                                        <th class="th-sm sorting py-2" aria-controls="VisitorDt"
                                            aria-label="Last Update: activate to sort column ascending">Last Update</th>

                                        <th class="th-sm sorting py-2" aria-controls="VisitorDt"
                                            aria-label="Module Name: activate to sort column ascending">Module Name</th>

                                        <th class="th-sm sorting py-2" aria-controls="VisitorDt"
                                            aria-label="Permission Name: activate to sort column ascending">Permission Name
                                        </th>

                                        <th class="th-sm sorting py-2" aria-controls="VisitorDt"
                                            aria-label="Slug: activate to sort column ascending">Slug</th>

                                        <th class="th-sm sorting text-center" aria-controls="VisitorDt"
                                            aria-label="Action: activate to sort column ascending">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($permissions as $permission)
                                        <tr role="row" class="odd">
                                            <td class="th-sm sorting_1 py-2 text-center">{{ $loop->index + 1 }}</td>
                                            <td class="th-sm py-2">{{ $permission->updated_at->format('d-M-Y') }}</td>
                                            <td class="th-sm py-2">{{ $permission->module['module_name'] }}</td>
                                            <td class="th-sm py-2">{{ $permission->permission_name }}</td>
                                            <td class="th-sm py-2">{{ $permission->permission_slug }}</td>
                                            <td class="th-sm text-center py-2">
                                                <a class="btn btn-sm btn-info p-2"
                                                    href="{{ route('permission.edit', $permission->id) }}"><i
                                                        class="fas fa-edit"></i></a>

                                                <form class="d-inline"
                                                    action="{{ route('permission.destroy', $permission->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="show_confirm btn btn-sm btn-danger p-2" type="submit"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr role="row" class="odd">
                                            <th class="th-sm" colspan="6">
                                                <h3 class="text-center">No Data Found!</h3>
                                            </th>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $(".show_confirm").click(function(event) {
                let form = $(this).closest('form');
                event.preventDefault();

                Swal.fire({
                    title: 'Are you sure?',
                    text: "You won't be able to revert this!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, delete it!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        form.submit();
                        Swal.fire(
                            'Deleted!',
                            'Your file has been deleted.',
                            'success'
                        )
                    }
                })
            });
        })
    </script>
@endpush
