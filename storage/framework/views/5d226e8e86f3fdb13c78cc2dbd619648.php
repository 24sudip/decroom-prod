<?php $__env->startSection('title', 'Home'); ?>

<?php $__env->startSection('content'); ?>
    <!-- banner -->
    <?php echo $__env->make('frontend.include.banner', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- banner -->

    <!-- service  offer-->
    <?php echo $__env->make('frontend.include.service_offer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- service offer -->

    <!-- product  offer-->
    <?php echo $__env->make('frontend.include.product_offer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- product offer -->

    <!-- products cate -->
    <?php echo $__env->make('frontend.include.product_category', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- products cate -->

    <!-- service center -->
    <?php echo $__env->make('frontend.include.service_center', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- service center -->
<?php $__env->stopSection(); ?>


<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/index.blade.php ENDPATH**/ ?>