<?php

namespace App\Http\Controllers;

use App\Product;
use App\Purchase;
use App\PurchaseDetails;
use App\Supplier;
use App\SupplierLedger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Spatie\SimpleExcel\SimpleExcelReader;
use Yajra\DataTables\Facades\DataTables;

class PurchaseController extends Controller {

    public function index(Request $request) {

        if ($request->ajax()) {
            $query = Purchase::with('supplier');

            if ($request->filled('supplier_id')) {
                $query->where('supplier_id', $request->supplier_id);
            }

            if ($request->filled('from_date') && $request->filled('to_date')) {
                $query->whereBetween('purchase_date', [$request->from_date, $request->to_date]);
            }

            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function ($q) use ($search) {
                    $q->where('bill_no', 'like', "%{$search}%")
                        ->orWhereHas('supplier', function ($subQuery) use ($search) {
                            $subQuery->where('name', 'like', "%{$search}%");
                        });
                });
            }

            return DataTables::of($query)
                ->addIndexColumn()
                ->editColumn('purchase_date', function ($row) {
                    return date('d M, Y', strtotime($row->purchase_date));
                })
                ->addColumn('name', function ($row) {
                    return $row->supplier->name ?? '';
                })
                ->addColumn('total_amount', function ($row) {
                    return number_format($row->total_amount, 2);
                })
                ->addColumn('note', function ($row) {
                    return $row->note ?? '';
                })
                ->addColumn('option', function ($row) {
                    return '
                    <a href="' . route('purchase.show', $row->id) . '" class="btn btn-sm btn-info"><i class="mdi mdi-eye"></i></a>
                    <a href="' . route('purchase.edit', $row->id) . '">
                        <button class="btn btn-primary btn-sm" title="Update product">
                            <i class="mdi mdi-lead-pencil"></i>
                        </button>
                    </a>
                    <a href="' . route('purchase-return.create', $row->id) . '" class="btn btn-sm btn-warning">Return</a>
                    <form action="' . route('purchase.destroy', $row->id) . '" method="POST" style="display:inline-block;">
                        ' . csrf_field() . method_field('DELETE') . '
                        <button type="submit" class="btn btn-sm btn-danger" onclick="return confirm(\'Are you sure?\')"><i class="mdi mdi-trash-can"></i></button>
                    </form>';
                })
                ->rawColumns(['option'])
                ->make(true);
        }

        $suppliers = Supplier::all();

        return view('backend.purchase.index', compact('suppliers'));
    }

    public function create(Request $request) {
        // Clear previous session data
        $request->session()->forget(['puritem']);

        $suppliers         = Supplier::all();
        $products          = Product::with('unit')->get();
        $data['date']      = date('d-m-Y');
        $data['suppliers'] = $suppliers;
        $data['products']  = $products;

        return view('backend.purchase.create', $data);
    }

    public function store(Request $request) {
        $rules = [
            'purchase_date' => ['required', 'date'],
            'supplier_id'   => ['required', 'exists:suppliers,id'],
            'bill_no'       => ['required'],
            'bill_date'     => ['required', 'date'],
        ];

        $this->validate($request, $rules);

        if (!$request->session()->has('puritem') || empty($request->session()->get('puritem'))) {
            return redirect()->route('purchase.create')->with('error', 'No Product Selected!!');
        }

        $transport_cost = $request->transport_cost ?? 0;

        DB::beginTransaction();

        try {
            $puritems = $request->session()->get('puritem');

            $total_bill_amt = array_reduce($puritems, function ($res, $item) {
                return $res + ($item['quantity'] * $item['price']);
            }, 0);

            $total_amt = round($total_bill_amt + $transport_cost, 2);
            $paid_amt  = $request->paid_amt ?? 0;

            // Create main purchase
            $purchase = Purchase::create([
                'supplier_id'    => $request->supplier_id,
                'user_id'        => auth()->id(),
                'purchase_date'  => $request->purchase_date,
                'bill_no'        => $request->bill_no,
                'bill_date'      => $request->bill_date,
                'bill_amt'       => $total_bill_amt,
                'transport_cost' => $transport_cost,
                'total_bill'     => $total_amt,
                'paid_amt'       => $paid_amt,
                'due_amt'        => round($total_amt - $paid_amt, 2),
                'payment_method' => $request->payment_method ?? null,
                'tax_rate'       => $request->tax_rate ?? 0,
                'is_paid'        => $paid_amt >= $total_amt ? 1 : 0,
                'status'         => 1,
            ]);

            // Save purchase details and update stock
            foreach ($puritems as $item) {
                $tax_amt = ($item['tax_rate'] ?? 0) > 0
                ? ($item['quantity'] * $item['price']) * ($item['tax_rate'] / 100)
                : 0;

                PurchaseDetails::create([
                    'pur_id'            => $purchase->id,
                    'pur_date'          => $request->purchase_date,
                    'product_id'        => $item['product_id'],
                    'quantity'          => $item['quantity'],
                    'rate'              => $item['price'],
                    'tax_amt'           => round($tax_amt, 2),
                    'total'             => round(($item['quantity'] * $item['price']) + $tax_amt, 2),
                    'manufacturer_date' => $item['manufacturer_date'] ?? null,
                    'expire_date'       => $item['expire_date'] ?? null,
                ]);

                $product = Product::find($item['product_id']);

                if ($product) {
                    $product->stock          = ($product->stock ?? 0) + $item['quantity'];
                    $product->purchase_price = $item['price'];
                    $product->batch_no = $item['batch_no'];
                    $product->save();
                }

            }

            // Insert into Supplier Ledger: PURCHASE is a debit (supplier owes more)
            SupplierLedger::create([
                'supplier_id' => $request->supplier_id,
                'date'        => $request->purchase_date,
                'type'        => 'purchase',
                'ref_id'      => $purchase->id,
                'debit'       => $total_amt,
                'credit'      => 0,
                'note'        => 'Purchase Bill #' . $request->bill_no,
            ]);

            if ($paid_amt > 0) {
                SupplierLedger::create([
                    'supplier_id' => $request->supplier_id,
                    'date'        => $request->purchase_date,
                    'type'        => 'payment',
                    'ref_id'      => $purchase->id,
                    'debit'       => 0,
                    'credit'      => $paid_amt,
                    'note'        => 'Payment against Purchase #' . $request->bill_no,
                ]);
            }

            DB::commit();

            $request->session()->forget('puritem');

            return redirect()->route('purchase.create')->with('success', 'Purchase saved successfully.');
        } catch (\Exception $e) {
            DB::rollback();

            return redirect()->route('purchase.create')->with('error', 'Failed to save purchase. ' . $e->getMessage());
        }

    }

    public function show($id) {
        $purchase = Purchase::with(['supplier', 'items.product.unit'])
            ->findOrFail($id);

        return view('backend.purchase.show', compact('purchase'));
    }

    public function addPurRow(Request $request) {
        $purItems = session('puritem', []);

        if ($request->filled(['product_id', 'quantity', 'price'])) {
            $product_id = $request->product_id;

            $purItems[$product_id] = [
                'product_id'        => $product_id,
                'quantity'          => $request->quantity,
                'price'             => $request->price,
                'tax_rate'          => $request->tax_rate ?? 0,
                'total'             => $request->total ?? ($request->quantity * $request->price),
                'unit_name'         => $request->unit_name,
                'pname'             => $request->pname,
                'batch_no'          => $request->batch_no,
                'manufacturer_date' => $request->manufacturer_date,
                'expire_date'       => $request->expire_date,
            ];

            session(['puritem' => $purItems]);
        }

        return response(generatePurRows($purItems));
    }

    public function edit($id) {
        $purchase  = Purchase::with('purchaseDetails.product.unit')->findOrFail($id);
        $suppliers = Supplier::all();
        $products  = Product::all();

        return view('backend.purchase.edit', compact('purchase', 'suppliers', 'products'));
    }

    public function update(Request $request, $id) {

        $request->validate([
            'purchase_date' => 'required|date',
            'supplier_id'   => 'required|exists:suppliers,id',
            'bill_no'       => 'required',
            'bill_date'     => 'required|date',
        ]);

        try {
            DB::beginTransaction();

            $purchase = Purchase::findOrFail($id);

            // 1. Revert old stock quantities
            foreach ($purchase->purchaseDetails as $oldDetail) {
                $product = Product::find($oldDetail->product_id);
                if ($product) {
                    $product->stock = max(0, ($product->stock ?? 0) - $oldDetail->quantity);
                    $product->save();
                }

            }

            // 2. Delete old purchase details
            $purchase->purchaseDetails()->delete();

            // 3. Delete old ledger entries for this purchase
            SupplierLedger::where('ref_id', $purchase->id)
                ->whereIn('type', ['purchase', 'payment'])
                ->delete();

            // 4. Calculate new bill amount
            $bill_amt = 0;

            if ($request->has('purchase_details')) {

                foreach ($request->purchase_details as $detail) {
                    $qty  = $detail['quantity'] ?? 0;
                    $rate = $detail['rate'] ?? 0;
                    $tax  = $detail['tax_rate'] ?? 0;

                    $subtotal = $qty * $rate;
                    $tax_amt  = ($tax > 0) ? ($subtotal * $tax / 100) : 0;
                    $bill_amt += $subtotal + $tax_amt;
                }

            }

            $transport_cost = $request->transport_cost ?? 0;
            $total_bill     = round($bill_amt + $transport_cost, 2);
            $paid_amt       = $request->paid_amt ?? 0;

            // 5. Update purchase main record
            $purchase->update([
                'purchase_date'  => $request->purchase_date,
                'supplier_id'    => $request->supplier_id,
                'bill_no'        => $request->bill_no,
                'bill_date'      => $request->bill_date,
                'transport_cost' => $transport_cost,
                'bill_amt'       => round($bill_amt, 2),
                'total_bill'     => $total_bill,
                'paid_amt'       => $paid_amt,
                'due_amt'        => round($total_bill - $paid_amt, 2),
                'payment_method' => $request->payment_method ?? null,
                'tax_rate'       => $request->tax_rate ?? 0,
                'is_paid'        => $paid_amt >= $total_bill ? 1 : 0,
            ]);

            foreach ($request->purchase_details as $detail) {
                $tax_amt = ($detail['tax_rate'] ?? 0) > 0
                ? ($detail['quantity'] * $detail['rate']) * ($detail['tax_rate'] / 100)
                : 0;

                PurchaseDetails::create([
                    'pur_id'            => $purchase->id,
                    'pur_date'          => $request->purchase_date,
                    'product_id'        => $detail['product_id'],
                    'quantity'          => $detail['quantity'],
                    'rate'              => $detail['rate'],
                    'tax_amt'           => round($tax_amt, 2),
                    'total'             => round(($detail['quantity'] * $detail['rate']) + $tax_amt, 2),
                    'manufacturer_date' => $detail['manufacturer_date'] ?? null,
                    'expire_date'       => $detail['expire_date'] ?? null,
                ]);

                $product = Product::find($detail['product_id']);

                if ($product) {
                    $product->stock          = ($product->stock ?? 0) + $detail['quantity'];
                    $product->purchase_price = $detail['rate'];
                    $product->batch_no = $detail['batch_no'];
                    $product->save();
                }

            }

            SupplierLedger::create([
                'supplier_id' => $request->supplier_id,
                'date'        => $request->purchase_date,
                'type'        => 'purchase',
                'ref_id'      => $purchase->id,
                'debit'       => $total_bill,
                'credit'      => 0,
                'note'        => 'Purchase Bill #' . $request->bill_no,
            ]);

            if ($paid_amt > 0) {
                SupplierLedger::create([
                    'supplier_id' => $request->supplier_id,
                    'date'        => $request->purchase_date,
                    'type'        => 'payment',
                    'ref_id'      => $purchase->id,
                    'debit'       => 0,
                    'credit'      => $paid_amt,
                    'note'        => 'Payment against Purchase #' . $request->bill_no,
                ]);
            }

            DB::commit();

            return redirect()->route('purchase.index')->with('success', 'Purchase updated successfully.');
        } catch (\Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Update failed: ' . $e->getMessage());
        }

    }

    public function delPurRow(Request $request) {
        $product_id = $request->product_id;
        $purItems   = session('puritem', []);

        unset($purItems[$product_id]);

        session(['puritem' => $purItems]);

        return response(generatePurRows($purItems));
    }

    public function importExcel(Request $request) {

        $request->validate([
            'excel_file' => 'required|file|mimes:xlsx,xls,csv',
        ]);

        $path = $request->file('excel_file')->storeAs(
            'temp_imports',
            uniqid() . '.' . $request->file('excel_file')->getClientOriginalExtension()
        );

        $fullPath = storage_path('app/' . $path);

        try {
            $rows = SimpleExcelReader::create($fullPath)
                ->useHeaders([
                    'purchase_date',
                    'supplier_id',
                    'bill_no',
                    'bill_date',
                    'product_id',
                    'quantity',
                    'price',
                    'tax_rate',
                    'manufacturer_date',
                    'expire_date',
                ])
                ->getRows()
                ->toArray();

            if (empty($rows)) {
                Storage::delete($path);

                return redirect()->back()->with('error', 'Excel file contains no data.');
            }

            // Group rows by purchase info (to create one Purchase per group)
            $grouped = [];

            foreach ($rows as $row) {

                if (
                    empty($row['purchase_date']) ||
                    empty($row['supplier_id']) ||
                    empty($row['bill_no']) ||
                    empty($row['bill_date']) ||
                    empty($row['product_id']) ||
                    !is_numeric($row['quantity']) ||
                    !is_numeric($row['price'])
                ) {
                    continue;
                }

                $key = $row['supplier_id'] . '|' . $row['purchase_date'] . '|' . $row['bill_no'] . '|' . $row['bill_date'];

                $grouped[$key]['purchase_date'] = $row['purchase_date'];
                $grouped[$key]['supplier_id']   = $row['supplier_id'];
                $grouped[$key]['bill_no']       = $row['bill_no'];
                $grouped[$key]['bill_date']     = $row['bill_date'];
                $grouped[$key]['items'][]       = [
                    'product_id'        => $row['product_id'],
                    'quantity'          => (float) $row['quantity'],
                    'price'             => (float) $row['price'],
                    'tax_rate'          => isset($row['tax_rate']) ? (float) $row['tax_rate'] : 0,
                    'manufacturer_date' => $row['manufacturer_date'] ?? null,
                    'expire_date'       => $row['expire_date'] ?? null,
                ];
            }

            if (empty($grouped)) {
                Storage::delete($path);

                return redirect()->back()->with('error', 'No valid purchase data found in Excel.');
            }

            DB::beginTransaction();

            foreach ($grouped as $purchaseData) {

                $total_bill_amt = 0;

                foreach ($purchaseData['items'] as $item) {
                    $total_bill_amt += $item['quantity'] * $item['price'];
                }

                $transport_cost = 0;
                $total_amt      = round($total_bill_amt + $transport_cost, 2);
                $paid_amt       = 0;

                // Create purchase
                $purchase = Purchase::create([
                    'supplier_id'    => $purchaseData['supplier_id'],
                    'user_id'        => auth()->id(),
                    'purchase_date'  => $purchaseData['purchase_date'],
                    'bill_no'        => $purchaseData['bill_no'],
                    'bill_date'      => $purchaseData['bill_date'],
                    'bill_amt'       => $total_bill_amt,
                    'transport_cost' => $transport_cost,
                    'total_bill'     => $total_amt,
                    'paid_amt'       => $paid_amt,
                    'due_amt'        => $total_amt - $paid_amt,
                    'payment_method' => null,
                    'tax_rate'       => 0,
                    'is_paid'        => 0,
                    'status'         => 1,
                ]);

                foreach ($purchaseData['items'] as $item) {
                    $tax_amt = ($item['tax_rate'] > 0)
                    ? ($item['quantity'] * $item['price']) * ($item['tax_rate'] / 100)
                    : 0;

                    PurchaseDetails::create([
                        'pur_id'            => $purchase->id,
                        'pur_date'          => $purchaseData['purchase_date'],
                        'product_id'        => $item['product_id'],
                        'quantity'          => $item['quantity'],
                        'rate'              => $item['price'],
                        'tax_amt'           => round($tax_amt, 2),
                        'total'             => round(($item['quantity'] * $item['price']) + $tax_amt, 2),
                        'manufacturer_date' => $item['manufacturer_date'],
                        'expire_date'       => $item['expire_date'],
                    ]);
                }

            }

            DB::commit();
            Storage::delete($path);

            return redirect()->route('purchase.create')->with('success', 'Excel data imported and saved successfully.');

        } catch (\Exception $e) {
            DB::rollback();
            Storage::delete($path);

            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }

    }

    public function downloadDemoExcel() {
        $spreadsheet = new Spreadsheet();
        $sheet       = $spreadsheet->getActiveSheet();

        $headers = [
            'purchase_date',
            'supplier_id',
            'bill_no',
            'bill_date',
            'product_id',
            'quantity',
            'price',
            'tax_rate',
            'manufacturer_date',
            'expire_date',
        ];

        $columns = range('A', 'J');

        foreach ($headers as $i => $header) {
            $sheet->setCellValue($columns[$i] . '1', $header);
        }

        $writer = new Xlsx($spreadsheet);

        $fileName  = 'purchase_import_demo.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);

        return response()->download($temp_file, $fileName)->deleteFileAfterSend(true);
    }

    public function destroy($id) {
        try {

            $purchase = Purchase::findOrFail($id);

            $purchase->purchaseDetails()->delete();

            // Delete the purchase record
            $purchase->delete();

            return redirect()->route('purchase.index')->with('success', 'Purchase deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('purchase.index')->with('error', 'Delete failed: ' . $e->getMessage());
        }

    }

}
