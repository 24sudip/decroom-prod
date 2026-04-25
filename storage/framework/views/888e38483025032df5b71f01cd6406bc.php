<!-- Right Sidebar -->
<div class="right-bar">
    <div data-simplebar class="h-100">
        <div class="rightbar-title d-flex align-items-center px-3 py-4">
            <h5 class="m-0 me-2"><?php echo e(__('Settings')); ?></h5>
            <a href="javascript:void(0);" class="right-bar-toggle ms-auto">
                <i class="mdi mdi-close noti-icon"></i>
            </a>
        </div>
        <!-- Settings -->
        <hr class="mt-0" />
        <h6 class="text-center mb-0"><?php echo e(__('Choose Layouts')); ?></h6>
        <div class="p-4">
            <div class="mb-2">
                <img src="<?php echo e(URL::asset('build/images/layouts/layout-1.jpg')); ?>" class="img-thumbnail" alt="">
            </div>
            <div class="form-check form-switch mb-3">
                <input type="checkbox" class="form-check-input theme-choice" id="light-mode-switch" checked />
                <label class="form-check-label" for="light-mode-switch"><?php echo e(__('Light Mode')); ?></label>
            </div>
            <div class="mb-2">
                <img src="<?php echo e(URL::asset('build/images/layouts/layout-2.jpg')); ?>" class="img-thumbnail" alt="">
            </div>
            <div class="form-check form-switch mb-3">
                <input type="checkbox" class="form-check-input theme-choice" id="dark-mode-switch"
                    data-bsStyle="<?php echo e(URL::asset('build/css/bootstrap-dark.min.css')); ?>"
                    data-appStyle="<?php echo e(URL::asset('build/css/app-dark.min.css')); ?>" />
                <label class="form-check-label" for="dark-mode-switch"><?php echo e(__('Dark Mode')); ?></label>
            </div>
            <div class="mb-2">
                <img src="<?php echo e(URL::asset('build/images/layouts/layout-3.jpg')); ?>" class="img-thumbnail" alt="">
            </div>
            <div class="form-check form-switch mb-5">
                <input type="checkbox" class="form-check-input theme-choice" id="rtl-mode-switch"
                    data-appStyle="<?php echo e(URL::asset('build/css/app-rtl.min.css')); ?>" />
                <label class="form-check-label" for="rtl-mode-switch"><?php echo e(__('RTL Mode')); ?></label>
            </div>
            <a href="<?php echo e(url('/')); ?>" class="btn btn-success w-100 mt-3" target="_blank"><i
                    class="bx bx-home-circle me-1"></i> <?php echo e(__('Front Side')); ?></a>
            <a href="https://1.envato.market/doctorly-laravel" class="btn btn-primary w-100 mt-3" target="_blank"><i
                    class="mdi mdi-cart me-1"></i> <?php echo e(__('Purchase Now')); ?></a>
        </div>
    </div> <!-- end slim-scroll-menu-->
</div>
<!-- /Right-bar -->

<div class="rightbar-overlay"></div>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/layouts/right-sidebar.blade.php ENDPATH**/ ?>