@extends('frontend.layouts.master')
@section('title', 'Customer Login')
@section('content')
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
    <section id="quicktech-login" style="background: url('{{ asset('frontend/images/login.png') }}') no-repeat center / cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="quikctech-login">
                          <div class="login-container">
                            <h1>Welcome To Customer Verify!</h1>
                            <p>Enter your Token to verify your account</p>

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

                            <form method="POST" action="{{ route('customer.verify.submit') }}">
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
                                
                                <button type="submit" class="btn btn-primary btn-custom w-100">Send</button>
                                
                                @if(config('services.google.client_id') || config('services.apple.client_id'))
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
                                @endif
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
@endsection
