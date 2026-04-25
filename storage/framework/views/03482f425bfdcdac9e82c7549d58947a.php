<section id="quicktech-service-offer">
    <div class="container">
        <div class="row quicktech-head-border mt-3">
            <div class="col-lg-12">
                <div class="quicktech-head">
                    <h4><?php echo e(__('messages.offer_on_services')); ?></h4>
                    <a href="<?php echo e(route('service.index')); ?>"><?php echo e(__('messages.explore_all_offers')); ?></a>
                </div>
            </div>
        </div>
        <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
            <!-- Static Ad Section -->
            <div class="col-lg-3 col-6 col-sm-6">
                <div class="quicktech-side-ad">
            
                    <?php if(!empty($offerbanner) && !empty($offerbanner->image)): ?>
                        <a href="<?php echo e($offerbanner->link_url); ?>"><img src="<?php echo e(asset('storage/banners/' . $offerbanner->image)); ?>"
                             class="w-100"
                             alt="<?php echo e(__('messages.special_offer')); ?>"></a>
                    <?php else: ?>
                        <img src="<?php echo e(asset('frontend/images/Colorful Simple Special Offer Poster 1.png')); ?>"
                             class="w-100"
                             alt="<?php echo e(__('messages.special_offer')); ?>">
                    <?php endif; ?>
            
                </div>
            </div>
            
            <!-- Dynamic Services Section -->
            <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <div class="col-lg-3 col-6 col-sm-6">
                <!-- FIX: Use route() instead of url() -->
                <a href="<?php echo e(route('service.details', $service->id)); ?>">
                    <div class="quicktech-product">
                        <div class="quikctech-img-product text-center">
                            <?php if($service->catalog && Str::contains($service->catalog, ['.mp4', '.mov', '.avi'])): ?>
                                <video src="<?php echo e(asset('public/' . $service->catalog)); ?>" class="w-100" controls></video>
                            <?php elseif($service->attachment && Str::contains($service->attachment, ['.mp4', '.mov', '.avi'])): ?>
                                <video src="<?php echo e(asset('public/' . $service->attachment)); ?>" class="w-100" controls></video>
                            <?php elseif($service->catalog && Str::contains($service->catalog, ['.webp', '.png', '.jpg', '.jpeg', '.svg', '.gif'])): ?>
                                <!-- Fallback if no video available -->
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <!--<i class="fa-solid fa-video text-muted" style="font-size: 2rem;"></i>-->
                                    <img src="<?php echo e(asset($service->catalog)); ?>" class="w-100" alt="<?php echo e($service->title); ?>" style="height: 150px; object-fit: cover;">
                                </div>
                            <?php else: ?>
                                <!-- Fallback if no video available -->
                                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                    <!--<i class="fa-solid fa-video text-muted" style="font-size: 2rem;"></i>-->
                                    <img src="<?php echo e(asset($service->attachment)); ?>" class="w-100" alt="<?php echo e($service->title); ?>" style="height: 150px; object-fit: cover;">
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="quicktech-product-text">
                            <h6>
                                <?php echo e(Str::limit($service->title, 50)); ?>

                                <br>
                                <span style="font-size: 13px; font-weight: 700;">
                                    <i class="fa-solid fa-shop"></i> 
                                    <?php echo e($service->vendor->name ?? __('messages.vendor_name')); ?>

                                </span>
                            </h6>
                            <p>
                                <img src="<?php echo e(asset('frontend')); ?>/images/taka 1.png" alt="<?php echo e(__('messages.price')); ?>"> 
                                <?php if($service->discount > 0): ?>
                                    <span class="text-danger">
                                        <?php echo e(number_format($service->total_cost - $service->discount)); ?> 
                                        <small class="text-muted text-decoration-line-through"><?php echo e(number_format($service->total_cost)); ?></small>
                                    </span>
                                <?php else: ?>
                                    <?php echo e($service->total_cost ? '৳ ' . number_format($service->total_cost) : __('messages.negotiable')); ?>

                                <?php endif; ?>
                            </p>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            
            <!-- Fallback if no services available -->
            <!--<?php if($services->count() == 0): ?>-->
            <!--    <?php for($i = 0; $i < 3; $i++): ?>-->
            <!--    <div class="col-lg-3 col-6 col-sm-6">-->
            <!--        <div class="quicktech-product">-->
            <!--            <div class="quikctech-img-product text-center">-->
            <!--                <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">-->
            <!--                    <i class="fa-solid fa-video text-muted" style="font-size: 2rem;"></i>-->
            <!--                </div>-->
            <!--            </div>-->
            <!--            <div class="quicktech-product-text">-->
            <!--                <h6>-->
            <!--                    <?php echo e(__('messages.no_services_available')); ?>-->
            <!--                    <br>-->
            <!--                    <span style="font-size: 13px; font-weight: 700;">-->
            <!--                        <i class="fa-solid fa-shop"></i> <?php echo e(__('messages.coming_soon')); ?>-->
            <!--                    </span>-->
            <!--                </h6>-->
            <!--                <p><img src="<?php echo e(asset('frontend')); ?>/images/taka 1.png" alt="<?php echo e(__('messages.price')); ?>"> <?php echo e(__('messages.negotiable')); ?></p>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <?php endfor; ?>-->
            <!--<?php endif; ?>-->
        </div>
    </div>
</section><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/service_offer.blade.php ENDPATH**/ ?>