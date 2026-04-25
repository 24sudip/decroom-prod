
<?php $__env->startSection('title', 'Transactions'); ?>
<?php $__env->startSection('content'); ?>

<style>
    h2 { font-size: 22px; font-weight: 600; }
    .total-balance { float: right; font-size: 30px; font-weight: 700; }
    .total-balance::before { content: "৳"; }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        border-left: 4px solid #e1e1e1;
        padding-left: 15px;
        margin-bottom: 20px;
    }
    .credit { border-color: #28a745; }
    .debit { border-color: #dc3545; }
    .amount.credit { color: #28a745; }
    .amount.debit { color: #dc3545; }
</style>

<div class="container-fluid py-4">
    <div class="card shadow-sm">
        <div class="card-body">

            <h2>
                Transactions
                <span class="total-balance"><?php echo e(number_format($available_balance, 2)); ?></span>
            </h2>

            <!-- Filter -->
            <form method="GET" class="row mt-4">
                <div class="col-md-4">
                    <label>Start Date</label>
                    <input type="date" class="form-control" name="starting_at" value="<?php echo e($starting_at); ?>">
                </div>
                <div class="col-md-4">
                    <label>End Date</label>
                    <input type="date" class="form-control" name="ending_at" value="<?php echo e($ending_at); ?>">
                </div>
                <div class="col-md-4 d-flex align-items-end">
                    <button class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            <hr>

            <?php if($transactions->count() > 0): ?>

                <div class="transaction-item">
                    <strong>Opening Balance</strong>
                    <strong>৳<?php echo e(number_format($transactions->first()->current - $transactions->first()->amount, 2)); ?></strong>
                </div>

                <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <div class="transaction-item <?php echo e($item->credit ? 'credit' : 'debit'); ?>">
                        <div>
                            <h4><?php echo e($item->title); ?></h4>
                            <small class="text-muted">
                                <?php echo e(\Carbon\Carbon::parse($item->updated_at)->format('M d, Y h:i A')); ?>

                            </small><br>
                            <small class="text-muted"><?php echo e($item->note); ?></small>
                        </div>

                        <div class="text-end">
                            <strong class="amount <?php echo e($item->credit ? 'credit' : 'debit'); ?>">
                                ৳<?php echo e(number_format($item->amount, 2)); ?>

                            </strong>
                        </div>
                    </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php else: ?>
                <p class="text-center mt-5">No transactions found</p>
            <?php endif; ?>

        </div>
    </div>
</div>

<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/transactions.blade.php ENDPATH**/ ?>