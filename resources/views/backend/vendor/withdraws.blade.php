@extends('backend.layouts.master-layouts')

@section('title', __('Seller Withdraws'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />

    <style>
        .modal-content img {
            width: 100%;
            border-radius: 8px;
        }
    </style>
@endsection

@section('content')
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-12">

                <div class="card">
                    <div class="card-header d-flex justify-content-between">
                        <h3 class="card-title">Seller Withdraws</h3>
                    </div>

                    <div class="card-body user-border">
                        <table id="withdrawTable" class="table table-bordered table-striped">
                            <thead>
                                <tr>
                                    <th>SL</th>
                                    <th>Name</th>
                                    <th>Phone</th>
                                    <th>Amount</th>
                                    <th>Note</th>
                                    <th>Approved By</th>
                                    <th>Receipt</th>
                                    <th>Status</th>
                                </tr>
                            </thead>

                            <tbody>
                                @foreach ($show_datas as $value)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>

                                        <td>{{ optional($value->vendor)->vendorDetails->shop_name ?? 'N/A' }}</td>

                                        <td>
                                            @if ($value->vendor)
                                            {{ $value->vendor->phone }}
                                            @else
                                            No Vendor Found
                                            @endif
                                        </td>

                                        <td>BDT {{ number_format($value->amount, 2) }}</td>
                                        <td>{{ $value->note ?? 'N/A' }}</td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                {{ App\User::find($value->approved_by)->name ?? 'N/A' }}
                                            </span>
                                        </td>

                                        <td>
                                            @if ($value->receipt)
                                                <!-- View Button -->
                                                <button type="button"
                                                        class="btn btn-outline-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal{{ $value->id }}">
                                                    <i class="fa fa-eye"></i> View
                                                </button>
                                            @else
                                                <!-- Upload Button -->
                                                <button type="button"
                                                        class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#uploadModal{{ $value->id }}">
                                                    <i class="fa fa-upload"></i> Upload
                                                </button>
                                            @endif
                                        </td>

                                        <td>
                                            <span class="badge bg-success">Completed</span>
                                        </td>
                                    </tr>

                                    <!-- Upload Modal -->
                                    <div class="modal fade" id="uploadModal{{ $value->id }}" tabindex="-1" aria-labelledby="uploadLabel{{ $value->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="uploadLabel{{ $value->id }}">
                                                        Upload Receipt
                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <form action="{{ route('seller.receiptUpload') }}" method="POST" enctype="multipart/form-data">
                                                    @csrf
                                                    <div class="modal-body">

                                                        <input type="hidden" name="hidden_id" value="{{ $value->id }}">

                                                        <div class="mb-3">
                                                            <label class="form-label">Upload Receipt Image</label>
                                                            <input type="file" name="receipt" class="form-control" required>
                                                        </div>

                                                    </div>

                                                    <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary btn-sm">Upload</button>
                                                    </div>
                                                </form>

                                            </div>
                                        </div>
                                    </div>

                                    <!-- View Modal -->
                                    <div class="modal fade" id="viewModal{{ $value->id }}" tabindex="-1" aria-labelledby="viewLabel{{ $value->id }}" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content p-0 border-0 bg-transparent shadow-none">

                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close bg-white rounded-circle p-2" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body p-0">
                                                    <img src="{{ asset($value->receipt) }}" class="img-fluid rounded" alt="Receipt Image">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection


@section('script')
    <!-- jQuery (must load first) -->
    <!--<script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>-->

    <!-- Bootstrap 5 JS (for modals) -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <!-- DataTables -->
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>

    <script>
        $(document).ready(function () {
            $('#withdrawTable').DataTable();
        });
    </script>
@endsection
