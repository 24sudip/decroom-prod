<div class="quikctech-dashboard-top-selling">
    <div class="row gapp">
        <div class="col-lg-6">
            <div class="quikctech-dash-main">
                <div class="quikctech-top-head-dash">
                    <h5><?php echo e(__('messages.top_selling_items')); ?></h5>
                    <a href="<?php echo e(route('vendor.products.manage')); ?>"><?php echo e(__('messages.view_all')); ?></a>
                </div>
                <div class="quikctech-dash-top-pro">
                    <div class="row gapp pt-2">
                        <?php $__empty_1 = true; $__currentLoopData = $topSellingProducts; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="<?php echo e(route('product.details', $product->id)); ?>">
                                <div class="quicktech-product">
                                    <div class="quikctech-wishlist">
                                        <button><i class="fa-solid fa-heart"></i></button>
                                    </div>
                                    <div class="quicktech-sold">
                                        <span><?php echo e($product->total_sold ?? 0); ?> <?php echo e(__('messages.sold')); ?></span>
                                    </div>
                                    <div class="quikctech-img-product text-center">
                                        
                                        <?php
                                            $primaryImage = $product->primaryImage();
                                        ?>
                                        
                                        <?php if($primaryImage && $primaryImage->image_path): ?>
                                            <img src="<?php echo e(asset($primaryImage->image_path)); ?>" alt="<?php echo e($product->name); ?>" style="height: 120px; object-fit: cover;">
                                        <?php elseif($product->promotion_image): ?>
                                            <img src="<?php echo e(asset($product->promotion_image)); ?>" alt="<?php echo e($product->name); ?>" style="height: 120px; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" alt="<?php echo e(__('messages.no_image')); ?>" style="height: 120px; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6><?php echo e(Str::limit($product->name, 50)); ?></h6>
                                        <div class="d-flex justify-content-between quicktech-pp-t">
                                            <p>৳ <?php echo e(number_format($product->price, 2)); ?>

                                                <br>
                                                <?php if($product->special_price): ?>
                                                <span style="font-size: 13px;">
                                                    <s>৳ <?php echo e(number_format($product->price, 2)); ?></s> 
                                                    -<?php echo e(number_format((($product->price - $product->special_price) / $product->price) * 100, 0)); ?>%
                                                </span>
                                                <?php endif; ?>
                                            </p>
                                            <?php
                                                $avgRating = $product->averageRating();
                                                $ratingCount = $product->ratingCount();
                                            ?>
                                                    
                                            <span class="d-flex align-items-center">
                                                <?php for($i = 1; $i <= 5; $i++): ?>
                                                    <?php if($i <= round($avgRating)): ?>
                                                        <i class="fa-solid fa-star" style="color: #FFD700; font-size: 14px;"></i>
                                                    <?php else: ?>
                                                        <i class="fa-regular fa-star" style="color: #ccc; font-size: 14px;"></i>
                                                    <?php endif; ?>
                                                <?php endfor; ?>
                                                <span style="margin-left: 3px; font-size: 13px;">
                                                    (<?php echo e(number_format($avgRating, 1)); ?>)
                                                </span>
                                            </span>
                                        </div>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <div class="col-12">
                            <div class="text-center py-4">
                                <p><?php echo e(__('messages.no_products_found')); ?></p>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="quikctech-dash-main">
                <div class="quikctech-top-head-dash">
                    <h5><?php echo e(__('messages.top_rated_services')); ?></h5>
                    <a href="<?php echo e(route('services.index')); ?>"><?php echo e(__('messages.view_all')); ?></a>
                </div>
                <div class="quikctech-dash-top-pro">
                    <div class="row gapp pt-2">
                        <?php $__empty_1 = true; $__currentLoopData = $topRatedServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <?php if($service->service_video): ?>
                                            <video src="<?php echo e(asset($service->service_video)); ?>" class="w-100" style="height: 120px; object-fit: cover;" controls></video>
                                        <?php elseif($service->catalog): ?>
                                            <img src="<?php echo e(asset($service->catalog)); ?>" alt="<?php echo e($service->title); ?>" style="height: 120px; object-fit: cover;">
                                        <?php else: ?>
                                            <img src="<?php echo e(asset('frontend/images/placeholder.jpg')); ?>" alt="<?php echo e(__('messages.no_image')); ?>" style="height: 120px; object-fit: cover;">
                                        <?php endif; ?>
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6><?php echo e(Str::limit($service->title, 50)); ?>

                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> <?php echo e($vendor->shop_name ?? __('messages.shop_name')); ?>

                                            </span>
                                        </h6>
                                        <p>
                                            <img src="<?php echo e(asset('frontend/images/taka 1.png')); ?>" alt="<?php echo e(__('messages.currency')); ?>"> 
                                            <?php if($service->total_cost): ?>
                                                ৳ <?php echo e(number_format($service->total_cost, 2)); ?>

                                            <?php else: ?>
                                                <?php echo e(__('messages.negotiable')); ?>

                                            <?php endif; ?>
                                        </p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <img src="<?php echo e(asset('frontend/images/logo.png')); ?>" alt="<?php echo e(__('messages.service')); ?>" style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6><?php echo e(__('messages.get_discount_architect')); ?>

                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> <?php echo e($vendor->shop_name ?? __('messages.shop_name')); ?>

                                            </span>
                                        </h6>
                                        <p><img src="<?php echo e(asset('frontend/images/taka 1.png')); ?>" alt="<?php echo e(__('messages.currency')); ?>"> <?php echo e(__('messages.negotiable')); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <img src="<?php echo e(asset('frontend/images/logo.png')); ?>" alt="<?php echo e(__('messages.service')); ?>" style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6><?php echo e(__('messages.professional_interior_design')); ?>

                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> <?php echo e($vendor->shop_name ?? __('messages.shop_name')); ?>

                                            </span>
                                        </h6>
                                        <p><img src="<?php echo e(asset('frontend/images/taka 1.png')); ?>" alt="<?php echo e(__('messages.currency')); ?>"> <?php echo e(__('messages.negotiable')); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <img src="<?php echo e(asset('frontend/images/logo.png')); ?>" alt="<?php echo e(__('messages.service')); ?>" style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6><?php echo e(__('messages.home_renovation_consultation')); ?>

                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> <?php echo e($vendor->shop_name ?? __('messages.shop_name')); ?>

                                            </span>
                                        </h6>
                                        <p><img src="<?php echo e(asset('frontend/images/taka 1.png')); ?>" alt="<?php echo e(__('messages.currency')); ?>"> <?php echo e(__('messages.negotiable')); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <div class="col-lg-6 col-6 col-sm-6">
                            <a href="#">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <img src="<?php echo e(asset('frontend/images/logo.png')); ?>" alt="<?php echo e(__('messages.service')); ?>" style="height: 120px; object-fit: cover;">
                                    </div>
                                    <div class="quicktech-product-text">
                                        <h6><?php echo e(__('messages.construction_project_management')); ?>

                                            <br>
                                            <span style="font-size: 13px; font-weight: 700;">
                                                <i class="fa-solid fa-shop"></i> <?php echo e($vendor->shop_name ?? __('messages.shop_name')); ?>

                                            </span>
                                        </h6>
                                        <p><img src="<?php echo e(asset('frontend/images/taka 1.png')); ?>" alt="<?php echo e(__('messages.currency')); ?>"> <?php echo e(__('messages.negotiable')); ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/dashboard-top-selling.blade.php ENDPATH**/ ?>