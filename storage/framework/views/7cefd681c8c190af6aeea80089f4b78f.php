<?php $__env->startSection('title', __('messages.vendor_profile')); ?>
<?php $__env->startSection('content'); ?>

<!-- seller-menu-top -->
<?php echo $__env->make('frontend.include.seller-menu-top', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

<div class="quikctech-profile-inner mt-4 mb-5">
    <!-- Cover Section -->
    <div class="quikctech-profile-cover">
        <?php if($vendor->banner_image && file_exists(public_path($vendor->banner_image))): ?>
            <img src="<?php echo e(asset($vendor->banner_image)); ?>?t=<?php echo e(time()); ?>" class="w-100" alt="<?php echo e(__('messages.cover_banner')); ?>" style="max-height: 300px; object-fit: cover;">
        <?php else: ?>
            <img src="<?php echo e(asset('frontend/images/Vector (9).png')); ?>" class="w-100" alt="<?php echo e(__('messages.default_cover')); ?>" style="max-height: 300px; object-fit: cover;">
        <?php endif; ?>

        <div class="quicktech-profile-pictures">
            <?php if($vendor->logo && file_exists(public_path($vendor->logo))): ?>
                <img src="<?php echo e(asset($vendor->logo)); ?>?t=<?php echo e(time()); ?>" alt="<?php echo e(__('messages.profile_picture')); ?>" style="width: 120px; height: 120px; object-fit: cover;">
            <?php else: ?>
                <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($vendor->user->name)); ?>&background=random&size=200" alt="<?php echo e(__('messages.profile_picture')); ?>" style="width: 120px; height: 120px; object-fit: cover;">
            <?php endif; ?>
        </div>

    </div>

    <!-- Profile Header -->
    <div class="quikctech-profile-head-text mt-3">
        <div class="quicktech-profile-name">
            <h4><?php echo e($vendor->user->name); ?>

                <?php if($vendor->isActive()): ?>
                    <?php if($vendor->banner_image): ?>
                    <img src="<?php echo e(asset($vendor->banner_image)); ?>?t=<?php echo e(time()); ?>" alt="<?php echo e(__('messages.verified')); ?>" title="<?php echo e(__('messages.verified_vendor')); ?>"
                        style="width: 20px; height: 20px;">
                    <?php else: ?>
                    verified
                    <?php endif; ?>
                <?php endif; ?>
            </h4>
            <p class="mb-1"><?php echo e(number_format($vendor->followers_count)); ?> <span class="text-muted"><?php echo e(__('messages.followers')); ?></span></p>
            <span class="text-warning"><i class="fa-solid fa-star"></i> <?php echo e(number_format($vendor->rating, 1)); ?> <?php echo e(__('messages.rating')); ?></span>
        </div>

        <!-- Shop Information -->
        <div class="shop-info mb-3">
            <h5 class="text-success mb-2"><?php echo e($vendor->shop_name ?? $vendor->user->name . "'s " . __('messages.shop')); ?></h5>
            <p class="text-muted mb-1">
                <i class="fas fa-store"></i>
                <?php echo e(ucfirst($vendor->type ?? __('messages.individual'))); ?> <?php echo e(__('Type')); ?> <?php echo e(__('messages.vendor')); ?>

                <?php if($vendor->commission): ?>
                    • <?php echo e($vendor->commission); ?>% <?php echo e(__('messages.commission')); ?>

                <?php endif; ?>
            </p>
            <?php if($vendor->address): ?>
                <p class="text-muted mb-0">
                    <i class="fas fa-map-marker-alt"></i> <?php echo e($vendor->address); ?>

                </p>
            <?php endif; ?>
        </div>

        <!-- Vendor Description -->
        <p class="desp mb-4">
            <?php echo e($vendorUser->welcome_description ?? __('messages.default_vendor_description')); ?>

        </p>

        <!-- Statistics Cards -->
        <div class="row mb-4">
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-number text-primary"><?php echo e($stats['total_products'] ?? 0); ?></div>
                    <div class="stat-label"><?php echo e(__('messages.total_products')); ?></div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-number text-success"><?php echo e($stats['active_products'] ?? 0); ?></div>
                    <div class="stat-label"><?php echo e(__('messages.active_products')); ?></div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-number text-info"><?php echo e($stats['total_services'] ?? 0); ?></div>
                    <div class="stat-label"><?php echo e(__('messages.services')); ?></div>
                </div>
            </div>
            <div class="col-md-3 col-6 mb-3">
                <div class="stat-card text-center">
                    <div class="stat-number text-warning"><?php echo e($stats['total_orders'] ?? 0); ?></div>
                    <div class="stat-label"><?php echo e(__('messages.total_orders')); ?></div>
                </div>
            </div>
        </div>
        <style>
            @media (min-width: 991.98px) and (max-width: 1199.98px) {
                .quikctech-button-menu-profile ul li a {
                    padding: 4px 25px;
                }
            }
            .ser-active {
                background-color: #1dbf73 !important;
                color: white !important;
            }
            .quikctech-button-menu-profile ul li a {
                border: 2px solid #1dbf73;
            }
        </style>
        <!-- Navigation Menu -->
        <div class="quikctech-button-menu-profile mb-4">
            <ul>
                <li><a href="<?php echo e(route('vendor.profile')); ?>" class="seller-active ser-active"><?php echo e(__('messages.profile')); ?></a></li>
                <li><a href="<?php echo e(route('vendor.products.manage')); ?>" class="ser-active"><?php echo e(__('messages.products')); ?> (<?php echo e($stats['active_products'] ?? 0); ?>)</a></li>
                <li><a href="<?php echo e(route('vendor.orders.list')); ?>" class="ser-active"><?php echo e(__('messages.orders')); ?> (<?php echo e($stats['total_orders'] ?? 0); ?>)</a></li>
                <li><a href="<?php echo e(route('vendor.message')); ?>" class="ser-active"><?php echo e(__('messages.message')); ?></a></li>
                <li><a href="<?php echo e(route('vendor.profile.edit')); ?>" class="ser-active"><?php echo e(__('messages.edit_profile')); ?></a></li>
                <li>
                    <a href="<?php echo e(route('services.index')); ?>" class="ser-active">
                        <?php echo e(__('Services')); ?>

                    </a>
                </li>
            </ul>
        </div>
    </div>

    <!-- Recent Activities/Posts Section -->
    <div class="row gapp">
        <!-- Recent Products -->
        <div class="col-lg-6">
            <div class="quikctech-profile-post mt-4">
                <div class="quikctech-post-pro-img-head d-flex align-items-center">
                    <?php if($vendor->logo && file_exists(public_path($vendor->logo))): ?>
                        <img src="<?php echo e(asset($vendor->logo)); ?>?t=<?php echo e(time()); ?>" alt="<?php echo e(__('messages.profile_picture')); ?>" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php else: ?>
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($vendor->user->name)); ?>&background=random&size=200" alt="<?php echo e(__('messages.profile_picture')); ?>" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php endif; ?>
                    <div>
                        <h5 class="mb-0"><?php echo e($vendor->user->name); ?>

                            <span class="text-muted"><?php echo e(__('messages.added_new_products')); ?></span>
                        </h5>
                        <p class="text-muted mb-0 small"><?php echo e(\Carbon\Carbon::now()->subDays(2)->diffForHumans()); ?></p>
                    </div>
                </div>

                <div class="quikctech-post-seller-img mt-3">
                    
                    <p class="quikctech-text quikctech-content" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php echo e(__('messages.recently_added_products', ['count' => $stats['active_products'] ?? 0])); ?>

                        <?php if(($vendor->products->count() ?? 0) > 0): ?>
                            <?php echo e(__('messages.check_latest_collection')); ?>

                            <?php echo e($vendor->products->take(3)->pluck('name')->implode(', ')); ?>

                            <?php if($vendor->products->count() > 3): ?>
                                <?php echo e(__('messages.and_x_more_products', ['count' => $vendor->products->count() - 3])); ?>

                            <?php endif; ?>
                        <?php else: ?>
                            <?php echo e(__('messages.setting_up_catalog')); ?>

                        <?php endif; ?>
                    </p>

                    <div class="row gapp mt-5 mb-5">
                    <?php $__empty_1 = true; $__currentLoopData = $vendor->products; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $product): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <div class="col-lg-6 col-md-6 col-sm-12 mb-4">
                        <a href="<?php echo e(route('product.details', $product->id)); ?>">
                            <div class="quicktech-product position-relative">
                                
                                <div class="quikctech-wishlist position-absolute top-0 end-0 p-2">
                                    <button type="button"><i class="fa-solid fa-heart"></i></button>
                                </div>

                                
                                <div class="quicktech-sold position-absolute top-0 start-0 p-2 bg-white rounded">
                                    <span><?php echo e($product->orderItems ? $product->orderItems->sum('quantity') : 0); ?> <?php echo e(__('messages.sold')); ?></span>
                                </div>

                                
                                <div class="quikctech-img-product text-center">
                                    <?php
                                        $productImage = null;
                                        // Safe check for images
                                        if($product->images && is_object($product->images) && method_exists($product->images, 'count') && $product->images->count() > 0) {
                                            $productImage = $product->images->first();
                                        }
                                    ?>

                                    <?php if($productImage && $productImage->image_path): ?>
                                        <img src="<?php echo e(asset($productImage->image_path)); ?>" alt="<?php echo e($product->name); ?>" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                                    <?php elseif($product->promotion_image): ?>
                                        <img src="<?php echo e(asset($product->promotion_image)); ?>" alt="<?php echo e($product->name); ?>" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                                    <?php else: ?>
                                        <img src="<?php echo e(asset('frontend/images/Architect1.png')); ?>" alt="<?php echo e(__('messages.default_product_image')); ?>" class="img-fluid" style="width: 100%; height: 200px; object-fit: cover;">
                                    <?php endif; ?>
                                </div>

                                
                                <div class="quicktech-product-text mt-2">
                                    <h6 class="text-truncate"><?php echo e(Str::limit($product->name, 50)); ?></h6>
                                    <div class="d-flex justify-content-between align-items-center quicktech-pp-t">
                                        <p class="mb-0">
                                            ৳ <?php echo e(number_format($product->price)); ?>

                                            <?php if($product->special_price && $product->special_price < $product->price): ?>
                                                <br>
                                                <span style="font-size: 13px;">
                                                    <s>৳ <?php echo e(number_format($product->price)); ?></s>
                                                    -<?php echo e(number_format((($product->price - $product->special_price) / $product->price) * 100, 0)); ?>%
                                                </span>
                                            <?php endif; ?>
                                        </p>

                                        
                                        <?php
                                            $avgRating = $product->averageRating();
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
                    <div class="col-lg-12">
                        <div class="text-center py-5">
                            <h4><?php echo e(__('messages.no_products_found')); ?></h4>
                            <p><?php echo e(__('messages.no_products_in_shop')); ?></p>
                            <p class="text-muted"><?php echo e(__('messages.check_back_later')); ?></p>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>

                </div>

                
                <script>
                document.querySelectorAll('.quicktech-seemore').forEach(button => {
                    button.addEventListener('click', function() {
                        const content = this.previousElementSibling;
                        if (content.style.webkitLineClamp === 'unset' || !content.style.webkitLineClamp) {
                            content.style.webkitLineClamp = '3';
                            this.textContent = '<?php echo e(__('messages.see_more')); ?>';
                        } else {
                            content.style.webkitLineClamp = 'unset';
                            this.textContent = '<?php echo e(__('messages.see_less')); ?>';
                        }
                    });
                });
                </script>

            </div>
        </div>

        <!-- Store Performance -->
        <div class="col-lg-6">
            <div class="quikctech-profile-post mt-4">
                <div class="quikctech-post-pro-img-head d-flex align-items-center">
                    <?php if($vendor->logo && file_exists(public_path($vendor->logo))): ?>
                        <img src="<?php echo e(asset($vendor->logo)); ?>?t=<?php echo e(time()); ?>" alt="<?php echo e(__('messages.profile_picture')); ?>" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php else: ?>
                        <img src="https://ui-avatars.com/api/?name=<?php echo e(urlencode($vendor->user->name)); ?>&background=random&size=200" alt="<?php echo e(__('messages.profile_picture')); ?>" class="rounded-circle me-3" style="width: 50px; height: 50px; object-fit: cover;">
                    <?php endif; ?>
                    <div>
                        <h5 class="mb-0"><?php echo e($vendor->user->name); ?>

                            <span class="text-muted"><?php echo e(__('messages.store_performance')); ?></span>
                        </h5>
                        <p class="text-muted mb-0 small"><?php echo e(__('messages.updated_today')); ?></p>
                    </div>
                </div>

                <div class="quikctech-post-seller-img mt-3">
                    <div class="performance-stats">
                        <div class="row text-center">
                            <div class="col-4">
                                <div class="performance-item">
                                    <div class="performance-value text-success"><?php echo e(number_format($vendor->rating, 1)); ?>/5</div>
                                    <div class="performance-label"><?php echo e(__('messages.rating')); ?></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="performance-item">
                                    <div class="performance-value text-primary"><?php echo e($stats['total_followers'] ?? 0); ?></div>
                                    <div class="performance-label"><?php echo e(__('messages.followers')); ?></div>
                                </div>
                            </div>
                            <div class="col-4">
                                <div class="performance-item">
                                    <div class="performance-value text-warning"><?php echo e($stats['total_orders'] ?? 0); ?></div>
                                    <div class="performance-label"><?php echo e(__('messages.orders')); ?></div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <p class="quikctech-text quikctech-content mt-3" style="display: -webkit-box; -webkit-line-clamp: 3; -webkit-box-orient: vertical; overflow: hidden;">
                        <?php echo e(__('messages.store_performance_overview', [
                            'rating' => number_format($vendor->rating, 1),
                            'followers' => $stats['total_followers'] ?? 0
                        ])); ?>

                        <?php if($vendor->rating >= 4.5): ?>
                            <?php echo e(__('messages.excellent_performance')); ?>

                        <?php elseif($vendor->rating >= 4.0): ?>
                            <?php echo e(__('messages.good_performance')); ?>

                        <?php else: ?>
                            <?php echo e(__('messages.improving_performance')); ?>

                        <?php endif; ?>
                    </p>

                    <button class="btn quikctech-toggle quicktech-seemore btn-link p-0 text-decoration-none"><?php echo e(__('messages.see_more')); ?></button>

                    <div class="quikctech-reaction mt-3 pt-3 border-top">
                        <ul class="list-inline mb-0">
                            <li class="list-inline-item me-3">
                                <a href="#" class="text-decoration-none text-dark">
                                    <i style="color: red; font-size: 18px;" class="fa-solid fa-heart"></i>
                                    <span class="small"><?php echo e($totalLikes); ?></span>
                                </a>
                            </li>
                            <li class="list-inline-item me-3">
                                <a href="#" class="text-decoration-none text-dark">
                                    <i style="color: #1dbf73; font-size: 18px;" class="fa-regular fa-comment"></i>
                                    <span class="small"><?php echo e($totalComments); ?></span>
                                </a>
                            </li>
                            <li class="list-inline-item">
                                <a href="#" class="text-decoration-none text-dark">
                                    <i style="color: #1dbf73; font-size: 18px;" class="fa-solid fa-share"></i>
                                    <span class="small"><?php echo e($totalShares); ?></span>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Upload Modal -->
