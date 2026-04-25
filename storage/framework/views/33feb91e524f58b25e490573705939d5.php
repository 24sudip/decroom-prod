<div class="col-lg-12">
    <div class="quikctech-product-details">
        <div class="quicktech-p-head">
            <h4><?php echo e(__('messages.product_details')); ?></h4>
        </div>
        <div class="quicktech-product-description">
            <h4><?php echo $product->description; ?></h4>
        </div>
        
        <?php if($product->highlight): ?>
        <div class="quicktech-product-description mt-4">
            <h4><strong><?php echo e(__('messages.highlights')); ?>:</strong> <?php echo $product->highlight; ?></h4>
        </div>
        <?php endif; ?>

        <?php if($product->video_path || $product->youtube_url): ?>
        <div class="row gapp mt-4">
            <div class="col-lg-6">
                <div class="quikctech-product-img-details">
                    <?php if($product->video_path && file_exists(public_path($product->video_path))): ?>
                        <video src="<?php echo e(asset($product->video_path)); ?>" class="w-100" controls></video>
                    <?php elseif($product->youtube_url): ?>
                        <?php
                            // Extract video ID from YouTube URL
                            preg_match('/(youtu\.be\/|youtube\.com\/(watch\?v=|shorts\/))([\w\-]+)/', $product->youtube_url, $matches);
                            $youtubeId = $matches[3] ?? null;
                            $youtubeEmbed = $youtubeId ? "https://www.youtube.com/embed/{$youtubeId}" : null;
                        ?>
        
                        <?php if($youtubeEmbed): ?>
                        <div class="embed-responsive embed-responsive-16by9">
                            <iframe class="embed-responsive-item w-100" src="<?php echo e($youtubeEmbed); ?>" allowfullscreen style="height: 300px;"></iframe>
                        </div>
                        <?php else: ?>
                            <p><?php echo e(__('messages.invalid_youtube_url')); ?></p>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>


        <div class="quicktech-specification mt-4">
            <h4><?php echo e(__('messages.specification_of', ['name' => $product->name])); ?></h4>
            <div class="quikctech-spec-info">
                <div class="row gapp mt-3">
                    <div class="col-lg-6">
                        <div class="quicktech-spec-brand">
                            <h4><?php echo e(__('messages.brand')); ?>

                                <br>
                                <p><?php echo e($product->brand->name ?? __('messages.no_brand')); ?></p>
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="quicktech-spec-brand">
                            <h4><?php echo e(__('messages.sku')); ?>

                                <br>
                                <p><?php echo e($product->sku ?? __('messages.not_available')); ?></p>
                            </h4>
                        </div>
                    </div>
                    <?php if($product->weight || $product->length || $product->width || $product->height): ?>
                    <div class="col-lg-6">
                        <div class="quicktech-spec-brand">
                            <h4><?php echo e(__('messages.dimensions')); ?>

                                <br>
                                <p>
                                    <?php if($product->weight): ?><?php echo e(__('messages.weight')); ?>: <?php echo e($product->weight); ?>kg<br><?php endif; ?>
                                    <?php if($product->length && $product->width && $product->height): ?>
                                    <?php echo e(__('messages.size')); ?>: <?php echo e($product->length); ?>x<?php echo e($product->width); ?>x<?php echo e($product->height); ?>cm
                                    <?php endif; ?>
                                </p>
                            </h4>
                        </div>
                    </div>
                    <?php endif; ?>
                    <div class="col-lg-6">
                        <div class="quicktech-spec-brand">
                            <h4><?php echo e(__('messages.stock')); ?>

                                <br>
                                <p><?php echo e($product->stock > 0 ? $product->stock . ' ' . __('messages.items_available') : __('messages.out_of_stock')); ?></p>
                            </h4>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="quicktech-spec-brand">
                            <h4 style="display: flex; gap: 20px;"><?php echo e(__('messages.whats_in_the_box')); ?>:
                                <p><?php echo e($product->free_items ?? $product->name); ?></p>
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/include/product_details.blade.php ENDPATH**/ ?>