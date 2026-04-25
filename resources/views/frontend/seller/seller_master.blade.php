<!DOCTYPE html>
<html lang="{{ app()->getLocale() }}">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title')</title>

    @include('frontend.include.style')

    <!-- jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"
    integrity="sha256-3fpQq9q6bVxM20x/4RVvE6sW1+/I34mBS5CgKxQ5Euo="
    crossorigin="anonymous"></script>
</head>
<body>

@include('frontend.include.topbar')
@include('frontend.include.mobile-navbar')
@include('frontend.include.bottom-nav')
@include('frontend.include.offcanvas_menu')

{{-- @include('frontend.include.navbar') --}}

@include('frontend.include.offcanvas_content')

<section id="quikctech-seller-home">
    <div class="container-fluid quikctech-fl">
        <div class="row gapp my-4">

            <!-- Sidebar Left -->
            <div class="col-lg-3">
                <div class="quicktech-side-bar-seller quikctech-mb-none">

                    <!-- Vendor Info -->
                    <a href="{{ route('vendor.dashboard') }}">
                        <div class="quikctech-seller-name text-center">
                            @php
                                $user = auth('vendor')->user();
                                $vendor = App\Vendor::where('user_id', $user->id)->first();
                                $vendorInfo = $user ? ($user->vendorDetails ?? $vendor) : null;
                            @endphp

                            @if($vendorInfo->logo && file_exists(public_path($vendorInfo->logo)))
                                <img src="{{ asset($vendorInfo->logo) }}?t={{ time() }}" alt="Profile Picture" width="100%">
                            @else
                                <img src="https://ui-avatars.com/api/?name={{ urlencode($user->name) }}&background=random&size=200" alt="Profile Picture" width="100%">
                            @endif

                            <p>{{ $user->name ?? 'Vendor Name' }}</p>
                        </div>
                    </a>

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
                                        <li>
                                            <a href="{{ route('service-draft.index') }}">Post A Reel</a>
                                        </li>
                                        <li>
                                            <a href="{{ route('services.index') }}">
                                                {{ __('messages.manage_services') }}
                                            </a>
                                        </li>
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
                                        <li><a href="{{ route('vendor.wallet') }}">Account</a></li>
                                        <li><a href="{{ route('vendor.wallet.all') }}">{{ __('messages.wallet') }}</a></li>
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
                </div>
            </div>

            <!-- Right Content -->
            <div class="col-lg-9">
                <div class="content">
                    @yield('content')
                </div>
            </div>
        </div>
    </div>
</section>
@include('frontend.include.footer')
@include('frontend.include.script')

<script>
document.addEventListener('DOMContentLoaded', function () {
    // Disable right click
    document.addEventListener('contextmenu', function(e) {
        e.preventDefault();
        alert('Right Click is disabled on this website.');
    });

    // Disable copy
    document.addEventListener('copy', function(e) {
        e.preventDefault();
        alert('Copying is disabled on this website.');
    });

    // Disable keyboard shortcuts
    document.addEventListener('keydown', function(e) {
        if ((e.ctrlKey || e.metaKey) &&
            ['c','u','s','a','x'].includes(e.key.toLowerCase())) {
            e.preventDefault();
            alert('Keyboard Copy is disabled on this website.');
        }
    });
});
</script>
</body>
</html>
