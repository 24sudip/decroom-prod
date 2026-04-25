
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $__env->yieldContent('title'); ?></title>
    <?php echo $__env->make('frontend.include.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.css" >
</head>
<body>
  

  <!-- top bar -->
  <?php echo $__env->make('frontend.include.topbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
  
  <!-- top bar -->
    
  <!-- mobile navbar-->
    <?php echo $__env->make('frontend.include.mobile-navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
  <!-- mobile navbar -->

  <!-- mobile bottom nav -->
    <?php echo $__env->make('frontend.include.bottom-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>     
  <!-- offcanvas menu -->
  
 <!-- Offcanvas Menu -->
    <?php echo $__env->make('frontend.include.offcanvas_menu', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?> 
  <!-- mobile bottom nav -->

  <!-- navbar -->
  <?php echo $__env->make('frontend.include.navbar', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- desktopmenu offcanvas -->

   <!-- Offcanvas -->
   <?php echo $__env->make('frontend.include.offcanvas_content', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- desktop menu off canvas -->
   
  <!-- navbar -->

  <!-- Main Content -->
    <div class="content">

        <?php echo $__env->yieldContent('content'); ?>

    </div>

   <!-- footer -->
    <?php echo $__env->make('frontend.include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
   <!-- footer -->
     
    <?php echo $__env->make('frontend.include.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/toastr.js/latest/toastr.min.js"></script>

    <script>
     <?php if(Session::has('message')): ?>
     var type = "<?php echo e(Session::get('alert-type','info')); ?>"
     switch(type){
        case 'info':
        toastr.info(" <?php echo e(Session::get('message')); ?> ");
        break;
    
        case 'success':
        toastr.success(" <?php echo e(Session::get('message')); ?> ");
        break;
    
        case 'warning':
        toastr.warning(" <?php echo e(Session::get('message')); ?> ");
        break;
    
        case 'error':
        toastr.error(" <?php echo e(Session::get('message')); ?> ");
        break; 
     }
     <?php endif; ?> 
    </script>
    <script>
      <?php if(session('success')): ?>
          toastr.success("<?php echo e(session('success')); ?>");
      <?php endif; ?>
  
      <?php if($errors->any()): ?>
          <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
              toastr.error("<?php echo e($error); ?>");
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
      <?php endif; ?>
  </script>
</body>
</html><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/layouts/master.blade.php ENDPATH**/ ?>