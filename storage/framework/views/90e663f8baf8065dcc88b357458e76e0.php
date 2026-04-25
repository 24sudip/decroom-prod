<section id="quikctech-bottom-nav" class="d-lg-none">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="quikctech-bottom-nav-menu">
                    <ul>
                        <!--Home-->
                        <?php if(auth()->guard('vendor')->check()): ?>
                        <li>
                            <a href="<?php echo e(route('vendor.dashboard')); ?>" title="<?php echo e(__('messages.home')); ?>">
                                <i class="fa-solid fa-house"></i>
                            </a>
                        </li>
                        <?php else: ?>
                        <li>
                            <a href="<?php echo e(route('home')); ?>" title="<?php echo e(__('messages.home')); ?>">
                                <i class="fa-solid fa-house"></i>
                            </a>
                        </li>
                        <!--Cart -->
                        <li class="position-relative">
                            <a href="<?php echo e(route('cart.view')); ?>" id="mobile-cart-link" title="<?php echo e(__('messages.cart')); ?>">
                                <i class="fa-solid fa-bag-shopping"></i>

                                <span id="mobile-cart-count"
                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                      style="font-size: 10px; display: <?php echo e(($cartCount ?? 0) > 0 ? 'inline-block' : 'none'); ?>">
                                    <?php echo e($cartCount ?? 0); ?>

                                </span>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!--Menu Offcanvas-->
                        <li>
                            <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft" title="<?php echo e(__('messages.menu')); ?>">
                                <i class="fa-solid fa-bars"></i>
                            </a>
                        </li>

                        <?php if(auth()->guard('vendor')->check()): ?>
                        <?php else: ?>
                            <?php if(session('user_type') !== 'customer'): ?>
                            <li>
                                <a href="<?php echo e(route('vendor.login')); ?>" title="Vendor Login">
                                    <i class="fa fa-male"></i>
                                </a>
                            </li>
                            <?php endif; ?>
                        <?php endif; ?>

                        <?php if(auth()->guard('customer')->check()): ?>
                        <!--Message / Chat-->
                        <li>
                            <a href="<?php echo e(route('customer.message')); ?>" title="<?php echo e(__('messages.message')); ?>">
                                <i class="fa-solid fa-message"></i>
                            </a>
                        </li>
                        <?php endif; ?>

                        <!--Profile-->
                        <?php if(auth()->guard('customer')->check()): ?>
                        <li>
                            <a href="<?php echo e(route('customer.profile')); ?>" title="<?php echo e(__('messages.profile')); ?>">
                                <?php if(auth('customer')->user()->image): ?>
                                    <img src="<?php echo e(asset('storage/'.auth('customer')->user()->image)); ?>"
                                         style="height: 22px; width: 22px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fa-solid fa-user"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        
                        <?php endif; ?>

                        <?php if(auth()->guard('vendor')->check()): ?>
                        <li>
                            <a href="<?php echo e(route('vendor.profile')); ?>" title="<?php echo e(__('messages.seller_profile')); ?>">
                                <?php if(auth('vendor')->user()->image): ?>
                                    <img src="<?php echo e(asset(auth('vendor')->user()->image)); ?>"
                                         style="height: 22px; width: 22px; border-radius: 50%; object-fit: cover;">
                                <?php else: ?>
                                    <i class="fa fa-male"></i>
                                <?php endif; ?>
                            </a>
                        </li>
                        
                        <?php endif; ?>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    #quikctech-bottom-nav ul {
        display: flex;
        justify-content: space-around;
        padding: 12px 0;
        margin: 0;
        list-style: none;
    }

    #quikctech-bottom-nav ul li a {
        font-size: 20px;
        color: #222;
    }

    #quikctech-bottom-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: white;
        box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
        z-index: 9999;
    }

</style>
<script>
    function updateMobileCartCount() {
        fetch("<?php echo e(route('cart.count')); ?>")
            .then(res => res.json())
            .then(data => {
                let count = data.count ?? 0;
                let badge = document.getElementById("mobile-cart-count");

                if (badge) {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? "inline-block" : "none";
                }
            });
    }

    document.addEventListener("DOMContentLoaded", updateMobileCartCount);
    window.updateMobileCartCount = updateMobileCartCount;

</script>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/bottom-nav.blade.php ENDPATH**/ ?>