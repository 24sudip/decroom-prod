
<?php $__env->startSection('title', $category->name . ' - Services'); ?>
<?php $__env->startSection('content'); ?>
    <section id="quikctech-service-menu">
        <div class="container">
            <div class="row my-3 quicktech-border">
                <div class="col-lg-12">
                    <div class="quikctech-ser-menu">
                        <ul>
                            <li><a href="<?php echo e(route('vendorproduct.index')); ?>">Product</a></li>
                            <li><a class="ser-active" href="<?php echo e(route('service.index')); ?>">Services</a></li>
                            <li><a href="<?php echo e(route('vendor.shop.list')); ?>">View Shop</a></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </section>

    
    <section id="quicktech-breadcrumb">
        <div class="container">
            <div class="row my-3">
                <div class="col-lg-12">
                    <nav aria-label="breadcrumb">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="<?php echo e(url('/')); ?>">Home</a></li>
                            <li class="breadcrumb-item"><a href="<?php echo e(route('service.index')); ?>">Services</a></li>
                            <li class="breadcrumb-item active" aria-current="page"><?php echo e($category->name); ?></li>
                        </ol>
                    </nav>
                </div>
            </div>
        </div>
    </section>
    
    <section id="quicktech-servicemain">
        <div class="container">
            
            <div class="row mb-4">
                <div class="col-lg-12">
                    <div class="quicktech-head">
                        <h4><?php echo e($category->name); ?> Services</h4>
                        <p class="text-muted"><?php echo e($services->total()); ?> services found</p>
                    </div>
                </div>
            </div>

            <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
                <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-6 col-sm-6 mb-4">
                    <a href="<?php echo e(route('service.details', $service->id)); ?>">
                        <div class="quicktech-product">
                            <div class="quikctech-img-product text-center">
                                
                                <?php if($service->service_video && file_exists(public_path($service->service_video))): ?>
                                    <video src="<?php echo e(asset($service->service_video)); ?>" class="w-100" style="height: 200px; object-fit: cover;"></video>
                                <?php elseif($service->attachment && file_exists(public_path($service->attachment))): ?>
                                    <img src="<?php echo e(asset($service->attachment)); ?>" alt="<?php echo e($service->title); ?>" class="w-100" style="height: 200px; object-fit: cover;">
                                <?php else: ?>
                                    <div class="bg-light d-flex align-items-center justify-content-center" style="height: 200px;">
                                        <i class="fa-solid fa-concierge-bell text-muted" style="font-size: 2rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <div class="quicktech-product-text">
                                <h6><?php echo e(Str::limit($service->title, 50)); ?>

                                    <br>
                                    <span style="font-size: 13px; font-weight: 700;">
                                        <i class="fa-solid fa-shop"></i> 
                                        <?php echo e($service->vendor->shop_name ?? $service->vendor->name ?? 'Vendor'); ?>

                                    </span>
                                </h6>
                                <p>
                                    <img src="<?php echo e(asset('frontend/images/taka 1.png')); ?>" alt="Price"> 
                                    <?php if($service->total_cost): ?>
                                        <?php echo e(number_format($service->total_cost)); ?> Tk
                                    <?php else: ?>
                                        Negotiable
                                    <?php endif; ?>
                                </p>
                            </div>
                        </div>
                    </a>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                
                <?php if($services->count() == 0): ?>
                <div class="col-12 text-center py-5">
                    <div class="mb-3">
                        <i class="fa-solid fa-concierge-bell fa-3x text-muted"></i>
                    </div>
                    <h4 class="text-muted">No services found in <?php echo e($category->name); ?></h4>
                    <p class="text-muted">Check other categories or come back later.</p>
                    <a href="<?php echo e(route('service.index')); ?>" class="btn btn-primary">Browse All Services</a>
                </div>
                <?php endif; ?>
            </div>

            
            <?php if($services->hasPages()): ?>
            <div class="row">
                <div class="col-12">
                    <div class="quikctech-pagination d-flex justify-content-center">
                        <?php echo e($services->links('pagination::bootstrap-4')); ?>

                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </section>
<?php $__env->stopSection(); ?>
<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/pages/service/category_services.blade.php ENDPATH**/ ?>