<section id="quikctech-banner" style="background: url(<?php echo e(asset('frontend/images/Rectangle 11.png')); ?>) no-repeat center / cover;">
    <div class="swiper">
        <div class="swiper-wrapper">
            <?php $__currentLoopData = $sliders; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $slider): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="swiper-slide quikctech-banner-img">
                    <a href="<?php echo e($slider->link ?? '#'); ?>">
                        <img src="<?php echo e(asset('storage/sliders/' . ($slider->image ?? 'frontend/images/default.png'))); ?>" class="w-100" alt="<?php echo e(__('messages.banner')); ?>">
                    </a>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
        <div class="swiper-pagination"></div>
    </div>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-12">
                <div class="quikctech-cate-main">
                    <div class="quikctech-cate-head text-center pt-4">
                        <img src="<?php echo e(asset('frontend/images/Orange Minimalist Beauty & Skincare Handmade Shop LinkedIn Banner-3 2.png')); ?>" alt="<?php echo e(__('messages.banner_image')); ?>">
                    </div>
                </div>
            </div>
        </div>

        <div class="row mt-3">
            <div class="col-lg-12">
                <div class="quikctech-cat-main">
                    <?php $__currentLoopData = $serviceCategories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <a href="<?php echo e(route('service_category', $category->slug)); ?>" title="<?php echo e($category->name); ?>">
                            <div class="quikctech-cate-inner">
                                <img src="<?php echo e(asset('storage/service/' . ($category->image ?? 'frontend/images/default.png'))); ?>" alt="<?php echo e($category->name); ?>">
                                <h6><?php echo e($category->name); ?></h6>
                            </div>
                        </a>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>
    </div>
</section><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/banner.blade.php ENDPATH**/ ?>