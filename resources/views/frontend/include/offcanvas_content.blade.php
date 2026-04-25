<div class="offcanvas offcanvas-start quikctech-offcanvas" tabindex="-1" id="quikctechOffcanvas1" aria-labelledby="quikctechOffcanvasLabel1">
    <div class="offcanvas-header">
        <h5 class="offcanvas-title" id="quikctechOffcanvasLabel1">
            <img src="{{ asset('frontend/images/logo-removebg-preview(3).png') }}" alt="{{ __('messages.logo') }}">
        </h5>
        <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="{{ __('messages.close') }}"></button>
    </div>

    <div class="offcanvas-body">
        <ul class="list-unstyled quikctech-desktop-menu-sidebar mb-0">
            @auth('vendor')
            <li>
                <!-- Sidebar Menu -->
                <div class="quicktech-sidebar-menu">
                    <div class="accordion quicktech-sidebar" id="sidebarMenu">
                        <!-- PROFILE -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <a href="{{ route('vendor.profile') }}"
                                   class="accordion-button quicktech-imgg-side quikctech-no-drop">
                                    <img src="{{ asset('frontend/images/User-Profile-PNG.png') }}" 
                                     style="height: 24px; width: 24px; display: inline-block;" 
                                     alt="{{ __('messages.login') }}">
                                    <img src="{{ asset('frontend/images/user-avatar 2.png') }}" alt="">
                                    {{ __('messages.seller_profile') }}
                                </a>
                            </h2>
                        </div>
                        <!-- PRODUCTS -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed quicktech-imgg-side"
                                        data-bs-toggle="collapse" data-bs-target="#productsMenu">
                                    <img src="{{ asset('frontend/images/box-2 1.png') }}" alt="">
                                    {{ __('messages.products') }}
                                </button>
                            </h2>
        
                            <div id="productsMenu" class="accordion-collapse collapse">
                                <ul class="list-unstyled ms-4 p-2">
                                    <li><a href="{{ route('vendor.products.manage') }}">{{ __('messages.manage_products') }}</a></li>
                                    <li><a href="{{ route('vendor.products.create') }}">{{ __('messages.add_product') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        <!-- SERVICES -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed quicktech-imgg-side"
                                        data-bs-toggle="collapse" data-bs-target="#servicesMenu">
                                    <img src="{{ asset('frontend/images/box-2 1.png') }}" alt="">
                                    {{ __('messages.services') }}
                                </button>
                            </h2>
        
                            <div id="servicesMenu" class="accordion-collapse collapse">
                                <ul class="list-unstyled ms-4 p-2">
                                    <li><a href="{{ route('services.index') }}">{{ __('messages.manage_services') }}</a></li>
                                    <li><a href="{{ route('services.create') }}">{{ __('messages.add_service') }}</a></li>
                                </ul>
                            </div>
                        </div>
                        
                        <!-- INVENTORY -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed quicktech-imgg-side"
                                        data-bs-toggle="collapse" data-bs-target="#inventoryMenu">
                                    <img src="{{ asset('frontend/images/box-2 1.png') }}" alt="">
                                    Inventory
                                </button>
                            </h2>
        
                            <div id="inventoryMenu" class="accordion-collapse collapse">
                                <ul class="list-unstyled ms-4 p-2">
                                    <li><a href="{{ route('vendor.inventory.stock.manage') }}">Stock Manage</a></li>
                                    <li><a href="{{ route('vendor.inventory.stock.warning') }}">Stock Warning</a></li>
                                    <li><a href="{{ route('vendor.inventory.stock.out') }}">Stock Out</a></li>
                                </ul>
                            </div>
                        </div>
        
                        <!-- ORDERS -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed quicktech-imgg-side"
                                        data-bs-toggle="collapse" data-bs-target="#ordersMenu">
                                    <img src="{{ asset('frontend/images/compliant 1.png') }}" alt="">
                                    {{ __('messages.order_review') }}
                                </button>
                            </h2>
        
                            <div id="ordersMenu" class="accordion-collapse collapse">
                                <ul class="list-unstyled ms-4 p-2">
                                    <li><a href="{{ route('vendor.orders.list') }}">{{ __('messages.orders') }}</a></li>
                                </ul>
                            </div>
                        </div>
        
                        <!-- WALLET & LEDGER -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed quicktech-imgg-side"
                                        data-bs-toggle="collapse" data-bs-target="#walletMenu">
                                    <img src="{{ asset('frontend/images/compliant 1.png') }}" alt="">
                                    {{ __('messages.wallet_ledger') }}
                                </button>
                            </h2>
        
                            <div id="walletMenu" class="accordion-collapse collapse">
                                <ul class="list-unstyled ms-4 p-2">
                                    <li><a href="{{ route('vendor.wallet') }}">{{ __('messages.wallet') }}</a></li>
                                    <li><a href="{{ route('vendor.transactions') }}">{{ __('messages.ledger') }}</a></li>
                                </ul>
                            </div>
                        </div>
        
                        <!-- STORE -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed quicktech-imgg-side"
                                        data-bs-toggle="collapse" data-bs-target="#storeMenu">
                                    <img src="{{ asset('frontend/images/supermaket.png') }}" alt="">
                                    {{ __('messages.store') }}
                                </button>
                            </h2>
        
                            <div id="storeMenu" class="accordion-collapse collapse">
                                <ul class="list-unstyled ms-4 p-2">
                                    <li><a href="{{ route('vendor.profile') }}">{{ __('messages.store_settings') }}</a></li>
                                </ul>
                            </div>
                        </div>
        
                        <!-- LANGUAGE -->
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed quicktech-imgg-side"
                                        data-bs-toggle="collapse" data-bs-target="#languageMenu">
                                    <img src="{{ asset('frontend/images/language.png') }}" alt="">
                                    {{ __('messages.language') }}
                                </button>
                            </h2>
        
                            <div id="languageMenu" class="accordion-collapse collapse">
                                <ul class="list-unstyled ms-4 p-2">
                                    <li><a href="{{ route('lang.change','en') }}">English</a></li>
                                    <li><a href="{{ route('lang.change','bn') }}">বাংলা</a></li>
                                </ul>
                            </div>
                        </div>
        
                        <!-- LOGOUT -->
                        <div class="accordion-item mt-3">
                            <form action="{{ route('vendor.logout') }}" method="POST">
                                @csrf
                                <button class="btn btn-danger w-100">
                                    {{ __('messages.logout') }}
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </li>
            @endauth
            <!--Product Categories-->
            <li>
                <a class="d-flex justify-content-between align-items-center py-2"
                   data-bs-toggle="collapse"
                   href="#productCatMenu"
                   role="button"
                   aria-expanded="false"
                   aria-controls="productCatMenu">
                    <span>{{ __('messages.product_categories') }}</span>
                    <i class="fas fa-chevron-down"></i>
                </a>

                <ul class="collapse list-unstyled ps-3" id="productCatMenu">
                    @foreach($categories as $cat)
                        <li>
                            <a href="{{ route('product_category', $cat->slug) }}" class="d-block py-2">
                                {{ $cat->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
            <!--<hr>-->

            <!--Service Categories-->
            <li>
                <a class="d-flex justify-content-between align-items-center py-2"
                   data-bs-toggle="collapse"
                   href="#serviceCatMenu"
                   role="button"
                   aria-expanded="false"
                   aria-controls="serviceCatMenu">
                    <span>{{ __('messages.service_categories') }}</span>
                    <i class="fas fa-chevron-down"></i>
                </a>

                <ul class="collapse list-unstyled ps-3" id="serviceCatMenu">
                    @foreach($serviceCategories as $sc)
                        <li>
                            <a href="{{ route('service_category', $sc->slug) }}" class="d-block py-2">
                                {{ $sc->name }}
                            </a>
                        </li>
                    @endforeach
                </ul>
            </li>
        </ul>
    </div>
</div>