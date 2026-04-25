<?php $__env->startSection('title', 'Seller Dashboard'); ?>

<?php $__env->startSection('content'); ?>

    <!-- seller-menu-top -->
    <?php echo $__env->make('frontend.include.seller-menu-top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- seller-menu-top -->

    <!-- seller-dasboard-->
    <?php echo $__env->make('frontend.include.seller-dasboard', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- seller-dasboard -->

    <!-- dashboard-top-selling-->
    <?php echo $__env->make('frontend.include.dashboard-top-selling', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- dashboard-top-selling -->
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/dashboard.blade.php ENDPATH**/ ?>