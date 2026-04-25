<div class="quikctech-seller-dasboard mt-4">
    <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong><?php echo e(session('success')); ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    
    <?php if(session('error')): ?>
    <div class="alert alert-danger alert-dismissible fade show" role="alert">
      <strong><?php echo e(session('error')); ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <div class="quikctech-dashboard-head">
        <h5><img style="height: 20px;" src="<?php echo e(asset('frontend/images/dashboard (1).png')); ?>" alt="<?php echo e(__('messages.dashboard_icon')); ?>"> <?php echo e(__('messages.dashboard')); ?></h5>
    </div>

    <hr>

    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h5 class="quikctech-dashboard-title"><?php echo e(__('messages.dashboard_order_statistics')); ?></h5>
        <select class="form-select w-auto">
            <option><?php echo e(__('messages.overall_statistics')); ?></option>
        </select>
    </div>

    <!-- Top Row -->
    <div class="row g-3 mb-3">
        <div class="col-lg-3 col-md-4">
            <a href="<?php echo e(route('vendor.orders.list', ['status' => 2])); ?>">
                <div class="quikctech-dashboard-card quikctech-dashboard-bg-blue">
                    <div>
                        <div class="quikctech-dashboard-value text-primary"><?php echo e($orderStats['accepted']); ?></div>
                        <div class="quikctech-dashboard-label"><?php echo e(__('messages.accepted')); ?></div>
                    </div>
                    <img src="<?php echo e(asset('frontend/images/communication (1).png')); ?>" style="height: 44px;" alt="<?php echo e(__('messages.accepted_icon')); ?>">
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="<?php echo e(route('vendor.orders.list', ['status' => 1])); ?>">
                <div class="quikctech-dashboard-card quikctech-dashboard-bg-yellow">
                    <div>
                        <div class="quikctech-dashboard-value text-warning"><?php echo e($orderStats['pending']); ?></div>
                        <div class="quikctech-dashboard-label"><?php echo e(__('messages.pending')); ?></div>
                    </div>
                    <img src="<?php echo e(asset('frontend/images/real-time.png')); ?>" style="height: 44px;" alt="<?php echo e(__('messages.pending_icon')); ?>">
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="<?php echo e(route('vendor.orders.list', ['status' => 3])); ?>">
                <div class="quikctech-dashboard-card quikctech-dashboard-bg-green">
                    <div>
                        <div class="quikctech-dashboard-value text-success"><?php echo e($orderStats['in_process']); ?></div>
                        <div class="quikctech-dashboard-label"><?php echo e(__('messages.in_process')); ?></div>
                    </div>
                    <img src="<?php echo e(asset('frontend/images/ready.png')); ?>" style="height: 44px;" alt="<?php echo e(__('messages.in_process_icon')); ?>">
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="<?php echo e(route('vendor.orders.list', ['status' => 4])); ?>">
                <div class="quikctech-dashboard-card quikctech-dashboard-bg-red">
                    <div>
                        <div class="quikctech-dashboard-value text-danger"><?php echo e($orderStats['picked_up']); ?></div>
                        <div class="quikctech-dashboard-label"><?php echo e(__('messages.picked_up')); ?></div>
                    </div>
                    <img src="<?php echo e(asset('frontend/images/international-shipping.png')); ?>" style="height: 44px;" alt="<?php echo e(__('messages.picked_up_icon')); ?>">
                </div>
            </a>
        </div>
    </div>

    <!-- Bottom Row -->
    <div class="row g-3">
        <div class="col-lg-3 col-md-4">
            <a href="<?php echo e(route('vendor.orders.list', ['status' => 6])); ?>">
                <div class="quikctech-dashboard-card quicktech-dashboard-b-card quikctech-dashboard-bg-white">
                    <div class="quikctech-dashboard-label"><img src="<?php echo e(asset('frontend/images/shipped.png')); ?>" alt="<?php echo e(__('messages.delivered_icon')); ?>"> <?php echo e(__('messages.delivered')); ?></div>
                    <div class="quikctech-dashboard-value text-primary"><?php echo e($orderStats['delivered']); ?></div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="<?php echo e(route('vendor.orders.list', ['status' => 8])); ?>">
                <div class="quikctech-dashboard-card quicktech-dashboard-b-card quikctech-dashboard-bg-white">
                    <div class="quikctech-dashboard-label"><img src="<?php echo e(asset('frontend/images/transaction (1).png')); ?>" alt="<?php echo e(__('messages.return_icon')); ?>"> <?php echo e(__('messages.return')); ?></div>
                    <div class="quikctech-dashboard-value text-primary"><?php echo e($orderStats['return']); ?></div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="<?php echo e(route('vendor.orders.list', ['status' => 5])); ?>">
                <div class="quikctech-dashboard-card quicktech-dashboard-b-card quikctech-dashboard-bg-white">
                    <div class="quikctech-dashboard-label"><img src="<?php echo e(asset('frontend/images/deadline (1).png')); ?>" alt="<?php echo e(__('messages.rescheduled_icon')); ?>"> <?php echo e(__('messages.rescheduled')); ?></div>
                    <div class="quikctech-dashboard-value text-primary"><?php echo e($orderStats['rescheduled']); ?></div>
                </div>
            </a>
        </div>
        <div class="col-lg-3 col-md-4">
            <a href="<?php echo e(route('vendor.orders.list')); ?>">
                <div class="quikctech-dashboard-card quicktech-dashboard-b-card quikctech-dashboard-bg-white">
                    <div class="quikctech-dashboard-label"><img src="<?php echo e(asset('frontend/images/all.png')); ?>" alt="<?php echo e(__('messages.all_icon')); ?>"> <?php echo e(__('messages.all')); ?></div>
                    <div class="quikctech-dashboard-value text-primary"><?php echo e($orderStats['all']); ?></div>
                </div>
            </a>
        </div>
    </div>
</div>
<br><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/seller-dasboard.blade.php ENDPATH**/ ?>