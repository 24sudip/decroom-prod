<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Supplier;
use App\SupplierLedger;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class SupplierController extends Controller {
    public function index(Request $request) {

        if ($request->ajax()) {
            $data = Supplier::query();

            return DataTables::of($data)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $url = $row->image
                    ? asset('storage/suppliers/' . $row->image)
                    : asset('storage/logo.png');

                    return '<img src="' . $url . '" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('option', function ($row) {
                    $editUrl   = route('supplier.edit', $row->id);
                    $ledgerUrl = route('supplier.ledger', $row->id);

                    return '
                    <a href="' . $editUrl . '" class="btn btn-sm btn-primary me-1" title="Edit">
                        <i class="mdi mdi-pencil"></i>
                    </a>
                    <a href="' . $ledgerUrl . '" class="btn btn-sm btn-info me-1" title="Ledger">
                        <i class="mdi mdi-file-document"></i>
                    </a>
                    <button class="btn btn-sm btn-danger delete-supplier" data-id="' . $row->id . '" title="Delete">
                        <i class="mdi mdi-trash-can"></i>
                    </button>
                ';
                })
                ->rawColumns(['image', 'option'])
                ->make(true);
        }

        return view('backend.supplier.supplier');
    }

    public function create() {
        return view('backend.supplier.supplier-details');
    }

    public function store(Request $request) {
        $request->validate([
            'name'            => 'required|string|max:200',
            'phone'           => 'required|string|max:20|unique:suppliers,phone',
            'email'           => 'nullable|email|max:200|unique:suppliers,email',
            'address'         => 'nullable|string',
            'tread_name'      => 'nullable|string|max:200',
            'tread_no'        => 'nullable|string|max:200',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'opening_balance' => 'nullable|numeric',
        ]);

        DB::beginTransaction();

        try {
            $supplier                  = new Supplier();
            $supplier->name            = $request->name;
            $supplier->phone           = $request->phone;
            $supplier->email           = $request->email;
            $supplier->address         = $request->address;
            $supplier->tread_name      = $request->tread_name;
            $supplier->tread_no        = $request->tread_no;
            $supplier->opening_balance = $request->opening_balance ?? 0;
            $supplier->main_balance    = $request->opening_balance ?? 0;
            $supplier->due_balance     = 0;
            $supplier->is_active       = 1;

            if ($request->hasFile('image')) {
                $file      = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $imageName = time() . '.' . $extension;
                $file->move(public_path('storage/suppliers/'), $imageName);
                $supplier->image = $imageName;
            }

            $supplier->save();

            DB::commit();

            return redirect()->route('supplier.index')->with('success', 'Supplier created successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->route('supplier.index')->with('error', 'Something went wrong! ' . $e->getMessage());
        }

    }

    public function show($id) {
        abort(404); // or redirect somewhere
    }

    public function edit(Supplier $supplier) {
        return view('backend.supplier.supplier-details', compact('supplier'));
    }

    public function update(Request $request, Supplier $supplier) {
        $request->merge([
            'is_active' => $request->has('is_active'),
        ]);

        $validated = $request->validate([
            'name'            => 'required|string|max:200',
            'phone'           => 'required|string|max:20|unique:suppliers,phone,' . $supplier->id,
            'email'           => 'nullable|email|max:200|unique:suppliers,email,' . $supplier->id,
            'address'         => 'nullable|string',
            'tread_name'      => 'nullable|string|max:200',
            'tread_no'        => 'nullable|string|max:200',
            'image'           => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'opening_balance' => 'nullable|numeric',
            'is_active'       => 'nullable|boolean',
        ]);

        DB::beginTransaction();

        try {

            if ($request->hasFile('image')) {
                $file      = $request->file('image');
                $extension = $file->getClientOriginalExtension();
                $imageName = time() . '.' . $extension;
                $file->move(public_path('storage/suppliers/'), $imageName);
                $validated['image'] = $imageName;

// Optionally delete old image file
                if ($supplier->image) {
                    $oldImagePath = public_path('storage/suppliers/' . $supplier->image);
                    if (file_exists($oldImagePath)) {
                        @unlink($oldImagePath);
                    }

                }

            }

            $validated['opening_balance'] = $validated['opening_balance'] ?? $supplier->opening_balance;

            $supplier->update($validated);

            DB::commit();

            return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully!');
        } catch (Exception $e) {
            DB::rollBack();

            return redirect()->back()->with('error', 'Failed to update supplier: ' . $e->getMessage());
        }

    }

    public function destroy(Request $request) {
        try {
            $supplier = Supplier::find($request->id);

            if (!$supplier) {
                return response()->json([
                    'isSuccess' => false,
                    'message'   => 'Supplier not found.',
                ], 404);
            }

            if ($supplier->image) {
                $imagePath = public_path('storage/suppliers/' . $supplier->image);
                if (file_exists($imagePath)) {
                    @unlink($imagePath);
                }

            }

            $supplier->delete();

            return response()->json([
                'isSuccess' => true,
                'message'   => 'Supplier deleted successfully.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'isSuccess' => false,
                'message'   => 'Something went wrong! ' . $e->getMessage(),
            ], 500);
        }

    }

    public function ledger($id) {

        $supplier = Supplier::findOrFail($id);
        $ledgers  = SupplierLedger::where('supplier_id', $id)
            ->orderBy('date')
            ->orderBy('id')
            ->get();

        $balance = 0;
        foreach ($ledgers as $ledger) {
            $balance += $ledger->credit - $ledger->debit;
            $ledger->running_balance = $balance;
        }

        return view('backend.supplier.ledger', compact('supplier', 'ledgers'));
    }

    public function allLedgers(Request $request) {

        $query = SupplierLedger::with('supplier')->orderBy('date', 'desc');

        if ($request->filled('supplier_id')) {
            $query->where('supplier_id', $request->supplier_id);
        }

        if ($request->filled('from_date')) {
            $query->whereDate('date', '>=', $request->from_date);
        }

        if ($request->filled('to_date')) {
            $query->whereDate('date', '<=', $request->to_date);
        }

        $ledgers = $query->paginate(50);

        return view('backend.supplier.ledger_list', compact('ledgers'));
    }

}
