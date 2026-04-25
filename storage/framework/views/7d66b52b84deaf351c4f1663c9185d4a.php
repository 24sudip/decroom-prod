<?php $__env->startSection('title', 'Warning Stock Manage'); ?>
<?php $__env->startSection('content'); ?>

<?php echo $__env->make('frontend.include.seller-menu-top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="quikctech-manage-p-inner">
    <div class="quicktech-manage-head">
        <h4>Warning Stock Manage</h4>
    </div>
</div>

<?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        <?php echo e(session('success')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
        <?php echo e(session('error')); ?>

        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
<?php endif; ?>

<form action="<?php echo e(route('vendor.inventory.warning-limit.update')); ?>" method="post">
    <?php echo csrf_field(); ?>
    <div class="form-group mb-3">
        <label class="form-label" for="product_warning_limit">Product Warning Limit</label>
        <input type="number" class="form-control" name="product_warning_limit" id="product_warning_limit"
        value="<?php echo e(old('product_warning_limit', $real_vendor->product_warning_limit)); ?>">
    </div>
    <button type="submit" class="btn btn-primary mb-3">Save</button>
</form>

<div class="table-responsive">
    <table class="table quicktech-manage-table">
        <thead>
            <tr>
                <th>#</th>
                <th>Image</th>
                <th>Product</th>
                <th>Category</th>
                <th>Brand</th>
                <th>Stock</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($key + 1); ?></td>

                    <td>
                        <?php if($product->primaryImage()): ?>
                            <img
                                src="<?php echo e(asset($product->primaryImage()->image_path)); ?>"
                                width="50"
                                height="50"
                                class="img-thumbnail"
                            >
                        <?php else: ?>
                            <img
                                src="<?php echo e(asset('frontend/images/no-image.png')); ?>"
                                width="50"
                                height="50"
                            >
                        <?php endif; ?>
                    </td>

                    <td><?php echo e($product->name); ?></td>
                    <td><?php echo e($product->category->name ?? '-'); ?></td>
                    <td><?php echo e($product->brand->name ?? '-'); ?></td>

                    <td>
                        <span class="badge bg-<?php echo e($product->total_stock > 0 ? 'success' : 'danger'); ?>">
                            <?php echo e($product->total_stock); ?>

                        </span>
                    </td>

                    <td>
                        <span style="color: <?php echo e($product->status_color); ?>">
                            <?php echo e($product->status_text); ?>

                        </span>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center">No products found</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/inventory/stock_warning.blade.php ENDPATH**/ ?>