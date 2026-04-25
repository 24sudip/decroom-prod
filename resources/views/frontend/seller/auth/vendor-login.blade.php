<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.seller_login') }}</title>
    @include('frontend.include.style')

</head>
<body>

  <!-- top bar -->
  @include('frontend.include.topbar')

  <!-- top bar -->

  <!-- mobile navbar-->
    @include('frontend.include.mobile-navbar')
  <!-- mobile navbar -->

  <!-- mobile bottom nav -->
    @include('frontend.include.bottom-nav')
  <!-- offcanvas menu -->

 <!-- Offcanvas Menu -->
    @include('frontend.include.offcanvas_menu')
  <!-- mobile bottom nav -->

  <!-- navbar -->
  @include('frontend.include.navbar')
   <!-- desktopmenu offcanvas -->

   <!-- Offcanvas -->
   @include('frontend.include.offcanvas_content')
   <!-- desktop menu off canvas -->

     <!-- seller home -->
     @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong>{{ session('success') }}</strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    @endif
    <section id="quicktech-login" style="background: url({{ asset('frontend/images/Rectangle.png') }}) no-repeat center / cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 ms-auto">
                    <div class="quikctech-login">
                        <div style="background: white !important;" class="login-container">
                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <strong>{{ session('error') }}</strong>
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            <h1 style="color: black;">{{ __('messages.welcome') }}</h1>
                            <p style="color: black;">{{ __('messages.enter_credentials') }}</p>

                            <form action="{{ route('vendor.login.submit') }}" method="POST">
                                @csrf

                                <!-- Email -->
                                <div class="mb-3">
                                    <label style="color: black;" for="phone" class="form-label quikctech-login-label">{{ __('Enter Phone Number') }}</label>
                                    <input style="color: black; border: 1px solid #ddd;"
                                           type="tel"
                                           name="phone"
                                           class="form-control"
                                           id="phone"
                                           placeholder="{{ __('Enter Phone Number') }}"
                                           value="{{ old('phone') }}"
                                           required>
                                    @error('phone')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <style>
                                    .password-wrapper {
                                        position: relative;
                                    }

                                    .password-wrapper input {
                                        padding-right: 45px; /* space for the eye icon */
                                    }

                                    .toggle-password {
                                        position: absolute;
                                        top: 50%;
                                        right: 12px;
                                        transform: translateY(-50%);
                                        cursor: pointer;
                                        color: #6c757d;
                                    }

                                    .toggle-password:hover {
                                        color: #000;
                                    }
                                </style>
                                <!-- Password -->
                                <div class="mb-3">
                                    <label style="color: black;" for="password" class="form-label quikctech-login-label">{{ __('messages.password') }}</label>
                                    <div class="password-wrapper">
                                        <input style="color: black; border: 1px solid #ddd;"
                                           type="password"
                                           name="password"
                                           class="form-control"
                                           id="password"
                                           placeholder="{{ __('messages.password') }}"
                                           required>
                                        <span
                                            class="toggle-password"
                                            data-target="password"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Remember & Forgot -->
                                <div class="d-flex justify-content-between">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label style="color: black;" class="form-check-label" for="remember">{{ __('messages.remember_30_days') }}</label>
                                    </div>
                                    <div class="text-center">
                                        <a style="color: black;" href="{{ route('vendor.password.forget') }}" class="text-black">{{ __('messages.forgot_password') }}</a>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-custom">{{ __('messages.log_in') }}</button>

                                <!-- Signup -->
                                <div class="mt-4 text-center">
                                    <p style="color: black;">
                                        {{ __('messages.dont_have_account') }}
                                        <a href="{{ route('vendor.register') }}" class="text-primary">{{ __('messages.sign_up') }}</a>
                                    </p>
                                </div>

                                <!-- Social Login -->
                                <div class="social-btns">
                                    <a href="{{ route('vendor.google.login') }}" class="google-btn" style="border: 2px solid black; color: black;">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRb5LOPUgzjbz_m4aVulC-GU5zu-30HBdYnAg&s" alt=""> {{ __('messages.sign_in_google') }}
                                    </a>
                                    {{-- <a href="#" class="apple-BTN" style="border: 2px solid black; color: black;">
                                        <img src="https://www.apple.com/v/app-store/c/images/overview/icon_appstore__ev0z770zyxoy_large_2x.png" alt=""> {{ __('messages.sign_in_apple') }}
                                    </a> --}}
                                </div>

                                <!-- General Errors -->
                                @if ($errors->any())
                                    <div class="mt-3 text-danger">
                                        <ul class="mb-0">
                                            @foreach ($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif

                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <script>
    document.querySelectorAll(".toggle-password").forEach(toggle => {
        toggle.addEventListener("click", function () {

            const input = document.getElementById(this.dataset.target);
            const icon = this.querySelector("i");

            if (!input) return;

            if (input.type === "password") {
                input.type = "text";
                icon.classList.replace("fa-eye", "fa-eye-slash");
            } else {
                input.type = "password";
                icon.classList.replace("fa-eye-slash", "fa-eye");
            }
        });
    });
    </script>
    <!-- seller home -->

    <!-- footer -->
    @include('frontend.include.footer')
    <!-- footer -->

    @include('frontend.include.script')
</body>
</html>
