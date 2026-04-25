@extends('frontend.layouts.master')
@section('title', 'Customer Login')
@section('content')
    <section id="quicktech-login" style="background: url('{{ asset('frontend/images/login.png') }}') no-repeat center / cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="quikctech-login">
                          <div class="login-container">
                            <h1>Welcome To Customer Login!</h1>
                            <p>Enter your credentials to access your account</p>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if(session('error'))
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    {{ session('error') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            @if($errors->any())
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        @foreach($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form method="POST" action="{{ route('customer.login.submit') }}">
                                @csrf

                                <div class="mb-3">
                                    <label for="phone" class="form-label quikctech-login-label">Mobile Number</label>
                                    <input type="tel"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           id="phone"
                                           name="phone"
                                           placeholder="mobile number"
                                           value="{{ old('phone') }}"
                                           required
                                           autofocus>
                                    @error('phone')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
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
                                <div class="mb-3">
                                    <label for="password" class="form-label quikctech-login-label">Password</label>
                                    <div class="password-wrapper">
                                        <input type="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           id="password"
                                           name="password"
                                           placeholder="Enter your password"
                                           required>
                                        <span
                                            class="toggle-password"
                                            data-target="password"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="remember"
                                               name="remember" {{ old('remember') ? 'checked' : '' }}>
                                        <label class="form-check-label" for="remember">Remember for 30 days</label>
                                    </div>
                                    <div class="text-center">
                                        <a href="{{ route('customer.password.request') }}" class="text-black">Forgot password?</a>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-custom w-100">Log in</button>

                                <div class="mt-4 text-center">
                                    <p>Don't have an account?
                                        <a href="{{ route('customer.register') }}" class="text-primary">Sign up</a>
                                    </p>
                                </div>

                                {{-- @if(config('services.google.client_id') || config('services.apple.client_id'))
                                <div class="social-btns">
                                    @if(config('services.google.client_id'))
                                    <a href="{{ route('customer.login.google') }}" class="google-btn">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRb5LOPUgzjbz_m4aVulC-GU5zu-30HBdYnAg&amp;s" alt="Google">
                                        Sign in with Google
                                    </a>
                                    @endif

                                    @if(config('services.apple.client_id'))
                                    <a href="{{ route('customer.login.apple') }}" class="apple-btn">
                                        <img src="https://www.apple.com/v/app-store/c/images/overview/icon_appstore__ev0z770zyxoy_large_2x.png" alt="Apple">
                                        Sign in with Apple
                                    </a>
                                    @endif
                                </div>
                                @endif --}}
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
@endsection
