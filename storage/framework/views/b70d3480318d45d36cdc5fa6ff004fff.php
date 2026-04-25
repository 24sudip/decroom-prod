
<?php $__env->startSection('title','404 Not Found'); ?>
<?php $__env->startSection('body'); ?>
<body>
<?php $__env->stopSection(); ?>
<?php $__env->startSection('content'); ?>
<div class="container text-center mt-5">
    <h1>404</h1>
    <h3>Page Not Found</h3>
    <p>The page you are looking for does not exist.</p>
    <a href="<?php echo e(url('/')); ?>" class="btn btn-primary">Go Home</a>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/error/404.blade.php ENDPATH**/ ?>