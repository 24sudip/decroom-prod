<section id="quicktech-service-offer">
    <div class="container">
        <div class="row quicktech-head-border mt-3">
            <div class="col-lg-12">
                <div class="quicktech-head">
                    <h4><?php echo e(__('messages.offer_on_products')); ?></h4>
                    <a href="<?php echo e(route('vendorproduct.index')); ?>"><?php echo e(__('messages.explore_all_offers')); ?></a>
                </div>
            </div>
        </div>
        <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
            <?php if(isset($products) && $products->count() > 0): ?>
                <?php $__currentLoopData = $products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="col-lg-3 col-6 col-sm-6">
                    <a href="<?php echo e(url('product-details/' . $product->id)); ?>">
                        <div class="quicktech-product">
                            <div class="quikctech-wishlist">
                                <button><i class="fa-solid fa-heart"></i></button>
                            </div>
                            
                            <?php
                                $soldCount = $product->orderItems->sum('quantity');
                            ?>
                            <div class="quicktech-sold">
                                <span>
                                    <?php if($soldCount >= 1000): ?>
                                        <?php echo e(number_format($soldCount / 1000, 1)); ?>k <?php echo e(__('messages.sold')); ?>

                                    <?php else: ?>
                                        <?php echo e($soldCount); ?> <?php echo e(__('messages.sold')); ?>

                                    <?php endif; ?>
                                </span>
                            </div>

                             
                            <div class="quikctech-img-product text-center">

                                <?php
                                    $defaultImage = asset('frontend/images/productmain.png');
                                    $productImage = null;

                                    // 1. Collect product images
                                    $images = \App\ProductImage::where('product_id', $product->id)->get();

                                    // 2. Try primary image first
                                    if ($images->count() > 0) {
                                        $primary = $images->where('is_primary', true)->first();

                                        if ($primary && file_exists(public_path($primary->image_path))) {
                                            $productImage = asset($primary->image_path);
                                        } else {
                                            $firstImage = $images->first();
                                            if ($firstImage && file_exists(public_path($firstImage->image_path))) {
                                                $productImage = asset($firstImage->image_path);
                                            }
                                        }
                                    }

                                    // 3. If still no image → check promotion image
                                    if (!$productImage && $product->promotion_image) {
                                        if (file_exists(public_path($product->promotion_image))) {
                                            $productImage = asset($product->promotion_image);
                                        }
                                    }

                                    // 4. Final fallback
                                    if (!$productImage) {
                                        $productImage = $defaultImage;
                                    }
                                ?>

                                <img
                                    src="<?php echo e($productImage); ?>"
                                    alt="<?php echo e($product->name); ?>"
                                    class="img-fluid"
                                    style="width: 100%; height: 200px; object-fit: cover;"
                                    onerror="this.src='<?php echo e($defaultImage); ?>'"
                                >
                            </div>

                            <div class="quicktech-product-text">
                                <h6><?php echo e(Str::limit($product->name, 40)); ?></h6>
                                <div class="d-flex justify-content-between quicktech-pp-t">
                                    <p>
                                        <?php if($product->special_price && $product->special_price < $product->price): ?>
                                            ৳ <?php echo e(number_format($product->special_price)); ?>

                                            <br>
                                            <span style="font-size: 13px;">
                                                <s>৳ <?php echo e(number_format($product->price)); ?></s>
                                                -<?php echo e(number_format((($product->price - $product->special_price) / $product->price) * 100)); ?>%
                                            </span>
                                        <?php else: ?>
                                            ৳ <?php echo e(number_format($product->price)); ?>

                                            <br>
                                            <span style="font-size: 13px;"><?php echo e(__('messages.regular_price')); ?></span>
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
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            <?php else: ?>
                <!-- Fallback if no products available or $products is null -->
                <!--<?php for($i = 0; $i < 4; $i++): ?>-->
                <!--<div class="col-lg-3 col-6 col-sm-6">-->
                <!--    <div class="quicktech-product">-->
                <!--        <div class="quikctech-wishlist">-->
                <!--            <button><i class="fa-solid fa-heart"></i></button>-->
                <!--        </div>-->
                <!--        <div class="quicktech-sold">-->
                <!--            <span>0 <?php echo e(__('messages.sold')); ?></span>-->
                <!--        </div>-->
                <!--        <div class="quikctech-img-product text-center">-->
                <!--            <img src="<?php echo e(asset('frontend')); ?>/images/Architect 1.png" alt="<?php echo e(__('messages.no_product_available')); ?>" class="img-fluid">-->
                <!--        </div>-->
                <!--        <div class="quicktech-product-text">-->
                <!--            <h6><?php echo e(__('messages.no_product_available')); ?></h6>-->
                <!--            <div class="d-flex justify-content-between quicktech-pp-t">-->
                <!--                <p>-->
                <!--                    ৳ 0-->
                <!--                    <br>-->
                <!--                    <span style="font-size: 13px;"><?php echo e(__('messages.no_discount')); ?></span>-->
                <!--                </p>-->
                <!--                <span>-->
                <!--                    <img src="<?php echo e(asset('frontend')); ?>/images/star.png" alt="<?php echo e(__('messages.rating')); ?>"> 0.0-->
                <!--                </span>-->
                <!--            </div>-->
                <!--        </div>-->
                <!--    </div>-->
                <!--</div>-->
                <!--<?php endfor; ?>-->
                <h3 class="text-center"><?php echo e(__('messages.no_product')); ?></h3>
            <?php endif; ?>
        </div>
    </div>
</section>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/product_offer.blade.php ENDPATH**/ ?>