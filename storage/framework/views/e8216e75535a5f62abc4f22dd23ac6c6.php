<section id="quicktech-navbar">
  <div class="container-fluid">
    <div class="row">
      <!-- Logo + Menu Button -->
      <div class="col-lg-3">
        <div class="quikctech-logo-desktop">
          <button class="btn quikctech-menu-btn" type="button" data-bs-toggle="offcanvas" data-bs-target="#quikctechOffcanvas1" aria-controls="quikctechOffcanvas1" title="<?php echo e(__('messages.menu')); ?>">
            <i class="fa-solid fa-bars"></i>
          </button>

          <a class="w-100" href="<?php echo e(route('home')); ?>">
            <img src="<?php echo e(asset('build/images/' . ($settings->logo_lg ?? 'frontend/images/logo-removebg-preview(3).png'))); ?>" class="w-100" alt="<?php echo e(__('messages.logo')); ?>">
          </a>
        </div>
      </div>
        <!--<div class="alert alert-warning alert-dismissible fade show" role="alert">-->
        <!--  <strong>Holy guacamole!</strong> You should check in on some of those fields below.-->
        <!--  <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>-->
        <!--</div>-->
      <!-- Search Bar -->
        <style>
            .quikctech-search-input {
                padding-top: 15px;
            }
            .quikctech-cart-p {
                margin-top: 19px;
            }
        </style>
      <div class="col-lg-7">
        <form action="<?php echo e(route('frontend.searchlist')); ?>" method="GET">
          <div class="quikctech-search-input">
            <input type="text" name="keyword" placeholder="<?php echo e(__('messages.search_in', ['app' => 'Decroom'])); ?>" value="<?php echo e(request('keyword')); ?>">

            <select name="category_id">
              <option value=""><?php echo e(__('messages.all_categories')); ?></option>
              <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                  <?php echo e($category->name); ?>

                </option>
              <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>

            <button type="submit" style="border: none; background: transparent;margin-left: 16px;" title="<?php echo e(__('messages.search')); ?>">
              <img src="<?php echo e(asset('frontend/images/Group (3).png')); ?>" style="height: 40px; margin-top: 7px;" alt="<?php echo e(__('messages.search')); ?>">
            </button>

            <div id="searchResultBox"
                 class="search-suggestion-box shadow-sm"
                 style="display:none; position:absolute; z-index:99999; width:100%; background:white; max-height:350px; overflow-y:auto;">
            </div>

          </div>
        </form>
      </div>
        <style>
            .quikctech-cart-p {
                padding: 0 0px;
            }
        </style>
      <!-- Cart + Profile -->
      <div class="col-lg-2">
        <div class="quikctech-cart-p">
          <div class="quicktech-cart-desktop position-relative">
            <a href="<?php echo e(route('cart.view')); ?>" class="position-relative" title="<?php echo e(__('messages.cart')); ?>">
                <img src="<?php echo e(asset('frontend/images/ant-design_shopping-cart-outlined.png')); ?>"
                     style="height: 40px;" alt="<?php echo e(__('messages.cart')); ?>">
                <span id="cart-count"
                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                    <?php echo e($cartCount ?? 0); ?>

                </span>
            </a>

          </div>

          <div class="quikctech-destop-profile">
            <div class="dropdown quikctech-dropdown-wrapper" style="margin-top: -4px;">
                <?php if(auth()->guard('customer')->check()): ?>
                    <!-- Customer is logged in - Show customer logo/avatar -->
                    <button class="btn quikctech-dropdown-btn position-relative" type="button" data-bs-toggle="dropdown" aria-expanded="false" title="<?php echo e(__('messages.profile')); ?>">
                        <?php if(auth('customer')->user()->image): ?>
                            <img src="<?php echo e(asset('storage/' . auth('customer')->user()->image)); ?>"
                                 style="height: 40px; width: 40px; border-radius: 50%; object-fit: cover;"
                                 alt="<?php echo e(auth('customer')->user()->name); ?>">
                        <?php else: ?>
                            <div class="customer-avatar-inline d-flex align-items-center justify-content-center rounded-circle text-white fw-bold"
                                 style="height: 40px; width: 40px; background: linear-gradient(45deg, #007bff, #0056b3); font-size: 16px;">
                                <?php echo e(strtoupper(substr(auth('customer')->user()->name, 0, 1))); ?>

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
                            title="<?php echo e(__('messages.login')); ?>"
                            style="border: 2px solid #007bff; border-radius: 25px; padding: 8px 16px; background: transparent; transition: all 0.3s ease;">
                        <img src="<?php echo e(asset('frontend/images/User-Profile-PNG.png')); ?>"
                             style="height: 24px; width: 24px;"
                             alt="<?php echo e(__('messages.login')); ?>">
                        <span class="text-primary fw-semibold" style="font-size: 14px;"><?php echo e(__('messages.login')); ?></span>
                    </button>
                <?php endif; ?>
                <style>
                    .dropdown-menu.quikctech-dropdown {
                        min-width: 367px;
                    }
                </style>
                <div class="dropdown-menu quikctech-dropdown shadow-lg border-0 rounded-3 p-0">
                    <?php if(auth()->guard('customer')->check()): ?>
                        <?php $customer = auth('customer')->user(); ?>
                        <div class="quikctech-profile d-flex align-items-center gap-2 p-3 border-bottom">
                            <?php if($customer->image): ?>
                                <img src="<?php echo e(asset('storage/' . $customer->image)); ?>" class="rounded-circle" alt="<?php echo e($customer->name); ?>" width="50" height="50" style="object-fit: cover;">
                            <?php else: ?>
                                <div class="customer-avatar rounded-circle d-flex align-items-center justify-content-center text-white fw-bold" style="width: 50px; height: 50px; background: linear-gradient(45deg, #007bff, #0056b3); font-size: 18px;">
                                    <?php echo e(strtoupper(substr($customer->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                            <div class="flex-grow-1">
                                <a href="<?php echo e(route('customer.profile')); ?>">
                                    <h6 class="mb-0 fw-semibold text-dark text-truncate" style="max-width: 180px;"><?php echo e($customer->name); ?></h6>
                                    <small class="text-muted d-block">
                                        <?php echo e($customer->email ?? $customer->phone); ?>

                                    </small>
                                    <small class="text-muted">
                                        <?php echo e(__('messages.uid')); ?>: <?php echo e(str_pad($customer->id, 8, '0', STR_PAD_LEFT)); ?>

                                    </small>
                                </a>
                            </div>
                        </div>

                        <div class="quikctech-section border-bottom">
                            <div class="row text-center g-0">
                                <div class="col-4">
                                    <a href="<?php echo e(route('customer.profile', 'orders')); ?>" class="dropdown-item py-2 d-block text-decoration-none" title="<?php echo e(__('messages.orders')); ?>">
                                        <i class="fas fa-shopping-bag d-block mb-1 text-primary"></i>
                                        <small><?php echo e(__('messages.orders')); ?></small>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="<?php echo e(route('customer.profile', 'followed-vendors')); ?>" class="dropdown-item py-2 d-block text-decoration-none" title="<?php echo e(__('messages.wishlist')); ?>">
                                        <i class="fas fa-heart d-block mb-1 text-danger"></i>
                                        <small><?php echo e(__('messages.wishlist')); ?></small>
                                    </a>
                                </div>
                                <div class="col-4">
                                    <a href="<?php echo e(route('customer.profile', 'addresses')); ?>" class="dropdown-item py-2 d-block text-decoration-none" title="<?php echo e(__('messages.address')); ?>">
                                        <i class="fas fa-map-marker-alt d-block mb-1 text-success"></i>
                                        <small><?php echo e(__('messages.address')); ?></small>
                                    </a>
                                </div>
                            </div>
                        </div>
                        <div class="">
                        <?php
                            $n_count = $customer->unreadNotifications()->count();
                        ?>
        <li class="nav-item">
            <a class="nav-link position-relative" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                <img src="<?php echo e(asset('frontend/images/bell.png')); ?>" alt="<?php echo e(__('messages.notifications')); ?>">
                <span class="alert-count" id="notification-count"><?php echo e($n_count); ?></span>
            </a>
            <div class="dropdown-menu-end">
                <a href="javascript:;">
                    <div class="msg-header">
                        <p class="msg-header-title"><?php echo e($customer->notifications()->count()); ?> Notifications</p>
                        
                    </div>
                </a>
                <div class="header-notifications-list">
                    <?php $__empty_1 = true; $__currentLoopData = $customer->unreadNotifications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $notification): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
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
                        </div>
                    <?php else: ?>
                        <div class="p-4 text-center">
                            <div class="mb-3">
                                <i class="fas fa-user-circle fa-3x text-muted"></i>
                            </div>
                            <h6 class="fw-semibold text-dark mb-2"><?php echo e(__('messages.welcome_to_app', ['app' => 'Decroom'])); ?></h6>
                            <p class="text-muted small mb-3"><?php echo e(__('messages.sign_in_message')); ?></p>

                            <div class="d-grid gap-2">
                                <a href="<?php echo e(route('customer.login')); ?>" class="btn btn-primary btn-sm">
                                    <i class="fas fa-sign-in-alt me-1"></i>
                                    <?php echo e(__('messages.login')); ?>

                                </a>
                                <a href="<?php echo e(route('customer.register')); ?>" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-user-plus me-1"></i>
                                    <?php echo e(__('messages.register')); ?>

                                </a>
                            </div>

                            <div class="mt-3 pt-3 border-top">
                                <small class="text-muted d-block mb-2"><?php echo e(__('messages.are_you_vendor')); ?></small>
                                <a href="<?php echo e(route('vendor.login')); ?>" class="btn btn-outline-success btn-sm w-100">
                                    <i class="fas fa-store me-1"></i>
                                    <?php echo e(__('messages.vendor_login')); ?>

                                </a>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        </div>
      </div>
    </div>
  </div>
</section>

<style>
.search-suggestion-box .search-item:hover {
    background: #f5f5f5;
}

.quikctech-dropdown-btn { border: none; background: transparent; padding: 4px; border-radius: 50%; transition: all 0.3s ease; }
.quikctech-dropdown-btn:hover { background: rgba(0, 123, 255, 0.1); transform: scale(1.05); }
.quikctech-login-btn:hover { background: #007bff !important; transform: translateY(-2px); box-shadow: 0 4px 12px rgba(0, 123, 255, 0.3); }
.quikctech-login-btn:hover span { color: white !important; }
.quikctech-login-btn:hover img { filter: brightness(0) invert(1); }
.customer-avatar-inline { transition: all 0.3s ease; }
.quikctech-dropdown-btn:hover .customer-avatar-inline { transform: scale(1.1); }
@keyframes pulse { 0% { transform: scale(1); } 50% { transform: scale(1.2); } 100% { transform: scale(1); } }
.position-absolute.bg-success { animation: pulse 2s infinite; }
</style>

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

<script>
document.addEventListener("DOMContentLoaded", function () {

    const input = document.querySelector('input[name="keyword"]');
    const category = document.querySelector('select[name="category_id"]');
    const box = document.getElementById("searchResultBox");

    let searchTimer;

    if (input) {
        input.addEventListener("keyup", function () {
            clearTimeout(searchTimer);

            const keyword = input.value.trim();
            const categoryId = category ? category.value : '';

            if (keyword.length < 2) {
                box.style.display = "none";
                box.innerHTML = "";
                return;
            }

            searchTimer = setTimeout(() => {
                fetch(`<?php echo e(route('frontend.search')); ?>?q=${keyword}&category_id=${categoryId}`)
                    .then(res => res.json())
                    .then(data => {
                        if (data.length === 0) {
                            box.innerHTML = `<div class="p-2 text-muted"><?php echo e(__('messages.no_results_found')); ?></div>`;
                            box.style.display = "block";
                            return;
                        }

                        let html = '';
                        data.forEach(item => {
                            html += `
                                <div class="search-item p-2 border-bottom"
                                     style="cursor:pointer;"
                                     onclick="window.location.href='/product/${item.slug ?? item.id}'">
                                    <strong>${item.name}</strong><br>
                                    <small class="text-muted">${item.description?.substring(0, 60) || ''}...</small>
                                </div>
                            `;
                        });

                        box.innerHTML = html;
                        box.style.display = "block";
                    });
            }, 300);
        });

        // Hide suggestion box when clicking outside
        document.addEventListener("click", function (e) {
            if (box && !box.contains(e.target) && e.target !== input) {
                box.style.display = "none";
            }
        });
    }

});
</script>


<script>
document.addEventListener('DOMContentLoaded', function() {
  // Close dropdown when clicking outside
  document.addEventListener('click', function(event) {
    const dropdowns = document.querySelectorAll('.quikctech-dropdown-wrapper');
    dropdowns.forEach(dropdown => {
      if (!dropdown.contains(event.target)) {
        const dropdownMenu = dropdown.querySelector('.dropdown-menu');
        if (dropdownMenu && dropdownMenu.classList.contains('show')) {
          const btn = dropdown.querySelector('[data-bs-toggle="dropdown"]');
          if (btn) btn.click();
        }
      }
    });
  });
});

/**
 * Helper: get CSRF token (meta tag) or from Laravel
 * Ensure you have <meta name="csrf-token" content="<?php echo e(csrf_token()); ?>"> in your layout head.
 */
function getCsrfToken() {
  const m = document.querySelector('meta[name="csrf-token"]');
  return m ? m.getAttribute('content') : '';
}

/**
 * updateCartCount()
 * - Fetches cart count from server route: route('cart.count')
 * - Updates (or creates) the badge element
 */
function updateCartCount() {
    fetch("<?php echo e(route('cart.count')); ?>", {
        method: 'GET',
        headers: { 'Accept': 'application/json' }
    })
    .then(resp => {
        if (!resp.ok) throw new Error('<?php echo e(__("messages.network_error")); ?>');
        return resp.json();
    })
    .then(data => {
        const count = parseInt(data.count || 0, 10);
        let badge = document.getElementById('cart-count');

        if (badge) {
            if (count > 0) {
                badge.textContent = count;
                badge.style.display = 'inline-block';
            } else {
                badge.textContent = '0';
                badge.style.display = 'inline-block';
            }
        }
    })
    .catch(err => {
        console.error('<?php echo e(__("messages.cart_update_failed")); ?>:', err);
    });
}

// Auto-update on page load
document.addEventListener('DOMContentLoaded', updateCartCount);

// Expose so other scripts can call it
window.updateCartCount = updateCartCount;

/**
 * Generic AJAX handler for quantity change buttons.
 * - Expects increment/decrement buttons to have:
 *    class: .cart-qty-increment or .cart-qty-decrement
 *    data-id="cart_item_id"
 *    data-url="/cart/update"  (optional - default '/cart/update')
 *
 * This code will:
 *  - POST { id, action } to the URL
 *  - on success: call updateCartCount()
 *  - you can extend to update row subtotal / cart totals if returned by server
 */
document.addEventListener('click', function(e) {
  const inc = e.target.closest('.cart-qty-increment');
  const dec = e.target.closest('.cart-qty-decrement');

  if (!inc && !dec) return;

  e.preventDefault();

  const el = inc || dec;
  const id = el.getAttribute('data-id');
  const action = inc ? 'increment' : 'decrement';
  const url = el.getAttribute('data-url') || '/cart/update';

  if (!id) return console.warn('<?php echo e(__("messages.cart_action_missing_id")); ?>');

  const payload = {
    id: id,
    action: action
  };

  fetch(url, {
    method: 'POST',
    headers: {
      'Accept': 'application/json',
      'Content-Type': 'application/json',
      'X-CSRF-TOKEN': getCsrfToken()
    },
    body: JSON.stringify(payload)
  })
  .then(resp => resp.json())
  .then(json => {
    // success — backend should update session cart
    if (json.success === false) {
      // backend may send success:false with message
      console.warn('<?php echo e(__("messages.cart_update_failed")); ?>:', json.message || json);
    } else {
      // Optionally update DOM (row totals) if server returned updated item subtotal / cart totals
      // Example: if (json.itemSubtotal) { document.querySelector(`#row-subtotal-${id}`).textContent = json.itemSubtotal; }
    }

    // Always refresh navbar count (and any other cart widgets)
    updateCartCount();
  })
  .catch(err => {
    console.error('<?php echo e(__("messages.cart_update_error")); ?>:', err);
  });
});
</script>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/navbar.blade.php ENDPATH**/ ?>