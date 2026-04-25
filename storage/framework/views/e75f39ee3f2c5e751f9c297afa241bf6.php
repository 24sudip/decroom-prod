<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description"
        content="Manage your hospital and clinic operations efficiently with Doctorly, a powerful Laravel-based system for healthcare institutions. Streamline patient records, appointments, billing, and more.">
    <meta name="keywords" content="Doctorly, Hospital Management, Clinic Management, Laravel System, Healthcare Software">
    <meta name="author" content="Themesbrand">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">


    <!-- App favicon -->
    <link rel="shortcut icon" href="<?php echo e(URL::asset('build/images/') . '/' . AppSetting('favicon')); ?>">
    <?php echo $__env->make('backend.layouts.head', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</head>

<body data-sidebar="dark" data-topbar="light" data-layout="vertical">

    <!-- Loader -->
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
    <!-- Begin page -->

    <div id="layout-wrapper">
        <?php echo $__env->make('backend.layouts.top-hor', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        <?php echo $__env->make('backend.layouts.sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <!-- Start content -->
                <div class="container-fluid">
                    <?php echo $__env->yieldContent('content'); ?>
                </div> <!-- content -->
            </div>
            <?php echo $__env->make('backend.layouts.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
        <!-- ============================================================== -->
        <!-- End Right content here -->
        <!-- ============================================================== -->
    </div>
    <!-- END wrapper -->

    <!-- Right Sidebar -->
    <?php echo $__env->make('backend.layouts.right-sidebar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- END Right Sidebar -->

    <?php echo $__env->make('backend.layouts.footer-script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>

</html>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/layouts/master-layouts.blade.php ENDPATH**/ ?>