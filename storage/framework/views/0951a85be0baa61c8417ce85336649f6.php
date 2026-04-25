<?php $__env->startSection('title', 'Customer Profile - ' . ucfirst($active_tab)); ?>
<?php $__env->startSection('content'); ?>

<section id="quicktech-customer-profile" style="background: url('<?php echo e(asset('frontend/images/profile-bg.png')); ?>') no-repeat center / cover;">
    <div class="container py-5">
        <div class="row">
            <div class="col-lg-12">
                <div class="profile-header text-center text-white mb-5">
                    <div class="profile-avatar mb-3">
                        <div class="avatar-placeholder rounded-circle bg-white d-inline-flex align-items-center justify-content-center"
                             style="width: 80px; height: 80px;">
                            <i class="fas fa-user fa-2x text-primary"></i>
                        </div>
                    </div>
                    <h2 class="mb-2"><?php echo e($customer->name); ?></h2>
                    <p class="mb-0"><?php echo e($customer->email ?? $customer->phone); ?></p>
                    <p class="mb-0"><?php echo e($customer->address); ?></p>
                </div>
            </div>
        </div>

        <div class="row">
            <!-- Sidebar Navigation -->
            <div class="col-lg-3 mb-4">
                <div class="profile-sidebar card border-0 shadow-sm">
                    <div class="card-body">
                        <ul class="nav nav-pills flex-column">
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($active_tab == 'dashboard' ? 'active' : ''); ?>"
                                   href="<?php echo e(route('customer.profile', 'dashboard')); ?>">
                                    <i class="fas fa-tachometer-alt me-2"></i> Dashboard
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($active_tab == 'orders' ? 'active' : ''); ?>"
                                   href="<?php echo e(route('customer.profile', 'orders')); ?>">
                                    <i class="fas fa-shopping-bag me-2"></i> My Orders
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($active_tab == 'service-orders' ? 'active' : ''); ?>"
                                   href="<?php echo e(route('customer.profile', 'service-orders')); ?>">
                                    <i class="fas fa-shopping-bag me-2"></i> My Services
                                </a>
                            </li>
                            <!--<li class="nav-item">-->
                            <!--    <a class="nav-link <?php echo e($active_tab == 'addresses' ? 'active' : ''); ?>" -->
                            <!--       href="<?php echo e(route('customer.profile', 'addresses')); ?>">-->
                            <!--        <i class="fas fa-map-marker-alt me-2"></i> Addresses-->
                            <!--    </a>-->
                            <!--</li>-->
                            <?php if(method_exists($customer, 'followedVendors')): ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($active_tab == 'followed-vendors' ? 'active' : ''); ?>"
                                   href="<?php echo e(route('customer.profile', 'followed-vendors')); ?>">
                                    <i class="fas fa-heart me-2"></i> Followed Shops
                                </a>
                            </li>
                            <?php endif; ?>
                            <li class="nav-item">
                                <a class="nav-link <?php echo e($active_tab == 'chat' ? 'active' : ''); ?>"
                                   href="<?php echo e(route('customer.message')); ?>">
                                    <i class="fas fa-comments me-2"></i> Message
                                </a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link <?php echo e($active_tab == 'settings' ? 'active' : ''); ?>"
                                   href="<?php echo e(route('customer.profile', 'settings')); ?>">
                                    <i class="fas fa-cog me-2"></i> Settings
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link text-danger" href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                                    <i class="fas fa-sign-out-alt me-2"></i> Logout
                                </a>
                                <form id="logout-form" action="<?php echo e(route('customer.logout')); ?>" method="POST" class="d-none">
                                    <?php echo csrf_field(); ?>
                                </form>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>

            <!-- Main Content -->
            <div class="col-lg-9">
                <div class="profile-content card border-0 shadow-sm">
                    <div class="card-body">

                        <?php if(session('success')): ?>
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                <?php echo e(session('success')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                <?php echo e(session('error')); ?>

                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        <?php endif; ?>

                        <!-- Dashboard Tab -->
                        <?php if($active_tab == 'dashboard'): ?>
                            <div class="dashboard-welcome">
                                <h4 class="mb-4">Welcome back, <?php echo e($customer->name); ?>!</h4>

                                <div class="row">
                                    <div class="col-md-4 mb-4">
                                        <div class="stat-card text-center p-4 border rounded">
                                            <i class="fas fa-shopping-bag fa-2x text-primary mb-3"></i>
                                            <h5>Total Orders</h5>
                                            <h3 class="text-primary"><?php echo e(isset($orders) ? $orders->count() : 0); ?></h3>
                                        </div>
                                    </div>
                                    <div class="col-md-4 mb-4">
                                        <div class="stat-card text-center p-4 border rounded">
                                            <i class="fas fa-map-marker-alt fa-2x text-success mb-3"></i>
                                            <h5>Saved Addresses</h5>
                                            <h3 class="text-success"><?php echo e(isset($shippingAddresses) ? $shippingAddresses->count() : 0); ?></h3>
                                        </div>
                                    </div>
                                    <?php if(isset($followedVendors)): ?>
                                    <div class="col-md-4 mb-4">
                                        <div class="stat-card text-center p-4 border rounded">
                                            <i class="fas fa-heart fa-2x text-danger mb-3"></i>
                                            <h5>Followed Shops</h5>
                                            <h3 class="text-danger"><?php echo e($followedVendors->count()); ?></h3>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>

                                <div class="recent-activity mt-5">
                                    <h5 class="mb-3">Recent Activity</h5>
                                    <div class="list-group">
                                        <div class="list-group-item">
                                            <small class="text-muted">Just now</small>
                                            <p class="mb-1">You logged into your account</p>
                                        </div>
                                        <?php if(isset($orders) && $orders->count() > 0): ?>
                                        <div class="list-group-item">
                                            <small class="text-muted"><?php echo e($orders->first()->created_at->diffForHumans()); ?></small>
                                            <p class="mb-1">You placed order #<?php echo e($orders->first()->id); ?></p>
                                        </div>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>

                        <!-- Orders Tab -->
                        <?php elseif($active_tab == 'orders'): ?>
                            <h4 class="mb-4">My Orders</h4>

                            <?php if(isset($orders) && $orders->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Order ID</th>
                                                <th>Date</th>
                                                <th>Status</th>
                                                <th>Total</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $__currentLoopData = $orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>#<?php echo e($order->id); ?></td>
                                                <td><?php echo e($order->created_at->format('M d, Y')); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo e($order->status == 6 ? 'success' : 'warning'); ?>">
                                                        <?php echo e(ucfirst($order->ordertype->name)); ?>

                                                    </span>
                                                </td>
                                                <td>৳<?php echo e(number_format($order->total_amount, 2)); ?></td>

                                                <td>
                                                    <a href="<?php echo e(route('order.details', $order->id)); ?>" class="btn btn-sm btn-outline-primary">
                                                        View
                                                    </a>

                                                    
                                                    <?php if($order->status == 6): ?>
                                                        <button class="btn btn-sm btn-outline-success"
                                                                data-bs-toggle="modal"
                                                                data-bs-target="#ratingModal<?php echo e($order->id); ?>">
                                                            Rate Now
                                                        </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            
                                            <div class="modal fade" id="ratingModal<?php echo e($order->id); ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Rate Your Order #<?php echo e($order->id); ?></h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <form action="<?php echo e(route('product.rate')); ?>" method="POST">
                                                            <?php echo csrf_field(); ?>

                                                            <div class="modal-body">

                                                                <?php if($order->items && $order->items->count() > 0): ?>

                                                                    <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                        <div class="mb-3 p-2 border rounded">

                                                                            <strong><?php echo e($item->product->name ?? 'Product removed'); ?></strong>

                                                                            
                                                                            <input type="hidden" name="product_id[]" value="<?php echo e($item->product_id); ?>">

                                                                            <?php
                                                                                $myRating = $item->product->ratings()
                                                                                    ->where('customer_id', auth('customer')->id())
                                                                                    ->first()->rating ?? 0;
                                                                            ?>

                                                                            <div class="rating-stars mt-2" data-item="<?php echo e($item->id); ?>">
                                                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                                                    <label style="cursor: pointer; font-size: 22px;">
                                                                                        <input type="radio"
                                                                                               name="rating[<?php echo e($item->product_id); ?>]"
                                                                                               value="<?php echo e($i); ?>"
                                                                                               class="d-none star-input"
                                                                                               <?php echo e($i == $myRating ? 'checked' : ''); ?>>
                                                                                        <i class="fa fa-star star-icon <?php echo e($i <= $myRating ? 'text-warning' : 'text-secondary'); ?>"
                                                                                           data-value="<?php echo e($i); ?>"
                                                                                           data-product="<?php echo e($item->product_id); ?>">
                                                                                        </i>
                                                                                    </label>
                                                                                <?php endfor; ?>
                                                                            </div>

                                                                            
                                                                            <textarea name="review[<?php echo e($item->product_id); ?>]"
                                                                                      class="form-control mt-2"
                                                                                      rows="2"
                                                                                      placeholder="Write your review (optional)">
                                                                                <?php echo e($item->product->ratings()->where('customer_id', auth('customer')->id())->first()->review ?? ''); ?>

                                                                            </textarea>

                                                                        </div>
                                                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                                                                <?php else: ?>
                                                                    <p class="text-danger">No items found for this order.</p>
                                                                <?php endif; ?>

                                                            </div>

                                                            <div class="modal-footer">
                                                                <button type="submit" class="btn btn-primary">Submit Rating</button>
                                                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                    Close
                                                                </button>
                                                            </div>

                                                        </form>


                                                    </div>
                                                </div>
                                            </div>
                                            

                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php echo e($orders->links()); ?>


                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5>No orders yet</h5>
                                    <p class="text-muted">You haven't placed any orders yet.</p>
                                    <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Start Shopping</a>
                                </div>
                            <?php endif; ?>
                        <!-- Service Orders Tab -->
                        <?php elseif($active_tab == 'service-orders'): ?>
                            <h4 class="mb-4">My Services</h4>

                            <?php if(isset($service_orders) && $service_orders->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>Service Order ID</th>
                                                <th>Expire Date</th>
                                                <th>Installment Status</th>
                                                <th>Installment Number</th>
                                                <th>Paid Amount</th>
                                                <th>Due Amount</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>

                                        <tbody>
                                            <?php $__currentLoopData = $service_orders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service_order): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>#<?php echo e($service_order->id); ?></td>
                                                <td><?php echo e(Carbon\Carbon::parse($service_order->expired_at)->format('M d, Y')); ?></td>
                                                <td>
                                                    <span class="badge bg-<?php echo e($service_order->installment_status == 1 ? 'success' : 'warning'); ?>">
                                                        <?php echo e($service_order->installment_status == 1 ? 'Confirmed' : 'Pending'); ?>

                                                    </span>
                                                </td>
                                                <td><?php echo e($service_order->installment_number); ?></td>
                                                <td>৳ <?php echo e(number_format($service_order->paid_amount, 2)); ?></td>
                                                <td>৳ <?php echo e(number_format($service_order->due_amount, 2)); ?></td>

                                                <td>
                                                    <a href="<?php echo e(route('service-draft.details', $service_order->id)); ?>" class="btn btn-sm btn-outline-primary" target="_blank">
                                                        View
                                                    </a>
                                                    <?php if($service_order->installment_status == 0): ?>
                                                    Confirming 
                                                    <?php else: ?>
                                                    <button class="btn btn-sm btn-outline-success"
                                                        data-bs-toggle="modal"
                                                        data-bs-target="#addPayment<?php echo e($service_order->id); ?>">
                                                        Add Payment
                                                    </button>
                                                    <?php endif; ?>
                                                </td>
                                            </tr>

                                            <div class="modal fade" id="addPayment<?php echo e($service_order->id); ?>" tabindex="-1" aria-hidden="true">
                                                <div class="modal-dialog modal-dialog-centered">
                                                    <div class="modal-content">

                                                        <div class="modal-header">
                                                            <h5 class="modal-title">Add Installment</h5>
                                                            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                                        </div>

                                                        <div class="modal-body">
                                                            <form action="<?php echo e(route('customer.add.payment',  $service_order->id)); ?>" method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <div class="mb-3">
                                                                    <label for="form-label">Payment Amount</label>
                                                                    <input type="number" class="form-control" id="form-label" name="amount" required placeholder="Amount">
                                                                </div>
                                                                <button type="submit" class="btn btn-primary">Submit</button>
                                                            </form>
                                                        </div>
                                                        <div class="modal-footer">
                                                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                                                                Close
                                                            </button>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php echo e($service_orders->links()); ?>


                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5>No Services yet</h5>
                                    <p class="text-muted">You haven't ordered any services yet.</p>
                                    <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Start Shopping</a>
                                </div>
                            <?php endif; ?>

                        <!-- Follow Tab -->
                        <?php elseif($active_tab == 'followed-vendors'): ?>
                            <h4 class="mb-4">Followed Vendors</h4>
                            <?php if(isset($followedVendors) && $followedVendors->count() > 0): ?>
                                <div class="table-responsive">
                                    <table class="table table-striped">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Date</th>
                                                <th>Shop Name</th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php $__currentLoopData = $followedVendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $shop): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <tr>
                                                <td>#<?php echo e($shop->id); ?></td>
                                                <td><?php echo e($shop->created_at->format('M d, Y')); ?></td>
                                                <td><?php echo e($shop->shop_name ?? "N/A"); ?></td>
                                                <td>
                                                    <a href="<?php echo e(route('vendor.shop.view', $shop->user_id)); ?>" class="btn btn-sm btn-outline-primary">View</a>
                                                </td>
                                            </tr>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php echo e($followedVendors->links()); ?>

                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-shopping-bag fa-3x text-muted mb-3"></i>
                                    <h5>No followed vendors yet</h5>
                                    <p class="text-muted">You haven't followed vendors yet.</p>
                                    <a href="<?php echo e(route('home')); ?>" class="btn btn-primary">Start Shopping</a>
                                </div>
                            <?php endif; ?>

                        <!-- Addresses Tab -->
                        <?php elseif($active_tab == 'addresses'): ?>
                            <h4 class="mb-4">My Addresses</h4>
                            <?php if(isset($shippingAddresses) && $shippingAddresses->count() > 0): ?>
                                <div class="row">
                                    <?php $__currentLoopData = $shippingAddresses; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $address): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="col-md-6 mb-3">
                                        <div class="card border">
                                            <div class="card-body">
                                                <h6 class="card-title"><?php echo e($address->address_title ?? 'Primary Address'); ?></h6>
                                                <p class="card-text mb-1"><?php echo e($address->address); ?></p>
                                                <p class="card-text mb-1">
                                                    <?php echo e($address->upazila->name ?? ''); ?>,
                                                    <?php echo e($address->district->name ?? ''); ?>

                                                </p>
                                                <p class="card-text mb-1">Phone: <?php echo e($address->phone); ?></p>
                                                <div class="mt-3">
                                                    <a href="#" class="btn btn-sm btn-outline-primary">Edit</a>
                                                    <a href="#" class="btn btn-sm btn-outline-danger">Delete</a>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                            <?php else: ?>
                                <div class="text-center py-5">
                                    <i class="fas fa-map-marker-alt fa-3x text-muted mb-3"></i>
                                    <h5>No addresses saved</h5>
                                    <p class="text-muted">You haven't saved any shipping addresses yet.</p>
                                    <a href="<?php echo e(route('shipping.create')); ?>" class="btn btn-primary">Add Address</a>
                                </div>
                            <?php endif; ?>

                        <!-- Settings Tab -->
                        <?php elseif($active_tab == 'settings'): ?>
                            <h4 class="mb-4">Profile Settings</h4>
                            <form method="POST" action="<?php echo e(route('customer.profile.update')); ?>">
                                <?php echo csrf_field(); ?>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Full Name *</label>
                                            <input type="text" name="name" class="form-control"
                                                   value="<?php echo e(old('name', $customer->name)); ?>" required>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Phone Number *</label>
                                            <input type="text" name="phone" class="form-control"
                                                   value="<?php echo e(old('phone', $customer->phone)); ?>" required>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Email Address</label>
                                    <input type="email" name="email" class="form-control"
                                           value="<?php echo e(old('email', $customer->email)); ?>">
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Address</label>
                                    <textarea name="address" class="form-control" rows="3"><?php echo e(old('address', $customer->address)); ?></textarea>
                                </div>

                                <hr class="my-4">
                                <h6 class="mb-3">Change Password</h6>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">Current Password</label>
                                            <input type="password" name="current_password" class="form-control">
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label class="form-label">New Password</label>
                                            <input type="password" name="new_password" class="form-control">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">Confirm New Password</label>
                                    <input type="password" name="new_password_confirmation" class="form-control">
                                </div>

                                <button type="submit" class="btn btn-primary">Update Profile</button>
                            </form>

                        <?php else: ?>
                            <div class="text-center py-5">
                                <h4>Coming Soon</h4>
                                <p class="text-muted">This section is under development.</p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

