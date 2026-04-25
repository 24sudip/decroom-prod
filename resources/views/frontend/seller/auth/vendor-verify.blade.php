<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Seller Verify') }}</title>
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
    <style>
        .otp-container{
            display:flex;
            gap:12px;
            justify-content:center;
        }
        
        .otp-input{
            width:55px;
            height:60px;
            text-align:center;
            font-size:24px;
            font-weight:600;
            border-radius:8px;
            border:2px solid #ccc;
            outline:none;
            transition:0.3s;
        }
        
        .otp-input:focus{
            border-color:#4f46e5;
            box-shadow:0 0 5px rgba(79,70,229,.4);
        }
        
        @media(max-width:480px){
            .otp-input{
                width:45px;
                height:50px;
            }
        }
    </style>
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
                            <p style="color: black;">{{ __('Enter your Token to verify your account') }}</p>
    
                            <form action="{{ route('vendor.verify.submit') }}" method="POST">
                                @csrf
    
                                <div class="otp-container">
                                    <input type="text" maxlength="1" class="otp-input">
                                    <input type="text" maxlength="1" class="otp-input">
                                    <input type="text" maxlength="1" class="otp-input">
                                    <input type="text" maxlength="1" class="otp-input">
                                    <!--<input type="text" maxlength="1" class="otp-input">-->
                                    <!--<input type="text" maxlength="1" class="otp-input">-->
                                </div>
                                
                                <input type="hidden" id="finalOtp" name="otp">
    
                                <button type="submit" class="btn btn-primary btn-custom mt-3">{{ __('Submit') }}</button>
    
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

    <script>
        const inputs = document.querySelectorAll(".otp-input");
        const hiddenOtp = document.getElementById("finalOtp");
        
        inputs.forEach((input, index) => {
        
            // Only numbers
            input.addEventListener("input", e => {
                input.value = input.value.replace(/\D/g,'');
        
                if(input.value && index < inputs.length - 1){
                    inputs[index+1].focus();
                }
        
                updateOtp();
            });
        
            // Backspace navigation
            input.addEventListener("keydown", e => {
                if(e.key === "Backspace" && !input.value && index > 0){
                    inputs[index-1].focus();
                }
            });
        
        });
        
        // Paste support
        inputs[0].addEventListener("paste", e => {
            const data = e.clipboardData.getData("text").replace(/\D/g,'');
            data.split('').forEach((char,i)=>{
                if(inputs[i]){
                    inputs[i].value = char;
                }
            });
            updateOtp();
        });
        
        function updateOtp(){
            hiddenOtp.value = Array.from(inputs)
                .map(i => i.value)
                .join('');
        
            console.log(hiddenOtp.value);
        }
    </script>

    <!-- footer -->
    @include('frontend.include.footer')
    <!-- footer -->
    
    @include('frontend.include.script')
</body>
</html>