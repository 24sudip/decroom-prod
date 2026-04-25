<section id="quicktech-product-c">
    <div class="container">
        <div class="row mb-4">

            <!-- Categories Sidebar -->
            <!--<div class="col-lg-3">-->
            <!--    <div class="row quicktech-head-border">-->
            <!--        <div class="col-lg-12">-->
            <!--            <div class="quikctech-category-n-head p-2 border-bottom text-center">-->
            <!--                <h4><?php echo e(__('messages.categories')); ?></h4>-->
            <!--            </div>-->
            <!--            <div class="quikctech-category-n-n p-2 border-bottom text-center">-->
            <!--                <h4><?php echo e(__('messages.product')); ?></h4>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--    <div class="row quicktech-border py-3">-->
            <!--        <div class="col-lg-12">-->
            <!--            <div class="quick-c-p">-->
            <!--                <?php if(isset($productCategories) && $productCategories->count() > 0): ?>-->
            <!--                    <?php $__currentLoopData = $productCategories->take(10); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>-->
            <!--                    <div class="c-inner">-->
            <!--                        <a href="<?php echo e(route('product_category', $category->slug)); ?>">-->
            <!--                            <div class="quicktech-categories-side" style="height: 160px;">-->
            <!--                                <?php if($category->image): ?>-->
            <!--                                    <img src="<?php echo e(asset('public/storage/categories/' . $category->image)); ?>" alt="<?php echo e($category->name); ?>" style="height: 75px; width: 75px">-->
            <!--                                <?php else: ?>-->
            <!--                                    <img src="<?php echo e(asset('frontend')); ?>/images/wedding-dress 1.png" alt="<?php echo e($category->name); ?>" style="height: 75px; width: 75px">-->
            <!--                                <?php endif; ?>-->
            <!--                                <p><?php echo e(Str::limit($category->name, 20)); ?></p>-->
            <!--                            </div>-->
            <!--                        </a>-->
            <!--                    </div>-->
            <!--                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>-->
            <!--                <?php else: ?>-->
            <!--                    <?php for($i = 0; $i < 10; $i++): ?>-->
            <!--                    <div class="c-inner">-->
            <!--                        <a href="#">-->
            <!--                            <div class="quicktech-categories-side">-->
            <!--                                <img src="<?php echo e(asset('frontend')); ?>/images/wedding-dress 1.png" alt="<?php echo e(__('messages.category')); ?>">-->
            <!--                                <p><?php echo e(__('messages.category')); ?> <?php echo e($i + 1); ?></p>-->
            <!--                            </div>-->
            <!--                        </a>-->
            <!--                    </div>-->
            <!--                    <?php endfor; ?>-->
            <!--                <?php endif; ?>-->
            <!--            </div>-->
            <!--        </div>-->
            <!--    </div>-->
            <!--</div>-->

            <!-- Products Main Content -->
            <div class="col-lg-12">
                <!-- Products Section -->
                <div class="row quicktech-head-border quikctech-margin">
                    <div class="col-lg-12">
                        <div class="quicktech-head">
                            <!--<h4><?php echo e(__('messages.offer_on_products')); ?></h4>-->
                            <h4>All Product</h4>
                            <!--<a href="<?php echo e(route('vendorproduct.index')); ?>"><?php echo e(__('messages.explore_all_offers')); ?></a>-->
                            <a href="<?php echo e(route('vendorproduct.index')); ?>">Explore All Products</a>
                        </div>
                    </div>
                </div>

                <div class="row py-4 quikctech-margin quicktech-border gapp" id="productArea">

                    <?php if(isset($allProducts) && $allProducts->count() > 0): ?>
                        <?php $__currentLoopData = $allProducts->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
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
                                            $images = \App\ProductImage::where('product_id', $product->id)->get();

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

                                            if (!$productImage && $product->promotion_image) {
                                                if (file_exists(public_path($product->promotion_image))) {
                                                    $productImage = asset($product->promotion_image);
                                                }
                                            }

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
                        <h3 class="text-center"><?php echo e(__('messages.no_product')); ?></h3>
                    <?php endif; ?>

                    <div class="col-lg-12 mt-4 text-center">
                        <div class="quikctech-load-more text-center">
                            <a href="#" id="loadMoreProducts" class="quikctech-load-more"><?php echo e(__('messages.load_more')); ?></a>
                        </div>
                    </div>
                </div>

                <br>

                <!-- Services Section -->
                <div class="row quicktech-head-border quikctech-margin">
                    <div class="col-lg-12">
                        <div class="quicktech-head">
                            <h4><?php echo e(__('messages.services_for_you')); ?></h4>
                            <a href="<?php echo e(route('service.index')); ?>">Explore all Services</a>
                        </div>
                    </div>
                </div>

                <div class="row py-4 quikctech-margin quicktech-border gapp" id="serviceArea">
                    <?php if(isset($services) && $services->count() > 0): ?>
                        <?php $__currentLoopData = $services->take(6); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="col-lg-4 col-6 col-sm-6">
                            <a href="<?php echo e(url('service-details/' . $service->id)); ?>">
                                <div class="quicktech-product">
                                    <div class="quikctech-img-product text-center">
                                        <?php if($service->catalog && Str::contains($service->catalog, ['.mp4', '.mov', '.avi'])): ?>
                                            <video src="<?php echo e(asset('public/storage/' . $service->catalog)); ?>" class="w-100" controls></video>
                                        <?php elseif($service->attachment && Str::contains($service->attachment, ['.mp4', '.mov', '.avi'])): ?>
                                            <video src="<?php echo e(asset('public/storage/' . $service->attachment)); ?>" class="w-100" controls></video>
                                        <?php elseif($service->catalog && Str::contains($service->catalog, ['.webp', '.png', '.jpg', '.jpeg', '.svg', '.gif'])): ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <img src="<?php echo e(asset($service->catalog)); ?>" class="w-100" alt="<?php echo e($service->title); ?>" style="height: 150px; object-fit: cover;">
                                            </div>
                                        <?php else: ?>
                                            <div class="bg-light d-flex align-items-center justify-content-center" style="height: 150px;">
                                                <img src="<?php echo e(asset($service->attachment)); ?>" class="w-100" alt="<?php echo e($service->title); ?>" style="height: 150px; object-fit: cover;">
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    <div class="quicktech-product-text">
                                        <h6>
                                            <?php echo e(Str::limit($service->title, 40)); ?>

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
                    <?php else: ?>
                        <h3 class="text-center"><?php echo e(__('messages.no_service_available')); ?></h3>
                    <?php endif; ?>

                    <div class="col-lg-12 mt-4 text-center">
                        <div class="quikctech-load-more text-center">
                            <a href="#" id="loadMoreServices" class="quikctech-load-more"><?php echo e(__('messages.load_more')); ?></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
