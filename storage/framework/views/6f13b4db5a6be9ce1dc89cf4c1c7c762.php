<!DOCTYPE html>
<html lang="<?php echo e(app()->getLocale()); ?>">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>">
    <title><?php echo $__env->yieldContent('title'); ?></title>

    <?php echo $__env->make('frontend.include.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-3fpQq9q6bVxM20x/4RVvE6sW1+/I34mBS5CgKxQ5Euo="
    crossorigin="anonymous"></script>
</head>
<body>

<?php echo $__env->make('frontend.include.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.include.mobile-navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.include.bottom-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.include.offcanvas_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>



<?php echo $__env->make('frontend.include.offcanvas_content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<section id="quikctech-seller-home">
    <div class="container-fluid quikctech-fl">
        <div class="row gapp my-4">

            <!-- Sidebar Left -->
            <div class="col-lg-3">
                <div class="quicktech-side-bar-seller quikctech-mb-none">

                    <!-- Vendor Info -->
                    <a href="<?php echo e(route('vendor.dashboard')); ?>">
                        <div class="quikctech-seller-name text-center">
                            <?php
                                $user = auth('vendor')->user();
                                $vendor = App\Vendor::where('user_id', $user->id)->first();
                                $vendorInfo = $user ? ($user->vendorDetails ?? $vendor) : null;
                            ?>

                            <?php if($vendorInfo->logo && file_exists(public_path($vendorInfo->logo))): ?>
                                <img src="<?php echo e(asset($vendorInfo->logo)); ?>?t=<?php echo e(time()); ?>" alt="Profile Picture" width="100%">
                            <?php else: ?>
                                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($user->name)); ?>&background=random&size=200" alt="Profile Picture" width="100%">
                            <?php endif; ?>

                            <p><?php echo e($user->name ?? 'Vendor Name'); ?></p>
                        </div>
                    </a>

                    <!-- Sidebar Menu -->
                    <div class="quicktech-sidebar-menu">
                        <div class="accordion quicktech-sidebar" id="sidebarMenu">

                            <!-- PROFILE -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <a href="<?php echo e(route('vendor.profile')); ?>"
                                       class="accordion-button quicktech-imgg-side quikctech-no-drop">
                                        <img src="<?php echo e(asset('frontend/images/User-Profile-PNG.png')); ?>"
                                         style="height: 24px; width: 24px; display: inline-block;"
                                         alt="<?php echo e(__('messages.login')); ?>">
                                        <img src="<?php echo e(asset('frontend/images/user-avatar 2.png')); ?>" alt="">
                                        <?php echo e(__('messages.seller_profile')); ?>

                                    </a>
                                </h2>
                            </div>

                            <!-- PRODUCTS -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed quicktech-imgg-side"
                                            data-bs-toggle="collapse" data-bs-target="#productsMenu">
                                        <img src="<?php echo e(asset('frontend/images/box-2 1.png')); ?>" alt="">
                                        <?php echo e(__('messages.products')); ?>

                                    </button>
                                </h2>

                                <div id="productsMenu" class="accordion-collapse collapse">
                                    <ul class="list-unstyled ms-4 p-2">
                                        <li><a href="<?php echo e(route('vendor.products.manage')); ?>"><?php echo e(__('messages.manage_products')); ?></a></li>
                                        <li><a href="<?php echo e(route('vendor.products.create')); ?>"><?php echo e(__('messages.add_product')); ?></a></li>
                                    </ul>
                                </div>
                            </div>


                            <!-- SERVICES -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed quicktech-imgg-side"
                                            data-bs-toggle="collapse" data-bs-target="#servicesMenu">
                                        <img src="<?php echo e(asset('frontend/images/box-2 1.png')); ?>" alt="">
                                        <?php echo e(__('messages.services')); ?>

                                    </button>
                                </h2>

                                <div id="servicesMenu" class="accordion-collapse collapse">
                                    <ul class="list-unstyled ms-4 p-2">
                                        <li>
                                            <a href="<?php echo e(route('service-draft.index')); ?>">Post A Reel</a>
                                        </li>
                                        <li>
                                            <a href="<?php echo e(route('services.index')); ?>">
                                                <?php echo e(__('messages.manage_services')); ?>

                                            </a>
                                        </li>
                                        <li><a href="<?php echo e(route('services.create')); ?>"><?php echo e(__('messages.add_service')); ?></a></li>
                                    </ul>
                                </div>
                            </div>

                            <!-- INVENTORY -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed quicktech-imgg-side"
                                            data-bs-toggle="collapse" data-bs-target="#inventoryMenu">
                                        <img src="<?php echo e(asset('frontend/images/box-2 1.png')); ?>" alt="">
                                        Inventory
                                    </button>
                                </h2>

                                <div id="inventoryMenu" class="accordion-collapse collapse">
                                    <ul class="list-unstyled ms-4 p-2">
                                        <li><a href="<?php echo e(route('vendor.inventory.stock.manage')); ?>">Stock Manage</a></li>
                                        <li><a href="<?php echo e(route('vendor.inventory.stock.warning')); ?>">Stock Warning</a></li>
                                        <li><a href="<?php echo e(route('vendor.inventory.stock.out')); ?>">Stock Out</a></li>
                                    </ul>
                                </div>
                            </div>


                            <!-- ORDERS -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed quicktech-imgg-side"
                                            data-bs-toggle="collapse" data-bs-target="#ordersMenu">
                                        <img src="<?php echo e(asset('frontend/images/compliant 1.png')); ?>" alt="">
                                        <?php echo e(__('messages.order_review')); ?>

                                    </button>
                                </h2>

                                <div id="ordersMenu" class="accordion-collapse collapse">
                                    <ul class="list-unstyled ms-4 p-2">
                                        <li><a href="<?php echo e(route('vendor.orders.list')); ?>"><?php echo e(__('messages.orders')); ?></a></li>
                                    </ul>
                                </div>
                            </div>


                            <!-- WALLET & LEDGER -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed quicktech-imgg-side"
                                            data-bs-toggle="collapse" data-bs-target="#walletMenu">
                                        <img src="<?php echo e(asset('frontend/images/compliant 1.png')); ?>" alt="">
                                        <?php echo e(__('messages.wallet_ledger')); ?>

                                    </button>
                                </h2>

                                <div id="walletMenu" class="accordion-collapse collapse">
                                    <ul class="list-unstyled ms-4 p-2">
                                        <li><a href="<?php echo e(route('vendor.wallet')); ?>">Account</a></li>
                                        <li><a href="<?php echo e(route('vendor.wallet.all')); ?>"><?php echo e(__('messages.wallet')); ?></a></li>
                                        <li><a href="<?php echo e(route('vendor.transactions')); ?>"><?php echo e(__('messages.ledger')); ?></a></li>
                                    </ul>
                                </div>
                            </div>


                            <!-- STORE -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed quicktech-imgg-side"
                                            data-bs-toggle="collapse" data-bs-target="#storeMenu">
                                        <img src="<?php echo e(asset('frontend/images/supermaket.png')); ?>" alt="">
                                        <?php echo e(__('messages.store')); ?>

                                    </button>
                                </h2>

                                <div id="storeMenu" class="accordion-collapse collapse">
                                    <ul class="list-unstyled ms-4 p-2">
                                        <li><a href="<?php echo e(route('vendor.profile')); ?>"><?php echo e(__('messages.store_settings')); ?></a></li>
                                    </ul>
                                </div>
                            </div>


                            <!-- LANGUAGE -->
                            <div class="accordion-item">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed quicktech-imgg-side"
                                            data-bs-toggle="collapse" data-bs-target="#languageMenu">
                                        <img src="<?php echo e(asset('frontend/images/language.png')); ?>" alt="">
                                        <?php echo e(__('messages.language')); ?>

                                    </button>
                                </h2>

                                <div id="languageMenu" class="accordion-collapse collapse">
                                    <ul class="list-unstyled ms-4 p-2">
                                        <li><a href="<?php echo e(route('lang.change','en')); ?>">English</a></li>
                                        <li><a href="<?php echo e(route('lang.change','bn')); ?>">বাংলা</a></li>
                                    </ul>
                                </div>
                            </div>


                            <!-- LOGOUT -->
                            <div class="accordion-item mt-3">
                                <form action="<?php echo e(route('vendor.logout')); ?>" method="POST">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-danger w-100">
                                        <?php echo e(__('messages.logout')); ?>

                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Right Content -->
            <div class="col-lg-9">
                <div class="content">
                    <?php echo $__env->yieldContent('content'); ?>
                </div>
            </div>
        </div>
    </div>
</section>
<?php echo $__env->make('frontend.include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.include.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


</body>
</html>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/seller_master.blade.php ENDPATH**/ ?>