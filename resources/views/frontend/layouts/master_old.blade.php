<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>

    @include('frontend.include.style')
</head>

<body>

    <!--fixed side message-->
    <div class="quicktech-side-msg">
        <div class="quicktech-msg" id="messageIcon">
            <i class="fa-regular fa-message"></i>
        </div>
        <div class="quicktech-chatbox" id="chatBox">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Hi there!</h5>
                    <p class="card-text">Any Questions?? <br>
                        Please feel free to knock us!</p>
                    <div class="d-flex align-items-center mb-2">
                        <!--<img src="" class="rounded-circle mr-2" alt="User">-->
                    </div>
                    <a href="" class="btn btn-success messenger btn-block"><i
                            class="fa-brands fa-facebook-messenger"></i> Messenger</a>
                    <a href="" class="btn btn-outline-success whatsapp btn-block mt-2"><i
                            class="fa-brands fa-whatsapp"></i> Whatsapp</a>
                </div>
            </div>
        </div>
    </div>
    <!--fixed side message-->

    @php
        $cartCount = \Cart::getTotalQuantity();
        $cartTotal = \Cart::getTotal();
    @endphp

    <!-- side cart btn -->
    <button class="item-sidebar" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasRight"
        aria-controls="offcanvasRight">
        <div class="quikctech-side-itemm">
            <i class="fa-solid fa-bag-shopping"></i>
            <p>{{ $cartCount }} {{ Str::plural('ITEM', $cartCount) }}</p>
        </div>
        <p class="quicktech-side-price">৳ {{ number_format($cartTotal, 2) }}</p>
    </button>

    <!-- Top Navbar -->
    @include('frontend.include.header_with_search')
    

    <!-- top categoreies -->
    @include('frontend.include.top_category')
    <!-- top categories -->


    <!-- top navbar -->

    <!-- Sidebar -->
    <div class="sidebar">
        <div class="accordion" id="accordionCategories">
            <!-- Medicine -->
            <a class="quicktech-no-acc" href="{{ route('frontend.prescriptions.create') }}">
                <img src="images/medicine.png"
                    style="height: 25px; padding-right:10px;" alt=""> Upload Prescription</a>
            <!-- Medicine -->
            <div class="accordion-item bg-dark border-0">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed bg-dark text-white" data-bs-toggle="collapse"
                        data-bs-target="#medicineCollapse">
                        <img src="images/medicine.png" style="height: 25px; padding-right:10px;" alt="">
                        Medicine
                    </button>
                </h2>
                <div id="medicineCollapse" class="accordion-collapse collapse" data-bs-parent="#accordionCategories">
                    <div class="accordion-body ps-3">
                        <div class="accordion" id="medicineSubcategories">

                            <!-- Subcategory: Prescription -->
                            <div class="accordion-item bg-dark border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-dark text-white"
                                        data-bs-toggle="collapse" data-bs-target="#prescriptionCollapse">
                                        <img src="images/medicine.png" style="height: 25px; padding-right:10px;"
                                            alt=""> Prescription
                                    </button>
                                </h2>
                                <div id="prescriptionCollapse" class="accordion-collapse collapse"
                                    data-bs-parent="#medicineSubcategories">
                                    <div class="accordion-body ps-4">
                                        <a href="#" class="d-block text-white mb-1"><img src="images/medicine.png"
                                                style="height: 25px;" alt=""> Pain
                                            Relief</a>
                                        <a href="#" class="d-block text-white mb-1"><img src="images/medicine.png"
                                                style="height: 25px;" alt="">
                                            Antibiotics</a>
                                    </div>
                                </div>
                            </div>

                            <!-- Subcategory: OTC -->
                            <div class="accordion-item bg-dark border-0">
                                <h2 class="accordion-header">
                                    <button class="accordion-button collapsed bg-dark text-white"
                                        data-bs-toggle="collapse" data-bs-target="#otcCollapse">
                                        <img src="images/medicine.png" style="height: 25px; padding-right:10px;"
                                            alt=""> Over The Counter
                                    </button>
                                </h2>
                                <div id="otcCollapse" class="accordion-collapse collapse"
                                    data-bs-parent="#medicineSubcategories">
                                    <div class="accordion-body ps-4">
                                        <a href="#" class="d-block text-white mb-1"><img src="images/medicine.png"
                                                style="height: 25px; padding-right:10px;" alt=""> Cough
                                            Syrups</a>
                                        <a href="#" class="d-block text-white mb-1"><img src="images/medicine.png"
                                                style="height: 25px; padding-right:10px;" alt=""> Antacids</a>
                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <!-- side bar end -->

    <!-- mobile top nav -->
    <section id="quikcth-navbar-mobile">
        <div class="conatiner">
            <div class="row">
                <div class="col-7">
                    <div class="quikctech-mob-logo">
                       <a href="{{ route('home') }}">
                        <img src="{{ asset('build/images/' . $settings->logo_lg) }}" alt="logo">
                    </a>
                    </div>
                </div>
                <div class="col-5">
                    <div class="quikctehc-right">

                        <!-- Search Icon Button -->
                        <div class="quikctech-search-bbtn">
                            <button id="toggleSearch"><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                        <!-- Search Icon Button -->

                        <div class="quikctch-mob-cart text-end">
                            <button class="" type="button" data-bs-toggle="offcanvas"
                                data-bs-target="#offcanvasRight" aria-controls="offcanvasRight"><img
                                    src="{{ asset('public/frontend/images/shopping-bag(1).png') }}" alt=""></button>
                            <div class="quikctech-mob-cart-quantity">
                                <span>{{ $cartCount }}</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-12" id="searchBox" style="display: none;">
                    <div class="quicktech-search quikctch-mob-search">
                        <div class="quikctech-s-input">
                            <input type="text" placeholder="Search for products">
                        </div>
                        <div class="quicktech-search-btn">
                            <button><i class="fa-solid fa-magnifying-glass"></i></button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- mobile top nav -->

    <!-- mobile bottom nav -->
    <section id="quicktech-bottom-nav">
        <div class="container">
            <div class="row">
                <div class="col-3">
                    <div class="quicktech-bottom-nav-main text-center">
                        <a href="{{ route('home') }}">
                            <img src="{{ asset('public/frontend/images/home(4).png') }}" style="height: 20px;" alt=""> <br>
                            <span>Home</span>
                        </a>
                    </div>
                </div>
                <div class="col-3">
                    <div class="quicktech-bottom-nav-main text-center">
                        <a type="button" data-bs-toggle="offcanvas" data-bs-target="#quikctech-mobile"
                            aria-controls="quikctech-mobile">
                            <img src="{{ asset('public/frontend/images/app(1).png') }}" style="height: 20px;" alt=""> <br>
                            <span style="color: white;">Category</span>
                        </a>
                    </div>
                </div>
                <div class="col-3">
                    <div class="quicktech-bottom-nav-main text-center">
                        <a href="{{ route('frontend.prescriptions.create') }}">
                            <img src="{{ asset('public/frontend/images/prescription(1).png') }}" style="height: 20px;" alt=""> <br>
                            <span>Prescription</span>
                        </a>
                    </div>
                </div>
                <div class="col-3">
                    <div class="quicktech-bottom-nav-main text-center">
                        <a href="{{ route('customer.profile', ['tab' => 'profile']) }}">
                            <img src="{{ asset('public/frontend/images/user(1).png') }}" style="height: 20px;" alt=""> <br>
                            <span>Profile</span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- mobile category ofcanvas -->
    <div class="offcanvas offcanvas-start" tabindex="-1" id="quikctech-mobile"
        aria-labelledby="quikctech-mobileLabel">
        <div class="offcanvas-header">
            <h5 class="offcanvas-title" id="quikctech-mobileLabel">Menu</h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
        </div>
        <div class="offcanvas-body">
            <div class="accordion" id="accordionCategories">
               
                <!-- Medicine -->
                <div class="accordion" id="accordionCategories">
                    @foreach ($categories as $category)
                        <div class="accordion-item bg-dark border-0">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed bg-dark text-white"
                                    data-bs-toggle="collapse"
                                    data-bs-target="#categoryCollapse{{ $category->id }}">
                                    <img src="{{ asset('images/medicine.png') }}" style="height: 25px; padding-right:10px;" alt="">
                                    {{ $category->name }}
                                </button>
                            </h2>
                            <div id="categoryCollapse{{ $category->id }}" class="accordion-collapse collapse"
                                data-bs-parent="#accordionCategories">
                                <div class="accordion-body ps-3">
                
                                    {{-- Subcategories --}}
                                    <div class="accordion" id="subcategoryAccordion{{ $category->id }}">
                                        @foreach ($category->subcategories as $subcategory)
                                            <div class="accordion-item bg-dark border-0">
                                                <h2 class="accordion-header">
                                                    <button class="accordion-button collapsed bg-dark text-white"
                                                        data-bs-toggle="collapse"
                                                        data-bs-target="#subcategoryCollapse{{ $subcategory->id }}">
                                                        <img src="{{ asset('images/medicine.png') }}" style="height: 25px; padding-right:10px;" alt="">
                                                        {{ $subcategory->name }}
                                                    </button>
                                                </h2>
                                                <div id="subcategoryCollapse{{ $subcategory->id }}" class="accordion-collapse collapse"
                                                    data-bs-parent="#subcategoryAccordion{{ $category->id }}">
                                                    <div class="accordion-body ps-4">
                
                                                        {{-- Childcategories --}}
                                                        @foreach ($subcategory->childcategories as $childcategory)
                                                            <a href="{{ url('category/child/' . $childcategory->slug) }}"
                                                                class="d-block text-white mb-1">
                                                                <img src="{{ asset('images/medicine.png') }}" style="height: 25px;" alt="">
                                                                {{ $childcategory->name }}
                                                            </a>
                                                        @endforeach
                
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <!-- mobile categry ofcanvas -->


    <!-- mobile bottom nav -->


    <!-- Main Content -->
    <div class="content">

        @yield('content')

        <!-- footer -->
        @include('frontend.include.footer')
        <!-- footer -->
    </div>

    <!-- side cart modal -->
    @include('frontend.include.sidecart')


    <!-- side cart modal -->

    <!-- location modal -->
    @include('frontend.include.location_modal')
    <!-- location modal -->


    @include('frontend.include.script')

<script>
  const swiperbrands = new Swiper('.swiperbrands', {
    direction: 'horizontal',
    loop: true,
    autoplay: {
      delay: 2500, // 2.5 seconds per slide
      disableOnInteraction: false, // keep autoplay even after user interacts
    },

    // Optional: add scrollbar or navigation if needed
    // scrollbar: { el: '.swiper-scrollbar', draggable: true },
    // navigation: { nextEl: '.swiper-button-next', prevEl: '.swiper-button-prev' },

    // Responsive settings
    breakpoints: {
      320: {
        slidesPerView: 2,
        spaceBetween: 10,
      },
      768: {
        slidesPerView: 4,
        spaceBetween: 20,
      },
      1024: {
        slidesPerView: 6,
        spaceBetween: 30,
      }
    }
  });
</script>


</body>

</html>
