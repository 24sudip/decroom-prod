<?php $__env->startSection('title', __('Seller Withdraws')); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('build/libs/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />

    <style>
        .modal-content img {
            width: 100%;
            border-radius: 8px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
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
                                <?php $__currentLoopData = $show_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td><?php echo e($loop->iteration); ?></td>

                                        <td><?php echo e(optional($value->vendor)->vendorDetails->shop_name ?? 'N/A'); ?></td>

                                        <td>
                                            <?php if($value->vendor): ?>
                                            <?php echo e($value->vendor->phone); ?>

                                            <?php else: ?>
                                            No Vendor Found
                                            <?php endif; ?>
                                        </td>

                                        <td>BDT <?php echo e(number_format($value->amount, 2)); ?></td>
                                        <td><?php echo e($value->note ?? 'N/A'); ?></td>
                                        <td>
                                            <span class="badge bg-info text-dark">
                                                <?php echo e(App\User::find($value->approved_by)->name ?? 'N/A'); ?>

                                            </span>
                                        </td>

                                        <td>
                                            <?php if($value->receipt): ?>
                                                <!-- View Button -->
                                                <button type="button"
                                                        class="btn btn-outline-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#viewModal<?php echo e($value->id); ?>">
                                                    <i class="fa fa-eye"></i> View
                                                </button>
                                            <?php else: ?>
                                                <!-- Upload Button -->
                                                <button type="button"
                                                        class="btn btn-primary btn-sm"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#uploadModal<?php echo e($value->id); ?>">
                                                    <i class="fa fa-upload"></i> Upload
                                                </button>
                                            <?php endif; ?>
                                        </td>

                                        <td>
                                            <span class="badge bg-success">Completed</span>
                                        </td>
                                    </tr>

                                    <!-- Upload Modal -->
                                    <div class="modal fade" id="uploadModal<?php echo e($value->id); ?>" tabindex="-1" aria-labelledby="uploadLabel<?php echo e($value->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered">
                                            <div class="modal-content">

                                                <div class="modal-header">
                                                    <h5 class="modal-title" id="uploadLabel<?php echo e($value->id); ?>">
                                                        Upload Receipt
                                                    </h5>

                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>

                                                <form action="<?php echo e(route('seller.receiptUpload')); ?>" method="POST" enctype="multipart/form-data">
                                                    <?php echo csrf_field(); ?>
                                                    <div class="modal-body">

                                                        <input type="hidden" name="hidden_id" value="<?php echo e($value->id); ?>">

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
                                    <div class="modal fade" id="viewModal<?php echo e($value->id); ?>" tabindex="-1" aria-labelledby="viewLabel<?php echo e($value->id); ?>" aria-hidden="true">
                                        <div class="modal-dialog modal-dialog-centered modal-lg">
                                            <div class="modal-content p-0 border-0 bg-transparent shadow-none">

                                                <div class="modal-header border-0">
                                                    <button type="button" class="btn-close bg-white rounded-circle p-2" data-bs-dismiss="modal"></button>
                                                </div>

                                                <div class="modal-body p-0">
                                                    <img src="<?php echo e(asset($value->receipt)); ?>" class="img-fluid rounded" alt="Receipt Image">
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>

                        </table>
                    </div>

                </div>
            </div>
        </div>
    </section>
<?php $__env->stopSection(); ?>


<?php $__env->startSection('script'); ?>
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/vendor/withdraws.blade.php ENDPATH**/ ?>