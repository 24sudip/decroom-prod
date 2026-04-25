<?php $__env->startSection('title', __('Service List')); ?>

<?php $__env->startSection('css'); ?>
<link rel="stylesheet" href="<?php echo e(URL::asset('build/libs/select2/css/select2.min.css')); ?>">
<style>
    .status-dropdown {
        min-width: 140px;
        margin-bottom: 5px;
        display: block;
    }
    .action-buttons {
        min-width: 200px;
    }
    .service-image {
        width: 50px;
        height: 50px;
        object-fit: cover;
        border-radius: 4px;
        background-color: #f8f9fa;
    }
    .no-image {
        display: flex;
        align-items: center;
        justify-content: center;
        background-color: #f8f9fa;
        color: #6c757d;
        font-size: 12px;
        border: 1px solid #dee2e6;
    }
    .status-updating {
        opacity: 0.6;
        pointer-events: none;
    }
    .filter-card .card-body {
        padding: 1.5rem;
    }
    .filter-actions {
        display: flex;
        gap: 0.5rem;
    }
    .cost-badge {
        font-size: 0.8rem;
    }
    @media (max-width: 768px) {
        .filter-actions {
            flex-direction: column;
        }
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<?php
    $currentRequest = request();
?>

<div class="row mb-4">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0"><?php echo e(__('Service List')); ?></h4>

        </div>
    </div>
</div>


<?php if(env('APP_DEBUG')): ?>
<div class="alert alert-info mb-3" id="debugInfo">
    <div class="row">
        <div class="col-md-4">
            <strong>Total Services:</strong> <span id="serviceCount"><?php echo e($services->total()); ?></span>
        </div>
        <div class="col-md-4">
            <strong>Categories:</strong> <?php echo e($categories->count()); ?>

        </div>
        <div class="col-md-4">
            <strong>Vendors:</strong> <?php echo e($vendors->count()); ?>

        </div>
    </div>
    <?php if($currentRequest->anyFilled(['category_id', 'vendor_id', 'search', 'status'])): ?>
    <div class="mt-2">
        <strong>Active Filters:</strong>
        <?php $__currentLoopData = $currentRequest->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <?php if(in_array($key, ['category_id', 'vendor_id', 'search', 'status']) && !empty($value)): ?>
                <span class="badge bg-primary me-1"><?php echo e($key); ?>: <?php echo e($value); ?></span>
            <?php endif; ?>
        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
    </div>
    <?php endif; ?>
</div>
<?php endif; ?>


<div class="card mb-4 filter-card">
    <div class="card-header">
        <h5 class="card-title mb-0">
            <i class="fas fa-filter me-2"></i><?php echo e(__('Filter Services')); ?>

        </h5>
    </div>
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.service')); ?>" id="filterForm">
            <div class="row g-3 align-items-end">
                
                <div class="col-md-3">
                    <label class="form-label fw-semibold"><?php echo e(__('Category')); ?></label>
                    <select name="category_id" class="form-control select2" data-placeholder="<?php echo e(__('Select Category')); ?>">
                        <option value=""><?php echo e(__('All Categories')); ?></option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($category->id); ?>"
                                <?php echo e((string)$currentRequest->category_id === (string)$category->id ? 'selected' : ''); ?>>
                                <?php echo e($category->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-md-2">
                    <label class="form-label fw-semibold"><?php echo e(__('Vendor')); ?></label>
                    <select name="vendor_id" class="form-control select2" data-placeholder="<?php echo e(__('Select Vendor')); ?>">
                        <option value=""><?php echo e(__('All Vendors')); ?></option>
                        <?php $__currentLoopData = $vendors; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $vendor): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($vendor->id); ?>"
                                <?php echo e((string)$currentRequest->vendor_id === (string)$vendor->id ? 'selected' : ''); ?>>
                                <?php echo e($vendor->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                
                <div class="col-md-2">
                    <label class="form-label fw-semibold"><?php echo e(__('Status')); ?></label>
                    <select name="status" class="form-control select2" data-placeholder="<?php echo e(__('Select Status')); ?>">
                        <option value=""><?php echo e(__('All Status')); ?></option>
                        <option value="in_process" <?php echo e($currentRequest->status == 'in_process' ? 'selected' : ''); ?>>In Process</option>
                        <option value="response" <?php echo e($currentRequest->status == 'response' ? 'selected' : ''); ?>>Response</option>
                        <option value="on_hold" <?php echo e($currentRequest->status == 'on_hold' ? 'selected' : ''); ?>>On Hold</option>
                        <option value="cancelled" <?php echo e($currentRequest->status == 'cancelled' ? 'selected' : ''); ?>>Cancelled</option>
                        <option value="complete" <?php echo e($currentRequest->status == 'complete' ? 'selected' : ''); ?>>Complete</option>
                        <option value="draft" <?php echo e($currentRequest->status == 'draft' ? 'selected' : ''); ?>>Draft</option>
                        <option value="records" <?php echo e($currentRequest->status == 'records' ? 'selected' : ''); ?>>Records</option>
                    </select>
                </div>

                
                <div class="col-md-3">
                    <label class="form-label fw-semibold"><?php echo e(__('Search Services')); ?></label>
                    <div class="input-group">
                        <input type="text" name="search" value="<?php echo e($currentRequest->search); ?>" class="form-control"
                               placeholder="<?php echo e(__('Search by service title...')); ?>"
                               aria-label="Search services">
                        <button class="btn btn-outline-secondary" type="button" id="clearSearch">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                </div>

                
                <div class="col-md-1">
                    <div class="filter-actions">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="fas fa-search me-1"></i><?php echo e(__('Filter')); ?>

                        </button>
                        <a href="<?php echo e(route('admin.service')); ?>" class="btn btn-outline-secondary w-100">
                            <i class="fas fa-refresh me-1"></i><?php echo e(__('Reset')); ?>

                        </a>
                    </div>
                </div>
            </div>
        </form>
    </div>
</div>


<?php if($currentRequest->anyFilled(['category_id', 'vendor_id', 'search', 'status'])): ?>
<div class="alert alert-light mb-3">
    <div class="d-flex justify-content-between align-items-center">
        <div>
            <strong>Filtered Results:</strong>
            Showing <?php echo e($services->firstItem() ?? 0); ?> - <?php echo e($services->lastItem() ?? 0); ?> of <?php echo e($services->total()); ?> services
        </div>
        <div>
            <a href="<?php echo e(route('admin.service')); ?>" class="btn btn-sm btn-outline-secondary">
                <i class="fas fa-times me-1"></i>Clear Filters
            </a>
        </div>
    </div>
</div>
<?php endif; ?>


<div class="card">
    <div class="card-body">
        <?php if($services->count() > 0): ?>
        <div class="table-responsive">
            <table class="table table-hover table-bordered align-middle">
                <thead class="table-light">
                    <tr>
                        <th width="50">#</th>
                        <th width="80"><?php echo e(__('Image')); ?></th>
                        <th><?php echo e(__('Vendor')); ?></th>
                        <th><?php echo e(__('Service Title')); ?></th>
                        <th><?php echo e(__('Category')); ?></th>
                        <th><?php echo e(__('Organization')); ?></th>
                        <th width="120"><?php echo e(__('Total Cost')); ?></th>
                        <th width="100"><?php echo e(__('Status')); ?></th>
                        <th width="200"><?php echo e(__('Actions')); ?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td class="text-center"><?php echo e($services->firstItem() + $key); ?></td>
                            <td class="text-center">
                                
                                <div class="service-image no-image" title="No Image">
                                    <i class="fas fa-concierge-bell"></i>
                                </div>
                            </td>
                            <td>
                                <span class="badge bg-info"><?php echo e($service->vendor->name ?? 'N/A'); ?></span>
                            </td>
                            <td>
                                <div class="fw-semibold"><?php echo e(Str::limit($service->title, 200)); ?></div>
                                <small class="text-muted">ID: <?php echo e($service->id); ?></small>
                            </td>
                            <td>
                                <span class="badge bg-light text-dark"><?php echo e($service->category->name ?? 'N/A'); ?></span>
                            </td>
                            <td>
                                <span class="badge bg-secondary"><?php echo e($service->organization ?? 'N/A'); ?></span>
                            </td>
                            <td class="fw-bold text-success">
                                ৳<?php echo e(number_format($service->total_cost, 2)); ?>

                                <?php if($service->discount > 0): ?>
                                    <br><small class="text-danger cost-badge">-<?php echo e($service->discount); ?>%</small>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge
                                    <?php if($service->status == 'complete'): ?> bg-success
                                    <?php elseif($service->status == 'cancelled'): ?> bg-danger
                                    <?php elseif($service->status == 'on_hold'): ?> bg-warning
                                    <?php elseif($service->status == 'in_process'): ?> bg-primary
                                    <?php elseif($service->status == 'response'): ?> bg-info
                                    <?php elseif($service->status == 'draft'): ?> bg-secondary
                                    <?php elseif($service->status == 'records'): ?> bg-dark
                                    <?php else: ?> bg-light text-dark <?php endif; ?>">
                                    <?php echo e(ucfirst(str_replace('_', ' ', $service->status))); ?>

                                </span>
                                <?php if($service->admin_reject): ?>
                                    <br><small class="text-danger cost-badge">Rejected</small>
                                <?php endif; ?>

                                <?php if($service->admin_approval): ?>
                                    <br><small class="text-success cost-badge">✓Approved</small>
                                <?php endif; ?>

                                <?php if($service->delete_access): ?>
                                    <br><small class="text-success cost-badge">✓ Can Delete</small>
                                <?php else: ?>
                                    <br><small class="text-danger cost-badge">X Can Not Delete</small>
                                <?php endif; ?>
                            </td>
                            <td class="action-buttons">
                                <div class="d-flex flex-column gap-1">
                                    <!-- View Button -->
                                    <a href="<?php echo e(route('admin.services.view', $service->id)); ?>"
                                       class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-eye me-1"></i>View
                                    </a>

                                    <!-- Delete Button -->
                                    <?php if($service->delete_access): ?>
                                    <form action="<?php echo e(route('admin.services.destroy', $service->id)); ?>" method="POST" class="d-inline">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-outline-danger w-100 delete-btn">
                                            <i class="fas fa-trash me-1"></i>Delete
                                        </button>
                                    </form>
                                    <?php endif; ?>

                                    <!-- Service Status Dropdown -->
                                    <select class="form-select form-select-sm service-status status-dropdown"
                                            data-id="<?php echo e($service->id); ?>"
                                            data-type="status"
                                            data-current="<?php echo e($service->status); ?>">
                                        <option value="in_process" <?php echo e($service->status == 'in_process' ? 'selected' : ''); ?>>⏳ In Process</option>
                                        <option value="response" <?php echo e($service->status == 'response' ? 'selected' : ''); ?>>💬 Response</option>
                                        <option value="on_hold" <?php echo e($service->status == 'on_hold' ? 'selected' : ''); ?>>⏸️ On Hold</option>
                                        <option value="cancelled" <?php echo e($service->status == 'cancelled' ? 'selected' : ''); ?>>❌ Cancelled</option>
                                        <option value="complete" <?php echo e($service->status == 'complete' ? 'selected' : ''); ?>>✅ Complete</option>
                                        <option value="draft" <?php echo e($service->status == 'draft' ? 'selected' : ''); ?>>📝 Draft</option>
                                        <option value="records" <?php echo e($service->status == 'records' ? 'selected' : ''); ?>>📁 Records</option>
                                    </select>

                                    <!-- Admin Approval Dropdown -->
                                    <select class="form-select form-select-sm admin-approval status-dropdown"
                                            data-id="<?php echo e($service->id); ?>"
                                            data-type="admin_approval"
                                            data-current="<?php echo e($service->admin_approval); ?>">
                                        <option value="1" <?php echo e($service->admin_approval == 1 ? 'selected' : ''); ?>>✅ Approved</option>
                                        <option value="0" <?php echo e($service->admin_approval == 0 ? 'selected' : ''); ?>>⏳ Pending QC</option>
                                    </select>
                                    <?php if($service->admin_reject != 1): ?>
                                    <form action="<?php echo e(route('admin.services.reject', $service->id)); ?>"
                                        method="post">
                                        <?php echo csrf_field(); ?>
                                        <button class="btn btn-sm btn-outline-danger w-100" type="submit"
                                            onclick="return(confirm('Are you sure you want to reject this service?'))">Reject</button>
                                    </form>
                                    <?php endif; ?>
                                    <form action="<?php echo e(route('admin.service-delete.status', $service->id)); ?>" method="post">
                                        <?php echo csrf_field(); ?>
                                        <div class="dropdown">
                                          <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            Delete Permission
                                          </button>
                                          <div class="dropdown-menu">
                                            <button name="delete_access" value="0" class="dropdown-item" type="submit">Disable</button>
                                            <button name="delete_access" value="1" class="dropdown-item" type="submit">Enable</button>
                                          </div>
                                        </div>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>

        
        <?php if($services->hasPages()): ?>
        <div class="mt-4 d-flex justify-content-between align-items-center">
            <div class="text-muted">
                Showing <?php echo e($services->firstItem()); ?> to <?php echo e($services->lastItem()); ?> of <?php echo e($services->total()); ?> entries
            </div>
            <div>
                <?php echo e($services->appends($currentRequest->all())->links()); ?>

            </div>
        </div>
        <?php endif; ?>

        <?php else: ?>
        
        <div class="text-center py-5">
            <div class="mb-4">
                <i class="fas fa-concierge-bell fa-4x text-muted"></i>
            </div>
            <h4 class="text-muted"><?php echo e(__('No services found')); ?></h4>
            <p class="text-muted mb-4">
                <?php if($currentRequest->anyFilled(['category_id', 'vendor_id', 'search', 'status'])): ?>
                    <?php echo e(__('Try adjusting your search filters or clear them to see all services.')); ?>

                <?php else: ?>
                    <?php echo e(__('Get started by adding your first service.')); ?>

                <?php endif; ?>
            </p>
            <div class="d-flex justify-content-center gap-2">
                <?php if($currentRequest->anyFilled(['category_id', 'vendor_id', 'search', 'status'])): ?>
                    <a href="<?php echo e(route('admin.service')); ?>" class="btn btn-outline-primary">
                        <i class="fas fa-refresh me-1"></i><?php echo e(__('Clear Filters')); ?>

                    </a>
                <?php endif; ?>
                <a href="#" class="btn btn-primary">
                    <i class="fas fa-plus me-1"></i><?php echo e(__('Add New Service')); ?>

                </a>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('script'); ?>
<!-- Load existing app.min.js FIRST to avoid MetisMenu conflict -->
<script src="<?php echo e(URL::asset('build/js/app.min.js')); ?>"></script>

<!-- Then load jQuery and other libraries -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
$(document).ready(function() {
    console.log('🚀 Service List Initialized');

    try {
        // Initialize Select2 with better configuration
        if (typeof $.fn.select2 !== 'undefined') {
            $('.select2').select2({
                width: '100%',
                theme: 'bootstrap-5',
                dropdownParent: $('.filter-card .card-body'),
                allowClear: true,
                placeholder: function() {
                    return $(this).data('placeholder');
                }
            });
            console.log('✅ Select2 initialized');
        }

        // Clear search input
        $('#clearSearch').on('click', function() {
            $('input[name="search"]').val('').focus();
        });

        // Enter key in search input submits form
        $('input[name="search"]').on('keypress', function(e) {
            if (e.which === 13) { // Enter key
                $('#filterForm').submit();
            }
        });

        // Delete confirmation
        $(document).on('click', '.delete-btn', function(e) {
            e.preventDefault();
            const form = $(this).closest('form');
            const serviceTitle = form.closest('tr').find('.fw-semibold').text().trim();

            Swal.fire({
                title: 'Are you sure?',
                html: `You are about to delete service: <strong>"${serviceTitle}"</strong><br>This action cannot be undone!`,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#d33',
                cancelButtonColor: '#3085d6',
                confirmButtonText: 'Yes, delete it!',
                cancelButtonText: 'Cancel'
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit();
                }
            });
        });

        // Service Status change
        $(document).on('change', '.service-status', function() {
            const serviceId = $(this).data('id');
            const status = $(this).val();
            const previousValue = $(this).data('current');
            const dropdown = $(this);

            console.log(`Service Status Change: Service ${serviceId}, Status: ${status}`);

            // Show updating state
            dropdown.addClass('status-updating');

            $.ajax({
                url: "<?php echo e(route('admin.services.updateStatus')); ?>",
                method: 'POST',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    id: serviceId,
                    type: 'status',
                    value: status
                },
                success: function(response) {
                    console.log('Service Status Update SUCCESS:', response);

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Status Updated',
                            text: response.message || 'Service status updated successfully!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Update current value
                        dropdown.data('current', status);
                        // Update status badge in the table
                        const statusBadge = dropdown.closest('tr').find('td:nth-child(8) .badge');
                        statusBadge.removeClass('bg-success bg-danger bg-warning bg-primary bg-info bg-secondary bg-dark bg-light');
                        statusBadge.addClass(
                            status == 'complete' ? 'bg-success' :
                            status == 'cancelled' ? 'bg-danger' :
                            status == 'on_hold' ? 'bg-warning' :
                            status == 'in_process' ? 'bg-primary' :
                            status == 'response' ? 'bg-info' :
                            status == 'draft' ? 'bg-secondary' :
                            status == 'records' ? 'bg-dark' : 'bg-light'
                        );
                        statusBadge.text(status.replace('_', ' ').replace(/\b\w/g, l => l.toUpperCase()));
                    } else {
                        throw new Error(response.message || 'Update failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Service Status Update ERROR:', error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Failed to update service status. Please try again.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Revert dropdown to previous value
                    dropdown.val(previousValue);
                },
                complete: function() {
                    dropdown.removeClass('status-updating');
                }
            });
        });

        // Admin Approval change
        $(document).on('change', '.admin-approval', function() {
            const serviceId = $(this).data('id');
            const approvalStatus = $(this).val();
            const previousValue = $(this).data('current');
            const dropdown = $(this);

            console.log(`Admin Approval Change: Service ${serviceId}, Status: ${approvalStatus}`);

            // Show updating state
            dropdown.addClass('status-updating');

            $.ajax({
                url: "<?php echo e(route('admin.services.updateStatus')); ?>",
                method: 'POST',
                data: {
                    _token: "<?php echo e(csrf_token()); ?>",
                    id: serviceId,
                    type: 'admin_approval',
                    value: approvalStatus
                },
                success: function(response) {
                    console.log('Admin Approval Update SUCCESS:', response);

                    if (response.success) {
                        Swal.fire({
                            icon: 'success',
                            title: 'Approval Updated',
                            text: response.message || 'Admin approval status updated successfully!',
                            toast: true,
                            position: 'top-end',
                            showConfirmButton: false,
                            timer: 2000
                        });
                        // Update current value
                        dropdown.data('current', approvalStatus);
                        // Update approval badge in the table
                        const approvalBadge = dropdown.closest('tr').find('td:nth-child(8) .text-success');
                        if (approvalStatus == '1') {
                            if (approvalBadge.length === 0) {
                                dropdown.closest('tr').find('td:nth-child(8)').append('<br><small class="text-success cost-badge">✓ Approved</small>');
                            }
                        } else {
                            approvalBadge.remove();
                        }
                    } else {
                        throw new Error(response.message || 'Update failed');
                    }
                },
                error: function(xhr, status, error) {
                    console.error('Admin Approval Update ERROR:', error);

                    Swal.fire({
                        icon: 'error',
                        title: 'Update Failed',
                        text: 'Failed to update admin approval. Please try again.',
                        toast: true,
                        position: 'top-end',
                        showConfirmButton: false,
                        timer: 3000
                    });

                    // Revert dropdown to previous value
                    dropdown.val(previousValue);
                },
                complete: function() {
                    dropdown.removeClass('status-updating');
                }
            });
        });

        console.log('✅ All service list features initialized');

    } catch (error) {
        console.error('❌ Service list initialization error:', error);
    }
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/service/services.blade.php ENDPATH**/ ?>