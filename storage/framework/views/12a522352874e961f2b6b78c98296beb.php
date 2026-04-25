<?php $__env->startSection('title', $service->title . ' - Service Details'); ?>
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
    <?php if(session('error')): ?>
    <div class="alert alert-danger">
        <strong class="text-danger"><?php echo e(session('error')); ?></strong>
    </div>
    <?php endif; ?>

    <?php if(session('success')): ?>
    <div class="alert alert-success">
        <strong class="text-success"><?php echo e(session('success')); ?></strong>
    </div>
    <?php endif; ?>
    <section id="quicktech-servicemain">
      <div class="container">
        <div class="row gapp">
          <div class="col-lg-6">
            <div class="quikctech-profile-post mt-4">
              <div class="d-flex justify-content-between">
                <div class="quikctech-post-pro-img-head">
                  <img src="<?php echo e($service->vendor->profile_image ?? 'https://wallpapers.com/images/hd/cool-profile-picture-87h46gcobjl5e4xu.jpg'); ?>" alt="<?php echo e($service->vendor->name ?? 'Vendor'); ?>">
                  <h5>
                    <?php echo e($service->vendor->name ?? 'Vendor Name'); ?> <span>is at</span> <?php echo e($service->organization ?? 'Organization'); ?> <br>
                    <p><?php echo e($service->created_at->diffForHumans()); ?> •</p>
                  </h5>
                </div>
              </div>
              <div class="quikctech-post-seller-img">
                <p class="quikctech-text quikctech-content">
                  <?php echo e($service->note ?? 'No description available for this service.'); ?>

                </p>

                <?php if(strlen($service->note ?? '') > 200): ?>
                <button class="btn quikctech-toggle quicktech-seemore btn-link p-0">
                  See More
                </button>
                <?php endif; ?>

                <div>
                  <?php if($service->service_video): ?>
                    <video src="<?php echo e(asset($service->service_video)); ?>" class="w-100" controls></video>
                  <?php elseif($service->attachment): ?>
                    <img src="<?php echo e(asset($service->attachment)); ?>" class="w-100" alt="<?php echo e($service->title); ?>">
                  <?php else: ?>
                    <img src="https://wallpapers.com/images/hd/cool-profile-picture-87h46gcobjl5e4xu.jpg" class="w-100" alt="Default Service Image">
                  <?php endif; ?>
                </div>
                <?php if(session('success')): ?>
                <div class="alert alert-success">
                    <strong><?php echo e(session('success')); ?></strong>
                </div>
                <?php endif; ?>

                <?php if(session('error')): ?>
                <div class="alert alert-danger">
                    <strong><?php echo e(session('error')); ?></strong>
                </div>
                <?php endif; ?>
                <div class="quikctech-reaction">
                  <ul>
                    <li>
                      <a href="<?php echo e(route('customer.like-service', $service->id)); ?>"><i style="color: red; font-size: 21px" class="fa-solid fa-heart"></i>
                        <?php echo e($service->customers->count()); ?>

                      </a>
                    </li>
                    <li>
                      <a href="#" class="quikctech-comment-toggle">
                        <i style="color: #1dbf73; font-size: 21px" class="fa-regular fa-comment"></i>
                        <?php echo e($service_comments->count()); ?></a>
                    </li>
                    <li><a href="#" class="quikctech-more-toggle">More</a></li>
                  </ul>
                  <!-- comment modal -->
                  <div class="quikctech-comment-box" id="quikctechCommentBox">
                    <div class="quikctech-comment-header">
                      <div class="quikctech-tabs">
                        <button class="active">All</button>
                        <!--<button>User</button>-->
                        <!--<button>Client</button>-->
                        <!--<button>Q/A</button>-->
                      </div>
                      <button class="quikctech-comment-close">×</button>
                    </div>

                    <div class="quikctech-comment-body">
                        <?php $__currentLoopData = $service_comments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service_comment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="quikctech-comment-item">
                            <div class="quikctech-comment-avatar"></div>
                            <div class="quikctech-comment-content">
                                <h4><?php echo e($service_comment->customer->name); ?> <span><?php echo e(Carbon\Carbon::parse($service_comment->created_at)->diffForHumans()); ?></span></h4>
                                <p><?php echo e($service_comment->message); ?></p>
                                <div class="quikctech-comment-media">
                                    <img src="<?php echo e(asset($service_comment->comment_image)); ?>" style="width: 40px; height: 40px;">
                                    <!--<div class="media-item">🖼️ 🎥</div>-->
                                </div>
                              <!--<div class="quikctech-comment-actions">-->
                              <!--  <i class="fa-regular fa-thumbs-up"></i>-->
                              <!--  <span>41</span>-->
                              <!--</div>-->
                            </div>
                        </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                      <!--<div class="quikctech-pagination">-->
                      <!--  <button>&lt;</button>-->
                      <!--  <button class="active">1</button>-->
                      <!--  <button>2</button>-->
                      <!--  <button disabled="">3...</button>-->
                      <!--  <button>7</button>-->
                      <!--  <button>&gt;</button>-->
                      <!--</div>-->
                    </div>
                    <form method="post" action="<?php echo e(route('customer.comment-service', $service->id)); ?>" enctype="multipart/form-data">
                        <?php echo csrf_field(); ?>
                        <input type="file" placeholder="File here..." name="comment_image">
                        <div class="quikctech-comment-footer">
                          <input type="text" placeholder="Comment here..." name="message">
                          <button type="submit"><i class="fa-solid fa-paper-plane"></i></button>
                        </div>
                    </form>
                  </div>
                  <!-- comment modal -->

                  <!-- Popup Box -->
                  <div class="quikctech-more-box" id="quikctechMoreBox">
                    <div class="quikctech-more-header">
                      <h4>More - <span><?php echo e($service->vendor->vendorDetails->shop_name ?? 'Shop Name'); ?></span></h4>
                      <button class="quikctech-more-close">×</button>
                    </div>

                    <div class="quikctech-more-list">
                        <a target="_blank" class="btn btn-sm btn-success" href="<?php echo e(route('services.share', [$service->id, 'whatsapp'])); ?>">
                            Share on WhatsApp
                        </a>

                        <a target="_blank" class="btn btn-sm btn-primary" href="<?php echo e(route('services.share', [$service->id, 'facebook'])); ?>">
                            Share on Facebook
                        </a>

                        <button class="btn btn-sm btn-info" onclick="copyLink()">
                            Copy link
                        </button>

                        <script>
                        function copyLink() {
                            fetch("<?php echo e(route('services.share', [$service->id, 'copy'])); ?>")
                                .then(() => navigator.clipboard.writeText("<?php echo e(route('service.details', $service->id)); ?>"));
                        }
                        </script>

                      <?php $__currentLoopData = $similarServices->take(3); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similarService): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                      <div class="quikctech-more-item">
                        <div class="quikctech-thumb">
                          <?php if($similarService->attachment): ?>
                            <img src="<?php echo e(asset('public/' . $similarService->catalog)); ?>" alt="<?php echo e($similarService->title); ?>" style="width: 40px; height: 40px; object-fit: cover;">
                          <?php endif; ?>
                        </div>
                        <div class="quikctech-text">
                          <h5><?php echo e(Str::limit($similarService->title, 25)); ?></h5>
                          <p><?php echo e(Str::limit($similarService->note, 30)); ?></p>
                        </div>
                        <button class="quikctech-more-dots">⋯</button>
                      </div>
                      <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>

                    <div class="quikctech-more-footer">
                      <button><i class="fa-solid fa-angle-left"></i></button>
                      <button><i class="fa-solid fa-angle-right"></i></button>
                    </div>
                  </div>

                  <!-- more moadal -->
                </div>
              </div>
            </div>
          </div>
                <div class="col-lg-6 col-12">
                    <div class="quikctech-info-boxs">
                        <div class="quikctech-close">×</div>
                        <div class="quikctech-info-content">
                            <div class="quicktech-p-cost">
                                <strong>Project cost:</strong> starts from
                                <b><?php echo e(number_format($service->total_cost, 2)); ?> Tk.</b>
                            </div>
                            <div class="quicktech-p-r">
                                <strong>Service Charge:</strong> <?php echo e(number_format($service->service_charge, 2)); ?> Tk.
                            </div>
                            <div class="quicktech-p-r">
                                <strong>Material Cost:</strong> <?php echo e(number_format($service->material_cost, 2)); ?> Tk.
                            </div>
                            <div class="quicktech-p-r">
                                <strong>Delivery Duration:</strong>
                                <ul>
                                    <li><?php echo e($service->delivery_duration ?? 'Not specified'); ?></li>
                                    <li>(<?php echo e($service->time_line ?? 'Timeline not specified'); ?>)</li>
                                </ul>
                            </div>
                            <?php if($service->discount): ?>
                            <div class="quicktech-p-r">
                                <strong>Discount:</strong> <?php echo e(number_format($service->discount, 2)); ?> Tk.
                            </div>
                            <?php endif; ?>
                            <div class="quikctech-buttons">
                                <button class="quikctech-chat-btn">
                                    <a href="<?php echo e(route('customer.chat-with-seller', $service->vendor_id)); ?>">
                                        Chat with us
                                    </a>
                                </button>
                                <a href="<?php echo e(route('vendor.shop.view', $service->vendor_id)); ?>">Visit Shop</a>
                            </div>
                            <div class="quikctech-buttons">
                                <form action="<?php echo e(route('order.service', $service->id)); ?>" method="post">
                                    <?php echo csrf_field(); ?>
                                    <button class="btn btn-info" type="submit">
                                        Order Service
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="quikctech-askquestion">
        <div class="container">
            <div class="row mb-3">
                <div class="col-lg-12">
                    <div class="quikctech-question-head quicktech-border">
                        <?php if(session('success')): ?>
                        <div class="alert alert-success">
                            <strong><?php echo e(session('success')); ?></strong>
                        </div>
                        <?php endif; ?>

                        <?php if(session('error')): ?>
                        <div class="alert alert-danger">
                            <strong><?php echo e(session('error')); ?></strong>
                        </div>
                        <?php endif; ?>
                        <h4>Ask a Question</h4>
                    </div>
                    <div class="quikctech-ask-input pt-3">
                        <form action="<?php echo e(route('customer.ask-message')); ?>" id="contactForm" method="POST" enctype="multipart/form-data" class="d-flex flex-grow-1">
                            <?php echo csrf_field(); ?>
                            <input type="hidden" name="sellerId" value="<?php echo e($service->vendor_id); ?>">

                            <input type="file" id="file-input" name="file" style="display: none;"
                                   accept=".pdf,.doc,.docx,.txt,.zip,.jpg,.jpeg,.png,.gif,.webp,image/*">

                            <input id="message-input" name="message" class="chat-input-field w-100" placeholder="Write your question here...">
                            <button type="submit" id="sendBtn" class="chat-icon-btn" title="Send">
                                Ask
                            </button>
                        </form>
                        <!--<input type="text" placeholder="" class="">-->
                        <!--<button>-->
                        <!--    <i class="fa fa-paper-plane"></i>-->
                        <!--</button>-->
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="quicktech-service-offer">
      <div class="container">
        <div class="row quicktech-head-border mt-5">
          <div class="col-lg-12">
            <div class="quicktech-head">
              <h4>You may also like</h4>
            </div>
          </div>
        </div>
        <div class="row gapp quicktech-border mb-5 pt-3 pb-4">
          <?php $__currentLoopData = $similarServices; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $similarService): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
          <div class="col-lg-3 col-6 col-sm-6">
            <a href="<?php echo e(route('service.details', $similarService->id)); ?>">
              <div class="quicktech-product">
                <div class="quikctech-img-product text-center">
                  <?php if($similarService->service_video): ?>
                    <video src="<?php echo e(asset($similarService->service_video)); ?>" class="w-100" controls></video>
                  <?php elseif($similarService->attachment): ?>
                    <img src="<?php echo e(asset($similarService->attachment)); ?>" class="w-100" alt="<?php echo e($similarService->title); ?>" style="height: 200px; object-fit: cover;">
                  <?php else: ?>
                    <img src="https://via.placeholder.com/300x200?text=No+Image" class="w-100" alt="No Image" style="height: 200px; object-fit: cover;">
                  <?php endif; ?>
                </div>
                <div class="quicktech-product-text">
                  <h6><?php echo e(Str::limit($similarService->title, 40)); ?>

                    <br>
                    <span style="font-size: 13px; font-weight: 700;">
                      <i class="fa-solid fa-shop"></i> <?php echo e($similarService->vendor->shop_name ?? 'Shop Name'); ?>

                    </span>
                  </h6>
                  <p>
                    <img src="<?php echo e(asset('images/taka 1.png')); ?>" alt="">
                    <?php if($similarService->total_cost): ?>
                      <?php echo e(number_format($similarService->total_cost, 2)); ?> Tk.
                    <?php else: ?>
                      Negotiable
                    <?php endif; ?>
                  </p>
                </div>
              </div>
            </a>
          </div>
          <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
      </div>
    </section>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
