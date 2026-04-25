@extends('frontend.seller.seller_master')
@section('title', __('messages.edit_profile'))
@section('content')

<!-- seller-menu-top -->
@include('frontend.include.seller-menu-top')

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h4 class="mb-4">{{ __('messages.edit_profile') }}</h4>
                @if(session('success'))
                <div class="alert alert-success">
                    <p class="text-success">{{ session('success') }}</p>
                </div>
                @endif
                <form autocomplete="off" action="{{ route('vendor.profile.update') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    {{-- User Info --}}
                    <div class="mb-3">
                        <label for="welcome_description" class="form-label">{{ __('Welcome Description') }}</label>
                        <input type="text" name="welcome_description" id="welcome_description" class="form-control" value="{{ old('welcome_description', $user->welcome_description) }}"
                        placeholder="Welcome Description">
                        @error('welcome_description')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('messages.full_name') }}</label>
                        <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $user->name) }}" required>
                        @error('name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('messages.email') }}</label>
                        <input type="email" name="email" id="email" class="form-control" value="{{ old('email', $user->email) }}" required>
                        @error('email')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label">{{ __('messages.phone') }}</label>
                        <input type="text" name="phone" id="phone" class="form-control" value="{{ old('phone', $user->phone) }}">
                        @error('phone')<small class="text-danger">{{ $message }}</small>@enderror
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
                        <label for="password" class="form-label">{{ __('messages.new_password') }} <small>{{ __('messages.leave_blank_to_keep_current') }}</small></label>
                        <div class="password-wrapper">
                            <input type="text" style="display:none">
                            <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">

                            <span
                                class="toggle-password"
                                data-target="password"
                            >
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                        @error('password')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label">{{ __('messages.confirm_new_password') }}</label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <span
                                class="toggle-password"
                                data-target="password_confirmation"
                            >
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    {{-- Vendor Info --}}
                    <div class="mb-3">
                        <label for="shop_name" class="form-label">{{ __('messages.shop_name') }}</label>
                        <input type="text" name="shop_name" id="shop_name" class="form-control" value="{{ old('shop_name', $vendor->shop_name ?? '') }}" required>
                        @error('shop_name')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label">{{ __('messages.shop_address') }}</label>
                        <textarea name="address" id="address" class="form-control" rows="3">{{ old('address', $vendor->address ?? '') }}</textarea>
                        @error('address')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <!--Profile Image -->
                    <div class="mb-3">
                        <label class="form-label">{{ __('Profile Image') }}</label>
                        <input type="file" name="image" class="form-control">
                        @if($user && $user->image)
                            <div class="mt-2">
                                <img src="{{ asset($user->image) }}" alt="{{ __('Profile Image') }}" width="70">
                            </div>
                        @endif
                        @error('image')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>
                    
                    {{-- Logo Upload --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.shop_logo') }}</label>
                        <input type="file" name="logo" class="form-control">
                        @if($vendor && $vendor->logo)
                            <div class="mt-2">
                                <img src="{{ asset($vendor->logo) }}" alt="{{ __('messages.logo') }}" width="100">
                            </div>
                        @endif
                        @error('logo')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    {{-- Banner Upload --}}
                    <div class="mb-3">
                        <label class="form-label">{{ __('messages.banner_image') }}</label>
                        <input type="file" name="banner_image" class="form-control">
                        @if($vendor && $vendor->banner_image)
                            <div class="mt-2">
                                <img src="{{ asset($vendor->banner_image) }}" alt="{{ __('messages.banner') }}" width="200">
                            </div>
                        @endif
                        @error('banner_image')<small class="text-danger">{{ $message }}</small>@enderror
                    </div>

                    <button type="submit" class="btn btn-primary">{{ __('messages.update_profile') }}</button>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
window.addEventListener('DOMContentLoaded', () => {
    const pwd = document.getElementById('password');
    pwd.value = '';
});
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
