<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu"><?php echo e(__('Dashboard')); ?></li>
                <li>
                    <a href="<?php echo e(url('/dashboard')); ?>" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span><?php echo e(__('translation.dashboards')); ?></span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Order Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="<?php echo e(route('report.order.all')); ?>">All Order</a></li>
                        <li><a href="<?php echo e(route('report.order.today')); ?>">Today's Order</a></li>
                        <li><a href="<?php echo e(route('report.order.brandwise')); ?>">Brandwise Order</a></li>
                        <li><a href="<?php echo e(route('report.order.customerwise')); ?>">Customerwise Order</a></li>

                        <?php $__currentLoopData = $ordertypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ordertype): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <li>
                                <a href="<?php echo e(route('report.order.bytype', $ordertype->slug)); ?>">
                                    <?php echo e($ordertype->name); ?>

                                    <span>(<?php echo e(\App\Order::where('status', $ordertype->id)->count()); ?>)</span>
                                </a>
                            </li>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Product Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        
                            <li><a href="<?php echo e(url('admin/product')); ?>">Product Manage</a></li>

                        
                            <li><a href="<?php echo e(url('category')); ?>">Category Manage</a></li>

                        <!--<li><a href="<?php echo e(url('subcategories')); ?>">Sub Category Manage</a></li>-->
                        <!--<li><a href="<?php echo e(url('childcategories')); ?>">Child Category Manage</a></li>-->


                        
                            <li><a href="<?php echo e(url('brand')); ?>">Brand Manage</a></li>

                        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('index-unit')): ?>
                            <li><a href="<?php echo e(url('unit')); ?>">Unit Manage</a></li>
                        <?php endif; ?>

                        <!--<?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('index-generic')): ?>-->
                        <!--    <li><a href="<?php echo e(url('generic')); ?>">Generic Manage</a></li>-->
                        <!--<?php endif; ?>-->


                        <li><a href="<?php echo e(url('paymentmethod')); ?>">Payment Method Manage</a></li>

                        <li><a href="<?php echo e(route('coupons.index')); ?>">Coupon Manage</a></li>
                        <li><a href="<?php echo e(url('slider')); ?>">Slider Manage</a></li>
                        <li><a href="<?php echo e(url('offerbanner')); ?>">Offer Banner Manage</a></li>


                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Service Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="<?php echo e(url('admin/service-draft/all')); ?>">Post Reel Manage</a></li>
                        <li><a href="<?php echo e(url('admin/get-services')); ?>">Service Manage</a></li>
                        <li><a href="<?php echo e(url('servicecategory')); ?>">Service Category Manage</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Accounts Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <!--<li><a href="<?php echo e(route('account-heads.index')); ?>">Accounts Header Manage</a></li>-->
                        <li><a href="<?php echo e(route('account-entries.index')); ?>">Income/Expense Manage</a></li>

                    </ul>
                </li>

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class='bx bx-plus-medical'></i>-->
                <!--        <span>Inventory Manage</span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="true">-->

                <!--        <li><a href="<?php echo e(route('product.stock')); ?>">Stock Manage</a></li>-->
                <!--        <li><a href="<?php echo e(route('product.stock_warning')); ?>">Stock Warning</a></li>-->
                <!--        <li><a href="<?php echo e(route('product.stock_out')); ?>">Stock Out</a></li>-->
                <!--        <li><a href="<?php echo e(route('expired.list')); ?>">Expire Products</a></li>-->

                <!--    </ul>-->
                <!--</li>-->

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class='bx bx-plus-medical'></i>-->
                <!--        <span>Purchase Manage</span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="true">-->

                <!--        <li><a href="<?php echo e(route('purchase.create')); ?>">Purchase Entry</a></li>-->
                <!--        <li><a href="<?php echo e(route('purchase.index')); ?>">Purchase List</a></li>-->

                <!--        <li><a href="<?php echo e(route('purchase-return.index')); ?>">Purchase Return List</a></li>-->
                <!--        <li><a href="<?php echo e(route('supplier.ledger-list')); ?>">Supplier Ledger</a></li>-->

                <!--    </ul>-->
                <!--</li>-->


                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class='bx bx-plus-medical'></i>-->
                <!--        <span>Customer Manage</span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="true">-->

                <!--        <li><a href="<?php echo e(route('customer.wholesale_list')); ?>">Wholesale Customer</a></li>-->
                <!--        <li><a href="<?php echo e(route('customer.retailer_list')); ?>">Retailer Customer</a></li>-->

                <!--    </ul>-->
                <!--</li>-->


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Seller Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="<?php echo e(route('admin.vendor-kyc')); ?>">Seller KYC</a></li>
                        <li><a href="<?php echo e(route('admin.vendorList')); ?>">Seller Manage</a></li>
                        <li><a href="<?php echo e(route('admin.orderCommission')); ?>">Order Commission</a></li>
                        <li><a href="<?php echo e(route('seller.withdraw')); ?>">Seller Withdraws</a></li>
                        <li><a href="<?php echo e(route('seller.transactions')); ?>">Seller Ledger</a></li>
                        <li><a href="<?php echo e(route('seller.withdrawRequest')); ?>">Seller Withdraw Request</a></li>

                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Reports Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="<?php echo e(route('reports.profitLoss')); ?>">Profit / Loss Report</a></li>
                        <li><a href="<?php echo e(route('reports.purchaseSale')); ?>">Purchase & Sale Report</a></li>
                        <li><a href="<?php echo e(route('reports.supplierCustomer')); ?>">Customers & Suppliers Reports</a></li>
                        <!--<li><a href="<?php echo e(route('reports.stock')); ?>">Stock Report</a></li>-->
                        <li><a href="<?php echo e(route('reports.supplierProductStock')); ?>">Supplier Wise Stock Report</a></li>
                        <!--<li><a href="<?php echo e(route('reports.productSell')); ?>">Product Sell Report</a></li>-->
                        <li><a href="<?php echo e(route('reports.purchasePayments')); ?>">Supplier Ledger Report</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Page Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="<?php echo e(url('page-categories')); ?>">Page Category Manage</a></li>
                        <li><a href="<?php echo e(url('pages')); ?>">Page Manage</a></li>
                        <li><a href="<?php echo e(url('faqs')); ?>">Faq Manage</a></li>
                        <li><a href="<?php echo e(route('abouts.index')); ?>">About Manage</a></li>

                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Social Media</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="<?php echo e(url('editor/social-media/add')); ?>">Add</a></li>
                        <li><a href="<?php echo e(url('editor/social-media/manage')); ?>">Manage</a></li>

                    </ul>
                </li>

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class='bx bx-plus-medical'></i>-->
                <!--        <span>Role Manage</span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="true">-->

                <!--        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('index-module')): ?>-->
                <!--            <li><a href="<?php echo e(url('module')); ?>">Module Manage</a></li>-->
                <!--        <?php endif; ?>-->

                <!--        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('index-user')): ?>-->
                <!--            <li><a href="<?php echo e(url('user')); ?>">User Manage</a></li>-->
                <!--        <?php endif; ?>-->

                <!--        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('index-role')): ?>-->
                <!--            <li><a href="<?php echo e(url('role')); ?>">Role Manage</a></li>-->
                <!--        <?php endif; ?>-->

                <!--        <?php if (app(\Illuminate\Contracts\Auth\Access\Gate::class)->check('index-permission')): ?>-->
                <!--            <li><a href="<?php echo e(url('permission')); ?>">Permission Manage</a></li>-->
                <!--        <?php endif; ?>-->

                <!--    </ul>-->
                <!--</li>-->

                <li>
                    <a href="<?php echo e(url('app-setting')); ?>" class="waves-effect">
                        <i class='bx bx-cog'></i>
                        <span><?php echo e(__('translation.app-setting')); ?></span>
                    </a>
                </li>
                <!--<li>-->
                <!--    <a href="<?php echo e(url('front-setting')); ?>" class="waves-effect">-->
                <!--        <i class='bx bx-book-content'></i>-->
                <!--        <span><?php echo e(__('translation.front-side')); ?></span>-->
                <!--    </a>-->
                <!--</li>-->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/layouts/sidebar.blade.php ENDPATH**/ ?>