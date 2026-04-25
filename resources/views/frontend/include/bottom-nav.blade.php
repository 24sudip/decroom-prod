<section id="quikctech-bottom-nav" class="d-lg-none">
    <div class="container">
        <div class="row">
            <div class="col-12">
                <div class="quikctech-bottom-nav-menu">
                    <ul>
                        <!--Home-->
                        @auth('vendor')
                        <li>
                            <a href="{{ route('vendor.dashboard') }}" title="{{ __('messages.home') }}">
                                <i class="fa-solid fa-house"></i>
                            </a>
                        </li>
                        @else
                        <li>
                            <a href="{{ route('home') }}" title="{{ __('messages.home') }}">
                                <i class="fa-solid fa-house"></i>
                            </a>
                        </li>
                        <!--Cart -->
                        <li class="position-relative">
                            <a href="{{ route('cart.view') }}" id="mobile-cart-link" title="{{ __('messages.cart') }}">
                                <i class="fa-solid fa-bag-shopping"></i>

                                <span id="mobile-cart-count"
                                      class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger"
                                      style="font-size: 10px; display: {{ ($cartCount ?? 0) > 0 ? 'inline-block' : 'none' }}">
                                    {{ $cartCount ?? 0 }}
                                </span>
                            </a>
                        </li>
                        @endauth

                        <!--Menu Offcanvas-->
                        <li>
                            <a href="#" data-bs-toggle="offcanvas" data-bs-target="#offcanvasLeft" title="{{ __('messages.menu') }}">
                                <i class="fa-solid fa-bars"></i>
                            </a>
                        </li>

                        @auth('vendor')
                        @else
                            @if(session('user_type') !== 'customer')
                            <li>
                                <a href="{{ route('vendor.login') }}" title="Vendor Login">
                                    <i class="fa fa-male"></i>
                                </a>
                            </li>
                            @endif
                        @endauth

                        @auth('customer')
                        <!--Message / Chat-->
                        <li>
                            <a href="{{ route('customer.message') }}" title="{{ __('messages.message') }}">
                                <i class="fa-solid fa-message"></i>
                            </a>
                        </li>
                        @endauth

                        <!--Profile-->
                        @auth('customer')
                        <li>
                            <a href="{{ route('customer.profile') }}" title="{{ __('messages.profile') }}">
                                @if(auth('customer')->user()->image)
                                    <img src="{{ asset('storage/'.auth('customer')->user()->image) }}"
                                         style="height: 22px; width: 22px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <i class="fa-solid fa-user"></i>
                                @endif
                            </a>
                        </li>
                        {{-- <li>
                            <form method="post" action="{{ route('customer.logout') }}" title="{{ __('messages.logout') }}">
                                @csrf
                                <button type="submit" style="border: none; background-color: transparent;">
                                    <i class="fa fa-sign-out"></i>
                                </button>
                            </form>
                        </li> --}}
                        @endauth

                        @auth('vendor')
                        <li>
                            <a href="{{ route('vendor.profile') }}" title="{{ __('messages.seller_profile') }}">
                                @if(auth('vendor')->user()->image)
                                    <img src="{{ asset(auth('vendor')->user()->image) }}"
                                         style="height: 22px; width: 22px; border-radius: 50%; object-fit: cover;">
                                @else
                                    <i class="fa fa-male"></i>
                                @endif
                            </a>
                        </li>
                        {{--<li>
                            <form method="post" action="{{ route('vendor.logout') }}" title="{{ __('messages.logout') }}">
                                @csrf
                                <button type="submit" style="border: none; background-color: transparent;">
                                    <i class="fa fa-sign-out"></i>
                                </button>
                            </form>
                        </li>--}}
                        @endauth
                    </ul>
                </div>
            </div>
        </div>
    </div>
</section>
<style>
    #quikctech-bottom-nav ul {
        display: flex;
        justify-content: space-around;
        padding: 12px 0;
        margin: 0;
        list-style: none;
    }

    #quikctech-bottom-nav ul li a {
        font-size: 20px;
        color: #222;
    }

    #quikctech-bottom-nav {
        position: fixed;
        bottom: 0;
        width: 100%;
        background: white;
        box-shadow: 0 -3px 10px rgba(0,0,0,0.1);
        z-index: 9999;
    }

</style>
<script>
    function updateMobileCartCount() {
        fetch("{{ route('cart.count') }}")
            .then(res => res.json())
            .then(data => {
                let count = data.count ?? 0;
                let badge = document.getElementById("mobile-cart-count");

                if (badge) {
                    badge.textContent = count;
                    badge.style.display = count > 0 ? "inline-block" : "none";
                }
            });
    }

    document.addEventListener("DOMContentLoaded", updateMobileCartCount);
    window.updateMobileCartCount = updateMobileCartCount;

</script>
