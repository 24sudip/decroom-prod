
<?php $__env->startSection('title', 'Checkout'); ?>

<?php $__env->startSection('content'); ?>
<section id="quicktech-confirm-order">
    <div class="container-fluid">
        <div class="row my-5">
            <div class="col-lg-12">
                <div class="quikctech-confirm-order-inner">
                    <h4>Confirm Order</h4>
                    <div class="row gapp">

                        
                        <div class="col-lg-8">
                            <div class="quicktech-conter-order-left">
                                <div class="quikctech-confirm-order-head">
                                    <h5>Package Details</h5>
                                    <p>Shop: <?php echo e(config('app.name', 'Shop')); ?></p>
                                </div>

                                <div class="quikctech-confirm-order-product">
                                    
                                    <div class="quikctech-delivery-option">
                                        <h5 class="pt-4">Delivery Option:</h5>
                                        <div class="delivery-options">
                                            <?php $__currentLoopData = $shippingCharges; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $shipping): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <div class="delivery-option" id="option<?php echo e($index + 1); ?>">
                                                    <input type="radio" name="shipping_method_radio"
                                                        id="radio<?php echo e($index + 1); ?>"
                                                        value="<?php echo e($shipping->charge); ?>"
                                                        data-id="<?php echo e($shipping->id); ?>"
                                                        <?php echo e($loop->first ? 'checked' : ''); ?>

                                                        onchange="updateShippingCharge(<?php echo e($shipping->charge); ?>)">
                                                    <label for="radio<?php echo e($index + 1); ?>">
                                                        <span>
                                                            <img src="<?php echo e(asset('frontend/images/checked.png')); ?>" style="height: 15px;" alt=""> 
                                                            ৳ <?php echo e(number_format($shipping->charge, 2)); ?>

                                                        </span>
                                                        <p><?php echo e($shipping->location ?? 'Standard Delivery'); ?></p>
                                                        <p><?php echo e($shipping->type ?? ''); ?></p>
                                                    </label>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </div>
                                    </div>

                                    
                                    <?php $__currentLoopData = $cartItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <div class="quikctech-cart-p-main mt-4">
                                            <div class="quikctech-check-box d-flex align-items-center gap-3">
                                                <img 
                                                    src="<?php echo e(Str::startsWith($item['attributes']['image'], ['http://', 'https://']) ? $item['attributes']['image'] : asset('public/' . $item['attributes']['image'])); ?>" 
                                                    alt="<?php echo e($item['name']); ?>" 
                                                    style="width: 80px; height: 80px; object-fit: cover;">
                                                <h5>
                                                    <?php echo e($item['name']); ?><br>
                                                    <span><?php echo e($item['attributes']['variant_name'] ?? 'Default'); ?></span>
                                                </h5>
                                            </div>
                                            <div class="quikctech-cart-p-price text-center">
                                                <p>৳ <?php echo e(number_format($item['price'], 2)); ?></p>
                                            </div>
                                            <div class="counter-container counter quikctech-cart-quantity">
                                                <p>Qty: <?php echo e($item['quantity']); ?></p>
                                            </div>
                                        </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                </div>
                            </div>
                        </div>

                        
                        <div class="col-lg-4">
                            <div class="quickcktech-order-main">
                                <div class="quikctech-order-inner">
                                    <div class="d-flex justify-content-between">
                                        <h5>Order Delivery location</h5>
                                    </div>
                        
                                    <?php if($customer): ?>
                                        <div class="d-flex justify-content-between">
                                            <p><strong>Name:</strong> <?php echo e($customer->name); ?></p>
                                            <p><?php echo e($customer->phone); ?></p>
                                        </div>
                                        <p><strong>Address:</strong> <?php echo e($customer->address ?? 'Not provided'); ?></p>
                                    <?php else: ?>
                                        <p>Please log in to select your delivery address.</p>
                                    <?php endif; ?>
                                </div>
                        
                                
                                <div class="order-summary mt-4">
                                    <h5>Order Summary</h5>
                        
                                    <div class="quikctech-price-inner d-flex justify-content-between">
                                        <p>Sub total (<?php echo e($cartCount); ?> item<?php echo e($cartCount > 1 ? 's' : ''); ?>)</p>
                                        <span id="subtotal">৳ <?php echo e(number_format($cartTotal, 2)); ?></span>
                                    </div>
                        
                                    <div class="quikctech-price-inner d-flex justify-content-between">
                                        <p>Shipping Fee</p>
                                        <span id="shippingFee">৳ <?php echo e(number_format($shipping_method, 2)); ?></span>
                                    </div>
                        
                                    <?php if(session('coupon_discount', 0) > 0): ?>
                                        <div class="quikctech-price-inner d-flex justify-content-between">
                                            <p>Coupon Discount</p>
                                            <span>-৳ <?php echo e(number_format(session('coupon_discount'), 2)); ?></span>
                                        </div>
                                    <?php endif; ?>
                        
                                    <div class="quikctech-total-price mt-4 d-flex justify-content-between">
                                        <p>Total Price</p>
                                        <span id="totalPrice">৳ <?php echo e(number_format($finalTotal, 2)); ?></span>
                                    </div>
                                    
                                    
                                    <div class="quikctech-order-btn text-center mt-3">
                                        <form id="checkoutForm" action="<?php echo e(route('proceed-to-pay')); ?>" method="POST">    
                                            <?php echo csrf_field(); ?>
                                            <input type="hidden" name="shipping_method" id="shipping_method" value="<?php echo e($shipping_method); ?>">
                                            <input type="hidden" name="coupon_code" value="<?php echo e(session('coupon_code')); ?>">
                                            <button type="submit" class="btn btn-success w-100">
                                                Proceed to Pay
                                            </button>
                                        </form>
                                    </div>
                                    
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>


<script>
    function updateShippingCharge(charge) {
        const cartTotal = parseFloat(<?php echo e($cartTotal); ?>);
        const discount = parseFloat(<?php echo e(session('coupon_discount', 0)); ?>);
        const newTotal = (cartTotal - discount) + parseFloat(charge);
    
        // Update text
        document.getElementById('shippingFee').textContent = '৳ ' + parseFloat(charge).toFixed(2);
        document.getElementById('totalPrice').textContent = '৳ ' + newTotal.toFixed(2);
    
        // Update hidden input
        document.getElementById('shipping_method').value = parseFloat(charge);
    }
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/pages/checkout.blade.php ENDPATH**/ ?>