// JavaScript for handling modals and interactions
document.addEventListener('DOMContentLoaded', function() {
    // See More functionality
    const seeMoreBtn = document.querySelector('.quicktech-seemore');
    if (seeMoreBtn) {
        seeMoreBtn.addEventListener('click', function() {
            const content = document.querySelector('.quikctech-content');
            content.classList.toggle('expanded');
            this.textContent = content.classList.contains('expanded') ? 'See Less' : 'See More';
        });
    }

    // Comment modal toggle
    const commentToggle = document.querySelector('.quikctech-comment-toggle');
    const commentBox = document.getElementById('quikctechCommentBox');
    const commentClose = document.querySelector('.quikctech-comment-close');

    if (commentToggle && commentBox) {
        commentToggle.addEventListener('click', function(e) {
            e.preventDefault();
            commentBox.style.display = 'block';
        });

        commentClose.addEventListener('click', function() {
            commentBox.style.display = 'none';
        });
    }

    // More modal toggle
    const moreToggle = document.querySelector('.quikctech-more-toggle');
    const moreBox = document.getElementById('quikctechMoreBox');
    const moreClose = document.querySelector('.quikctech-more-close');

    if (moreToggle && moreBox) {
        moreToggle.addEventListener('click', function(e) {
            e.preventDefault();
            moreBox.style.display = 'block';
        });

        moreClose.addEventListener('click', function() {
            moreBox.style.display = 'none';
        });
    }
});
</script>
<?php $__env->stopPush(); ?>

<style>
.quikctech-content {
    max-height: 100px;
    overflow: hidden;
    transition: max-height 0.3s ease;
}

.quikctech-content.expanded {
    max-height: none;
}

.quikctech-comment-box,
.quikctech-more-box {
    display: none;
    position: fixed;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
    background: white;
    padding: 20px;
    border-radius: 10px;
    box-shadow: 0 0 20px rgba(0,0,0,0.2);
    z-index: 1000;
    max-width: 500px;
    width: 90%;
}
</style>

<?php echo $__env->make('frontend.layouts.master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/pages/service/details.blade.php ENDPATH**/ ?>