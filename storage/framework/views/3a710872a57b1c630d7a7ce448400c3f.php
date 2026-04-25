
<?php $__env->startSection('title', __('Login')); ?>

<?php $__env->startSection('body'); ?>
<body>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="account-pages my-5 pt-5">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-8 col-lg-6 col-xl-5">
                <div class="card overflow-hidden">
                    <div class="bg-primary-subtle">
                        <div class="row">
                            <div class="col-7">
                                <div class="text-primary p-4">
                                    <h5 class="text-primary"><?php echo e(__('Welcome Back!')); ?></h5>
                                    <h6><?php echo e(AppSetting('title')); ?></h6>
                                </div>
                            </div>
                            <div class="col-5 align-self-end">
                                <img src="<?php echo e(URL::asset('build/images/profile-img.png')); ?>" alt="" class="img-fluid">
                            </div>
                        </div>
                    </div>

                    <div class="card-body pt-0">
                        <div class="auth-logo text-center mt-3">
                            <a href="<?php echo e(url('/')); ?>" class="auth-logo-light">
                                <div class="avatar-md profile-user-wid mb-4">
                                    <span class="avatar-title rounded-circle bg-light">
                                        <img src="<?php echo e(asset('public/storage/logo.png')); ?>" alt="logo" class="rounded-circle" height="34">
                                    </span>
                                </div>
                            </a>
                        </div>

                        <div class="p-3">
                            <form id="loginForm" method="POST" action="<?php echo e(old('type', 'admin') == 'customer' ? route('customer.login.submit') : route('admin.submitLogin')); ?>">
                                <?php echo csrf_field(); ?>

                                <?php if($msg = Session::get('error')): ?>
                                    <div class="alert alert-danger"><?php echo e($msg); ?></div>
                                <?php endif; ?>
                                <?php if($msg = Session::get('success')): ?>
                                    <div class="alert alert-success"><?php echo e($msg); ?></div>
                                <?php endif; ?>

                                <div class="mb-3">
                                    <label for="type"><?php echo e(__('Login As')); ?> <span class="text-danger">*</span></label>
                                    <select name="type" id="type" class="form-control" required>
                                        <option value="admin" <?php echo e(old('type') == 'admin' ? 'selected' : 'selected'); ?>>Admin</option>
                                    </select>
                                    <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback d-block" role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="mb-3">
                                    <label for="email"><?php echo e(__('Email Address')); ?> <span class="text-danger">*</span></label>
                                    <input type="email"
                                           name="email"
                                           id="email"
                                           class="form-control <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           value="<?php echo e(old('email')); ?>"
                                           placeholder="Enter email"
                                           autocomplete="email" autofocus>
                                    <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                        <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
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
                                    <label for="password"><?php echo e(__('Password')); ?> <span class="text-danger">*</span></label>
                                    <div class="password-wrapper">
                                        <input type="password"
                                           name="password"
                                           id="password"
                                           class="form-control <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                           placeholder="Enter password">
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
                                        <span class="invalid-feedback" role="alert"><strong><?php echo e($message); ?></strong></span>
                                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                                </div>

                                <div class="form-check mb-3">
                                    <input type="checkbox" class="form-check-input" name="remember" id="customControlInline">
                                    <label class="form-check-label" for="customControlInline"><?php echo e(__('Remember me')); ?></label>
                                </div>

                                <div class="mt-3">
                                    <button class="btn btn-primary w-100 waves-effect waves-light" type="submit">
                                        <?php echo e(__('Log In')); ?>

                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="mt-5 text-center">
                    <p>© <?php echo e(date('Y')); ?> <?php echo e(AppSetting('title')); ?>. Crafted with 
                        <i class="mdi mdi-heart text-danger"></i> by <?php echo e(__('QuicktechIT')); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
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
document.getElementById('type').addEventListener('change', function() {
    const form = document.getElementById('loginForm');
    form.action = this.value === 'customer' ? "<?php echo e(route('customer.login.submit')); ?>" : "<?php echo e(route('admin.submitLogin')); ?>";
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master-without-nav', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/auth/login.blade.php ENDPATH**/ ?>