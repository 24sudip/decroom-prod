<?php $__env->startSection('title', 'Wallet List'); ?>
<?php $__env->startSection('content'); ?>

<style>
    @import url('https://fonts.googleapis.com/css?family=Open+Sans:300,400,600|Roboto:300,400');

    h2 { margin: 0; font-weight: 600; font-size: 22px; }
    h3 { margin: 0 0 5px; font-weight: 600; font-size: 16px; }

    .wallet-box {
        background: #f8f9fa;
        padding: 30px;
        border-radius: 8px;
        height: 100%;
    }

    .transactions-wrapper {
        padding: 30px;
    }

    .total-balance {
        font-size: 32px;
        font-weight: 700;
        float: right;
    }

    .total-balance::before { content: "৳"; }

    .transaction-item {
        display: flex;
        justify-content: space-between;
        border-left: 4px solid #e1e1e1;
        padding-left: 15px;
        margin-bottom: 20px;
        padding-bottom: 10px;
    }

    .transaction-item.credit { border-color: #28a745; }
    .transaction-item.credit .amount { color: #28a745; }

    .transaction-item.debit { border-color: #dc3545; }
    .transaction-item.debit .amount { color: #dc3545; }

    .pending-amount { font-weight: 700; color: #ff9800; }
</style>

<div class="container-fluid py-4">
    <div class="row">

        <!-- Withdraw / Pending Section -->
        <div class="col-lg-12">
            <div class="wallet-box shadow-sm">
                <h2>Wallet List</h2>
                <table class="table table-bordered table-striped mb-0 mt-4">
                    <thead>
                        <tr>
                            <th scope="col">SL</th>
                            <th scope="col">Title</th>
                            <th scope="col">Type</th>
                            <th scope="col">Created At</th>
                            <th scope="col">Status</th>
                            <th scope="col">Note</th>
                            <th scope="col">Amount</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <th scope="row"><?php echo e($loop->iteration); ?></th>
                            <td><?php echo e($item->title); ?></td>
                            <td><?php echo e($item->credit ? 'credit' : 'debit'); ?></td>
                            <td><?php echo e(\Carbon\Carbon::parse($item->created_at)->format('M d, Y h:i A')); ?></td>
                            <td>
                                <?php if($item->status == 1): ?>
                                <span class="badge bg-success">Approved</span>
                                <?php else: ?>
                                <span class="badge bg-warning">Pending</span>
                                <?php endif; ?>
                                <br>
                                <?php if($item->receipt): ?>
                                <span class="badge bg-info">Receipt Available</span>
                                <?php else: ?>
                                <span class="badge bg-secondary">No Receipt</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($item->note); ?></td>
                            <td>৳<?php echo e(number_format($item->amount, 2)); ?></td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('frontend.seller.seller_master', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/frontend/seller/wallet-all.blade.php ENDPATH**/ ?>