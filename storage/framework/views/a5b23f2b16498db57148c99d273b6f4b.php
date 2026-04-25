<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?> | <?php echo e(AppSetting('title')); ?> </title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Hospital Management System" name="description" />
    <meta content="Doctorly" name="author" />
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">

    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(URL::asset('build/images/') . '/' . AppSetting('favicon')); ?>">
    <?php echo $__env->make('backend.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<?php echo $__env->yieldContent('body'); ?>

<div id="preloader">
    <div id="status">
        <div class="spinner-chase">
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
            <div class="chase-dot"></div>
        </div>
    </div>
</div>

<?php echo $__env->yieldContent('content'); ?>

<?php echo $__env->make('backend.layouts.footer-script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

</body>

</html>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/layouts/master-without-nav.blade.php ENDPATH**/ ?>