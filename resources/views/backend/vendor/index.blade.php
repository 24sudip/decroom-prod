@extends('backend.layouts.master-layouts')

@section('title', __('Vendor List'))

@section('css')
    <link rel="stylesheet" href="{{ URL::asset('build/libs/select2/css/select2.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 p-2">

            <div class="d-flex justify-content-between align-items-center py-2">
                <h5 class="title">Vendor List</h5>
            </div>

            <div id="VisitorDt_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
                <div class="row">
                    <div class="col-sm-12">

                        <table id="VisitorDt" 
                            class="table table-striped table-sm table-bordered dataTable no-footer" 
                            width="100%" role="grid">

                            <thead>
                                <tr>
                                    <th class="text-center py-2" style="width: 10px">NO</th>
                                    <th class="py-2">Shop Name</th>
                                    <th class="py-2">Vendor Name</th>
                                    <th class="py-2">Phone Number</th>
                                    <th class="py-2">Email Address</th>
                                    <th class="py-2">Address</th>
                                    <th class="py-2">Commission</th>
                                    <th class="text-center py-2">Action</th>
                                </tr>
                            </thead>

                            <tbody>
                                @forelse ($vendors as $vendor)
                                    <tr>
                                        <td class="text-center py-2">{{ $loop->iteration }}</td>

                                        <td class="py-2">
                                            {{ optional($vendor->vendorDetails)->shop_name ?? 'N/A' }}
                                        </td>

                                        <td class="py-2">{{ $vendor->name }}</td>

                                        <td class="py-2">{{ $vendor->phone ?? 'N/A' }}</td>

                                        <td class="py-2">{{ $vendor->email ?? 'N/A' }}</td>

                                        <td class="py-2">
                                            {{ optional($vendor->vendorDetails)->address ?? 'N/A' }}
                                        </td>
                                        
                                        <td class="py-2 text-center">
                                            @if($vendor->vendorDetails->commission_type == 1)
                                                <span class="badge bg-info">{{ $vendor->vendorDetails->commission }}% </span>
                                            @elseif($vendor->vendorDetails->commission_type == 2)
                                                <span class="badge bg-warning">৳ {{ number_format($vendor->vendorDetails->commission, 2) }}</span>
                                            @else
                                                <span class="badge bg-secondary">Not Set</span>
                                            @endif
                                            
                                        </td>

                                        <td class="text-center py-2">
                                            <a class="btn btn-sm btn-success p-2 commissionBtn" 
                                               href="javascript:void(0)"
                                               data-id="{{ $vendor->id }}"
                                               data-name="{{ $vendor->vendorDetails->shop_name ?? $vendor->name }}"
                                               data-type="{{ $vendor->commission_type }}"
                                               data-commission="{{ $vendor->commission }}">
                                               Commission Set
                                            </a>
                                            <form action="{{ route('admin.vendor-destroy', $vendor->id) }}" style="display: inline-block;"
                                                method="post">
                                                @csrf
                                                <button class="btn btn-sm btn-danger" type="submit"
                                                    onclick="return(confirm('Are you sure want to delete this item?'))">Delete</button>
                                            </form>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
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

<!-- Commission Modal -->
<div class="modal fade" id="commissionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('admin.vendor.commission.update') }}" method="POST" id="commissionForm">
            @csrf
            <input type="hidden" name="vendor_id" id="vendor_id">

            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Set Seller Commission</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label class="form-label fw-bold">Vendor</label>
                        <input type="text" id="vendor_name" class="form-control" disabled>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Commission Type</label>
                        <select name="commission_type" id="commission_type" class="form-select" required>
                            <option value="1">Percentage (%)</option>
                            <option value="2">Fixed Amount</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label fw-bold">Commission</label>
                        <input type="number" name="commission" id="commission" class="form-control" step="0.01" required>
                    </div>

                </div>

                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary">Save Commission</button>
                </div>
            </div>
        </form>
    </div>
</div>

@endsection

@section('script')
    <script src="{{ URL::asset('build/libs/select2/js/select2.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>
    
    <script>
    $(document).on("click", ".commissionBtn", function () {
    
        let id = $(this).data("id");
        let name = $(this).data("name");
        let type = $(this).data("type");
        let commission = $(this).data("commission");
    
        $("#vendor_id").val(id);
        $("#vendor_name").val(name);
    
        if(type) {
            $("#commission_type").val(type);
        }
    
        $("#commission").val(commission ?? '');
    
        $("#commissionModal").modal("show");
    });
    </script>
    
@endsection
