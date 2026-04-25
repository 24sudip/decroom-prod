<div class="offcanvas offcanvas-start" tabindex="-1" id="offcanvasLeft" aria-labelledby="offcanvasLeftLabel">
    <div class="offcanvas-header">
        <img src="<?php echo e(asset('frontend/images/logo-removebg-preview(3).png')); ?>" style="height: 50px;" alt="<?php echo e(__('messages.logo')); ?>">
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="<?php echo e(__('messages.close')); ?>"></button>
    </div>

    <div class="offcanvas-body">
        <?php if(auth()->guard('vendor')->check()): ?>
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
                            <li><a href="<?php echo e(route('services.index')); ?>"><?php echo e(__('messages.manage_services')); ?></a></li>
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
                            <li><a href="<?php echo e(route('vendor.wallet')); ?>"><?php echo e(__('messages.wallet')); ?></a></li>
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
        <?php endif; ?>
        <!--Product Categories-->
        <a class="d-flex justify-content-between align-items-center fw-bold mb-2"
           data-bs-toggle="collapse"
           href="#mobileProductCat"
           role="button"
           aria-expanded="false"
           aria-controls="mobileProductCat">
            <span><?php echo e(__('messages.product_categories')); ?></span>
            <i class="fas fa-chevron-down"></i>
        </a>

        <ul class="quikctech-mob-cat collapse" id="mobileProductCat">
            <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $cat): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e(route('product_category', $cat->slug)); ?>" class="d-block py-2">
                        <?php echo e($cat->name); ?>

                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

        <!--<hr>-->

        <!--Service Categories-->
        <a class="d-flex justify-content-between align-items-center fw-bold mb-2"
           data-bs-toggle="collapse"
           href="#mobileServiceCat"
           role="button"
           aria-expanded="false"
           aria-controls="mobileServiceCat">
            <span><?php echo e(__('messages.service_categories')); ?></span>
            <i class="fas fa-chevron-down"></i>
        </a>

        <ul class="quikctech-mob-cat collapse" id="mobileServiceCat">
            <?php $__currentLoopData = $serviceCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $sc): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <li>
                    <a href="<?php echo e(route('service_category', $sc->slug)); ?>" class="d-block py-2">
                        <?php echo e($sc->name); ?>

                    </a>
                </li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </ul>

    </div>
</div><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/offcanvas_menu.blade.php ENDPATH**/ ?>