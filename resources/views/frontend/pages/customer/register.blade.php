@extends('frontend.layouts.master')
@section('title', 'Customer Registration')
@section('content')
    <section id="quicktech-register" style="background: url('{{ asset('frontend/images/login.png') }}') no-repeat center / cover;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <div class="quikctech-register">
                        <div class="register-container">
                            <h1>Create Account</h1>
                            <p>Join our platform and start exploring amazing vendors</p>

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

                            <form method="POST" action="{{ route('customer.register.submit') }}">
                                @csrf

                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="mb-3">
                                            <label for="name" class="form-label quikctech-login-label">Full Name *</label>
                                            <input type="text"
                                                   class="form-control @error('name') is-invalid @enderror"
                                                   id="name"
                                                   name="name"
                                                   placeholder="Enter your full name"
                                                   value="{{ old('name') }}"
                                                   required>
                                            @error('name')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="email" class="form-label quikctech-login-label">Email Address *</label>
                                            <input type="email"
                                                   class="form-control @error('email') is-invalid @enderror"
                                                   id="email"
                                                   name="email"
                                                   placeholder="Enter your email"
                                                   value="{{ old('email') }}"
                                                   required>
                                            @error('email')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="phone" class="form-label quikctech-login-label">Phone Number *</label>
                                            <input type="tel"
                                                   class="form-control @error('phone') is-invalid @enderror"
                                                   id="phone"
                                                   name="phone"
                                                   placeholder="Enter your phone number"
                                                   value="{{ old('phone') }}"
                                                   required>
                                            @error('phone')
                                                <div class="invalid-feedback">
                                                    {{ $message }}
                                                </div>
                                            @enderror
                                        </div>
                                    </div>
                                </div>

                                <div id="pharmacy-fields" style="display: {{ old('type') == 'pharmacy' ? 'block' : 'none' }};">
                                    <div class="row">
                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="pharmacy_name" class="form-label quikctech-login-label">Pharmacy Name *</label>
                                                <input type="text"
                                                       class="form-control @error('pharmacy_name') is-invalid @enderror"
                                                       id="pharmacy_name"
                                                       name="pharmacy_name"
                                                       placeholder="Enter pharmacy name"
                                                       value="{{ old('pharmacy_name') }}">
                                                @error('pharmacy_name')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>

                                        <div class="col-md-6">
                                            <div class="mb-3">
                                                <label for="tread_no" class="form-label quikctech-login-label">Trade License No</label>
                                                <input type="text"
                                                       class="form-control @error('tread_no') is-invalid @enderror"
                                                       id="tread_no"
                                                       name="tread_no"
                                                       placeholder="Enter trade license number"
                                                       value="{{ old('tread_no') }}">
                                                @error('tread_no')
                                                    <div class="invalid-feedback">
                                                        {{ $message }}
                                                    </div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="drug_licence_no" class="form-label quikctech-login-label">Drug License No</label>
                                        <input type="text"
                                               class="form-control @error('drug_licence_no') is-invalid @enderror"
                                               id="drug_licence_no"
                                               name="drug_licence_no"
                                               placeholder="Enter drug license number"
                                               value="{{ old('drug_licence_no') }}">
                                        @error('drug_licence_no')
                                            <div class="invalid-feedback">
                                                {{ $message }}
                                            </div>
                                        @enderror
                                    </div>
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
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password" class="form-label quikctech-login-label">Password *</label>
                                            <div class="password-wrapper">
                                                <input type="password"
                                                   class="form-control @error('password') is-invalid @enderror"
                                                   id="password"
                                                   name="password"
                                                   placeholder="Enter password"
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
                                    </div>

                                    <div class="col-md-6">
                                        <div class="mb-3">
                                            <label for="password_confirmation" class="form-label quikctech-login-label">Confirm Password *</label>
                                            <div class="password-wrapper">
                                                <input type="password"
                                                   class="form-control"
                                                   id="password_confirmation"
                                                   name="password_confirmation"
                                                   placeholder="Confirm your password"
                                                   required>
                                                <span
                                                    class="toggle-password"
                                                    data-target="password_confirmation"
                                                >
                                                    <i class="fa fa-eye"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-3">
                                    <label for="address" class="form-label quikctech-login-label">Address</label>
                                    <textarea class="form-control @error('address') is-invalid @enderror"
                                              id="address"
                                              name="address"
                                              placeholder="Enter your address"
                                              rows="3">{{ old('address') }}</textarea>
                                    @error('address')
                                        <div class="invalid-feedback">
                                            {{ $message }}
                                        </div>
                                    @enderror
                                </div>

                                <button type="submit" class="btn btn-primary btn-custom w-100">Create Account</button>

                                <div class="mt-4 text-center">
                                    <p>Already have an account?
                                        <a href="{{ route('customer.login') }}" class="text-primary">Sign in</a>
                                    </p>
                                </div>
                                {{-- <div class="social-btns">
                                    <a href="" class="google-btn" style="border: 2px solid black; color: black;">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRb5LOPUgzjbz_m4aVulC-GU5zu-30HBdYnAg&amp;s" alt=""> Sign in with Google
                                    </a>
                                    <a href="#" class="apple-BTN" style="border: 2px solid black; color: black;">
                                        <img src="https://www.apple.com/v/app-store/c/images/overview/icon_appstore__ev0z770zyxoy_large_2x.png" alt=""> Sign in with Apple
                                    </a>
                                </div> --}}
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

    <style>
        .register-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 2.5rem;
            border-radius: 15px;
            box-shadow: 0 8px 32px rgba(0, 0, 0, 0.1);
            margin: 2rem 0;
            position: relative;
            overflow: hidden;
        }

        .register-container::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 4px;
            background: linear-gradient(90deg, #007bff, #0056b3);
        }

        .register-container h1 {
            color: #2c3e50;
            font-weight: 700;
            margin-bottom: 0.5rem;
            text-align: center;
        }

        .register-container p {
            color: #6c757d;
            text-align: center;
            margin-bottom: 2rem;
        }

        .btn-custom {
            background: linear-gradient(45deg, #007bff, #0056b3);
            border: none;
            padding: 12px;
            font-weight: 600;
            border-radius: 8px;
            transition: all 0.3s ease;
            margin-top: 1rem;
        }

        .btn-custom:hover {
            transform: translateY(-2px);
            box-shadow: 0 8px 20px rgba(0, 123, 255, 0.3);
        }

        .quikctech-login-label {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 8px;
            font-size: 0.9rem;
        }

        .form-control {
            padding: 12px 15px;
            border: 2px solid rgba(0, 123, 255, 0.1);
            border-radius: 8px;
            transition: all 0.3s ease;
            background: rgba(255, 255, 255, 0.8);
            font-size: 0.95rem;
        }

        .form-control:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.15);
            background: rgba(255, 255, 255, 0.95);
        }

        .form-control::placeholder {
            color: #adb5bd;
        }

        .alert {
            border-radius: 8px;
            border: none;
            backdrop-filter: blur(5px);
            -webkit-backdrop-filter: blur(5px);
        }

        .alert-success {
            background: rgba(40, 167, 69, 0.9);
            color: white;
        }

        .alert-danger {
            background: rgba(220, 53, 69, 0.9);
            color: white;
        }

        .invalid-feedback {
            font-size: 0.85rem;
            font-weight: 500;
        }

        .text-primary {
            color: #007bff !important;
            font-weight: 600;
            text-decoration: none;
        }

        .text-primary:hover {
            color: #0056b3 !important;
            text-decoration: underline;
        }

        /* Custom select styling */
        .form-control[type="select"] {
            appearance: none;
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='m6 8 4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        /* Textarea specific styling */
        textarea.form-control {
            resize: vertical;
            min-height: 100px;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .register-container {
                padding: 2rem 1.5rem;
                margin: 1rem 0;
            }

            .register-container h1 {
                font-size: 1.75rem;
            }
        }

        /* Animation for form elements */
        .form-control, .btn-custom {
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Focus states for accessibility */
        .form-control:focus, .btn-custom:focus {
            outline: none;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.1);
        }

        /* Custom scrollbar for textarea */
        textarea.form-control::-webkit-scrollbar {
            width: 6px;
        }

        textarea.form-control::-webkit-scrollbar-track {
            background: rgba(0, 0, 0, 0.1);
            border-radius: 3px;
        }

        textarea.form-control::-webkit-scrollbar-thumb {
            background: rgba(0, 123, 255, 0.3);
            border-radius: 3px;
        }

        textarea.form-control::-webkit-scrollbar-thumb:hover {
            background: rgba(0, 123, 255, 0.5);
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const typeSelect = document.getElementById('type');
            const pharmacyFields = document.getElementById('pharmacy-fields');

            typeSelect.addEventListener('change', function() {
                if (this.value === 'pharmacy') {
                    pharmacyFields.style.display = 'block';
                    // Smooth fade in
                    pharmacyFields.style.opacity = '0';
                    pharmacyFields.style.transition = 'opacity 0.3s ease';
                    setTimeout(() => {
                        pharmacyFields.style.opacity = '1';
                    }, 10);
                    // Make pharmacy name required
                    document.getElementById('pharmacy_name').required = true;
                } else {
                    // Smooth fade out
                    pharmacyFields.style.opacity = '0';
                    setTimeout(() => {
                        pharmacyFields.style.display = 'none';
                    }, 300);
                    // Remove required from pharmacy name
                    document.getElementById('pharmacy_name').required = false;
                }
            });

            // Add floating label effect
            const formControls = document.querySelectorAll('.form-control');
            formControls.forEach(control => {
                // Add focus effect
                control.addEventListener('focus', function() {
                    this.parentElement.classList.add('focused');
                });

                control.addEventListener('blur', function() {
                    if (this.value === '') {
                        this.parentElement.classList.remove('focused');
                    }
                });

                // Check initial state
                if (control.value !== '') {
                    control.parentElement.classList.add('focused');
                }
            });
        });
    </script>
@endsection