let productSkip = 6;
let serviceSkip = 6;

// Create a loading element
const loadingHtml = '<div class="text-center my-3" id="loadingSpinner"><span><?php echo e(__("messages.loading")); ?></span></div>';

// Load More Products
$('#loadMoreProducts').click(function(e){
    e.preventDefault();

    // Show loading
    $('#loadMoreProducts').after(loadingHtml);

    $.get("<?php echo e(route('load.more.products')); ?>", { skip: productSkip }, function(response){
        $('#productArea').append(response.html);

        // Hide loading
        $('#loadingSpinner').remove();

        if(response.count < 6){
            $('#loadMoreProducts').hide();
        }
        productSkip += 6;
    }).fail(function(){
        $('#loadingSpinner').remove();
        alert('<?php echo e(__("messages.something_went_wrong")); ?>');
    });
});

// Load More Services
$('#loadMoreServices').click(function(e){
    e.preventDefault();

    // Show loading
    $('#loadMoreServices').after(loadingHtml);

    $.get("<?php echo e(route('load.more.services')); ?>", { skip: serviceSkip }, function(response){
        $('#serviceArea').append(response.html);

        // Hide loading
        $('#loadingSpinner').remove();

        if(response.count < 6){
            $('#loadMoreServices').hide();
        }
        serviceSkip += 6;
    }).fail(function(){
        $('#loadingSpinner').remove();
        alert('<?php echo e(__("messages.something_went_wrong")); ?>');
    });
});
</script>
<?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/product_category.blade.php ENDPATH**/ ?>