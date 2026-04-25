<?php

namespace App\Http\Controllers;

use App\Coupon;
use Illuminate\Http\Request;

class CouponController extends Controller {
    public function index() {
        $coupons = Coupon::latest()->paginate(10);

        return view('backend.coupons.index', compact('coupons'));
    }

    public function create() {
        return view('backend.coupons.create');
    }

    public function store(Request $request) {
        $request->validate([
            'code'         => 'required|unique:coupons,code',
            'type'         => 'required|in:fixed,percent',
            'amount'       => 'required|numeric|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'status'       => 'required|boolean',
        ]);

        Coupon::create($request->all());

        return redirect()->route('coupons.index')->with('success', 'Coupon created successfully.');
    }

    public function edit(Coupon $coupon) {
        return view('backend.coupons.edit', compact('coupon'));
    }

    public function update(Request $request, Coupon $coupon) {
        $request->validate([
            'code'         => 'required|unique:coupons,code,' . $coupon->id,
            'type'         => 'required|in:fixed,percent',
            'amount'       => 'required|numeric|min:1',
            'min_purchase' => 'nullable|numeric|min:0',
            'start_date'   => 'required|date',
            'end_date'     => 'required|date|after_or_equal:start_date',
            'status'       => 'required|boolean',
        ]);

        $coupon->update($request->all());

        return redirect()->route('coupons.index')->with('success', 'Coupon updated successfully.');
    }

    public function destroy(Coupon $coupon) {
        $coupon->delete();

        return redirect()->route('coupons.index')->with('success', 'Coupon deleted.');
    }
}