<div class="modal fade" id="imageUploadModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalTitle"><?php echo e(__('messages.upload_image')); ?></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="<?php echo e(__('messages.close')); ?>"></button>
            </div>
            <div class="modal-body">
                <form id="imageUploadForm" enctype="multipart/form-data">
                    <?php echo csrf_field(); ?>
                    <input type="hidden" name="image_type" id="imageType">
                    <div class="mb-3">
                        <label for="image" class="form-label"><?php echo e(__('messages.select_image')); ?></label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/jpeg,image/png,image/jpg,image/gif" required>
                        <div class="form-text"><?php echo e(__('messages.supported_formats')); ?></div>
                    </div>
                    <div class="text-center">
                        <button type="submit" class="btn btn-primary" id="uploadBtn">
                            <i class="fas fa-upload me-1"></i><?php echo e(__('messages.upload_image')); ?>

                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Loading Spinner -->
<div class="modal fade" id="loadingModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-sm">
        <div class="modal-content">
            <div class="modal-body text-center py-4">
                <div class="spinner-border text-primary mb-3" role="status">
                    <span class="visually-hidden"><?php echo e(__('messages.loading')); ?></span>
                </div>
                <p class="mb-0"><?php echo e(__('messages.uploading_image')); ?></p>
            </div>
        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php $__env->startSection('styles'); ?>
