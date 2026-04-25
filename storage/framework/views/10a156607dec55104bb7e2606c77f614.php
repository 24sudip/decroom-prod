

<?php $__env->startSection('title', 'Order Invoice - #' . $order->id); ?>

<?php $__env->startSection('css'); ?>
    <style>
        .invoice-box {
            max-width: 900px;
            margin: auto;
            padding: 30px;
            border: 1px solid #eee;
            font-size: 16px;
            line-height: 24px;
            font-family: 'Helvetica Neue', 'Helvetica', Helvetica, Arial, sans-serif;
            color: #555;
        }

        .invoice-box table {
            width: 100%;
            line-height: inherit;
            text-align: left;
            border-collapse: collapse;
        }

        .invoice-box table td {
            padding: 5px;
            vertical-align: top;
        }

        .invoice-box table th {
            background: #eee;
            border-bottom: 1px solid #ddd;
            font-weight: bold;
        }

        .invoice-box table tr.total td {
            border-top: 2px solid #eee;
            font-weight: bold;
        }

        .invoice-box .title {
            font-size: 30px;
            color: #333;
        }

        @media print {
            .no-print {
                display: none !important;
            }
        }
    </style>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="invoice-box mt-4">

        
        <table>
            <tr>
                <td>
                    <img src="<?php echo e(asset('build/images/' . AppSetting('logo_lg'))); ?>" alt="Logo" style="width: 120px;">
                </td>
                <td style="text-align: right;">
                    <button onclick="window.print()" class="btn btn-sm btn-primary no-print">🖨 Print</button>
                </td>
            </tr>
        </table>

        <hr>

        <table>
            <tr>
                <td class="title">
                    <h2>Order Invoice</h2>
                </td>

                <td style="text-align: right;">
                    Order #: <?php echo e($order->id); ?><br>
                    Created: <?php echo e($order->created_at->format('d M Y, h:i A')); ?><br>
                    Status: <?php echo e($order->ordertype->name ?? 'N/A'); ?>

                </td>
            </tr>
        </table>

        <hr>

        <table>
            <tr>
                <td>
                    <strong>Customer Details</strong><br>
                    Name: <?php echo e($order->customer->name ?? ($order->name ?? 'N/A')); ?><br>
                    Phone: <?php echo e($order->customer->phone ?? ($order->phone ?? 'N/A')); ?><br>
                    Email: <?php echo e($order->customer->email ?? ($order->email ?? '-')); ?><br>
                    Address: <?php echo e($order->address); ?><br>
                    District: <?php echo e(optional($order->district)->name ?? '-'); ?><br>
                    Upazila: <?php echo e(optional($order->upazila)->name ?? '-'); ?>

                </td>

                <td>
                    <strong>Shipping Address</strong><br>
                    <?php
                        $shipping = $order->customer->shippingAddresses->first() ?? null;
                    ?>
                    <?php if($shipping): ?>
                        <?php echo e($shipping->address); ?><br>
                        District: <?php echo e(optional($shipping->district)->name ?? '-'); ?><br>
                        Upazila: <?php echo e(optional($shipping->upazila)->name ?? '-'); ?>

                    <?php else: ?>
                        N/A
                    <?php endif; ?>
                </td>
            </tr>
        </table>

        <hr>

        <table>
            <thead>
                <tr>
                    <th>#</th>
                    <th>Product</th>
                    <th>Variant</th>
                    <th>Qty</th>
                    <th>Unit Price</th>
                    <th>Total Price</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $order->items; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $item): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($loop->iteration); ?></td>
                        <td><?php echo e($item->product_name); ?></td>
                        <td><?php echo e($item->variant_name ?? '-'); ?></td>
                        <td><?php echo e($item->quantity); ?></td>
                        <td><?php echo e(number_format($item->unit_price, 2)); ?></td>
                        <td><?php echo e(number_format($item->total_price, 2)); ?></td>
                    </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>

        <table style="margin-top: 20px;">
            <tr>
                <td></td>
                <td style="text-align: right; width: 300px;">
                    <table>
                        <tr>
                            <td>Subtotal:</td>
                            <td><?php echo e(number_format($order->items->sum('total_price'), 2)); ?></td>
                        </tr>
                        <tr>
                            <td>Shipping Cost:</td>
                            <td><?php echo e(number_format($order->shipping_cost, 2)); ?></td>
                        </tr>
                        <?php if($order->coupon_code): ?>
                            <tr>
                                <td>Coupon:</td>
                                <td><?php echo e($order->coupon_code); ?></td>
                            </tr>
                        <?php endif; ?>
                        <tr class="total">
                            <td><strong>Total Amount:</strong></td>
                            <td><strong><?php echo e(number_format($order->total_amount, 2)); ?></strong></td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <hr>

        <table>
            <tr>
                <td><strong>Payment Method:</strong> <?php echo e(ucfirst($order->payment_method)); ?></td>
            </tr>
            <tr>
                <td><strong>Order Note:</strong> <?php echo e($order->order_note ?? '-'); ?></td>
            </tr>
        </table>

        <div class="mt-4 no-print">
            <a href="<?php echo e(url()->previous()); ?>" class="btn btn-secondary">← Back</a>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('backend.layouts.master-layouts', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH E:\new-project-quick-tech\decroom-prod\resources\views/backend/orders/view.blade.php ENDPATH**/ ?>