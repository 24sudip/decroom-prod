<style>
    #quicktech-topbar {
    padding: 0px 0;
}
</style>
<div id="chat-alert-container"
     style="position: fixed; top: 20px; right: 20px; z-index: 1055;">
</div>
<section id="quicktech-topbar">
    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-4">
                <div class="quikctech-t-text" style="padding-top: 12px;">
                    <p><?php echo e(now()->format('l, F d, Y')); ?></p>
                </div>
            </div>
            <style>
                .top_nav_ul {
                    margin-top: 4px;
                    align-items: center;
                }
            </style>
            <div class="col-lg-8">
                <div class="quikctech-top-right">
                    <ul class="top_nav_ul">
                        <!--<li><a href=""><?php echo e(__('messages.get_offer')); ?></a></li>-->
                        <?php if(auth()->guard('vendor')->check()): ?>
                        <li>
                            <form action="<?php echo e(route('vendor.logout')); ?>" method="POST" style="margin-top: 3px;">
                                <?php echo csrf_field(); ?>
                                <button class="btn btn-outline-danger w-100">
                                    <?php echo e(__('messages.logout')); ?>

                                </button>
                            </form>
                        </li>
                        <?php else: ?>
                        <li><a href="<?php echo e(route('vendor.register')); ?>">Join as a Vendor</a></li>
                        <?php endif; ?>
                        
                        <li><a href="<?php echo e(route('help_center')); ?>"><?php echo e(__('messages.help_support')); ?></a></li>

                        
                        <li>
                            <a href="<?php echo e(route('lang.change','en')); ?>"><?php echo e(__('messages.english')); ?></a> /
                            <a href="<?php echo e(route('lang.change','bn')); ?>"><?php echo e(__('messages.bangla')); ?></a>
                        </li>
                        
                        <li>
                            <div class="quikctech-destop-profile">
                                <div class="dropdown quikctech-dropdown-wrapper">
                                    <?php if(auth()->guard('vendor')->check()): ?>
                                    <!-- Customer is logged in - Show customer logo/avatar height: 40px; border-radius: 50%; object-fit: cover; 'storage/' .  -->
                                    <button class="btn quikctech-dropdown-btn position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo e(__('messages.profile')); ?>">
                                        <?php if(auth('vendor')->user()->image): ?>
                                            <img src="<?php echo e(asset(auth('vendor')->user()->image)); ?>" 
                                                 style="width: 40px;" 
                                                 alt="<?php echo e(auth('vendor')->user()->name); ?>">
                                        <?php else: ?>
                                            <div class="customer-avatar-inline d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                                                 style="height: 40px; width: 40px; background: linear-gradient(45deg, #007bff, #0056b3); font-size: 16px;">
                                                <?php echo e(strtoupper(substr(auth('vendor')->user()->name, 0, 1))); ?>

                                            </div>
                                        <?php endif; ?>
                                        <span class="position-absolute bottom-0 end-0 bg-success rounded-circle border border-2 border-white"
                                              style="width: 12px; height: 12px;"></span>
                                    </button>
                                    <?php else: ?>
                                    <button class="btn quikctech-login-btn d-flex align-items-center gap-2" 
                                            type="button" 
                                            data-bs-toggle="dropdown" 
                                            aria-expanded="false"
                                            title="<?php echo e(__('messages.vendor_login')); ?>"
                                            style="border: 2px solid #007bff; border-radius: 25px; padding: 8px 16px; background: transparent; transition: all 0.3s ease;">
                                        <img src="<?php echo e(asset('frontend/images/User-Profile-PNG.png')); ?>" 
                                             style="height: 24px; width: 24px;" 
                                             alt="<?php echo e(__('messages.vendor_login')); ?>">
                                        <span class="text-primary fw-semibold" style="font-size: 14px;"><?php echo e(__('messages.vendor_login')); ?></span>
                                    </button>
                                    <?php endif; ?>
                                    <div class="dropdown-menu quikctech-dropdown shadow-lg border-0 rounded-3 p-0" style="min-width: 280px;">
                                        <div class="p-4 text-center">
                                            <!--<div class="mt-3 pt-3 border-top">-->
                                            <!--    <small class="text-muted d-block mb-2"><?php echo e(__('messages.are_you_vendor')); ?></small>-->
                                            <!--</div>-->
                                            <?php if(auth()->guard('vendor')->check()): ?>
                                            <a href="<?php echo e(route('vendor.profile')); ?>" class="btn btn-outline-success btn-sm w-100">
                                                <i class="fas fa-store me-1"></i>
                                                <?php echo e(__('messages.seller_profile')); ?>

                                            </a>
                                            <?php else: ?>
                                            <a href="<?php echo e(route('vendor.login')); ?>" class="btn btn-outline-success btn-sm w-100">
                                                <i class="fas fa-store me-1"></i>
                                                <?php echo e(__('messages.vendor_login')); ?>

                                            </a>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<script>
function showChatAlert(message) {
    const container = document.getElementById('chat-alert-container');

    const alert = document.createElement('div');
    alert.className = 'alert alert-info alert-dismissible fade show shadow';
    alert.innerHTML = `
        <strong>New Message</strong><br>
        ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    container.appendChild(alert);

    // Auto fade after 4 seconds
    setTimeout(() => {
        alert.classList.remove('show');
        alert.classList.add('hide');
        setTimeout(() => alert.remove(), 500);
    }, 4000);
}
</script>

<script>
let notified = false;

setInterval(function () {
    fetch("<?php echo e(url('/vendor/unread-messages')); ?>")
        .then(response => response.json())
        .then(data => {
            if (data.count > 0 && !notified) {
                // toastr.info("You have new chat messages");
                showChatAlert(`
                <a href="/vendor/me/seller/chat-withcustomer/${data.customer}/${data.customer_id}">
                ${data.customer}: ${data.message}
                </a>
                `);
                notified = true;
            }

            if (data.count === 0) {
                notified = false;
            }
        });
}, 5000);
</script>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/topbar.blade.php ENDPATH**/ ?>