<style>
    .stat-card {
        background: #f8f9fa;
        padding: 15px;
        border-radius: 10px;
        border-left: 4px solid #007bff;
        transition: transform 0.2s ease;
    }
    .stat-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 8px rgba(0,0,0,0.1);
    }
    .stat-number {
        font-size: 24px;
        font-weight: bold;
        margin-bottom: 5px;
    }
    .stat-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
    }
    .shop-info {
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        padding: 20px;
        border-radius: 10px;
        border-left: 4px solid #28a745;
    }
    .performance-item {
        padding: 15px;
    }
    .performance-value {
        font-size: 20px;
        font-weight: bold;
    }
    .performance-label {
        font-size: 12px;
        color: #6c757d;
        text-transform: uppercase;
        font-weight: 600;
    }
    .product-thumbnail {
        transition: transform 0.2s ease;
    }
    .product-thumbnail:hover {
        transform: scale(1.05);
    }
    .product-thumbnail img {
        width: 100%;
        height: 80px;
        object-fit: cover;
        border: 1px solid #dee2e6;
        border-radius: 8px;
    }
    .recent-products {
        border-top: 1px solid #e9ecef;
        padding-top: 15px;
    }
    .quikctech-edit-cover-btn {
        position: absolute;
        bottom: 20px;
        right: 20px;
    }
    .quikctech-profile-cover {
        position: relative;
    }
    .quicktech-profile-pictures {
        position: absolute;
        bottom: -60px;
        left: 30px;
        border: 4px solid white;
        border-radius: 50%;
        background: white;
    }
    .quikctech-button-menu-profile ul {
        display: flex;
        flex-wrap: wrap;
        gap: 10px;
        list-style: none;
        padding: 0;
        margin: 0;
    }
    .quikctech-button-menu-profile ul li a {
        padding: 8px 16px;
        background: #f8f9fa;
        border-radius: 20px;
        text-decoration: none;
        color: #495057;
        font-weight: 500;
        transition: all 0.3s ease;
    }
    .quikctech-button-menu-profile ul li a:hover,
    .quikctech-button-menu-profile ul li a.seller-active {
        background: #007bff;
        color: white;
    }
