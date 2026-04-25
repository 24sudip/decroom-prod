<?php $__env->startSection('title', 'Cart'); ?>
<?php $__env->startSection('content'); ?>
    <section id="quicktech-cart">
        <div class="container-fluid">
            <div class="row my-5">
                <div class="col-lg-12">
                    <div class="quicktech-cart-inner">
                        <h4>Your Cart</h4>

                        <div class="row">
                            <div class="col-lg-8">
                                <div class="quikctech-cart-product">

                                    <?php if($cartItems && count($cartItems) > 0): ?>
                                        <?php
                                            $groupedItems = [];
                                            foreach($cartItems as $item) {
                                                $productId = $item['attributes']['product_id'] ?? null;
                                                $product = $productId ? \App\Product::find($productId) : null;
                                                $vendorId = $product->vendor_id ?? null;

                                                if ($vendorId) {
                                                    $groupedItems[$vendorId][] = $item;
                                                } else {
                                                    $groupedItems['unknown'][] = $item;
                                                }
                                            }
                                        ?>

                                        <?php $__currentLoopData = $groupedItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendorId => $vendorItems): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                if ($vendorId === 'unknown') {
                                                    $vendorName = 'Unknown Vendor';
                                                    $vendorLogo = asset('frontend/images/shop.png');
                                                } else {
                                                    $vendor = \App\Vendor::where('user_id', $vendorId)->first();
                                                    $vendorName = $vendor ? $vendor->shop_name : 'Unknown Vendor';
                                                    $vendorLogo = $vendor && $vendor->logo ? asset('storage/' . $vendor->logo) : asset('frontend/images/shop.png');
                                                }
                                            ?>

                                            <?php $__currentLoopData = $vendorItems; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <?php
                                                    $itemTotal = $item['price'] * $item['quantity'];
                                                    $productId = $item['attributes']['product_id'] ?? null;
                                                    $product = $productId ? \App\Product::find($productId) : null;
                                                    $hasDiscount = $product && $product->special_price && $product->special_price < $product->price;
                                                    $actualQuantity = $item['quantity'];
                                                ?>

                                                <div class="quikctech-cart-p-inner" id="cart-item-<?php echo e($item['id']); ?>">
                                                    <div class="row">
                                                        <div class="col-lg-12">
                                                            <div class="quikctech-cart-p-head">
                                                                <?php if($vendorId !== 'unknown'): ?>
                                                                <a href="<?php echo e($vendor ? route('vendor.shop.view', $vendor->user_id) : '#'); ?>">
                                                                    <img src="<?php echo e($vendorLogo); ?>" style="height: 20px;" alt="<?php echo e($vendorName); ?>">
                                                                    <?php echo e($vendorName); ?>

                                                                    <i class="fa-solid fa-angle-right"></i>
                                                                </a>
                                                                <?php else: ?>
                                                                <span>
                                                                    <img src="<?php echo e($vendorLogo); ?>" style="height: 20px;" alt="<?php echo e($vendorName); ?>">
                                                                    <?php echo e($vendorName); ?>

                                                                </span>
                                                                <?php endif; ?>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-6">
                                                            <div class="quikctech-check-box d-flex align-items-center gap-3">
                                                                <label>
                                                                    <input type="checkbox" name="cart_item[]" value="<?php echo e($item['id']); ?>" class="cart-item-checkbox">
                                                                </label>
                                                               <?php
                                                                    $image = $item['attributes']['image'] ?? 'default.png';
                                                                    $imageUrl = Str::startsWith($image, ['http://', 'https://'])
                                                                        ? $image
                                                                        : asset($image);
                                                                ?>

                                                                <img src="<?php echo e($imageUrl); ?>" alt="<?php echo e($item['name']); ?>" style="width: 80px; height: 80px; object-fit: cover;">
                                                                <h5>
                                                                    <?php echo e($item['name']); ?>

                                                                    <br>
                                                                    <span>
                                                                        <?php if($item['attributes']['variant_name']): ?>
                                                                            <?php echo e($item['attributes']['variant_name']); ?>

                                                                        <?php else: ?>
                                                                            No variant selected
                                                                        <?php endif; ?>
                                                                    </span>
                                                                </h5>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-6">
                                                            <div class="quikctech-middle-cart-text">
                                                                <p>
                                                                    ৳ <?php echo e(number_format($item['price'])); ?>

                                                                    <br>
                                                                    <?php if($hasDiscount): ?>
                                                                    <s>৳ <?php echo e(number_format($product->price)); ?></s>
                                                                    <?php endif; ?>
                                                                </p>
                                                                <div class="quikctech-cart-p-price text-center">
                                                                    <div class="quikctech-delete-wish">
                                                                        <ul style="display: flex; gap: 15px; justify-content: center;">
                                                                            <li>
                                                                                <a href="#" class="move-to-wishlist" data-item-id="<?php echo e($item['id']); ?>">
                                                                                    <i class="fa-solid fa-heart"></i>
                                                                                </a>
                                                                            </li>
                                                                            <li>
                                                                                <a href="#" class="remove-cart-item" data-item-id="<?php echo e($item['id']); ?>">
                                                                                    <i class="fa-regular fa-trash-can"></i>
                                                                                </a>
                                                                            </li>
                                                                        </ul>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="col-lg-3 col-6">
                                                            <div class="counter-container counter quikctech-cart-quantity">
                                                                <button class="counter-btn decrease" data-item-id="<?php echo e($item['id']); ?>">-</button>
                                                                <span class="number" id="quantity-<?php echo e($item['id']); ?>" data-qty="<?php echo e($item['quantity']); ?>">
                                                                    <?php echo e($item['quantity']); ?>

                                                                </span>
                                                                <button class="counter-btn increase" data-item-id="<?php echo e($item['id']); ?>">+</button>
                                                            </div>
                                                            <div class="item-total text-center mt-2">
                                                                <small class="text-muted">
                                                                    Total: ৳ <span id="item-total-<?php echo e($item['id']); ?>"><?php echo e(number_format($itemTotal)); ?></span>
                                                                </small>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                        <div class="quicktech-cart-p-remove-btn">
                                            <ul>
                                                <li>
                                                    <label>
                                                        <input type="checkbox" id="select-all-checkbox"> Select All
                                                    </label>
                                                </li>
                                                <li>
                                                    <a class="remove-btn" href="#" id="remove-selected-btn">Remove from my cart</a>
                                                </li>
                                                <li>
                                                    <a class="wishlist-btn" href="#" id="move-to-wishlist-btn">Move to Wishlist</a>
                                                </li>
                                            </ul>
                                        </div>
                                    <?php else: ?>
                                        <div class="text-center py-5">
                                            <div class="empty-cart">
                                                <i class="fas fa-shopping-cart fa-4x text-muted mb-3"></i>
                                                <h4>Your cart is empty</h4>
                                                <p class="text-muted">Add some products to your cart</p>
                                                <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Continue Shopping</a>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div class="col-lg-4">
                                <div class="quickcktech-order-main">
                                    <div class="quikctech-order-inner">
                                        <h5>Order Delivery location</h5>
                                        <p>
                                            <i class="fa-solid fa-location-dot"></i>
                                            <?php if(auth()->guard('customer')->check()): ?>
                                                <?php echo e(auth('customer')->user()->address ?? 'Mirpur 10 shah ali plaza level 8'); ?>

                                            <?php else: ?>
                                                Mirpur 10 shah ali plaza level 8
                                            <?php endif; ?>
                                        </p>
                                    </div>

                                    <div class="order-summary">
                                        <h5>Order Summary</h5>

                                        <div class="quikctech-price-inner d-flex justify-content-between">
                                            <p>Sub total (<span id="total-items"><?php echo e($cartCount); ?></span> items)</p>
                                            <span id="subtotal-price">৳ <?php echo e(number_format($cartTotal)); ?></span>
                                        </div>

                                        
                                        <div class="quikctech-coupon">
                                            <input type="text" id="coupon-code" value="<?php echo e(session('coupon_code')); ?>" placeholder="Enter Voucher Code">
                                            <a href="#" id="apply-coupon">Apply</a>
                                        </div>

                                        
                                        <?php if(session('coupon_code')): ?>
                                            <div class="mt-2 text-success" id="coupon-applied-box">
                                                Coupon "<strong><?php echo e(session('coupon_code')); ?></strong>" applied —
                                                Discount: ৳ <span id="coupon-discount"><?php echo e(number_format(session('coupon_discount'), 2)); ?></span>
                                                <a href="#" id="remove-coupon" class="text-danger ms-2">Remove</a>
                                            </div>
                                        <?php else: ?>
                                            <div id="coupon-applied-box" class="mt-2" style="display:none;"></div>
                                        <?php endif; ?>

                                        <?php
                                            $discount = session('coupon_discount', 0);
                                            $grandTotal = $cartTotal - $discount;
                                        ?>

                                        <div class="quikctech-total-price mt-4 d-flex justify-content-between">
                                            <p>Total Price</p>
                                            <span id="total-price">৳ <?php echo e(number_format($grandTotal)); ?></span>
                                        </div>

                                        <div class="quikctech-order-btn text-center">
                                            <?php if($cartItems && count($cartItems) > 0): ?>
                                                <button>
                                                    <a style="color: white;" href="<?php echo e(route('checkout')); ?>">Order Now</a>
                                                </button>
                                            <?php else: ?>
                                                <button disabled style="opacity: 0.6;">
                                                    <a style="color: white;" href="javascript:void(0)">Order Now</a>
                                                </button>
                                            <?php endif; ?>
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
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const csrfToken = '<?php echo e(csrf_token()); ?>';
    let updatingItems = new Set();

    // --- GLOBAL: Update cart count anywhere ---
    window.updateCartCount = function(count) {
        document.querySelectorAll('.cart-count, .cart-counter, #cart-count, #total-items').forEach(el => {
            el.textContent = count;
            el.style.display = count > 0 ? 'inline' : 'none';
        });
        window.dispatchEvent(new CustomEvent('cartUpdated', { detail: { count } }));
    };

    // --- Update cart summary (subtotal + total) ---
    function updateCartSummary(data) {
        if (!data) return;
        if (data.subtotal !== undefined) {
            const subtotalEl = document.getElementById('subtotal-price');
            const totalEl = document.getElementById('total-price');
            if (subtotalEl) subtotalEl.textContent = '৳ ' + parseInt(data.subtotal, 10).toLocaleString();
            if (totalEl) totalEl.textContent = '৳ ' + (parseInt(data.subtotal, 10)).toLocaleString();
        }
        if (data.cart_count !== undefined) {
            window.updateCartCount(data.cart_count);
        }
    }

    // --- Update quantity via AJAX ---
    async function updateQuantity(itemId, newQty) {
        if (updatingItems.has(itemId)) return;
        updatingItems.add(itemId);

        try {
            const res = await fetch('<?php echo e(route("cart.update")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ id: itemId, quantity: newQty })
            });

            const data = await res.json();
            if (!data.success) throw new Error(data.message || 'Error updating cart');

            // Update quantity display
            const qtyEl = document.getElementById(`quantity-${itemId}`);
            if (qtyEl) {
                qtyEl.textContent = newQty;
                qtyEl.dataset.qty = newQty;

                toggleButtons(itemId, newQty);
            }

            // Update item total
            const itemTotalEl = document.getElementById(`item-total-${itemId}`);
            if (itemTotalEl) itemTotalEl.textContent = (data.item_total || 0).toLocaleString();

            // Update order summary
            updateCartSummary(data);

        } catch (err) {
            console.error(err);
            Swal.fire({ icon: 'error', title: 'Oops!', text: err.message });
        } finally {
            updatingItems.delete(itemId);
        }
    }

    function toggleButtons(itemId, qty) {
        const decBtn = document.querySelector(`.decrease[data-item-id="${itemId}"]`);
        const incBtn = document.querySelector(`.increase[data-item-id="${itemId}"]`);

        if (decBtn) decBtn.disabled = qty <= 1;
        if (incBtn) incBtn.disabled = false;
    }


    // --- Remove cart item ---
    async function removeCartItem(itemId) {
        const result = await Swal.fire({
            title: 'Remove this item?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes'
        });
        if (!result.isConfirmed) return;

        try {
            const res = await fetch(`<?php echo e(route('cart.remove', '')); ?>/${itemId}`, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                }
            });

            const data = await res.json();
            if (!data.success) throw new Error(data.message || 'Error removing item');

            // Remove item from DOM
            document.getElementById(`cart-item-${itemId}`)?.remove();

            // Update order summary
            updateCartSummary(data);

            if (data.cart_count === 0) location.reload();

        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Oops!', text: err.message });
        }
    }

    // --- Initialize quantities ---
    document.querySelectorAll('[id^="quantity-"]').forEach(el => {
        const qty = parseInt(el.dataset.qty, 10) || 1;
        const itemId = el.id.replace("quantity-", "");
        el.textContent = qty;
        el.dataset.qty = qty;

        toggleButtons(itemId, qty);
    });

    async function moveToWishlist(ids) {
        try {
            const res = await fetch('<?php echo e(route("cart.moveToWishlist")); ?>', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': csrfToken,
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ ids: ids })
            });

            const data = await res.json();

            if (!data.success) throw new Error(data.message || 'Error');

            // Remove items from DOM
            ids.forEach(id => {
                document.getElementById(`cart-item-${id}`)?.remove();
            });

            // Update summary
            updateCartSummary(data);

            Swal.fire({
                icon: 'success',
                title: 'Moved!',
                text: 'Item moved to wishlist',
                timer: 1200,
                showConfirmButton: false
            });

            if (data.cart_count === 0) location.reload();

        } catch (err) {
            Swal.fire({ icon: 'error', title: 'Oops!', text: err.message });
        }
    }
    // --- Event delegation for cart buttons ---
    document.querySelector('.quikctech-cart-product')?.addEventListener('click', function(e) {
        const btn = e.target.closest('.increase, .decrease, .remove-cart-item, .move-to-wishlist');
        if (!btn) return;

        const itemId = btn.dataset.itemId;
        const qtyEl = document.getElementById(`quantity-${itemId}`);
        if (!qtyEl) return;

        let currentQty = parseInt(qtyEl.dataset.qty, 10) || 1;

        if (btn.classList.contains('increase')) {
            currentQty++;
        }
        if (btn.classList.contains('decrease')) {
            currentQty = Math.max(1, currentQty - 1); // Ensure min 1
        }

        // Always update the DOM qty and data-qty
        qtyEl.textContent = currentQty;
        qtyEl.dataset.qty = currentQty;

        toggleButtons(itemId, currentQty);

        // Update cart via AJAX
        if (btn.classList.contains('increase') || btn.classList.contains('decrease')) {
            updateQuantity(itemId, currentQty);
        }

        if (btn.classList.contains('remove-cart-item')) removeCartItem(itemId);

        if (btn.classList.contains('move-to-wishlist')) {
            e.preventDefault();
            moveToWishlist([itemId]);
        }
    });

    // --- Select all checkbox ---
    document.getElementById('select-all-checkbox')?.addEventListener('change', function() {
        document.querySelectorAll('.cart-item-checkbox').forEach(c => c.checked = this.checked);
    });

    // --- Remove selected items ---
    document.getElementById('remove-selected-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        const ids = Array.from(document.querySelectorAll('.cart-item-checkbox:checked')).map(c => c.value);
        if (!ids.length) return Swal.fire({ icon: 'warning', title: 'No items selected' });
        ids.forEach((id, i) => setTimeout(() => removeCartItem(id), i * 200));
    });

    // --- Move selected to wishlist ---
    document.getElementById('move-to-wishlist-btn')?.addEventListener('click', function(e) {
        e.preventDefault();
        const ids = Array.from(document.querySelectorAll('.cart-item-checkbox:checked')).map(c => c.value);
        if (!ids.length) return Swal.fire({ icon: 'warning', title: 'No items selected' });
        moveToWishlist(ids);
    });
});

