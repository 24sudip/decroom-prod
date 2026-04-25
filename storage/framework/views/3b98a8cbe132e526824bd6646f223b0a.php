

<?php $__env->startSection('title', __('Seller Ledger')); ?>

<?php $__env->startSection('css'); ?>
    <link rel="stylesheet" href="<?php echo e(URL::asset('build/libs/select2/css/select2.min.css')); ?>">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.4/css/jquery.dataTables.min.css" />

    <style>
        .filterform .form-group label {
            font-weight: 600;
        }
        .filterbutton button, 
        .filterbutton a {
            height: 38px;
            display: flex;
            align-items: center;
        }
        .short_button {
            margin-top: 15px;
        }
        table td {
            vertical-align: middle !important;
        }
        .badge-pill {
            padding: 6px 12px;
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<section class="content">
    <div class="row">
        <div class="col-12">

            <div class="card">

                
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h3 class="card-title m-0">Seller Ledger</h3>
                </div>

                
                <div class="card-body pb-0">
                    <div class="filterform">
                        <form action="" method="GET">
                            <?php echo csrf_field(); ?>
                            <div class="row">

                                
                                <div class="col-sm-7">
                                    <div class="form-group">
                                        <label>Seller</label>
                                        <select name="vendor_id" class="form-control select2">
                                            <option selected disabled>Select Seller</option>
                                            <?php $__currentLoopData = $sellers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <option value="<?php echo e($value->id); ?>" 
                                                    <?php if($vendor_id == $value->id): ?> selected <?php endif; ?>>
                                                    <?php echo e(optional($value->vendorDetails)->shop_name ?? 'N/A'); ?>

                                                </option>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </select>
                                    </div>
                                </div>

                                
                                <div class="col-sm-5 d-flex align-items-end">
                                    <div class="form-group d-flex filterbutton">
                                        <button class="btn btn-primary">Filter</button>
                                        <a class="btn btn-secondary mx-2" href="<?php echo e(route('seller.transactions')); ?>">
                                            Reset
                                        </a>
                                    </div>
                                </div>

                            </div>
                        </form>
                    </div>
                </div>

                
                <div class="card-body user-border">
                    <table id="example" class="table table-bordered table-striped table-sm">
                        <thead>
                            <tr>
                                <th>Sl</th>
                                <th>Name</th>
                                <th>Details</th>
                                <th>Debit</th>
                                <th>Credit</th>
                                <th>Balance</th>
                                <th>Status</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php $__currentLoopData = $show_datas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($loop->iteration); ?></td>

                                    
                                    <td>
                                        <?php echo e(optional(optional($value->vendor)->vendorDetails)->shop_name ?? 'N/A'); ?>

                                        <br>
                                        <?php echo e($value->vendor->phone ?? 'N/A'); ?>

                                    </td>

                                    
                                    <td>
                                        <?php echo e($value->title); ?>

                                        <br>
                                        <i><small><?php echo e($value->note); ?></small></i>
                                    </td>

                                    
                                    <td class="text-danger">
                                        <?php if(!$value->credit): ?>
                                            BDT <?php echo e(number_format($value->amount, 2)); ?>

                                        <?php endif; ?>
                                    </td>

                                    
                                    <td class="text-success">
                                        <?php if($value->credit): ?>
                                            BDT <?php echo e(number_format($value->amount, 2)); ?>

                                        <?php endif; ?>
                                    </td>

                                    
                                    <td>
                                        BDT <?php echo e(number_format($value->current, 2)); ?>

                                    </td>

                                    
                                    <td>
                                        <?php if($value->status): ?>
                                            <span class="text-success font-weight-bold">Completed</span>
                                            <br>
                                            <span class="badge badge-warning badge-pill">
                                                <?php echo e(App\User::find($value->approved_by)->name ?? ''); ?>

                                            </span>
                                        <?php else: ?>
                                            <span class="text-warning font-weight-bold">Pending</span>
                                        <?php endif; ?>

                                        <p class="mt-1 mb-0">
                                            <strong>Date: </strong>
                                            <?php echo e(date('F d, Y', strtotime($value->updated_at))); ?>

                                        </p>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>

                    </table>
                </div>

            </div>

        </div>
    </div>
</section>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/vendor/transactions.blade.php ENDPATH**/ ?>