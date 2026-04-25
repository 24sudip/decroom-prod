<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('messages.seller_register') }}</title>
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

    <section id="quicktech-login" style="background: url({{ asset('frontend/images/Rectangle.png') }}) no-repeat center / cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 ms-auto">
                    <div class="quikctech-login">
                        <div style="background: white;" class="login-container">
                            @if ($errors->any())
                                @foreach ($errors->all() as $error)
                                    <p class="text-danger">{{ $error }}</p>
                                @endforeach
                            @endif

                            @if(session('error'))
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <strong>{{ session('error') }}</strong>
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif

                            @if(session('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                              <strong>{{ session('success') }}</strong>
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            @endif
                            <h1 style="color: black;">{{ __('messages.create_new_account') }}</h1>

                            <form action="{{ route('vendor.register.submit') }}" method="POST">
                                @csrf

                                <!-- Full Name -->
                                <div class="mb-3">
                                    <label style="color: black;" for="name" class="form-label quikctech-login-label">{{ __('messages.full_name') }}</label>
                                    <input style="color: black; border: 1px solid black;"
                                           type="text"
                                           name="name"
                                           class="form-control"
                                           id="name"
                                           placeholder="{{ __('messages.enter_full_name') }}"
                                           value="{{ old('name') }}"
                                           required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label style="color: black;" for="email" class="form-label quikctech-login-label">{{ __('messages.enter_email') }}</label>
                                    <input style="color: black; border: 1px solid black;"
                                           type="email"
                                           name="email"
                                           class="form-control"
                                           id="email"
                                           placeholder="{{ __('messages.enter_email') }}"
                                           value="{{ old('email') }}"
                                           required>
                                    @error('email')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <!-- Mobile -->
                                <div class="mb-3">
                                    <label style="color: black;" for="phone" class="form-label quikctech-login-label">{{ __('Enter Phone Number') }}</label>
                                    <input style="color: black; border: 1px solid black;"
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
                                        <input style="color: black; border: 1px solid black;"
                                           type="password"
                                           name="password"
                                           class="form-control"
                                           id="password"
                                           placeholder="{{ __('messages.create_password') }}"
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

                                <!-- Confirm Password -->
                                <div class="mb-3">
                                    <label style="color: black;" for="password_confirmation" class="form-label quikctech-login-label">{{ __('messages.confirm_password') }}</label>
                                    <div class="password-wrapper">
                                        <input style="color: black; border: 1px solid black;"
                                           type="password"
                                           name="password_confirmation"
                                           class="form-control"
                                           id="password_confirmation"
                                           placeholder="{{ __('messages.confirm_password') }}"
                                           required>
                                        <span
                                            class="toggle-password"
                                            data-target="password_confirmation"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                </div>

                                <!-- Terms -->
                                <div class="d-flex justify-content-between">
                                    <div class="mb-3 form-check">
                                        <input style="color: black;" type="checkbox" class="form-check-input" id="terms" name="terms" {{ old('terms') ? 'checked' : '' }} required>
                                        <label style="color: black;" class="form-check-label" for="terms">
                                            {{ __('messages.agree_terms') }} <a href="#" class="privacy-policy">{{ __('messages.privacy_policy') }}</a>
                                        </label>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-custom">{{ __('messages.sign_up') }}</button>

                                <!-- Already have account -->
                                <div class="mt-4 text-center">
                                    <p style="color: black;">{{ __('messages.already_have_account') }}
                                        <a href="{{ route('vendor.login') }}" class="text-primary">{{ __('messages.log_in') }}</a>
                                    </p>
                                </div>

                                <!-- Social Login -->
                                {{-- <div class="social-btns">
                                    <a style="color: black; border: 2px solid black;" href="" class="google-btn">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRb5LOPUgzjbz_m4aVulC-GU5zu-30HBdYnAg&s" alt="Google"> {{ __('messages.sign_up_google') }}
                                    </a>
                                    <a style="color: black; border: 2px solid black;" href="#" class="apple-btn">
                                        <img src="https://www.apple.com/v/app-store/c/images/overview/icon_appstore__ev0z770zyxoy_large_2x.png" alt="Apple"> {{ __('messages.sign_up_apple') }}
                                    </a>
                                </div> --}}

                                <!-- Validation Errors -->
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
