@extends('backend.layouts.master-without-nav')

@section('title')
    {{ __('Forgot Password') }}
@endsection

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
                                            <h5 class="text-primary"> {{ __('Forgot Password') }}</h5>
                                            <p>Reset your password with {{ AppSetting('title') }}.</p>
                                        </div>
                                    </div>
                                    <div class="col-5 align-self-end">
                                        <img src="{{ URL::asset('build/images/profile-img.png') }}" alt=""
                                            class="img-fluid">
                                    </div>
                                </div>
                            </div>
                            <div class="card-body pt-0">
                                <div class="auth-logo">
                                    <a href="{{ url('/dashboard') }}" class="auth-logo-light">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ URL::asset('build/images/logo-light.svg') }}" alt=""
                                                    class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                    <a href="{{ url('/dashboard') }}" class="auth-logo-dark">
                                        <div class="avatar-md profile-user-wid mb-4">
                                            <span class="avatar-title rounded-circle bg-light">
                                                <img src="{{ URL::asset('build/images/logo.svg') }}" alt=""
                                                    class="rounded-circle" height="34">
                                            </span>
                                        </div>
                                    </a>
                                </div>
                                <div class="p-2">
                                    <form class="form-horizontal" method="POST" action="{{ url('forgot-password') }}">
                                        @csrf
                                        @if ($msg = Session::get('error'))
                                            <div class="alert alert-danger">
                                                <span> {{ $msg }} </span>
                                            </div>
                                        @endif
                                        @if ($msg = Session::get('success'))
                                            <div class="alert alert-success">
                                                <span> {{ $msg }} </span>
                                            </div>
                                        @endif
                                        <div class="mb-3">
                                            <label for="username">{{ __('Email') }} <span
                                                    class="text-danger">*</span></label>
                                            <input name="email" type="email"
                                                class="form-control @error('email') is-invalid @enderror"
                                                @if (old('email')) value="{{ old('email') }}" @endif
                                                id="username" placeholder="{{ __('Enter email') }}" autocomplete="email"
                                                autofocus>
                                            @error('email')
                                                <span class="invalid-feedback" role="alert">
                                                    <strong>{{ $message }}</strong>
                                                </span>
                                            @enderror
                                        </div>
                                        <div class="mb-3 row mb-0">
                                            <div class="col-12 text-end">
                                                <button class="btn btn-primary w-100 waves-effect waves-light"
                                                    type="submit">{{ __('Reset') }}</button>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                        <div class="mt-5 text-center">
                            <p>{{ __('Remember It ?') }} <a href="{{ url('login') }}" class="fw-medium text-primary">
                                    {{ __('Sign In here') }}</a> </p>
                            <p>© {{ date('Y') }} {{ AppSetting('title') }}. Crafted with <i
                                    class="mdi mdi-heart text-danger"></i> {{ __('by Themesbrand') }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endsection
