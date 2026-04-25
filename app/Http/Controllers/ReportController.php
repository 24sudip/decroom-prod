<?php

namespace App\Http\Controllers;

use App\AccountEntry;
use App\Order;
use App\OrderItem;
use App\Product;
use App\Purchase;
use App\PurchaseReturn;
use App\Supplier;
use App\SupplierLedger;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller {

    public function profitLoss(Request $request) {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to ?? now()->toDateString();

        // Total Order Sales (based on total_amount from orders table)
        $totalSales = Order::whereBetween('created_at', [$from, $to])
            ->sum('total_amount');

        // Total Purchase Amount (based on total_bill from purchases table)
        $totalPurchases = Purchase::whereDate('purchase_date', '>=', $from)
            ->whereDate('purchase_date', '<=', $to)
            ->sum('total_bill');

        // Total Purchase Return Amount
        $totalPurchaseReturns = PurchaseReturn::whereBetween('return_date', [$from, $to])
            ->sum('total_return_amt');

        // Custom Income Entries
        $income = AccountEntry::whereHas('accountHead', function ($q) {
            $q->where('type', 'income');
        })
            ->whereBetween('entry_date', [$from, $to])
            ->sum('amount');

        // Custom Expense Entries
        $expense = AccountEntry::whereHas('accountHead', function ($q) {
            $q->where('type', 'expenditure');
        })
            ->whereBetween('entry_date', [$from, $to])
            ->sum('amount');

        // Net Profit or Loss
        $profitOrLoss = ($totalSales + $income) - ($totalPurchases - $totalPurchaseReturns + $expense);

        return view('backend.reports.profit_loss', compact(
            'from',
            'to',
            'totalSales',
            'totalPurchases',
            'totalPurchaseReturns',
            'income',
            'expense',
            'profitOrLoss'
        ));
    }

    public function purchaseSale(Request $request) {
        $from = $request->from ?? now()->startOfMonth()->toDateString();
        $to   = $request->to ?? now()->toDateString();

        $purchases = Purchase::with('supplier')
            ->whereBetween('purchase_date', [$from, $to])
            ->get();

        $sales = Order::with('customer')
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $totalPurchaseAmount = $purchases->sum('total_bill');
        $totalSaleAmount     = $sales->sum('total_amount');

        return view('backend.reports.purchase_sale', compact(
            'from',
            'to',
            'purchases',
            'sales',
            'totalPurchaseAmount',
            'totalSaleAmount'
        ));
    }

    public function supplierCustomer(Request $request) {
        $from = $request->from ?? Carbon::now()->startOfMonth()->toDateString();
        $to   = $request->to ?? Carbon::now()->endOfMonth()->toDateString();

        // Purchases by supplier
        $supplierPurchases = Purchase::select(
            'supplier_id',
            DB::raw('SUM(total_bill) as total_purchase')
        )
            ->whereBetween('purchase_date', [$from, $to])
            ->groupBy('supplier_id')
            ->with('supplier:id,name')
            ->get();

        // Sales by customer
        $customerSales = Order::select(
            'name',
            'phone',
            DB::raw('SUM(total_amount) as total_sale')
        )
            ->whereBetween('created_at', [$from, $to])
            ->groupBy('name', 'phone')
            ->get();

        return view('backend.reports.supplier_customer', compact('from', 'to', 'supplierPurchases', 'customerSales'));
    }

    public function stock(Request $request) {
        $from = $request->input('from') ?: now()->startOfMonth()->toDateString();
        $to   = $request->input('to') ?: now()->endOfMonth()->toDateString();

        // Get all products with stock + variants stock sum
        $products = Product::withSum('variants as variants_stock', 'stock')
            ->select('id', 'product_code', 'name', 'stock')
            ->orderBy('name')
            ->get();

        foreach ($products as $product) {
            $product->total_stock = $product->stock + ($product->variants_stock ?? 0);
        }

        return view('backend.reports.stock', compact('products', 'from', 'to'));
    }

    public function supplierProductStock(Request $request) {
        $from       = $request->input('from') ?: now()->startOfMonth()->toDateString();
        $to         = $request->input('to') ?: now()->endOfMonth()->toDateString();
        $supplierId = $request->input('supplier_id');

        $suppliersQuery = Supplier::query();

        if ($supplierId) {
            $suppliersQuery->where('id', $supplierId);
        }

        $suppliers = $suppliersQuery->with([
            'purchases' => function ($query) use ($from, $to) {
                $query->whereBetween('purchase_date', [$from, $to]);
            },
            'purchases.purchaseDetails.product',
        ])->get();

        $supplierProducts = $suppliers->map(function ($supplier) {
            $products = collect();

            foreach ($supplier->purchases as $purchase) {

                foreach ($purchase->purchaseDetails as $detail) {

                    if ($detail->product) {
                        $products->push($detail->product);
                    }

                }

            }

            $uniqueProducts = $products->unique('id')->filter();

            return [
                'supplier' => $supplier,
                'products' => $uniqueProducts,
            ];
        })->filter(fn($item) => $item['products']->isNotEmpty());

        return view('backend.reports.supplier_product_stock', [
            'supplierProducts' => $supplierProducts,
            'from'             => $from,
            'to'               => $to,
            'supplierId'       => $supplierId,
            'allSuppliers'     => Supplier::orderBy('name')->get(),
        ]);
    }

    public function productSell(Request $request) {
        $from   = $request->input('from', now()->startOfMonth()->toDateString());
        $to     = $request->input('to', now()->endOfMonth()->toDateString());
        $search = $request->input('search');

        $query = OrderItem::with('product')
            ->whereHas('order', function ($q) use ($from, $to) {
                $q->whereBetween('created_at', [$from, $to]);
            });

        if ($search) {
            $query->whereHas('product', function ($q) use ($search) {
                $q->where('name', 'LIKE', "%$search%")
                    ->orWhere('batch_no', 'LIKE', "%$search%");
            });
        }

        $productSales = $query->selectRaw('product_id, SUM(quantity) as total_qty, SUM(total_price) as total_sales')
            ->groupBy('product_id')
            ->get();

        if ($request->ajax()) {
            return view('backend.reports.partials.product_sell_table', compact('productSales'))->render();
        }

        return view('backend.reports.product_sell', compact('productSales', 'from', 'to', 'search'));
    }

    public function purchasePayments(Request $request) {
        $from       = $request->input('from', now()->startOfMonth()->toDateString());
        $to         = $request->input('to', now()->endOfMonth()->toDateString());
        $supplierId = $request->input('supplier_id');

        $ledgerQuery = SupplierLedger::with('supplier')
            ->whereBetween('date', [$from, $to]);

        if ($supplierId) {
            $ledgerQuery->where('supplier_id', $supplierId);
        }

        $ledgers = $ledgerQuery->orderBy('date', 'asc')->get();

        return view('backend.reports.purchase_payments', [
            'ledgers'      => $ledgers,
            'from'         => $from,
            'to'           => $to,
            'supplierId'   => $supplierId,
            'allSuppliers' => Supplier::orderBy('name')->get(),
        ]);
    }

}
