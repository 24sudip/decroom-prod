<!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->

<?php $__env->startSection('title', 'Reel Of Service Add'); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <?php echo $__env->make('frontend.include.seller-menu-top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    </div>
</div>

    
    <?php if(session('success')): ?>
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-circle-check me-2"></i>
            <?php echo e(session('success')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if(session('error')): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="fa-solid fa-triangle-exclamation me-2"></i>
            <?php echo e(session('error')); ?>

            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

    <?php if($errors->any()): ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <h5 class="alert-heading">
                <i class="fa-solid fa-triangle-exclamation me-2"></i>
                Please fix the following errors:
            </h5>
            <ul class="mb-0">
                <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <li><?php echo e($error); ?></li>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    <?php endif; ?>

<form action="<?php echo e(route('service-draft.store')); ?>" method="POST" class="quikctech-form-wrapper border p-4 rounded-3 shadow-sm" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>

    <div class="row g-3">
        <!-- Service Title -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Service <span class="text-danger">*</span></label>
            <select name="service_id" class="form-control quikctech-input <?php $__errorArgs = ['service_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">Choose Service</option>
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <option value="<?php echo e($service->id); ?>"><?php echo e($service->title); ?></option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['service_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
        
        <div class="col-md-4">
            <label class="form-label quikctech-label">Service Title <span class="text-danger">*</span></label>
            <input type="text" name="title" class="form-control quikctech-input <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
            placeholder="ex. Floor Epoxy" value="<?php echo e(old('title')); ?>" required>
            <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Delivery In -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Delivery in <span class="text-muted">(expected)</span></label>
            <input type="text" name="delivery_duration" class="form-control quikctech-input <?php $__errorArgs = ['delivery_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="ex. next 3months / 45days" value="<?php echo e(old('delivery_duration')); ?>">
            <?php $__errorArgs = ['delivery_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Total Cost -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Total Project Cost</label>
            <input type="number" step="0.01" name="project_cost" class="form-control quikctech-input <?php $__errorArgs = ['project_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Tk" value="<?php echo e(old('project_cost')); ?>">
            <?php $__errorArgs = ['project_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Material Cost -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Material Cost</label>
            <input type="number" step="0.01" name="material_cost" class="form-control quikctech-input <?php $__errorArgs = ['material_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Material Cost" value="<?php echo e(old('material_cost')); ?>">
            <?php $__errorArgs = ['material_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Service Fee -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Service Charge</label>
            <input type="number" step="0.01" name="service_charge" class="form-control quikctech-input <?php $__errorArgs = ['service_charge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Tk" value="<?php echo e(old('service_charge')); ?>">
            <?php $__errorArgs = ['service_charge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Discount -->
        <div class="col-md-2">
            <label class="form-label quikctech-label">Discount</label>
            <input type="number" step="0.01" name="discount" class="form-control quikctech-input <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="(Amount)Tk" value="<?php echo e(old('discount')); ?>">
            <?php $__errorArgs = ['discount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Video -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Video:</label>
            <input type="file" id="video" name="service_video" accept="video/*">
            <?php $__errorArgs = ['service_video'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>
    </div>
    <!-- Buttons -->
    <div class="d-flex justify-content-end mt-4">
        
        <button type="submit" class="btn btn-success quikctech-btn">
            <i class="fa-solid fa-paper-plane me-1"></i> Submit Post
        </button>
    </div>
</form>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // File upload preview
        const catalogInput = document.getElementById('quikctech-catalog');
        const attachmentInput = document.getElementById('quikctech-attachment');

        catalogInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = catalogInput.nextElementSibling;
                label.innerHTML = `<i class="fa-solid fa-file"></i><span class="d-block mt-2">${fileName}</span>`;
            }
        });

        attachmentInput.addEventListener('change', function(e) {
            const fileName = e.target.files[0]?.name;
            if (fileName) {
                const label = attachmentInput.nextElementSibling;
                label.innerHTML = `<i class="fa-solid fa-file"></i><span class="d-block mt-2">${fileName}</span>`;
            }
        });

        // Auto-calculate total cost
        const materialCostInput = document.querySelector('input[name="material_cost"]');
        const serviceChargeInput = document.querySelector('input[name="service_charge"]');
        const discountInput = document.querySelector('input[name="discount"]');
        const totalCostInput = document.querySelector('input[name="total_cost"]');

        function calculateTotalCost() {
            const materialCost = parseFloat(materialCostInput.value) || 0;
            const serviceCharge = parseFloat(serviceChargeInput.value) || 0;
            const discount = parseFloat(discountInput.value) || 0;

            const total = materialCost + serviceCharge - discount;
            totalCostInput.value = total > 0 ? total.toFixed(2) : '';
        }

        if (materialCostInput && serviceChargeInput && discountInput && totalCostInput) {
            materialCostInput.addEventListener('input', calculateTotalCost);
            serviceChargeInput.addEventListener('input', calculateTotalCost);
            discountInput.addEventListener('input', calculateTotalCost);
        }

        // Form submission confirmation for draft
        const saveDraftBtn = document.querySelector('button[name="save_draft"]');
        const submitBtn = document.querySelector('button[type="submit"]:not([name])');

        if (saveDraftBtn) {
            saveDraftBtn.addEventListener('click', function(e) {
                // Optional: Add confirmation for draft save
                const title = document.querySelector('input[name="title"]').value;
                if (!title.trim()) {
                    if (!confirm('You are saving without a service title. Continue?')) {
                        e.preventDefault();
                    }
                }
            });
        }

        if (submitBtn) {
            submitBtn.addEventListener('click', function(e) {
                const title = document.querySelector('input[name="title"]').value;
                if (!title.trim()) {
                    e.preventDefault();
                    alert('Please enter a service title before submitting.');
                    document.querySelector('input[name="title"]').focus();
                }
            });
        }
    });
</script>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.quikctech-form-wrapper {
    background: #fff;
}

.quikctech-label {
    font-weight: 600;
    color: #333;
    margin-bottom: 8px;
}

.quikctech-input, .quikctech-select, .quikctech-textarea {
    border: 1px solid #ddd;
    border-radius: 6px;
    padding: 10px 12px;
    font-size: 14px;
}

.quikctech-input:focus, .quikctech-select:focus, .quikctech-textarea:focus {
    border-color: #007bff;
    box-shadow: 0 0 0 0.2rem rgba(0, 123, 255, 0.25);
}

.quikctech-upload-area {
    border: 2px dashed #ddd;
    border-radius: 6px;
    padding: 20px;
    background: #f8f9fa;
    transition: all 0.3s ease;
}

.quikctech-upload-area:hover {
    border-color: #007bff;
    background: #f0f8ff;
}

.quikctech-file-input {
    display: none;
}

.quikctech-upload-label {
    cursor: pointer;
    color: #666;
    transition: color 0.3s ease;
}

.quikctech-upload-label:hover {
    color: #007bff;
}

.quikctech-btn {
    padding: 10px 24px;
    border-radius: 6px;
    font-weight: 600;
    transition: all 0.3s ease;
    min-width: 140px;
}

.quikctech-btn:hover {
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}

.btn-secondary.quikctech-btn {
    background: #6c757d;
    border-color: #6c757d;
}

.btn-secondary.quikctech-btn:hover {
    background: #5a6268;
    border-color: #545b62;
}

.btn-success.quikctech-btn {
    background: #28a745;
    border-color: #28a745;
}

.btn-success.quikctech-btn:hover {
    background: #218838;
    border-color: #1e7e34;
}

.quicktech-manage-menu {
    background: #f8f9fa;
    border-radius: 8px;
    padding: 15px;
    margin-bottom: 20px;
}

.quicktech-manage-menu ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 15px;
    flex-wrap: wrap;
}

.quicktech-manage-menu li a {
    text-decoration: none;
    color: #333;
    padding: 8px 16px;
    border-radius: 6px;
    transition: all 0.3s ease;
    font-weight: 500;
}

.quicktech-manage-menu li a:hover {
    background: #007bff;
    color: white;
}

.managemenu-active {
    background: #007bff !important;
    color: white !important;
}

.quicktech-seller-menu-top {
    background: #fff;
    border-radius: 8px;
    padding: 10px;
    margin-bottom: 15px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.quicktech-seller-menu-top ul {
    list-style: none;
    padding: 0;
    margin: 0;
    display: flex;
    gap: 20px;
    justify-content: flex-end;
}

.quicktech-seller-menu-top li a {
    display: block;
    transition: transform 0.3s ease;
}

.quicktech-seller-menu-top li a:hover {
    transform: scale(1.1);
}

/* Responsive adjustments */
@media (max-width: 768px) {
    .d-flex.justify-content-end {
        justify-content: center !important;
    }

    .d-flex.justify-content-end .btn {
        width: 100%;
        margin-bottom: 10px;
    }
}
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/service-draft/create.blade.php ENDPATH**/ ?>