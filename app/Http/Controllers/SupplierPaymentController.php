<?php

namespace App\Http\Controllers;

use App\Supplier;
use App\SupplierLedger;
use App\SupplierPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierPaymentController extends Controller {

    public function index() {
        $payments = SupplierPayment::with('supplier')->latest()->paginate(50);

        return view('backend.supplier.payments.index', compact('payments'));
    }

    public function create() {
        $suppliers = Supplier::all();

        return view('backend.supplier.payments.create', compact('suppliers'));
    }

    public function store(Request $request) {
        $request->validate([
            'supplier_id'    => 'required|exists:suppliers,id',
            'payment_date'   => 'required|date',
            'amount'         => 'required|numeric|min:0.01',
            'payment_method' => 'nullable|string|max:100',
            'note'           => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($request) {
            // Save payment
            $payment = SupplierPayment::create($request->only(['supplier_id', 'payment_date', 'amount', 'payment_method', 'note']));

            // Log in supplier ledger
            SupplierLedger::create([
                'supplier_id' => $request->supplier_id,
                'date'        => $request->payment_date,
                'type'        => 'payment',
                'note'        => $request->note ?? 'Payment made',
                'debit'       => 0,
                'credit'      => $request->amount,
            ]);
        });

        return redirect()->route('supplier_payment.index')->with('success', 'Payment recorded successfully.');

    }

}
