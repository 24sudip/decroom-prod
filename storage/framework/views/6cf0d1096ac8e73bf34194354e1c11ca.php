<!-- The biggest battle is the war against ignorance. - Mustafa Kemal Atatürk -->


<?php $__env->startSection('title', 'Post Service Manage'); ?>

<?php $__env->startSection('content'); ?>

<?php echo $__env->make('frontend.include.seller-menu-top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>


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
<div class="row">
    <div class="col-lg-12">
        <div class="quicktech-manage-menu">
            <ul>
                <li><a href="<?php echo e(route('service-draft.create')); ?>" class="managemenu-active">Create Draft</a></li>
            </ul>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="table-responsive">
            <table class="table quicktech-manage-table">
                <thead>
                    <tr>
                        <th style="width:40px;">
                            SL
                        </th>
                        <th>Title</th>
                        <th>Details</th>
                        <th>Current Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $service_drafts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service_draft): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <?php echo e(($service_drafts->perPage() * ($service_drafts->currentPage() - 1)) + $loop->iteration); ?>

                        </td>
                        <td>
                            <h6><?php echo e($service_draft->title); ?></h6>
                        </td>
                        <td>
                            <div class="quicktech-manage-status">
                                Created: <strong><?php echo e($service_draft->created_at->format('Y-m-d H:i')); ?></strong>
                                <br>
                                Delivery: <strong><?php echo e($service_draft->delivery_duration); ?></strong>
                                <br>
                                Project Cost: <strong>৳<?php echo e(number_format($service_draft->project_cost, 2)); ?></strong>
                            </div>
                        </td>
                        <td class="status-cell">
                            <?php if($service_draft->status == 1): ?>
                                <span style="color: Green; font-weight: bold;">Approved</span>
                            <?php else: ?>
                                <span style="color: #6610f2; font-weight: bold;">Pending</span>
                            <?php endif; ?>
                        </td>
                        <td class="text-start">
                            <div class="btn-group">
                                <a href="<?php echo e(route('service-draft.edit', $service_draft->id)); ?>" class="quicktech-manage-edit me-2">
                                    Edit
                                </a>
                                <form action="<?php echo e(route('service-draft.destroy', $service_draft->id)); ?>" method="POST" class="d-inline">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="quicktech-manage-delete" onclick="return confirm('Are you sure you want to delete this service?')">
                                        Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="text-center py-4">
                            <div class="quicktech-empty-state">
                                <i class="fa-solid fa-inbox fa-3x text-muted mb-3"></i>
                                <h5>No Post services found</h5>
                                <p class="text-muted">You haven't created any post services yet.</p>
                                <a href="<?php echo e(route('service-draft.create')); ?>" class="btn btn-primary">
                                    <i class="fa-solid fa-plus me-2"></i>Create Your First Post Service
                                </a>
                            </div>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        
        <?php if($service_drafts->hasPages()): ?>
        <div class="d-flex justify-content-between align-items-center mt-4">
            <div class="text-muted">
                Showing <?php echo e($service_drafts->firstItem()); ?> to <?php echo e($service_drafts->lastItem()); ?> of
                <?php echo e($service_drafts->total()); ?> services
            </div>
            <nav>
                <?php echo e($service_drafts->links()); ?>

            </nav>
        </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('styles'); ?>
<style>
.quicktech-manage-table {
    background: white;
    border-radius: 8px;
    overflow: hidden;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.quicktech-manage-table thead {
    background: #f8f9fa;
    border-bottom: 2px solid #dee2e6;
}

.quicktech-manage-table th {
    font-weight: 600;
    color: #333;
    padding: 15px 12px;
    border: none;
}

.quicktech-manage-table td {
    padding: 15px 12px;
    vertical-align: middle;
    border-color: #f1f1f1;
}

.quicktech-manage-product {
    display: flex;
    align-items: flex-start;
    gap: 12px;
}

.quicktech-manage-product img {
    width: 60px;
    height: 60px;
    object-fit: cover;
    border-radius: 6px;
    border: 1px solid #e9ecef;
}

.quicktech-manage-product h6 {
    margin: 0 0 5px 0;
    font-weight: 600;
    color: #333;
}

.quicktech-manage-product small {
    color: #6c757d;
    font-size: 12px;
}

.quicktech-manage-status {
    font-size: 13px;
    color: #555;
    line-height: 1.4;
}

.quicktech-manage-edit,
.quicktech-manage-view,
.quicktech-manage-delete {
    padding: 6px 12px;
    border-radius: 4px;
    text-decoration: none;
    font-size: 13px;
    font-weight: 500;
    cursor: pointer;
    border: none;
    background: none;
}

.quicktech-manage-edit {
    color: #007bff;
    border: 1px solid #007bff;
}

.quicktech-manage-edit:hover {
    background: #007bff;
    color: white;
}

.quicktech-manage-view {
    color: #28a745;
    border: 1px solid #28a745;
}

.quicktech-manage-view:hover {
    background: #28a745;
    color: white;
}

.quicktech-manage-delete {
    color: #dc3545;
    border: 1px solid #dc3545;
}

.quicktech-manage-delete:hover {
    background: #dc3545;
    color: white;
}

.quicktech-manage-checkbox {
    width: 18px;
    height: 18px;
    cursor: pointer;
}

.quicktech-empty-state {
    padding: 40px 20px;
    text-align: center;
}

.quicktech-empty-state h5 {
    color: #6c757d;
    margin-bottom: 10px;
}

/* Status colors */
.status-cell {
    font-weight: 600;
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

/* Pagination styling */
.pagination {
    margin-bottom: 0;
}

.page-link {
    border-radius: 6px;
    margin: 0 2px;
    border: 1px solid #dee2e6;
}

.page-item.active .page-link {
    background-color: #007bff;
    border-color: #007bff;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/service-draft/index.blade.php ENDPATH**/ ?>