document.addEventListener("DOMContentLoaded", function() {
    const csrf = "<?php echo e(csrf_token()); ?>";
    const applyUrl = "<?php echo e(route('cart.applyCoupon')); ?>";      // POST
    const removeUrl = "<?php echo e(route('cart.removeCoupon')); ?>";   // GET

    // DOM elements
    const couponInput = document.getElementById("coupon-code");
    const applyBtn = document.getElementById("apply-coupon");
    const subtotalEl = document.getElementById("subtotal-price");
    const totalEl = document.getElementById("total-price");
    const totalItemsEl = document.getElementById("total-items");
    const couponBox = document.getElementById("coupon-applied-box");

    function safeParseInt(v) {
        const n = parseInt(v, 10);
        return isNaN(n) ? 0 : n;
    }

    function updateTotals(data) {
        if (!data) return;
        if (data.subtotal !== undefined && subtotalEl) {
            subtotalEl.textContent = "৳ " + safeParseInt(data.subtotal).toLocaleString();
        }
        if (data.total !== undefined && totalEl) {
            totalEl.textContent = "৳ " + safeParseInt(data.total).toLocaleString();
        }
        if (data.cart_count !== undefined && totalItemsEl) {
            totalItemsEl.textContent = data.cart_count;
        }
    }

    // --- Apply Coupon ---
    applyBtn?.addEventListener("click", async function(e) {
        e.preventDefault();
        const code = couponInput?.value?.trim();
        if (!code) {
            return Swal.fire({ icon: "warning", title: "Enter a code", text: "Please enter a coupon code" });
        }

        try {
            const res = await fetch(applyUrl, {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": csrf,
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                },
                body: JSON.stringify({ coupon_code: code })
            });

            const data = await res.json();

            if (!data.success) {
                return Swal.fire({ icon: "error", title: "Invalid Coupon", text: data.message || "Coupon not valid" });
            }

            // Update totals & coupon box
            updateTotals(data);

            if (couponBox) {
                couponBox.style.display = "block";
                couponBox.classList.add("text-success");

                // Ensure discount is a number
                const discount = Number(data.discount) || 0;

                couponBox.innerHTML = `
                    Coupon "<strong>${code}</strong>" applied —
                    Discount: ৳ <span id="coupon-discount">${discount.toFixed(2)}</span>
                    <a href="#" id="remove-coupon" class="text-danger ms-2">Remove</a>
                `;
            }

            Swal.fire({
                icon: "success",
                title: "Coupon Applied",
                text: data.message || "Coupon applied",
                timer: 1600,
                showConfirmButton: false
            });

        } catch (err) {
            console.error(err);
            Swal.fire({ icon: "error", title: "Oops!", text: "Something went wrong while applying coupon." });
        }
    });

    // --- Remove Coupon ---
    document.addEventListener("click", async function(e) {
        const removeBtn = (e.target.id === "remove-coupon") ? e.target : e.target.closest("#remove-coupon");
        if (!removeBtn) return;

        e.preventDefault();

        try {
            const res = await fetch(removeUrl, {
                method: "GET",
                headers: {
                    "X-Requested-With": "XMLHttpRequest",
                    "Accept": "application/json"
                }
            });

            const data = await res.json();

            if (!data.success) {
                throw new Error(data.message || "Failed to remove coupon");
            }

            // Hide coupon box & reset input
            if (couponBox) {
                couponBox.style.display = "none";
                couponBox.innerHTML = "";
            }
            if (couponInput) couponInput.value = "";

            updateTotals(data);

            Swal.fire({
                icon: "success",
                title: "Coupon Removed",
                text: data.message || "Coupon removed successfully",
                timer: 1400,
                showConfirmButton: false
            });

        } catch (err) {
            console.error(err);
            Swal.fire({ icon: "error", title: "Failed", text: err.message || "Could not remove coupon" });
        }
    });
});
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/pages/cart.blade.php ENDPATH**/ ?>