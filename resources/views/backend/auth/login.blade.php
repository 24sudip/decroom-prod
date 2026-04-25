@extends('backend.layouts.master-without-nav')
@section('title', __('Login'))

@section('body')
<body>
@endsection

@section('content')
<div class="account-pages my-5 pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary-subtle">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary">{{ __('Welcome Back!') }}</h5>
                                    <h6>{{ AppSetting('title') }}</h6>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="{{ URL::asset('build/images/profile-img.png') }}" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="auth-logo text-center mt-3">
                            <a href="{{ url('/') }}" class="auth-logo-light">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="{{ asset('public/storage/logo.png') }}" alt="logo" class="rounded-circle" height="34">
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="p-3">
                            <form id="loginForm" method="POST" action="{{ old('type', 'admin') == 'customer' ? route('customer.login.submit') : route('admin.submitLogin') }}">
                                @csrf

                                @if ($msg = Session::get('error'))
                                    <div class="alert alert-danger">{{ $msg }}</div>
                                @endif
                                @if ($msg = Session::get('success'))
                                    <div class="alert alert-success">{{ $msg }}</div>
                                @endif

                                <div class="mb-3">
                                    <label for="type">{{ __('Login As') }} <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="admin" {{ old('type') == 'admin' ? 'selected' : 'selected' }}>Admin</option>
                                    </select>
                                    @error('type')
                                        <span class="invalid-feedback d-block" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="mb-3">
                                    <label for="email">{{ __('Email Address') }} <span class="text-danger">*</span></label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control @error('email') is-invalid @enderror"
                                           value="{{ old('email') }}"
                                           placeholder="Enter email"
                                           autocomplete="email" autofocus>
                                    @error('email')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
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
                                    <label for="password">{{ __('Password') }} <span class="text-danger">*</span></label>
                                    <div class="password-wrapper">
                                        <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control @error('password') is-invalid @enderror"
                                           placeholder="Enter password">
                                        <span
                                            class="toggle-password"
                                            data-target="password"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    @error('password')
                                        <span class="invalid-feedback" role="alert"><strong>{{ $message }}</strong></span>
                                    @enderror
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" name="remember" id="customControlInline">
                                    <label class="form-check-label" for="customControlInline">{{ __('Remember me') }}</label>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">
                                        {{ __('Log In') }}
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <p>© {{ date('Y') }} {{ AppSetting('title') }}. Crafted with 
                        <i class="mdi mdi-heart text-danger"></i> by {{ __('QuicktechIT') }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
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

<script>
document.getElementById('type').addEventListener('change', function() {
    const form = document.getElementById('loginForm');
    form.action = this.value === 'customer' ? "{{ route('customer.login.submit') }}" : "{{ route('admin.submitLogin') }}";
});
</script>
@endsection
