
<style>
    .logo-lg img{
        height:30px;
        background-color:white;
    }
</style>
<header id="page-topbar">
    <div class="navbar-header">
        <div class="d-flex">
            <!-- LOGO -->
            <div class="navbar-brand-box">
                <a href="<?php echo e(url('/dashboard')); ?>" class="logo logo-dark">
                    <span class="logo-lg">
                        <img src="<?php echo e(asset('build/images/' . AppSetting('logo_dark_lg'))); ?>" alt="Logo Dark LG"
                            height="22">
                    </span>
                    <span class="logo-sm">
                        <img src="<?php echo e(asset('build/images/' . AppSetting('logo_dark_sm'))); ?>" alt="Logo Dark SM"
                            height="17">
                    </span>
                </a>

                <a href="<?php echo e(url('/dashboard')); ?>" class="logo logo-light">
                    <span class="logo-lg">
                        <img src="<?php echo e(asset('build/images/' . AppSetting('logo_lg'))); ?>" alt="Logo LG" height="22">
                    </span>
                    <span class="logo-sm">
                        <img src="<?php echo e(asset('build/images/' . AppSetting('logo_sm'))); ?>" alt="Logo SM" height="19">
                    </span>
                </a>
            </div>

            <button type="button" class="btn btn-sm px-3 font-size-16 d-lg-none header-item waves-effect waves-light"
                data-bs-toggle="collapse" data-bs-target="#topnav-menu-content" id="toggle-button">
                <i class="fa fa-fw fa-bars"></i>
            </button>
        </div>

        <div class="d-flex">
            <!-- Search (Mobile Only) -->
            <div class="dropdown d-inline-block d-lg-none ms-2">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="dropdown"
                    aria-haspopup="true" aria-expanded="false">
                    <i class="mdi mdi-magnify"></i>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                    <form class="p-3">
                        <div class="mb-0">
                            <div class="input-group">
                                <input type="text" class="form-control" placeholder="<?php echo e(__('Search ...')); ?>">
                                <button class="btn btn-primary" type="submit"><i class="mdi mdi-magnify"></i></button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Language -->
          

            <!-- Fullscreen -->
            <div class="dropdown d-none d-lg-inline-block ms-1">
                <button type="button" class="btn header-item noti-icon waves-effect" data-bs-toggle="fullscreen">
                    <i class="bx bx-fullscreen"></i>
                </button>
            </div>

            <!-- Notifications -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon waves-effect"
                    id="page-header-notifications-dropdown" data-bs-toggle="dropdown">
                    <i class="bx bx-bell bx-tada"></i>
                    <span class="badge bg-danger rounded-pill"></span>
                </button>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end p-0">
                    <div class="p-3 border-bottom">
                        <div class="d-flex justify-content-between align-items-center">
                            <h6 class="m-0"><?php echo e(__('Notifications')); ?></h6>
                            <a href="<?php echo e(url('/notification-list')); ?>" class="small"><?php echo e(__('View All')); ?></a>
                        </div>
                    </div>
                    <div class="p-2 border-top">
                        <a class="btn btn-sm btn-link font-size-14 w-100 text-center"
                            href="<?php echo e(url('/notification-list')); ?>">
                            <i class="mdi mdi-arrow-right-circle me-1"></i> <?php echo e(__('View More..')); ?>

                        </a>
                    </div>
                </div>
            </div>

            <!-- User Menu -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item waves-effect" id="page-header-user-dropdown"
                    data-bs-toggle="dropdown">
                    <img class="rounded-circle header-profile-user"
                        src="<?php echo e(Auth::check() && Auth::user()->image ? asset('storage/users/' . Auth::user()->image) : asset('storage/logo.png')); ?>"
                        alt="Avatar">
                    <span
                        class="d-none d-xl-inline-block ms-1"><?php echo e(Auth::check() ? Auth::user()->name : __('Guest')); ?></span>
                    <i class="mdi mdi-chevron-down d-none d-xl-inline-block"></i>
                </button>

                <div class="dropdown-menu dropdown-menu-end">
                    <a class="dropdown-item" href="' . route('user.show', Auth::user()->id) . '">
                        <i class="bx bx-user font-size-16 align-middle me-1"></i> <?php echo e(__('translation.profile')); ?>

                    </a>
                    <a class="dropdown-item" href="<?php echo e(url('change-password')); ?>">
                        <i class="bx bx-wrench font-size-16 align-middle me-1"></i>
                        <?php echo e(__('translation.change-password')); ?>

                    </a>
                    <div class="dropdown-divider"></div>

                    <form id="logout-form" action="<?php echo e(route('admin.logout')); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <button type="submit" class="dropdown-item btn btn-block ml-2">
                            Logout
                        </button>
                    </form>


                </div>
            </div>

            <!-- Settings -->
            <div class="dropdown d-inline-block">
                <button type="button" class="btn header-item noti-icon right-bar-toggle waves-effect">
                    <i class="bx bx-cog bx-spin"></i>
                </button>
            </div>
        </div>
    </div>
</header>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/layouts/top-hor.blade.php ENDPATH**/ ?>