<?php $__env->startSection('title', 'Edit Service'); ?>
<?php $__env->startSection('content'); ?>

<div class="row">
    <div class="col-lg-12">
        <?php echo $__env->make('frontend.include.seller-menu-top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        <div class="quicktech-manage-menu">
            <ul>
                <li><a href="<?php echo e(route('services.create')); ?>">Create New</a></li>
                <li><a href="<?php echo e(route('services.index', ['status' => 'in progress'])); ?>">In progress</a></li>
                <li><a href="<?php echo e(route('services.index', ['status' => 'response'])); ?>">Response</a></li>
                <li><a href="<?php echo e(route('services.index', ['status' => 'on hold'])); ?>">On Hold</a></li>
                <li><a href="<?php echo e(route('services.index', ['status' => 'cancelled'])); ?>">Cancelled</a></li>
                <li><a href="<?php echo e(route('services.index', ['status' => 'complete'])); ?>">Complete</a></li>
                <li><a href="<?php echo e(route('services.index', ['status' => 'draft'])); ?>">Draft</a></li>
                <li><a href="<?php echo e(route('services.index', ['status' => 'record'])); ?>">Records</a></li>
            </ul>
        </div>
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

<form action="<?php echo e(route('services.update', $service->id)); ?>" method="POST" class="quikctech-form-wrapper border p-4 rounded-3 shadow-sm" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php echo method_field('PUT'); ?>

    <div class="row g-3">
        <!-- Service Title -->
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
                   placeholder="ex. Floor Epoxy" value="<?php echo e(old('title', $service->title)); ?>" required>
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

        <!-- Service Category -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Service Category</label>
            <select name="category_id" class="form-select quikctech-select <?php $__errorArgs = ['category_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="">Select Category</option>
                <?php $__currentLoopData = $serviceCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <option value="<?php echo e($category->id); ?>" <?php echo e(old('category_id', $service->category_id) == $category->id ? 'selected' : ''); ?>>
                        <?php echo e($category->name); ?>

                    </option>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </select>
            <?php $__errorArgs = ['category_id'];
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

        <!-- Organization -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Organization</label>
            <input type="text" name="organization" class="form-control quikctech-input <?php $__errorArgs = ['organization'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Enter Organization Name" value="<?php echo e(old('organization', $service->organization)); ?>">
            <?php $__errorArgs = ['organization'];
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

        <!-- Client User ID -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Client User ID</label>
            <input type="text" name="client_id" class="form-control quikctech-input <?php $__errorArgs = ['client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Enter user id" value="<?php echo e(old('client_id', $service->client_id)); ?>">
            <?php $__errorArgs = ['client_id'];
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

        <!-- Vendor ID -->
        <?php
            $vendor = auth('vendor')->user();
            $isVendorLoggedIn = !empty($vendor);
        ?>

        <div class="col-md-4">
            <label class="form-label quikctech-label">Provider User ID</label>

            <?php if($isVendorLoggedIn && $vendor): ?>
                <div class="input-group">
                    <input type="text" class="form-control quikctech-input bg-light"
                           value="<?php echo e($vendor->name ?? $vendor->username ?? $vendor->email); ?> (ID: <?php echo e($vendor->id); ?>)"
                           readonly>
                    <input type="hidden" name="vendor_id" value="<?php echo e($vendor->id); ?>">
                    <span class="input-group-text bg-success text-white" style="height: 43px">
                        <i class="fa-solid fa-check"></i> Your Account
                    </span>
                </div>
                <small class="text-muted">Automatically set to your vendor account</small>
            <?php else: ?>
                <select name="vendor_id" class="form-select quikctech-select <?php $__errorArgs = ['vendor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                    <option value="">Select Vendor</option>
                    <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendorOption): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($vendorOption->id); ?>" <?php echo e(old('vendor_id', $service->vendor_id) == $vendorOption->id ? 'selected' : ''); ?>>
                            <?php echo e($vendorOption->name ?? $vendorOption->username ?? $vendorOption->email); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
                <?php $__errorArgs = ['vendor_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <div class="invalid-feedback"><?php echo e($message); ?></div>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            <?php endif; ?>
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
unset($__errorArgs, $__bag); ?>"
                   placeholder="ex. next 3months / 45days" value="<?php echo e(old('delivery_duration', $service->delivery_duration)); ?>">
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

        <!-- Timeline -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Time Line</label>
            <input type="text" name="time_line" class="form-control quikctech-input <?php $__errorArgs = ['time_line'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="ex. Milestone (in days)" value="<?php echo e(old('time_line', $service->time_line)); ?>">
            <?php $__errorArgs = ['time_line'];
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
            <label class="form-label quikctech-label">Total Cost</label>
            <input type="number" step="0.01" name="total_cost" class="form-control quikctech-input <?php $__errorArgs = ['total_cost'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Tk" value="<?php echo e(old('total_cost', $service->total_cost)); ?>">
            <?php $__errorArgs = ['total_cost'];
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
unset($__errorArgs, $__bag); ?>"
                   placeholder="Material Cost" value="<?php echo e(old('material_cost', $service->material_cost)); ?>">
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
            <label class="form-label quikctech-label">Service Fee</label>
            <input type="number" step="0.01" name="service_charge" class="form-control quikctech-input <?php $__errorArgs = ['service_charge'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                   placeholder="Tk" value="<?php echo e(old('service_charge', $service->service_charge)); ?>">
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
unset($__errorArgs, $__bag); ?>"
                   placeholder="% = (Amount)Tk" value="<?php echo e(old('discount', $service->discount)); ?>">
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

        <!-- Installment -->
        <div class="col-md-3">
            <label class="form-label quikctech-label">Installment</label>
            <select name="installment" class="form-select quikctech-select <?php $__errorArgs = ['installment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="0" <?php echo e(old('installment', $service->installment) == 0 ? 'selected' : ''); ?>>No Installment</option>
                <option value="1" <?php echo e(old('installment', $service->installment) == 1 ? 'selected' : ''); ?>>3 Installments</option>
                <option value="2" <?php echo e(old('installment', $service->installment) == 2 ? 'selected' : ''); ?>>6 Installments</option>
                <option value="3" <?php echo e(old('installment', $service->installment) == 3 ? 'selected' : ''); ?>>12 Installments</option>
            </select>
            <?php $__errorArgs = ['installment'];
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

        <!-- Advance -->
        

        <!-- Catalog Upload -->
        <div class="col-md-6">
            <label class="form-label quikctech-label">Catalog</label>

            <?php if($service->catalog): ?>
                <div class="mb-2">
                    <p class="mb-1">Current Catalog:</p>
                    <a href="<?php echo e(asset($service->catalog)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-eye me-1"></i>View Current Catalog
                    </a>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="remove_catalog" id="remove_catalog" value="1">
                        <label class="form-check-label text-danger" for="remove_catalog">
                            Remove current catalog
                        </label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="quikctech-upload-area text-center">
                <input type="file" name="catalog" class="quikctech-file-input <?php $__errorArgs = ['catalog'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       id="quikctech-catalog" accept=".pdf,.jpg,.jpeg,.png,.docx">
                <label for="quikctech-catalog" class="quikctech-upload-label">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    <span class="d-block mt-2">
                        <?php echo e($service->catalog ? 'Upload New Catalog' : 'Upload Catalog'); ?>

                    </span>
                </label>
            </div>
            <?php $__errorArgs = ['catalog'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Attachment Upload -->
        <div class="col-md-6">
            <label class="form-label quikctech-label">Attachment</label>

            <?php if($service->attachment): ?>
                <div class="mb-2">
                    <p class="mb-1">Current Attachment:</p>
                    <a href="<?php echo e(asset($service->attachment)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-eye me-1"></i>View Current Attachment
                    </a>
                    <div class="form-check mt-2">
                        <input class="form-check-input" type="checkbox" name="remove_attachment" id="remove_attachment" value="1">
                        <label class="form-check-label text-danger" for="remove_attachment">
                            Remove current attachment
                        </label>
                    </div>
                </div>
            <?php endif; ?>

            <div class="quikctech-upload-area text-center">
                <input type="file" name="attachment" class="quikctech-file-input <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                       id="quikctech-attachment" accept=".pdf,.jpg,.jpeg,.png,.docx">
                <label for="quikctech-attachment" class="quikctech-upload-label">
                    <i class="fa-solid fa-arrow-up-from-bracket"></i>
                    <span class="d-block mt-2">
                        <?php echo e($service->attachment ? 'Upload New Attachment' : 'Upload Attachment'); ?>

                    </span>
                </label>
            </div>
            <?php $__errorArgs = ['attachment'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <!-- Note -->
        <div class="col-md-12">
            <label class="form-label quikctech-label">Note:</label>
            <textarea name="note" class="form-control quikctech-textarea <?php $__errorArgs = ['note'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                      rows="5" placeholder="If you have other discussion write here, which will be part of this documentation"><?php echo e(old('note', $service->note)); ?></textarea>
            <?php $__errorArgs = ['note'];
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

        <!-- Status -->
        <div class="col-md-4">
            <label class="form-label quikctech-label">Status</label>
            <select name="status" class="form-select quikctech-select <?php $__errorArgs = ['status'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                <option value="draft" <?php echo e(old('status', $service->status) == 'draft' ? 'selected' : ''); ?>>Draft</option>
                <option value="in_progress" <?php echo e(old('status', $service->status) == 'in_progress' ? 'selected' : ''); ?>>In Progress</option>
                <option value="response" <?php echo e(old('status', $service->status) == 'response' ? 'selected' : ''); ?>>Response</option>
                <option value="on_hold" <?php echo e(old('status', $service->status) == 'on_hold' ? 'selected' : ''); ?>>On Hold</option>
                <option value="cancelled" <?php echo e(old('status', $service->status) == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                <option value="complete" <?php echo e(old('status', $service->status) == 'complete' ? 'selected' : ''); ?>>Complete</option>
                <option value="record" <?php echo e(old('status', $service->status) == 'record' ? 'selected' : ''); ?>>Record</option>
            </select>
            <?php $__errorArgs = ['status'];
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
            <?php if($service->service_video): ?>
                <div class="mb-2">
                    <p class="mb-1">Current Video:</p>
                    <a href="<?php echo e(asset($service->service_video)); ?>" target="_blank" class="btn btn-sm btn-outline-primary">
                        <i class="fa-solid fa-eye me-1"></i>View Current Video
                    </a>
                </div>
            <?php endif; ?>
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
        
        <div class="col-md-4">
            <label class="form-label quikctech-label">Expire Duration: (In Days)</label>
            <input type="number" name="expire_duration" class="form-control quikctech-input <?php $__errorArgs = ['expire_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" placeholder="Days" value="<?php echo e(old('expire_duration', $service->expire_duration)); ?>">
            <?php $__errorArgs = ['expire_duration'];
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
    <div class="d-flex justify-content-between mt-4">
        <div>
            <a href="<?php echo e(route('services.index')); ?>" class="btn btn-secondary quikctech-btn me-2">
                <i class="fa-solid fa-arrow-left me-1"></i> Back to List
            </a>
            <a href="<?php echo e(route('services.show', $service->id)); ?>" class="btn btn-info quikctech-btn me-2">
                <i class="fa-solid fa-eye me-1"></i> View Service
            </a>
        </div>
        <div>
            <button type="submit" class="btn btn-success quikctech-btn">
                <i class="fa-solid fa-check me-1"></i> Update Service
            </button>
        </div>
    </div>
</form>
<script>
document.getElementById('video').addEventListener('change', function (e) {
    const file = e.target.files[0];
    if (!file) return;

    const video = document.createElement('video');
    video.preload = 'metadata';

    video.onloadedmetadata = function () {
        window.URL.revokeObjectURL(video.src);

        const duration = video.duration; // seconds
        console.log('Duration:', duration);

        if (duration > 30) {
            alert('Video must be less than 30 seconds');
            e.target.value = ''; // reset input
        }
    };

    video.src = URL.createObjectURL(file);
});
</script>
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

        materialCostInput.addEventListener('input', calculateTotalCost);
        serviceChargeInput.addEventListener('input', calculateTotalCost);
        discountInput.addEventListener('input', calculateTotalCost);

        // Remove file checkboxes logic
        const removeCatalogCheckbox = document.getElementById('remove_catalog');
        const removeAttachmentCheckbox = document.getElementById('remove_attachment');

        if (removeCatalogCheckbox) {
            removeCatalogCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    catalogInput.disabled = true;
                    catalogInput.nextElementSibling.style.opacity = '0.5';
                } else {
                    catalogInput.disabled = false;
                    catalogInput.nextElementSibling.style.opacity = '1';
                }
            });
        }

        if (removeAttachmentCheckbox) {
            removeAttachmentCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    attachmentInput.disabled = true;
                    attachmentInput.nextElementSibling.style.opacity = '0.5';
                } else {
                    attachmentInput.disabled = false;
                    attachmentInput.nextElementSibling.style.opacity = '1';
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
}

.quikctech-btn:hover {
    transform: translateY(-1px);
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
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/services/edit.blade.php ENDPATH**/ ?>