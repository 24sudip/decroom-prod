<div class="quicktech-seller-menu-top">
    <ul>
        <li><a href="<?php echo e(route('vendor.dashboard')); ?>"><img src="<?php echo e(asset('frontend/images/store 1.png')); ?>" alt="<?php echo e(__('messages.store')); ?>"></a></li>
        <li><a href="<?php echo e(route('vendor.profile.edit')); ?>"><img src="<?php echo e(asset('frontend/images/settings (2).png')); ?>" alt="<?php echo e(__('messages.settings')); ?>"></a></li>
        <li><a href="<?php echo e(route('vendor.message')); ?>"><img src="<?php echo e(asset('frontend/images/volunteering.png')); ?>" alt="<?php echo e(__('messages.volunteer')); ?>"></a></li>
        
        <?php
            $user = auth('vendor')->user();
            $vendor = App\Vendor::where('user_id', $user->id)->first();
            $vendorInfo = $user ? ($user->vendorDetails ?? $vendor) : null;
            $n_count = $vendorInfo->unreadNotifications()->count();
        ?>
        <li class="nav-item dropdown dropdown-large">
            <a class="nav-link dropdown-toggle dropdown-toggle-nocaret position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo e(asset('frontend/images/bell.png')); ?>" alt="<?php echo e(__('messages.notifications')); ?>">
                <span class="alert-count" id="notification-count"><?php echo e($n_count); ?></span>
            </a>
            <div class="dropdown-menu dropdown-menu-end">
                <a href="javascript:;">
                    <div class="msg-header">
                        <p class="msg-header-title"><?php echo e($vendorInfo->notifications->count()); ?> Notifications</p>
                        
                    </div>
                </a>
                <div class="header-notifications-list">
                    <?php $__empty_1 = true; $__currentLoopData = $vendorInfo->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <a class="dropdown-item" href="javascript:;" onclick="markNotificationRead('<?php echo e($notification->id); ?>')">
                        <div class="d-flex align-items-center">
                            <div class="notify bg-light-danger text-danger"><i class="bx bx-cart-alt"></i>
                            </div>
                            <div class="flex-grow-1">
                                <h6 class="msg-name"><?php echo e($notification->data['message']); ?>

                                    <span class="msg-time float-end">
                                        <?php echo e(Carbon\Carbon::parse($notification->created_at)->diffForHumans()); ?>

                                    </span>
                                </h6>
                                <p class="msg-info">New</p>
                            </div>
                        </div>
                    </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <a href="javascript:;">
                        <div class="text-center msg-footer">No Notification</div>
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </li>
    </ul>
</div>
<script>
function markNotificationRead(notificationId) {
    fetch("/mark-notification-as-read/" + notificationId, {
        method: 'POST',
        headers: {
            "Content-Type": "application/json",
            "X-CSRF-TOKEN": "<?php echo e(csrf_token()); ?>",
        },
        body: JSON.stringify({})
    })
    .then(response => response.json())
    .then(data => {
        document.getElementById("notification-count").textContent = data.count;
    })
    .catch(error => {
        console.log('Error', error);
    });
}
</script>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/seller-menu-top.blade.php ENDPATH**/ ?>