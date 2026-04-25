

<?php $__env->startSection('title', __('Order Commission')); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('build/libs/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12 p-2">

            <div class="d-flex justify-content-between align-items-center py-2">
                <h5 class="title">Order Commission</h5>
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
                                    <th class="py-2">Vendor</th>
                                    <th class="py-2">Product/Service</th>
                                    <th class="py-2">Total Price</th>
                                    <th class="py-2">Admin Commission</th>
                                    <th class="py-2">Vendor Earning</th>
                                    <th class="text-center py-2">Order Time</th>
                                </tr>
                            </thead>

                            <tbody>
                                <?php $__empty_1 = true; $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <tr>
                                            <td class="text-center py-2"><?php echo e($loop->parent->iteration); ?></td>

                                            <!-- Vendor -->
                                            <td class="py-2">
                                                <?php echo e(optional($item->vendor)->name 
                                                   ?? optional($order->user)->name 
                                                   ?? 'N/A'); ?>

                                            </td>

                                            <!-- Product or service name -->
                                            <td class="py-2">
                                                <?php if($item->product_name): ?>
                                                    <?php echo e($item->product_name); ?>

                                                <?php elseif($item->service_name): ?>
                                                    <?php echo e($item->service_name); ?>

                                                <?php else: ?> 
                                                    N/A
                                                <?php endif; ?>
                                                <br>
                                                <?php if($item->quantity): ?>
                                                    Quantity: <?php echo e($item->quantity); ?>

                                                <?php endif; ?>
                                            </td>

                                            <!-- Total Price -->
                                            <td class="py-2">
                                                <?php echo e(number_format($item->total_price, 2)); ?>

                                            </td>

                                            <!-- Admin Commission -->
                                            <td class="py-2 text-danger fw-bold">
                                                <?php echo e(number_format($item->admin_commission, 2)); ?>

                                            </td>

                                            <!-- Vendor Earning -->
                                            <td class="py-2 text-success fw-bold">
                                                <?php echo e(number_format($item->vendor_earning, 2)); ?>

                                            </td>

                                            <!-- Order Date -->
                                            <td class="text-center py-2">
                                                <?php echo e($order->created_at->format('d M, Y h:i A')); ?>

                                            </td>
                                        </tr>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-3">
                                            <h4>No Orders Found!</h4>
                                        </td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>

                        </table>

                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/vendor/ordercommission.blade.php ENDPATH**/ ?>