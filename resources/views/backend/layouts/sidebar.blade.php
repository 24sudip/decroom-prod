<!-- ========== Left Sidebar Start ========== -->
<div class="vertical-menu">

    <div data-simplebar class="h-100">

        <!--- Sidemenu -->
        <div id="sidebar-menu">
            <!-- Left Menu Start -->
            <ul class="metismenu list-unstyled" id="side-menu">
                <li class="menu-title" key="t-menu">{{ __('Dashboard') }}</li>
                <li>
                    <a href="{{ url('/dashboard') }}" class="waves-effect">
                        <i class="bx bx-home-circle"></i>
                        <span>{{ __('translation.dashboards') }}</span>
                    </a>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Order Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">
                        <li><a href="{{ route('report.order.all') }}">All Order</a></li>
                        <li><a href="{{ route('report.order.today') }}">Today's Order</a></li>
                        <li><a href="{{ route('report.order.brandwise') }}">Brandwise Order</a></li>
                        <li><a href="{{ route('report.order.customerwise') }}">Customerwise Order</a></li>

                        @foreach ($ordertypes as $ordertype)
                            <li>
                                <a href="{{ route('report.order.bytype', $ordertype->slug) }}">
                                    {{ $ordertype->name }}
                                    <span>({{ \App\Order::where('status', $ordertype->id)->count() }})</span>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Product Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        {{--@can('product-list')
                        @endcan--}}
                            <li><a href="{{ url('admin/product') }}">Product Manage</a></li>

                        {{--@can('index-category')
                        @endcan--}}
                            <li><a href="{{ url('category') }}">Category Manage</a></li>

                        <!--<li><a href="{{ url('subcategories') }}">Sub Category Manage</a></li>-->
                        <!--<li><a href="{{ url('childcategories') }}">Child Category Manage</a></li>-->


                        {{--@can('index-brand')
                        @endcan--}}
                            <li><a href="{{ url('brand') }}">Brand Manage</a></li>

                        @can('index-unit')
                            <li><a href="{{ url('unit') }}">Unit Manage</a></li>
                        @endcan

                        <!--@can('index-generic')-->
                        <!--    <li><a href="{{ url('generic') }}">Generic Manage</a></li>-->
                        <!--@endcan-->


                        <li><a href="{{ url('paymentmethod') }}">Payment Method Manage</a></li>

                        <li><a href="{{ route('coupons.index') }}">Coupon Manage</a></li>
                        <li><a href="{{ url('slider') }}">Slider Manage</a></li>
                        <li><a href="{{ url('offerbanner') }}">Offer Banner Manage</a></li>


                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Service Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="{{ url('admin/service-draft/all') }}">Post Reel Manage</a></li>
                        <li><a href="{{ url('admin/get-services') }}">Service Manage</a></li>
                        <li><a href="{{ url('servicecategory') }}">Service Category Manage</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Accounts Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <!--<li><a href="{{ route('account-heads.index') }}">Accounts Header Manage</a></li>-->
                        <li><a href="{{ route('account-entries.index') }}">Income/Expense Manage</a></li>

                    </ul>
                </li>

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class='bx bx-plus-medical'></i>-->
                <!--        <span>Inventory Manage</span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="true">-->

                <!--        <li><a href="{{ route('product.stock') }}">Stock Manage</a></li>-->
                <!--        <li><a href="{{ route('product.stock_warning') }}">Stock Warning</a></li>-->
                <!--        <li><a href="{{ route('product.stock_out') }}">Stock Out</a></li>-->
                <!--        <li><a href="{{ route('expired.list') }}">Expire Products</a></li>-->

                <!--    </ul>-->
                <!--</li>-->

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class='bx bx-plus-medical'></i>-->
                <!--        <span>Purchase Manage</span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="true">-->

                <!--        <li><a href="{{ route('purchase.create') }}">Purchase Entry</a></li>-->
                <!--        <li><a href="{{ route('purchase.index') }}">Purchase List</a></li>-->

                <!--        <li><a href="{{ route('purchase-return.index') }}">Purchase Return List</a></li>-->
                <!--        <li><a href="{{ route('supplier.ledger-list') }}">Supplier Ledger</a></li>-->

                <!--    </ul>-->
                <!--</li>-->


                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class='bx bx-plus-medical'></i>-->
                <!--        <span>Customer Manage</span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="true">-->

                <!--        <li><a href="{{ route('customer.wholesale_list') }}">Wholesale Customer</a></li>-->
                <!--        <li><a href="{{ route('customer.retailer_list') }}">Retailer Customer</a></li>-->

                <!--    </ul>-->
                <!--</li>-->


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Seller Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="{{ route('admin.vendor-kyc') }}">Seller KYC</a></li>
                        <li><a href="{{ route('admin.vendorList') }}">Seller Manage</a></li>
                        <li><a href="{{ route('admin.orderCommission') }}">Order Commission</a></li>
                        <li><a href="{{ route('seller.withdraw') }}">Seller Withdraws</a></li>
                        <li><a href="{{ route('seller.transactions') }}">Seller Ledger</a></li>
                        <li><a href="{{ route('seller.withdrawRequest') }}">Seller Withdraw Request</a></li>

                    </ul>
                </li>


                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Reports Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="{{ route('reports.profitLoss') }}">Profit / Loss Report</a></li>
                        <li><a href="{{ route('reports.purchaseSale') }}">Purchase & Sale Report</a></li>
                        <li><a href="{{ route('reports.supplierCustomer') }}">Customers & Suppliers Reports</a></li>
                        <!--<li><a href="{{ route('reports.stock') }}">Stock Report</a></li>-->
                        <li><a href="{{ route('reports.supplierProductStock') }}">Supplier Wise Stock Report</a></li>
                        <!--<li><a href="{{ route('reports.productSell') }}">Product Sell Report</a></li>-->
                        <li><a href="{{ route('reports.purchasePayments') }}">Supplier Ledger Report</a></li>

                    </ul>
                </li>

                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Page Manage</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="{{ url('page-categories') }}">Page Category Manage</a></li>
                        <li><a href="{{ url('pages') }}">Page Manage</a></li>
                        <li><a href="{{ url('faqs') }}">Faq Manage</a></li>
                        <li><a href="{{ route('abouts.index') }}">About Manage</a></li>

                    </ul>
                </li>
                <li>
                    <a href="javascript: void(0);" class="has-arrow waves-effect">
                        <i class='bx bx-plus-medical'></i>
                        <span>Social Media</span>
                    </a>
                    <ul class="sub-menu" aria-expanded="true">

                        <li><a href="{{ url('editor/social-media/add') }}">Add</a></li>
                        <li><a href="{{ url('editor/social-media/manage') }}">Manage</a></li>

                    </ul>
                </li>

                <!--<li>-->
                <!--    <a href="javascript: void(0);" class="has-arrow waves-effect">-->
                <!--        <i class='bx bx-plus-medical'></i>-->
                <!--        <span>Role Manage</span>-->
                <!--    </a>-->
                <!--    <ul class="sub-menu" aria-expanded="true">-->

                <!--        @can('index-module')-->
                <!--            <li><a href="{{ url('module') }}">Module Manage</a></li>-->
                <!--        @endcan-->

                <!--        @can('index-user')-->
                <!--            <li><a href="{{ url('user') }}">User Manage</a></li>-->
                <!--        @endcan-->

                <!--        @can('index-role')-->
                <!--            <li><a href="{{ url('role') }}">Role Manage</a></li>-->
                <!--        @endcan-->

                <!--        @can('index-permission')-->
                <!--            <li><a href="{{ url('permission') }}">Permission Manage</a></li>-->
                <!--        @endcan-->

                <!--    </ul>-->
                <!--</li>-->

                <li>
                    <a href="{{ url('app-setting') }}" class="waves-effect">
                        <i class='bx bx-cog'></i>
                        <span>{{ __('translation.app-setting') }}</span>
                    </a>
                </li>
                <!--<li>-->
                <!--    <a href="{{ url('front-setting') }}" class="waves-effect">-->
                <!--        <i class='bx bx-book-content'></i>-->
                <!--        <span>{{ __('translation.front-side') }}</span>-->
                <!--    </a>-->
                <!--</li>-->

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
</div>
<!-- Left Sidebar End -->
