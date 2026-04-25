<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo e(__('messages.seller_login')); ?></title>
    <?php echo $__env->make('frontend.include.style', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

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

     <!-- seller home -->
     <?php if(session('success')): ?>
    <div class="alert alert-success alert-dismissible fade show" role="alert">
      <strong><?php echo e(session('success')); ?></strong>
      <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
    <?php endif; ?>
    <section id="quicktech-login" style="background: url(<?php echo e(asset('frontend/images/Rectangle.png')); ?>) no-repeat center / cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6 ms-auto">
                    <div class="quikctech-login">
                        <div style="background: white !important;" class="login-container">
                            <?php if(session('error')): ?>
                            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                              <strong><?php echo e(session('error')); ?></strong>
                              <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                            <?php endif; ?>
                            <h1 style="color: black;"><?php echo e(__('messages.welcome')); ?></h1>
                            <p style="color: black;"><?php echo e(__('messages.enter_credentials')); ?></p>

                            <form action="<?php echo e(route('vendor.login.submit')); ?>" method="POST">
                                <?php echo csrf_field(); ?>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label style="color: black;" for="phone" class="form-label quikctech-login-label"><?php echo e(__('Enter Phone Number')); ?></label>
                                    <input style="color: black; border: 1px solid #ddd;"
                                           type="tel"
                                           name="phone"
                                           class="form-control"
                                           id="phone"
                                           placeholder="<?php echo e(__('Enter Phone Number')); ?>"
                                           value="<?php echo e(old('phone')); ?>"
                                           required>
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
                                <!-- Password -->
                                <div class="mb-3">
                                    <label style="color: black;" for="password" class="form-label quikctech-login-label"><?php echo e(__('messages.password')); ?></label>
                                    <div class="password-wrapper">
                                        <input style="color: black; border: 1px solid #ddd;"
                                           type="password"
                                           name="password"
                                           class="form-control"
                                           id="password"
                                           placeholder="<?php echo e(__('messages.password')); ?>"
                                           required>
                                        <span
                                            class="toggle-password"
                                            data-target="password"
                                        >
                                            <i class="fa fa-eye"></i>
                                        </span>
                                    </div>
                                    <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <small class="text-danger"><?php echo e($message); ?></small>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <!-- Remember & Forgot -->
                                <div class="d-flex justify-content-between">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox" class="form-check-input" id="remember" name="remember">
                                        <label style="color: black;" class="form-check-label" for="remember"><?php echo e(__('messages.remember_30_days')); ?></label>
                                    </div>
                                    <div class="text-center">
                                        <a style="color: black;" href="<?php echo e(route('vendor.password.forget')); ?>" class="text-black"><?php echo e(__('messages.forgot_password')); ?></a>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-custom"><?php echo e(__('messages.log_in')); ?></button>

                                <!-- Signup -->
                                <div class="mt-4 text-center">
                                    <p style="color: black;">
                                        <?php echo e(__('messages.dont_have_account')); ?>

                                        <a href="<?php echo e(route('vendor.register')); ?>" class="text-primary"><?php echo e(__('messages.sign_up')); ?></a>
                                    </p>
                                </div>

                                <!-- Social Login -->
                                <div class="social-btns">
                                    <a href="<?php echo e(route('vendor.google.login')); ?>" class="google-btn" style="border: 2px solid black; color: black;">
                                        <img src="https://encrypted-tbn0.gstatic.com/images?q=tbn:ANd9GcRb5LOPUgzjbz_m4aVulC-GU5zu-30HBdYnAg&s" alt=""> <?php echo e(__('messages.sign_in_google')); ?>

                                    </a>
                                    
                                </div>

                                <!-- General Errors -->
                                <?php if($errors->any()): ?>
                                    <div class="mt-3 text-danger">
                                        <ul class="mb-0">
                                            <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li><?php echo e($error); ?></li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php endif; ?>

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
    <!-- seller home -->

    <!-- footer -->
    <?php echo $__env->make('frontend.include.footer', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <!-- footer -->

    <?php echo $__env->make('frontend.include.script', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
</body>
</html>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/auth/vendor-login.blade.php ENDPATH**/ ?>