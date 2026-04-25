@extends('backend.layouts.master-layouts')

@section('title', __('User Index'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
@endsection

@section('content')
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12 p-2">
                <!-- Table -->
                <div class="d-flex justify-content-between align-items-center py-2">
                    <h5 class="title">User List</h5>
                    <a href="{{ route('user.create') }}" class="btn btn-sm btn-info">Add New</a>
                </div>

                <div id="VisitorDt_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="VisitorDt" class="table table-striped table-sm table-bordered dataTable no-footer"
                                cellspacing="0" width="100%" role="grid" aria-describedby="VisitorDt_info"
                                style="width: 100%;">
                                <thead>
                                    <tr role="row">
                                        <th class="th-sm sorting_asc py-2 text-center" aria-controls="VisitorDt"
                                            aria-sort="ascending" aria-label="NO: activate to sort column descending"
                                            style="width: 10px">NO</th>

                                        <th class="th-sm sorting py-2">Last Update</th>
                                        <th class="th-sm sorting py-2">Image</th>
                                        <th class="th-sm sorting py-2">Role</th>
                                        <th class="th-sm sorting py-2">Name</th>
                                        <th class="th-sm sorting py-2">Email</th>
                                        <th class="th-sm sorting py-2">Phone</th>
                                        <th class="th-sm sorting text-center">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse ($users as $user)
                                        <tr role="row" class="odd">
                                            <td class="text-center py-2">{{ $loop->index + 1 }}</td>
                                            <td class="py-2">{{ $user->updated_at->format('d-M-Y') }}</td>
                                            <td class="py-2"><img src="{{ asset('storage/users/' . $user->image) }}"
                                                    height="50"></td>
                                            <td class="py-2">{{ $user->role->role_name }}</td>
                                            <td class="py-2">{{ $user->name }}</td>
                                            <td class="py-2">{{ $user->email }}</td>
                                            <td class="py-2">{{ $user->phone }}</td>
                                            <td class="text-center py-2">
                                                <a class="btn btn-sm btn-info p-2"
                                                    href="{{ route('user.edit', $user->id) }}"><i
                                                        class="fas fa-edit"></i></a>

                                                <form class="d-inline" action="{{ route('user.destroy', $user->id) }}"
                                                    method="post">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button class="show_confirm btn btn-sm btn-danger p-2" type="submit"><i
                                                            class="fas fa-trash-alt"></i></button>
                                                </form>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="8" class="text-center">
                                                <h4>No Data Found!</h4>
                                            </td>
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
                            'Customer has been deleted.',
                            'success'
                        )
                    }
                })
            });
        });
    </script>
@endpush
