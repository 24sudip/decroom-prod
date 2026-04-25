<div class="col-lg-3">
    <div class="quicktech-delivery-option">
        <div class="quicktech-delivey-address d-flex justify-content-between">
            <h5><?php echo e(__('messages.delivery_option')); ?></h5>
            <p>
                <i class="fa-solid fa-circle-info"></i>
            </p>
        </div>
        <div class="quikctech-address-inner">
            <div class="quikctech-address">
                <div class="quicktech-address-text" style="display: flex; align-items: baseline; gap: 8px;">
                    <i class="fa-solid fa-location-dot"></i>
                    <p><?php echo e(__('messages.delivery_address')); ?></p>
                </div>
            </div>
        </div>
        <hr>
        <div class="quikctech-address-inner">
            <div class="quikctech-address align-items-center">
                <div class="quicktech-address-text" style="display: flex; align-items: baseline; gap: 8px;">
                    <i class="fa-solid fa-box-open"></i>
                    <p><?php echo e(__('messages.standard_delivery')); ?>

                        <br>
                        <span><?php echo e(__('messages.guaranteed_by_date')); ?></span>
                    </p>
                </div>
                <div class="quicktech-ad-chan-btn">
                    <p style="font-weight: 600; font-size: 18px;">৳ <?php echo e(__('messages.delivery_charge')); ?></p>
                </div>
            </div>
        </div>
        <div class="quikctech-address-inner">
            <div class="quikctech-address align-items-center">
                <div class="quicktech-address-text" style="display: flex; align-items: baseline; gap: 8px;">
                    <i class="fa-solid fa-money-bill"></i>
                    <p>
                        <?php echo e(__('messages.cash_on_delivery')); ?>

                    </p>
                </div>
            </div>
        </div>
        <hr>
        <div class="quicktech-delivey-address d-flex justify-content-between">
            <h5><?php echo e(__('messages.return_warranty')); ?></h5>
            <p>
                <i class="fa-solid fa-circle-info"></i>
            </p>
        </div>
        <div class="quikctech-address-inner">
            <div class="quikctech-address align-items-center">
                <div class="quicktech-address-text" style="display: flex; align-items: baseline; gap: 8px;">
                    <i class="fa-solid fa-money-bill"></i>
                    <p>
                        <?php echo e(__('messages.return_policy_days')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="quikctech-seller-details mt-3">
        <div class="qikctech-sold-inner">
            <div class="quikctech-sold-by d-flex justify-content-between align-items-center">
                <h5>
                    <?php echo e(__('messages.sold_by')); ?>

                    <br>
                    
                    <span><?php echo e($product->vendor ? ($product->vendor->vendorDetails->shop_name ?? $product->vendor->name) : 'Admin'); ?></span>
                </h5>
                <?php if($product->vendor): ?>
                <a href="<?php echo e(route('customer.chat-with-seller', $product->vendor_id)); ?>">
                    <i class="fa-solid fa-message"></i> <?php echo e(__('messages.chat_now')); ?>

                </a>
                <?php endif; ?>
            </div>
        </div>
        <div class="quikctech-sold-rate">
            <div class="quicktech-sold-rate-main">
                <h5><?php echo e(__('messages.positive_seller_rating')); ?></h5>
                <h4>70%</h4>
            </div>
            <div class="quicktech-sold-rate-main">
                <h5><?php echo e(__('messages.ship_on_time')); ?></h5>
                <h4>100%</h4>
            </div>
            <div class="quicktech-sold-rate-main">
                <h5><?php echo e(__('messages.chat_response_rate')); ?></h5>
                <h4><?php echo e(__('messages.null')); ?></h4>
            </div>
        </div>
        <div class="quicktech-visit-btn text-center my-4">
            <a href="<?php echo e(route('vendor.shop.view', $product->vendor_id)); ?>"><?php echo e(__('messages.visit_shop')); ?></a>
        </div>
    </div>
</div>

<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/delivery_seller_info.blade.php ENDPATH**/ ?>