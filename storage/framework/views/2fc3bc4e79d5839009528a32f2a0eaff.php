
<?php $__env->startSection('title', __('messages.edit_profile')); ?>
<?php $__env->startSection('content'); ?>

<!-- seller-menu-top -->
<?php echo $__env->make('frontend.include.seller-menu-top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card p-4">
                <h4 class="mb-4"><?php echo e(__('messages.edit_profile')); ?></h4>
                <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <p class="text-success"><?php echo e(session('success')); ?></p>
                </div>
                <?php endif; ?>
                <form autocomplete="off" action="<?php echo e(route('vendor.profile.update')); ?>" method="POST" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    
                    <div class="mb-3">
                        <label for="welcome_description" class="form-label"><?php echo e(__('Welcome Description')); ?></label>
                        <input type="text" name="welcome_description" id="welcome_description" class="form-control" value="<?php echo e(old('welcome_description', $user->welcome_description)); ?>"
                        placeholder="Welcome Description">
                        <?php $__errorArgs = ['welcome_description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    <div class="mb-3">
                        <label for="name" class="form-label"><?php echo e(__('messages.full_name')); ?></label>
                        <input type="text" name="name" id="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label"><?php echo e(__('messages.email')); ?></label>
                        <input type="email" name="email" id="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
                        <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="phone" class="form-label"><?php echo e(__('messages.phone')); ?></label>
                        <input type="text" name="phone" id="phone" class="form-control" value="<?php echo e(old('phone', $user->phone)); ?>">
                        <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
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
                        <label for="password" class="form-label"><?php echo e(__('messages.new_password')); ?> <small><?php echo e(__('messages.leave_blank_to_keep_current')); ?></small></label>
                        <div class="password-wrapper">
                            <input type="text" style="display:none">
                            <input type="password" name="password" id="password" class="form-control" autocomplete="new-password">

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
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="password_confirmation" class="form-label"><?php echo e(__('messages.confirm_new_password')); ?></label>
                        <div class="password-wrapper">
                            <input type="password" name="password_confirmation" id="password_confirmation" class="form-control">
                            <span
                                class="toggle-password"
                                data-target="password_confirmation"
                            >
                                <i class="fa fa-eye"></i>
                            </span>
                        </div>
                    </div>

                    
                    <div class="mb-3">
                        <label for="shop_name" class="form-label"><?php echo e(__('messages.shop_name')); ?></label>
                        <input type="text" name="shop_name" id="shop_name" class="form-control" value="<?php echo e(old('shop_name', $vendor->shop_name ?? '')); ?>" required>
                        <?php $__errorArgs = ['shop_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="mb-3">
                        <label for="address" class="form-label"><?php echo e(__('messages.shop_address')); ?></label>
                        <textarea name="address" id="address" class="form-control" rows="3"><?php echo e(old('address', $vendor->address ?? '')); ?></textarea>
                        <?php $__errorArgs = ['address'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!--Profile Image -->
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(__('Profile Image')); ?></label>
                        <input type="file" name="image" class="form-control">
                        <?php if($user && $user->image): ?>
                            <div class="mt-2">
                                <img src="<?php echo e(asset($user->image)); ?>" alt="<?php echo e(__('Profile Image')); ?>" width="70">
                            </div>
                        <?php endif; ?>
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                    
                    
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(__('messages.shop_logo')); ?></label>
                        <input type="file" name="logo" class="form-control">
                        <?php if($vendor && $vendor->logo): ?>
                            <div class="mt-2">
                                <img src="<?php echo e(asset($vendor->logo)); ?>" alt="<?php echo e(__('messages.logo')); ?>" width="100">
                            </div>
                        <?php endif; ?>
                        <?php $__errorArgs = ['logo'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    
                    <div class="mb-3">
                        <label class="form-label"><?php echo e(__('messages.banner_image')); ?></label>
                        <input type="file" name="banner_image" class="form-control">
                        <?php if($vendor && $vendor->banner_image): ?>
                            <div class="mt-2">
                                <img src="<?php echo e(asset($vendor->banner_image)); ?>" alt="<?php echo e(__('messages.banner')); ?>" width="200">
                            </div>
                        <?php endif; ?>
                        <?php $__errorArgs = ['banner_image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?><small class="text-danger"><?php echo e($message); ?></small><?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <button type="submit" class="btn btn-primary"><?php echo e(__('messages.update_profile')); ?></button>
                </form>

            </div>
        </div>
    </div>
</div>
<script>
window.addEventListener('DOMContentLoaded', () => {
    const pwd = document.getElementById('password');
    pwd.value = '';
});
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

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/auth/edit-profile.blade.php ENDPATH**/ ?>