</style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
function openImageUpload(type) {
    document.getElementById('imageType').value = type;
    document.getElementById('modalTitle').textContent = type === 'banner' ? '<?php echo e(__('messages.upload_cover_banner')); ?>' : '<?php echo e(__('messages.upload_profile_picture')); ?>';

    // Reset form
    document.getElementById('imageUploadForm').reset();

    var modal = new bootstrap.Modal(document.getElementById('imageUploadModal'));
    modal.show();
}

// Handle image upload form submission
document.getElementById('imageUploadForm').addEventListener('submit', function(e) {
    e.preventDefault();

    const formData = new FormData(this);
    const submitBtn = document.getElementById('uploadBtn');
    const originalText = submitBtn.innerHTML;

    // Show loading
    submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-1"></i><?php echo e(__("messages.uploading")); ?>...';
    submitBtn.disabled = true;

    // Show loading modal
    const loadingModal = new bootstrap.Modal(document.getElementById('loadingModal'));
    loadingModal.show();

    fetch("<?php echo e(route('vendor.profile.updateImage')); ?>", {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': '<?php echo e(csrf_token()); ?>'
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error('<?php echo e(__("messages.network_error")); ?>');
        }
        return response.json();
    })
    .then(data => {
        // Hide loading modal
        loadingModal.hide();

        if (data.success) {
            // Close upload modal
            const uploadModal = bootstrap.Modal.getInstance(document.getElementById('imageUploadModal'));
            uploadModal.hide();

            // Show success message
            showToast('<?php echo e(__("messages.image_updated_success")); ?>', 'success');

            // Reload page after short delay
            setTimeout(() => {
                location.reload();
            }, 1500);
        } else {
            throw new Error(data.message || '<?php echo e(__("messages.upload_failed")); ?>');
        }
    })
    .catch(error => {
        // Hide loading modal
        loadingModal.hide();

        console.error('Error:', error);
        showToast('<?php echo e(__("messages.error_uploading_image")); ?>: ' + error.message, 'error');

        // Reset button
        submitBtn.innerHTML = originalText;
        submitBtn.disabled = false;
    });
});

