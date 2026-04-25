<?php

namespace App\Http\Controllers;

use App\Product;
use App\Purchase;
use App\PurchaseDetails;
use App\PurchaseReturn;
use App\PurchaseReturnDetail;
use App\Supplier;
use App\SupplierLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class PurchaseReturnController extends Controller {

    public function index(Request $request) {
        $suppliers = Supplier::all();

        if ($request->ajax()) {
            $query = PurchaseReturn::with(['supplier', 'purchase']);

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->whereHas('purchase', fn($q2) => $q2->where('bill_no', 'like', "%{$search}%"))
                        ->orWhereHas('supplier', fn($q2) => $q2->where('name', 'like', "%{$search}%"));
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('return_date', fn($row) => $row->return_date ? date('d M, Y', strtotime($row->return_date)) : '')
                ->addColumn('bill_no', fn($row) => $row->purchase->bill_no ?? 'N/A')
                ->addColumn('supplier_name', fn($row) => $row->supplier->name ?? 'N/A')
                ->addColumn('total_return_amt', fn($row) => number_format($row->total_return_amt ?? 0, 2))
                ->addColumn('action', function ($row) {
                    return '
                    <a href="' . route('purchase-return.show', $row->id) . '" class="btn btn-sm btn-info" title="View"><i class="mdi mdi-eye"></i></a>
                    <a href="' . route('purchase-return.edit', $row->id) . '" class="btn btn-sm btn-primary" title="Edit"><i class="mdi mdi-lead-pencil"></i></a>
                    <form action="' . route('purchase-return.destroy', $row->id) . '" method="POST" style="display:inline-block;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="mdi mdi-trash-can"></i></button>
                    </form>';
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('backend.purchase_return.index', compact('suppliers'));
    }

    public function create($purchaseId) {
        $purchase = Purchase::with('purchaseDetails.product')->findOrFail($purchaseId);

        return view('backend.purchase_return.create', compact('purchase'));
    }

    public function store(Request $request) {

        $request->validate([
            'return_date' => 'required|date',
            'returns'     => 'required|array',
            'supplier_id' => 'required|integer',
        ]);

        DB::beginTransaction();

        try {
            // Create the main PurchaseReturn record
            $purchaseReturn = PurchaseReturn::create([
                'purchase_id'      => $request->purchase_id,
                'supplier_id'      => $request->supplier_id,
                'return_date'      => $request->return_date,
                'total_return_amt' => 0,
            ]);

            $totalReturnAmount = 0;

            foreach ($request->returns as $ret) {

                if (!empty($ret['quantity']) && $ret['quantity'] > 0) {
                    $rate = $ret['quantity'] != 0 ? $ret['amount'] / $ret['quantity'] : 0;

                    // Save return detail
                    PurchaseReturnDetail::create([
                        'return_id'  => $purchaseReturn->id,
                        'product_id' => $ret['product_id'],
                        'quantity'   => $ret['quantity'],
                        'rate'       => $rate,
                        'tax_amt'    => 0,
                        'total'      => $ret['amount'],
                        'reason'     => $ret['reason'] ?? null,
                    ]);

                    $totalReturnAmount += $ret['amount'];

                    // Update returned_quantity in PurchaseDetails
                    $purchaseDetail = PurchaseDetails::where('pur_id', $request->purchase_id)
                        ->where('product_id', $ret['product_id'])
                        ->first();

                    if ($purchaseDetail) {
                        $purchaseDetail->returned_quantity = ($purchaseDetail->returned_quantity ?? 0) + $ret['quantity'];
                        $purchaseDetail->save();
                    }

                    // Update product stock (decrease)
                    $product = Product::find($ret['product_id']);

                    if ($product) {
                        $product->stock = max(0, $product->stock - $ret['quantity']);
                        $product->save();
                    }

                }

            }

            // Update total return amount
            $purchaseReturn->total_return_amt = $totalReturnAmount;
            $purchaseReturn->save();

            SupplierLedger::create([
                'supplier_id' => $request->supplier_id,
                'date'        => $request->return_date,
                'type'        => 'purchase_return',
                'ref_id'      => $purchaseReturn->id,
                'debit'       => $totalReturnAmount,
                'credit'      => 0,
                'note'        => 'Purchase Return #' . $purchaseReturn->id,
            ]);

            DB::commit();

            return redirect()->route('purchase-return.index')->with('success', 'Return processed successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->back()->with('error', 'Error processing return: ' . $e->getMessage());
        }

    }

    public function show($id) {

        $purchaseReturn = PurchaseReturn::with(['supplier', 'purchase', 'details.product'])->findOrFail($id);

        return view('backend.purchase_return.show', compact('purchaseReturn'));
    }

    public function edit($id) {
        $purchaseReturn = PurchaseReturn::with(['details.product'])->findOrFail($id);

        return view('backend.purchase_return.edit', compact('purchaseReturn'));
    }

    public function update(Request $request, $id) {
        $request->validate([
            'return_date' => 'required|date',
            'returns'     => 'required|array',
        ]);

        DB::beginTransaction();

        try {
            $purchaseReturn                   = PurchaseReturn::findOrFail($id);
            $purchaseReturn->return_date      = $request->return_date;
            $purchaseReturn->total_return_amt = 0;
            $purchaseReturn->save();

            foreach ($purchaseReturn->details as $old) {
                $purchaseDetail = PurchaseDetails::where('pur_id', $purchaseReturn->purchase_id)
                    ->where('product_id', $old->product_id)
                    ->first();

                if ($purchaseDetail) {
                    $purchaseDetail->returned_quantity -= $old->quantity;
                    $purchaseDetail->save();
                }

                $product = Product::find($old->product_id);

                if ($product) {
                    $product->stock += $old->quantity;
                    $product->save();
                }

                $old->delete();
            }

            // Now add updated return details
            $totalReturnAmount = 0;

            foreach ($request->returns as $ret) {

                if (!empty($ret['quantity']) && $ret['quantity'] > 0) {
                    $rate = $ret['quantity'] != 0 ? $ret['amount'] / $ret['quantity'] : 0;

                    PurchaseReturnDetail::create([
                        'return_id'  => $purchaseReturn->id,
                        'product_id' => $ret['product_id'],
                        'quantity'   => $ret['quantity'],
                        'rate'       => $rate,
                        'tax_amt'    => 0,
                        'total'      => $ret['amount'],
                        'reason'     => $ret['reason'] ?? null,
                    ]);

                    $totalReturnAmount += $ret['amount'];

                    // Update returned_quantity
                    $purchaseDetail = PurchaseDetails::where('pur_id', $purchaseReturn->purchase_id)
                        ->where('product_id', $ret['product_id'])
                        ->first();

                    if ($purchaseDetail) {
                        $purchaseDetail->returned_quantity = ($purchaseDetail->returned_quantity ?? 0) + $ret['quantity'];
                        $purchaseDetail->save();
                    }

                    // Decrease stock
                    $product = Product::find($ret['product_id']);

                    if ($product) {
                        $product->stock = max(0, $product->stock - $ret['quantity']);
                        $product->save();
                    }

                }

            }

            $purchaseReturn->total_return_amt = $totalReturnAmount;
            $purchaseReturn->save();

            DB::commit();

            return redirect()->route('purchase-return.index')->with('success', 'Purchase return updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }

    }

    public function destroy($id) {
        $return = PurchaseReturn::findOrFail($id);
        $return->delete();

        return response()->json(['success' => true, 'message' => 'Purchase return deleted successfully.']);
    }

}
