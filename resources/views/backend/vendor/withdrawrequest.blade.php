@extends('backend.layouts.master-layouts')

@section('title', __('Withdraw Request'))

@section('css')
<link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
<link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
<!-- Main content -->
<section class="content">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Manage Withdraw Request</h3>
                </div>
                <!-- /.card-header -->
                <div class="card-body user-border">
                    <table id="example" class="table table-bordered table-striped">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Phone</th>
                                <th>Amount</th>
                                <th>Note</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($show_datas as $key => $value)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ optional($value->vendor)->vendorDetails->shop_name }}</td>
                                <td>{{ $value->vendor->phone }}</td>
                                <td>{{ $value->amount }}</td>
                                <td>
                                    @if ($value->note)
                                        <span class="badge badge-info badge-pill"> {{ $value->note }}</span>
                                    @endif
                                </td>
                                <td>
                                    <button type="button" class="btn btn-info btn-sm col completeBtn"
                                        data-id="{{ $value->id }}"
                                        data-toggle="modal"
                                        data-target="#uploadModalCenter">
                                        <i class="fa fa-thumbs-up"></i> Complete
                                    </button>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                <!-- /.card-body -->
            </div>
            <!-- /.card -->
        </div>
        <!-- /.col -->
    </div>
    <!-- /.row -->
</section>
<!-- /.content -->

<!-- Single Dynamic Modal -->
<div class="modal fade" id="uploadModalCenter" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <form action="{{ route('seller.receiptUpload') }}" method="POST" enctype="multipart/form-data" id="receiptForm">
            @csrf
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Payment Receipt Upload</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <input type="hidden" name="hidden_id" id="modal_hidden_id">
                    <div class="form-group">
                        <label for="receipt">Image</label>
                        <input type="file" name="receipt" class="form-control" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection

@section('script')
<!--<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>-->
<!-- Bootstrap JS -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
<script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
<script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

<script>
$(document).ready(function() {
    $('#example').DataTable();

    // Dynamic modal populate
    $('.completeBtn').on('click', function() {
        var id = $(this).data('id');
        $('#modal_hidden_id').val(id);
        $('#uploadModalCenter').modal('show');
    });
});
</script>
@endsection
