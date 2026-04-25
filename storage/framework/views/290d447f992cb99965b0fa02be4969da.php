

<?php $__env->startSection('title', __('All Orders')); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('build/libs/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
    <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <div class="row mb-3">
        <div class="col-md-2">
            <label>Status</label>
            <select id="statusFilter" class="form-select">
                <option value="">All Status</option>
                <?php $__currentLoopData = $ordertypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($type->id); ?>"><?php echo e($type->name); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
                <?php $__currentLoopData = $customers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $customer): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($customer->id); ?>"><?php echo e($customer->name); ?> (<?php echo e($customer->phone); ?>)</option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
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
        <table id="orders-table" class="table table-bordered table-striped">
            <thead>
                <tr>
                    <th>#</th>
                    <th><?php echo e(__('Tracking ID')); ?></th>
                    <th><?php echo e(__('Customer')); ?></th>
                    <th><?php echo e(__('Phone')); ?></th>
                    <th><?php echo e(__('Status')); ?></th>
                    <th><?php echo e(__('Total')); ?></th>
                    <th><?php echo e(__('Method')); ?></th>
                    <th><?php echo e(__('Date')); ?></th>
                    <th><?php echo e(__('Action')); ?></th>
                </tr>
            </thead>
            <tbody></tbody>
        </table>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
    <script src="<?php echo e(URL::asset('build/libs/select2/js/select2.min.js')); ?>"></script>
    <script src="https://cdn.datatables.net/1.13.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
    <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        let table;

        $(function() {
            table = $('#orders-table').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: '<?php echo e(route('report.order.all.data')); ?>',
                    data: function(d) {
                        d.status = $('#statusFilter').val();
                        d.from = $('#fromDate').val();
                        d.to = $('#toDate').val();
                        d.customer_id = $('#customerFilter').val();
                        d.customer_type = $('#customerTypeFilter').val();
                    },
                    error: function(xhr, error) {
                        console.error('DataTables Ajax error:', error);
                        alert('Failed to load data. Check console for details.');
                    }
                },
                dom: 'Bfrtip',
                buttons: [{
                        extend: 'copy',
                        className: 'btn btn-sm btn-secondary'
                    },
                    {
                        extend: 'excel',
                        className: 'btn btn-sm btn-success'
                    },
                    {
                        extend: 'pdf',
                        className: 'btn btn-sm btn-danger'
                    },
                    {
                        extend: 'print',
                        className: 'btn btn-sm btn-primary'
                    }
                ],
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
                        name: 'customer.name'
                    },
                    {
                        data: 'phone',
                        name: 'customer.phone'
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

            // Reload table on all filter changes including customer type
            $('#statusFilter, #fromDate, #toDate, #customerFilter, #customerTypeFilter').on('change', function() {
                table.ajax.reload();
            });

            // Status update event handler
            $(document).on('change', '.status-update', function() {
                const id = $(this).data('id');
                const status = $(this).val();

                $.ajax({
                    url: '<?php echo e(route('order.update.status')); ?>',
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
        });
    </script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/orders/all.blade.php ENDPATH**/ ?>