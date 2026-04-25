
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title')</title>
    @include('frontend.include.style')
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
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
   
  <!-- navbar -->

  <!-- Main Content -->
    <div class="content">

        @yield('content')

    </div>

   <!-- footer -->
    @include('frontend.include.footer')
   <!-- footer -->
     
    @include('frontend.include.script')
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
     @if(Session::has('message'))
     var type = "{{ Session::get('alert-type','info') }}"
     switch(type){
        case 'info':
        toastr.info(" {{ Session::get('message') }} ");
        break;
    
        case 'success':
        toastr.success(" {{ Session::get('message') }} ");
        break;
    
        case 'warning':
        toastr.warning(" {{ Session::get('message') }} ");
        break;
    
        case 'error':
        toastr.error(" {{ Session::get('message') }} ");
        break; 
     }
     @endif 
    </script>
    <script>
      @if(session('success'))
          toastr.success("{{ session('success') }}");
      @endif
  
      @if($errors->any())
          @foreach ($errors->all() as $error)
              toastr.error("{{ $error }}");
          @endforeach
      @endif
  </script>
</body>
</html>