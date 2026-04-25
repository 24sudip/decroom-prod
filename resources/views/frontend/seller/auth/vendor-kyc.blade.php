<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Seller KYC') }}</title>
    @include('frontend.include.style')
</head>
<body>
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
  
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
                            <p style="color: black;">{{ __('Please Enter The Following Fields') }}</p>
    
                            <form action="{{ route('vendor.kyc.submit') }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                
                                @if(Session::has('user_id')) 
                                <input type="hidden" name="user_id" value="{{ Session::get('user_id') }}">
                                @endif 
                                
                                <div class="mb-3">
                                    <img src="{{ url('build/images/logo-light.jpeg') }}" alt="Profile" class="p-1 bg-primary" width="150" id="showImage">
                                </div>
                                
                                <div class="mb-3">
                                    <label style="color: black;" for="image" class="form-label quikctech-login-label">{{ __('Image') }}</label>
                                    <input style="color: black; border: 1px solid #ddd;" 
                                           type="file" 
                                           name="image" 
                                           class="form-control" 
                                           id="image" 
                                           placeholder="{{ __('Image') }}" 
                                           required>
                                    @error('image')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                    
                                <div class="mb-3">
                                    <label style="color: black;" for="name" class="form-label quikctech-login-label">{{ __('Name') }}</label>
                                    <input style="color: black; border: 1px solid #ddd;" 
                                           type="text" 
                                           name="name" 
                                           class="form-control" 
                                           id="name" 
                                           placeholder="{{ __('Name') }}" 
                                           required>
                                    @error('name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label style="color: black;" for="father_name" class="form-label quikctech-login-label">{{ __('Fathers Name') }}</label>
                                    <input style="color: black; border: 1px solid #ddd;" 
                                           type="text" 
                                           name="father_name" 
                                           class="form-control" 
                                           id="father_name" 
                                           placeholder="{{ __('Fathers Name') }}" 
                                           required>
                                    @error('father_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label style="color: black;" for="mother_name" class="form-label quikctech-login-label">{{ __('Mothers Name') }}</label>
                                    <input style="color: black; border: 1px solid #ddd;" 
                                           type="text" 
                                           name="mother_name" 
                                           class="form-control" 
                                           id="mother_name" 
                                           placeholder="{{ __('Mothers Name') }}" 
                                           required>
                                    @error('mother_name')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
    
                                <!-- Email -->
                                <div class="mb-3">
                                    <label style="color: black;" for="email" class="form-label quikctech-login-label">{{ __('Email') }}</label>
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
    
                                <div class="mb-3">
                                    <label style="color: black;" for="nid_front" class="form-label quikctech-login-label">{{ __('NID Front Photo') }}</label>
                                    <input style="color: black; border: 1px solid #ddd;" 
                                           type="file" 
                                           name="nid_front" 
                                           class="form-control" 
                                           id="nid_front" 
                                           placeholder="{{ __('NID Front Photo') }}" 
                                           required>
                                    @error('nid_front')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                
                                <div class="mb-3">
                                    <label style="color: black;" for="nid_back" class="form-label quikctech-login-label">{{ __('NID Back Photo') }}</label>
                                    <input style="color: black; border: 1px solid #ddd;" 
                                           type="file" 
                                           name="nid_back" 
                                           class="form-control" 
                                           id="nid_back" 
                                           placeholder="{{ __('NID Back Photo') }}" 
                                           required>
                                    @error('nid_back')
                                        <small class="text-danger">{{ $message }}</small>
                                    @enderror
                                </div>
                                <button type="submit" class="btn btn-primary btn-custom">{{ __('Save') }}</button>
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
    <script type="text/javascript">
        $(document).ready(function () {
            $("#image").change(function (e) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#showImage").attr("src", e.target.result);
                };
                reader.readAsDataURL(e.target.files['0']);
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