// See more functionality
document.querySelectorAll('.quicktech-seemore').forEach(button => {
    button.addEventListener('click', function() {
        const content = this.previousElementSibling;
        if (content.style.webkitLineClamp === 'unset' || !content.style.webkitLineClamp) {
            content.style.webkitLineClamp = '3';
            this.textContent = '<?php echo e(__("messages.see_more")); ?>';
        } else {
            content.style.webkitLineClamp = 'unset';
            this.textContent = '<?php echo e(__("messages.see_less")); ?>';
        }
    });
});

// Toast notification function
function showToast(message, type = 'info') {
    // Remove existing toasts
    document.querySelectorAll('.custom-toast').forEach(toast => toast.remove());

    // Create toast element
    const toast = document.createElement('div');
    toast.className = `custom-toast alert alert-${type === 'success' ? 'success' : 'danger'} alert-dismissible fade show`;
    toast.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        z-index: 99999;
        min-width: 300px;
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    `;
    toast.innerHTML = `
        <strong>${type === 'success' ? '<?php echo e(__("messages.success")); ?>!' : '<?php echo e(__("messages.error")); ?>!'}</strong> ${message}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    `;

    document.body.appendChild(toast);

    // Auto remove after 5 seconds
    setTimeout(() => {
        if (toast.parentNode) {
            toast.parentNode.removeChild(toast);
        }
    }, 5000);
}

// Initialize tooltips
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[title]'));
    var tooltipList = tooltipTriggerList.map(function(tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/auth/sellerprofile.blade.php ENDPATH**/ ?>