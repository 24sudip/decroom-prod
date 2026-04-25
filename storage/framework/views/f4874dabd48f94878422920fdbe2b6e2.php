
<?php $__env->startSection('title', 'Proceed Checkout'); ?>

<?php $__env->startSection('content'); ?>
<section id="quicktech-confirm-order">
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-lg-12">
                <div class="quikctech-confirm-order-inner">
                    <h4>Select Payment Method</h4>

                    <div class="row gapp my-4">
                        
                        <div class="col-lg-8">
                            <div class="quikctech-payment-tabs">
                                
                                
                                <ul class="nav nav-tabs" id="paymentTabs" role="tablist">
                                    <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li class="nav-item" role="presentation">
                                            
                                            <a class="nav-link <?php echo e($loop->first ? 'active' : ''); ?>" 
                                               id="tab-<?php echo e($method->id); ?>" 
                                               data-method-id="<?php echo e($method->id); ?>"
                                               data-bs-toggle="tab" 
                                               href="#method-<?php echo e($method->id); ?>" 
                                               role="tab" 
                                               aria-controls="method-<?php echo e($method->id); ?>" 
                                               aria-selected="<?php echo e($loop->first ? 'true' : 'false'); ?>">
                                                <img class="quikctech-tabs-pay-img" 
                                                     src="<?php echo e(asset('uploads/paymentmethod/' . $method->logo)); ?>" 
                                                     alt="<?php echo e($method->title); ?>" 
                                                     title="<?php echo e($method->title); ?>">
                                            </a>
                                        </li>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </ul>

                                
                                <div class="tab-content" id="paymentTabsContent">
                                    <?php $__currentLoopData = $methods; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $method): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="tab-pane quikctech-payemnt-inner fade <?php echo e($loop->first ? 'show active' : ''); ?>" 
                                             id="method-<?php echo e($method->id); ?>" 
                                             role="tabpanel" 
                                             aria-labelledby="tab-<?php echo e($method->id); ?>">
                                            
                                            <div class="quikctech-cash-on-text mt-3">
                                                <h5><?php echo e($method->title); ?></h5>
                                                <p><?php echo nl2br(e($method->description ?? 'No description available.')); ?></p>

                                                
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-4">
                            <div class="quickcktech-order-main">
                                <div class="order-summary">
                                    <h5>Order Summary</h5>

                                    <div class="quikctech-price-inner d-flex justify-content-between">
                                        <p>Subtotal (including shipping):</p>
                                        <span>৳ <?php echo e(number_format(($cartTotal ?? 0) + ($shipping_method ?? 0), 2)); ?></span>
                                    </div>

                                    <?php if(session('coupon_discount', 0) > 0): ?>
                                        <div class="quikctech-price-inner d-flex justify-content-between">
                                            <p>Coupon Discount:</p>
                                            <span>-৳ <?php echo e(number_format(session('coupon_discount'), 2)); ?></span>
                                        </div>
                                    <?php endif; ?>

                                    <div class="quikctech-total-price mt-4 d-flex justify-content-between">
                                        <p style="font-size: 20px;">Total Price</p>
                                        <span style="font-size: 20px;">৳ <?php echo e(number_format($finalTotal ?? (($cartTotal ?? 0) + ($shipping_method ?? 0)), 2)); ?></span>
                                    </div>

                                    
                                    <form id="checkoutFormSubmit" action="<?php echo e(route('checkout.placeOrder')); ?>" method="POST">
                                        <?php echo csrf_field(); ?>
                                        <input type="hidden" name="payment_method" id="payment_method" value="<?php echo e($methods->first()->id ?? ''); ?>">
                                        <input type="hidden" name="shipping_method" value="<?php echo e($shipping_method ?? ''); ?>">
                                        <input type="hidden" name="order_note" value="<?php echo e($request->order_note ?? ''); ?>">
                                    
                                        <div class="quikctech-order-btn text-center mt-3">
                                            <button class="btn btn-primary w-100" type="submit" id="proceedPaymentBtn">
                                                Proceed Payment - ৳ <?php echo e(number_format($finalTotal, 2)); ?>

                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>
</section>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Set initial payment method
    function setSelectedMethodFromActiveTab() {
        const activeTab = document.querySelector('#paymentTabs .nav-link.active');
        const methodInput = document.getElementById('payment_method');
        if (activeTab && methodInput) {
            const id = activeTab.getAttribute('data-method-id') || activeTab.id.replace('tab-', '');
            methodInput.value = id;
        }
    }

    setSelectedMethodFromActiveTab();

    // Update payment method when tabs change
    const tabs = document.querySelectorAll('#paymentTabs .nav-link');
    tabs.forEach(function(tabEl) {
        tabEl.addEventListener('shown.bs.tab', function (e) {
            const methodId = e.target.getAttribute('data-method-id') || e.target.id.replace('tab-', '');
            const methodInput = document.getElementById('payment_method');
            if (methodInput) methodInput.value = methodId;
        });
    });

    // Form validation
    const checkoutFormSubmit = document.getElementById('checkoutFormSubmit');
    if (checkoutFormSubmit) {
        checkoutFormSubmit.addEventListener('submit', function (e) {
            const methodInput = document.getElementById('payment_method');
            if (!methodInput || !methodInput.value) {
                e.preventDefault();
                alert('Please select a payment method.');
                return false;
            }
            
            // Optional: Show loading state
            const submitBtn = document.getElementById('proceedPaymentBtn');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Processing...';
            }
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/pages/proceed-checkout.blade.php ENDPATH**/ ?>