<script>
$(document).ready(function() {
    console.log("jQuery loaded!");

    $(".rating-stars .star-icon").on("click", function () {

        let value = $(this).data("value");
        let wrapper = $(this).closest(".rating-stars");

        wrapper.find("input[value='" + value + "']").prop("checked", true);

        wrapper.find(".star-icon").each(function () {
            if ($(this).data("value") <= value) {
                $(this).removeClass("text-secondary").addClass("text-warning");
            } else {
                $(this).removeClass("text-warning").addClass("text-secondary");
            }
        });
    });

});
</script>



<style>
.profile-sidebar .nav-link {
    color: #333;
    padding: 12px 15px;
    border-radius: 8px;
    margin-bottom: 5px;
    transition: all 0.3s ease;
}

.profile-sidebar .nav-link:hover,
.profile-sidebar .nav-link.active {
    background: linear-gradient(45deg, #007bff, #0056b3);
    color: white;
}

.profile-content {
    min-height: 500px;
}

.stat-card {
    transition: transform 0.3s ease;
}

.stat-card:hover {
    transform: translateY(-5px);
}

.avatar-placeholder {
    box-shadow: 0 0 20px rgba(0,0,0,0.1);
}

.profile-header {
    text-shadow: 0 2px 4px rgba(0,0,0,0.5);
}
</style>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/pages/customer/profile.blade.php ENDPATH**/ ?>