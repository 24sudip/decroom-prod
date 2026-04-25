<?php $__env->startSection('title', $product->name . ' - Product Details'); ?>
<?php $__env->startSection('content'); ?>
<?php echo $__env->make('frontend.include.breadcumb', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<section id="quicktech-product-details">
    <div class="container">
        <div class="row quicktexch-mm my-3">
            <div class="col-lg-9">
                <div class="row">
                    <div class="col-lg-6">
                        <?php
                            $defaultImage = asset('frontend/images/productmain.png');
                            $productImages = \App\ProductImage::where('product_id', $product->id)->get();
                            $productImage = null;

                            if($productImages->count() > 0){
                                $primaryImage = $productImages->where('is_primary', true)->first();
                                $productImage = $primaryImage ? $primaryImage->image_path : $productImages->first()->image_path;
                                if ($productImage && !file_exists(public_path($productImage))) {
                                    $productImage = $product->promotion_image;
                                }
                            }

                            $displayImage = $productImage ? asset($productImage) : $defaultImage;

                            $validThumbnails = [];
                            if($productImages->count() > 1){
                                foreach($productImages as $image){
                                    if($image->image_path && file_exists(public_path($image->image_path))){
                                        $validThumbnails[] = $image;
                                    }
                                }
                            }
                        ?>

<div class="quikctech-product-image-main" style="display: none;">
    <img id="main-image" src="<?php echo e(asset($product->promotion_image)); ?>" class="w-100" alt="<?php echo e($product->name); ?>">
</div>
                        <?php if($product->id == 12): ?>
                        <div class="quikctech-product-image-main">
                            <img id="main-image" src="<?php echo e(asset($product->promotion_image)); ?>" class="w-100" alt="<?php echo e($product->name); ?>">
                        </div>
                        <?php else: ?>
                        <div class="quikctech-product-image-main">
                            <img id="main-image" src="<?php echo e($displayImage); ?>" class="w-100" alt="<?php echo e($product->name); ?>" onerror="this.src='<?php echo e($defaultImage); ?>'">
                        </div>
                        <?php endif; ?>

                        <?php if(count($validThumbnails) > 1): ?>
                        <div class="quikctech-product-thumblin mt-4" style="overflow-x: hidden;">
                            <div class="swiperthumblin">
                                <div class="swiper-wrapper">
                                    <?php $__currentLoopData = $validThumbnails; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $image): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <div class="swiper-slide">
                                        <div class="quikctech-thublin-img">
                                            <img src="<?php echo e(asset($image->image_path)); ?>" alt="<?php echo e($product->name); ?>" onclick="changeImage('<?php echo e(asset($image->image_path)); ?>')" onerror="this.src='<?php echo e($defaultImage); ?>'">
                                        </div>
                                    </div>
                                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                </div>
                                <div class="swiper-button-prevv"><i class="fa-solid fa-angle-left"></i></div>
                                <div class="swiper-button-nextt"><i class="fa-solid fa-angle-right"></i></div>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-6">
                        <div class="quicktech-product-d-details">
                            <h2><?php echo e($product->name); ?></h2>
                            <div class="quikctech-r-s-main mt-2 d-flex justify-content-between">
                                <?php
                                    $averageRating = $product->ratings()->avg('rating') ?? 0;
                                    $ratingCount = $product->ratings()->count();
                                ?>

                                <div class="quikcteh-rating-inner">
                                    <ul class="d-flex align-items-center gap-1">
                                        <?php for($i=1; $i<=5; $i++): ?>
                                            <?php if($i <= round($averageRating)): ?>
                                                <li><i class="fa-solid fa-star" style="color: #FFD700;"></i></li>
                                            <?php else: ?>
                                                <li><i class="fa-regular fa-star" style="color: #ccc;"></i></li>
                                            <?php endif; ?>
                                        <?php endfor; ?>
                                        <li style="color: #1d71ff; font-weight: 600;"><?php echo e(number_format($averageRating,1)); ?> (<?php echo e($ratingCount); ?> Reviews)</li>
                                    </ul>
                                </div>

                                <div class="quikctech-share">
                                    <ul class="d-flex gap-2">
                                        <li><a href="#"><i class="fa-solid fa-share-nodes"></i></a></li>
                                        <li><a href="#"><i class="fa-solid fa-heart"></i></a></li>
                                    </ul>
                                </div>
                            </div>

                            <div class="quicktech-brand mt-2">
                                <ul class="d-flex gap-1">
                                    <li style="color: rgba(0, 0, 0, 0.521);">Brand:</li>
                                    <li style="color: #1d71ff;"><a href="<?php echo e(route('brand_wise_product', $product->brand->id)); ?>"><?php echo e($product->brand->name ?? 'No Brand'); ?></a></li>
                                    <?php if($product->brand): ?>
                                        <li>|</li>
                                        <li style="color: #1d71ff;">More <?php echo e($product->category->name ?? 'Products'); ?> From <?php echo e($product->brand->name); ?></li>
                                    <?php endif; ?>
                                </ul>
                            </div>
                            <hr>

                            <div class="quikctech-product-price">
                                <?php if($product->special_price && $product->special_price < $product->price): ?>
                                    <h3>৳ <?php echo e(number_format($product->special_price)); ?></h3>
                                    <span><s>৳ <?php echo e(number_format($product->price)); ?></s> -<?php echo e(number_format((($product->price - $product->special_price)/$product->price)*100)); ?>%</span>
                                <?php else: ?>
                                    <h3>৳ <?php echo e(number_format($product->price)); ?></h3>
                                <?php endif; ?>
                            </div>
                            <hr>

                            
                            <?php if($product->variants && count($product->variants) > 0): ?>
                                <?php $groupedVariants = $product->variants->groupBy('name'); ?>
                                <?php $__currentLoopData = $groupedVariants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variantName => $variants): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div class="quikctech-<?php echo e(strtolower($variantName)); ?> mb-3">
                                    <h4><?php echo e($variantName); ?>:</h4>
                                    <div class="d-flex gap-2 flex-wrap">
                                        <?php $__currentLoopData = $variants; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $variant): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <?php
                                                $variantImage = $defaultImage;
                                                if(isset($variant->image) && file_exists(public_path($variant->image)))
                                                    $variantImage = asset($variant->image);
                                            ?>

                                            <?php if(strtolower($variantName) == 'color'): ?>
                                                <div class="color-img" style="width:30px;height:30px;border-radius:50%;background-color: <?php echo e($variant->value); ?>;cursor:pointer; border:1px solid #ccc;"
                                                     onclick="selectVariant(<?php echo e($variant->id); ?>, '<?php echo e($variantName); ?>', '<?php echo e($variant->value); ?>', <?php echo e($variant->additional_price); ?>, <?php echo e($variant->stock); ?>, '<?php echo e($variantImage); ?>')">
                                                </div>
                                            <?php elseif(strtolower($variantName) == 'size'): ?>
                                                <div class="size-num px-2 py-1 border rounded" style="cursor:pointer;"
                                                     onclick="selectVariant(<?php echo e($variant->id); ?>, '<?php echo e($variantName); ?>', '<?php echo e($variant->value); ?>', <?php echo e($variant->additional_price); ?>, <?php echo e($variant->stock); ?>, '<?php echo e($variantImage); ?>')">
                                                    <?php echo e($variant->value); ?>

                                                </div>
                                            <?php else: ?>
                                                <p class="<?php echo e(strtolower($variantName)); ?>-option"
                                                   onclick="selectVariant(<?php echo e($variant->id); ?>, '<?php echo e($variantName); ?>', '<?php echo e($variant->value); ?>', <?php echo e($variant->additional_price); ?>, <?php echo e($variant->stock); ?>, '<?php echo e($variantImage); ?>')">
                                                    <?php echo e($variant->value); ?>

                                                </p>
                                            <?php endif; ?>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </div>
                                </div>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            <?php endif; ?>

                            <div class="quicktech-quantity mt-4">
                                <div class="quikctech-quan-head"><h4>Quantity</h4></div>
                                <div class="counter-container d-flex align-items-center gap-2">
                                    <button class="counter-btn" id="decrease">-</button>
                                    <span id="number">1</span>
                                    <button class="counter-btn" id="increase">+</button>
                                </div>
                                <div id="stock-message" class="mt-2 small text-muted"></div>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-12 my-5">
                        <div class="quikctech-pro-btns d-flex gap-2">
                            <a style="background-color: #0AAFCF; color: white;" href="javascript:void(0)" id="add-to-cart-btn">Add To Cart</a>
                            <a style="background-color: #DF9712; color: white;" href="javascript:void(0)" id="buy-now-btn">Order Now</a>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo $__env->make('frontend.include.delivery_seller_info', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

        </div>

        <div class="row">
            <?php echo $__env->make('frontend.include.product_details', ['product' => $product], \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
        </div>
    </div>
</section>

<?php echo $__env->make('frontend.include.review_rating', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.include.askquestion', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
<?php echo $__env->make('frontend.include.related_product', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
(function(){
    let selectedVariantId = null;
    let selectedVariantPrice = <?php echo e($product->special_price ?? $product->price); ?>;
    let selectedVariantStock = <?php echo e($product->stock); ?>;
    let selectedVariantImage = '<?php echo e($displayImage); ?>';
    let currentQuantity = 1;
    let maxStock = <?php echo e($product->stock); ?>;
    const defaultImage = '<?php echo e($defaultImage); ?>';

    function changeImage(src){
        const mainImage = document.getElementById('main-image');
        mainImage.src = src;
        mainImage.onerror = function(){ this.src = defaultImage; }
    }

    function selectVariant(variantId, variantName, variantValue, price, stock, image){
        selectedVariantId = variantId;
        selectedVariantPrice = Number(price);
        selectedVariantStock = stock;
        selectedVariantImage = image;
        maxStock = stock;

        changeImage(image);

        // Remove active from all of this variant type
        document.querySelectorAll(`.${variantName.toLowerCase()}-option, .color-img, .size-num`).forEach(el => el.classList.remove('active'));

        // Add active to selected
        document.querySelectorAll(`.${variantName.toLowerCase()}-option, .color-img, .size-num`).forEach(el=>{
            if(el.textContent===variantValue || el.style.backgroundColor===variantValue) el.classList.add('active');
        });

        updateStockMessage();
        updateQuantity();
    }

    function updateQuantity(){
        document.getElementById('number').textContent = currentQuantity;
        updateStockMessage();
    }

    function updateStockMessage(){
        const stockMessage = document.getElementById('stock-message');
        const addToCartBtn = document.getElementById('add-to-cart-btn');
        const buyNowBtn = document.getElementById('buy-now-btn');
        const increaseBtn = document.getElementById('increase');

        if(maxStock <=0){
            stockMessage.innerHTML='<span class="text-danger">Out of Stock</span>';
            [addToCartBtn,buyNowBtn].forEach(btn=>{ btn.style.opacity=0.6; btn.style.pointerEvents='none'; btn.disabled=true; });
            if(increaseBtn) increaseBtn.disabled=true;
        } else if(maxStock<=5){
            stockMessage.innerHTML=`<span class="text-warning">Only ${maxStock} left in stock!</span>`;
            [addToCartBtn,buyNowBtn].forEach(btn=>{ btn.style.opacity=1; btn.style.pointerEvents='auto'; btn.disabled=false; });
            if(increaseBtn) increaseBtn.disabled = currentQuantity>=maxStock;
        } else {
            stockMessage.innerHTML=`<span class="text-success">In Stock (${maxStock} available)</span>`;
            [addToCartBtn,buyNowBtn].forEach(btn=>{ btn.style.opacity=1; btn.style.pointerEvents='auto'; btn.disabled=false; });
            if(increaseBtn) increaseBtn.disabled = currentQuantity>=maxStock;
        }
    }

    function addToCart(isBuyNow){
        const cartItemId='<?php echo e($product->id); ?>'+(selectedVariantId?'_'+selectedVariantId:'');
        const price = Number(selectedVariantPrice);

        const formData = new FormData();
        formData.append('_token','<?php echo e(csrf_token()); ?>');
        formData.append('id',cartItemId);
        formData.append('name','<?php echo e($product->name); ?>');
        formData.append('price',price);
        formData.append('qty',currentQuantity);
        formData.append('variant_id',selectedVariantId||'');
        formData.append('image',selectedVariantImage);

        const addBtn=document.getElementById('add-to-cart-btn');
        const buyBtn=document.getElementById('buy-now-btn');
        const originalAdd=addBtn?addBtn.innerHTML:'';
        const originalBuy=buyBtn?buyBtn.innerHTML:'';

        if(isBuyNow && buyBtn){ buyBtn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Adding...'; buyBtn.disabled=true; }
        else if(addBtn){ addBtn.innerHTML='<i class="fa-solid fa-spinner fa-spin"></i> Adding...'; addBtn.disabled=true; }

        fetch('<?php echo e(route("cart.add")); ?>',{ method:'POST', headers:{'X-Requested-With':'XMLHttpRequest','Accept':'application/json'}, body:formData })
        .then(r=>r.ok? r.json() : Promise.reject('HTTP error'))
        .then(data=>{
            if(data.success) {
                updateCartCount(data.cart_count);
                // ✅ BUY NOW → direct redirect, no SweetAlert
                if (isBuyNow) {
                    window.location.href = '<?php echo e(route("checkout")); ?>';
                    return;
                }
                Swal.fire({
                    icon:'success',
                    title:'Success!',
                    text:data.message,
                    showCancelButton:true,
                    confirmButtonText:isBuyNow?'Checkout':'View Cart',
                    cancelButtonText:'Continue Shopping',
                    showDenyButton:!isBuyNow,
                    denyButtonText:'Checkout'
                }).then(result=>{
                    if(result.isConfirmed){ window.location.href=isBuyNow?'<?php echo e(route("checkout")); ?>':'<?php echo e(route("cart.view")); ?>'; }
                    else if(result.isDenied){ window.location.href='<?php echo e(route("checkout")); ?>'; }
                });
            } else { throw new Error(data.message||'Failed to add to cart'); }
        })
        .catch(e=>{ console.error(e); Swal.fire({icon:'error',title:'Error',text:e.message||'Something went wrong'}); })
        .finally(()=>{ if(addBtn){ addBtn.innerHTML=originalAdd; addBtn.disabled=false; } if(buyBtn){ buyBtn.innerHTML=originalBuy; buyBtn.disabled=false; } });
    }

    document.addEventListener('DOMContentLoaded',function(){
        const decreaseBtn=document.getElementById('decrease');
        const increaseBtn=document.getElementById('increase');

        decreaseBtn?.addEventListener('click',()=>{ if(currentQuantity>1){ currentQuantity--; updateQuantity(); } });
        increaseBtn?.addEventListener('click',()=>{ if(currentQuantity<maxStock){ currentQuantity++; updateQuantity(); } });

        document.getElementById('add-to-cart-btn')?.addEventListener('click',()=>addToCart(false));
        document.getElementById('buy-now-btn')?.addEventListener('click',()=>addToCart(true));

        <?php if($product->variants && count($product->variants)>0): ?>
            const firstVariant = <?php echo $product->variants->first(); ?>;
            selectVariant(firstVariant.id, firstVariant.name||'Variant', firstVariant.value, firstVariant.additional_price, firstVariant.stock, firstVariant.image? '<?php echo e(asset('public/')); ?>/'+firstVariant.image : '<?php echo e($displayImage); ?>');
        <?php endif; ?>

        <?php if(count($validThumbnails)>1): ?>
        new Swiper('.swiperthumblin',{ slidesPerView:4, spaceBetween:10, navigation:{nextEl:'.swiper-button-nextt', prevEl:'.swiper-button-prevv'}, breakpoints:{320:{slidesPerView:3,spaceBetween:5},768:{slidesPerView:4,spaceBetween:10}} });
        <?php endif; ?>
    });
})();
</script>

<style>
.counter-btn:disabled{ opacity:.5; cursor:not-allowed; }
.quikctech-pro-btns a{ padding:12px 30px; text-decoration:none; border-radius:5px; font-weight:600; transition:all .3s ease; cursor:pointer;}
.quikctech-pro-btns a:hover:not(:disabled){ opacity:.9; transform:translateY(-2px);}
.quikctech-pro-btns a:disabled{ opacity:.6; cursor:not-allowed; transform:none;}
.color-img.active{ border:3px solid #0AAFCF; border-radius:50%; }
.size-num.active{ background-color:#0AAFCF!important; color:white!important; font-weight:600;}
img{ object-fit:cover; }
</style>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/pages/product/details.blade.php ENDPATH**/ ?>