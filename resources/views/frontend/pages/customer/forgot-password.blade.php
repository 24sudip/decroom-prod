@extends('frontend.layouts.master')
@section('title', 'Customer Password Forget')
@section('content')
    <section id="quicktech-login" style="background: url('{{ asset('frontend/images/login.png') }}') no-repeat center / cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="quikctech-login">
                          <div class="login-container">
                            <h1>Welcome To Password Reset!</h1>
                            <p>Enter your Email to get your Reset Token</p>

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

                            <form method="POST" action="{{ route('customer.password.forget') }}">
                                @csrf
                                
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
                                
                                <button type="submit" class="btn btn-primary btn-custom w-100">Send</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
