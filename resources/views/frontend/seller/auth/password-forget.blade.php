<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Password Forget') }}</title>
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
                        <div style="background: white !important;" class="login-container">
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
                            <h1 style="color: black;">{{ __('messages.welcome') }}</h1>
                            <p style="color: black;">{{ __('Enter Your Email To Get Password Reset Token') }}</p>
    
                            <form action="{{ route('vendor.forget.password') }}" method="POST">
                                @csrf
    
                                <!-- Email -->
                                <div class="mb-3">
                                    <label style="color: black;" for="email" class="form-label quikctech-login-label">{{ __('Email Address') }}</label>
                                    <input style="color: black; border: 1px solid #ddd;" 
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
    
                                <button type="submit" class="btn btn-primary btn-custom">{{ __('Send') }}</button>
    
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

     <!-- seller home -->

    <!-- footer -->
    @include('frontend.include.footer')
    <!-- footer -->
    
    @include('frontend.include.script')
</body>
</html>