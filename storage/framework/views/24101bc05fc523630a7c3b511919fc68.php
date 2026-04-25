<section id="quicktech-review-rating">
    <div class="container">
        <h4><?php echo e(__('messages.review_rating')); ?></h4>

        <?php
            $totalReviews = $product->ratings()->count();
            $avgRating = $totalReviews > 0 ? $product->ratings()->avg('rating') : 0;
        
            // Count reviews by rating
            $ratingDistribution = [
                5 => $product->ratings()->where('rating',5)->count(),
                4 => $product->ratings()->where('rating',4)->count(),
                3 => $product->ratings()->where('rating',3)->count(),
                2 => $product->ratings()->where('rating',2)->count(),
                1 => $product->ratings()->where('rating',1)->count(),
            ];
        ?>
        
        <div class="mb-4">
            <h3><?php echo e(number_format($avgRating,1)); ?> / 5</h3>
            <div class="rating-stars mb-2">
                <?php for($i=1; $i<=5; $i++): ?>
                    <?php if($i <= floor($avgRating)): ?>
                        <i class="fas fa-star" style="color:#FFD700;"></i>
                    <?php elseif($i - 0.5 <= $avgRating): ?>
                        <i class="fas fa-star-half-alt" style="color:#FFD700;"></i>
                    <?php else: ?>
                        <i class="far fa-star" style="color:#ccc;"></i>
                    <?php endif; ?>
                <?php endfor; ?>
                <span class="ms-2"><?php echo e($totalReviews); ?> <?php echo e(__('messages.review', ['count' => $totalReviews])); ?></span>
            </div>
        
            <div class="rating-distribution">
                <?php $__currentLoopData = $ratingDistribution; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $star=>$count): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <?php $percent = $totalReviews>0?($count/$totalReviews)*100:0; ?>
                    <div class="d-flex align-items-center mb-1">
                        <span class="me-2"><?php echo e($star); ?> <i class="fas fa-star" style="color:#FFD700;"></i></span>
                        <div class="progress flex-grow-1" style="height: 8px;">
                            <div class="progress-bar" role="progressbar" style="width: <?php echo e($percent); ?>%; background-color:#FFD700;"></div>
                        </div>
                        <span class="ms-2"><?php echo e($count); ?></span>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </div>
        </div>
        
        <div class="reviews-list">
            <?php $__empty_1 = true; $__currentLoopData = $reviews; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $review): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="review-item mb-3 border-bottom pb-3">
                    <div class="d-flex justify-content-between align-items-center mb-1">
                        <div>
                            <?php for($i=1;$i<=5;$i++): ?>
                                <?php if($i <= $review->rating): ?>
                                    <i class="fas fa-star" style="color:#FFD700;"></i>
                                <?php else: ?>
                                    <i class="far fa-star" style="color:#ccc;"></i>
                                <?php endif; ?>
                            <?php endfor; ?>
                            <strong class="ms-2"><?php echo e($review->user->name ?? __('messages.anonymous')); ?></strong>
                            <?php if($review->is_verified_purchase): ?>
                                <span class="text-success ms-2"><i class="fa-solid fa-circle-check"></i> <?php echo e(__('messages.verified_purchase')); ?></span>
                            <?php endif; ?>
                        </div>
                        <small class="text-muted"><?php echo e($review->created_at->diffForHumans()); ?></small>
                    </div>
                    <p><?php echo e($review->comment); ?></p>
                    <?php if($review->variant_info): ?>
                        <small><?php echo e(__('messages.color')); ?>: <?php echo e($review->variant_info['color'] ?? __('messages.not_available')); ?>, <?php echo e(__('messages.size')); ?>: <?php echo e($review->variant_info['size'] ?? __('messages.not_available')); ?></small>
                    <?php endif; ?>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <p><?php echo e(__('messages.no_reviews_yet')); ?></p>
            <?php endif; ?>
        </div>
        
        <?php if(method_exists($reviews,'links')): ?>
            <div class="mt-3">
                <?php echo e($reviews->links()); ?>

            </div>
        <?php endif; ?>

    </div>
</section>

<style>
.rating-stars i { font-size: 18px; }
.rating-distribution .progress { background-color: #e0e0e0; border-radius: 4px; }
.rating-distribution .progress-bar { height: 8px; }
.review-item p { margin-bottom: 0.5rem; }
</style><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/review_rating.blade.php ENDPATH**/ ?>