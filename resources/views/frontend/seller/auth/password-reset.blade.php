<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ __('Password Reset') }}</title>
    @include('frontend.include.style')
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
                            <p style="color: black;">{{ __('Enter Token To Reset Your Password') }}</p>
    
                            <form action="{{ route('vendor.reset.password') }}" method="POST">
                                @csrf
                                
                                <div class="otp-container" autocomplete="off">
                                    <input type="text" maxlength="1" class="otp-input" autocomplete="one-time-code">
                                    <input type="text" maxlength="1" class="otp-input" autocomplete="one-time-code">
                                    <input type="text" maxlength="1" class="otp-input" autocomplete="one-time-code">
                                    <input type="text" maxlength="1" class="otp-input" autocomplete="one-time-code">
                                    <!--<input type="text" maxlength="1" class="otp-input">-->
                                    <!--<input type="text" maxlength="1" class="otp-input">-->
                                </div>
                                
                                <input type="hidden" id="finalOtp" name="passresetToken">
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
                                <div class="mt-3">
                                    <label style="color: black;" for="password" class="form-label quikctech-login-label">{{ __('messages.password') }}</label>
                                    <div class="password-wrapper">
                                        <input style="color: black; border: 1px solid #ddd;" 
                                           type="password" 
                                           name="password" 
                                           class="form-control" 
                                           id="password" 
                                           placeholder="{{ __('messages.password') }}" 
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

                                <button type="submit" class="btn btn-primary btn-custom mt-3">{{ __('Send') }}</button>
    
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
    window.addEventListener('DOMContentLoaded', () => {
        document.querySelectorAll('.otp-input').forEach(input => {
            input.value = '';
        });
    });

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
     <!-- seller home -->

    <!-- footer -->
    @include('frontend.include.footer')
    <!-- footer -->
    
    @include('frontend.include.script')
</body>
</html>