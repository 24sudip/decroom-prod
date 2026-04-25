<?php $__env->startSection('title', 'Customer Login'); ?>
<?php $__env->startSection('content'); ?>
    <section id="quicktech-login" style="background: url('<?php echo e(asset('frontend/images/login.png')); ?>') no-repeat center / cover;">
        <div class="container">
            <div class="row">
                <div class="col-lg-6">
                    <div class="quikctech-login">
                          <div class="login-container">
                            <h1>Welcome To Customer Login!</h1>
                            <p>Enter your credentials to access your account</p>

                            <?php if(session('success')): ?>
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    <?php echo e(session('success')); ?>

                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if(session('error')): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <?php echo e(session('error')); ?>

                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <?php if($errors->any()): ?>
                                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                                    <ul class="mb-0">
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            <?php endif; ?>

                            <form method="POST" action="<?php echo e(route('customer.login.submit')); ?>">
                                <?php echo csrf_field(); ?>

                                <div class="mb-3">
                                    <label for="phone" class="form-label quikctech-login-label">Mobile Number</label>
                                    <input type="tel"
                                           class="form-control <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="phone"
                                           name="phone"
                                           placeholder="mobile number"
                                           value="<?php echo e(old('phone')); ?>"
                                           required
                                           autofocus>
                                    <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <div class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
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
                                <div class="mb-3">
                                    <label for="password" class="form-label quikctech-login-label">Password</label>
                                    <div class="password-wrapper">
                                        <input type="password"
                                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           id="password"
                                           name="password"
                                           placeholder="Enter your password"
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
                                        <div class="invalid-feedback">
                                            <?php echo e($message); ?>

                                        </div>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mb-3 form-check">
                                        <input type="checkbox"
                                               class="form-check-input"
                                               id="remember"
                                               name="remember" <?php echo e(old('remember') ? 'checked' : ''); ?>>
                                        <label class="form-check-label" for="remember">Remember for 30 days</label>
                                    </div>
                                    <div class="text-center">
                                        <a href="<?php echo e(route('customer.password.request')); ?>" class="text-black">Forgot password?</a>
                                    </div>
                                </div>

                                <button type="submit" class="btn btn-primary btn-custom w-100">Log in</button>

                                <div class="mt-4 text-center">
                                    <p>Don't have an account?
                                        <a href="<?php echo e(route('customer.register')); ?>" class="text-primary">Sign up</a>
                                    </p>
                                </div>

                                
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
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/pages/customer/login.blade.php ENDPATH**/ ?>