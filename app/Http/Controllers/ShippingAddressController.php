<?php

namespace App\Http\Controllers;

use App\District;
use App\ShippingAddress;
use App\Upazila;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class ShippingAddressController extends Controller {
    public function create() {
        $districts = District::all();
        $upazilas  = Upazila::all();

        return view('backend.frontend.pages.shipping.create', compact('districts', 'upazilas'));
    }

    public function store(Request $request) {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return response()->json(['success' => false, 'message' => 'Unauthenticated.'], 401);
        }

        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email|max:255',
            'address'     => 'required|string|max:1000',
            'district_id' => 'required|integer|exists:districts,id',
            'upazila_id'  => 'required|integer|exists:upazilas,id',
            'union_id'    => 'nullable|integer',
            'postal_code' => 'nullable|string|max:20',
            'note'        => 'nullable|string|max:1000',
        ]);

        ShippingAddress::create([
            'customer_id' => $customer->id,
            'name'        => $request->name,
            'phone'       => $request->phone,
            'email'       => $request->email ?? '',
            'address'     => $request->address,
            'district_id' => $request->district_id,
            'upazila_id'  => $request->upazila_id,
            'union_id'    => $request->union_id ?? null,
            'postal_code' => $request->postal_code ?? '',
            'note'        => $request->note ?? '',
            'status'      => 1,
        ]);

        // For normal form POST redirect:

        return redirect()->route('customer.profile', ['tab' => 'address'])->with('success', 'Address added successfully.');

    }

    public function edit($id) {
        $address = ShippingAddress::where('customer_id', Auth::id())->find($id);

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found.'], 404);
        }

        return response()->json([
            'success' => true,
            'address' => $address,
        ]);
    }

    public function update(Request $request, $id) {
        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'email'       => 'nullable|email|max:255',
            'district_id' => 'required|integer|exists:districts,id',
            'upazila_id'  => 'required|integer|exists:upazilas,id',
            'union_id'    => 'nullable|integer',
            'postal_code' => 'nullable|string|max:20',
            'address'     => 'required|string|max:1000',
            'note'        => 'nullable|string|max:1000',
        ]);

        $address = ShippingAddress::where('id', $id)
            ->where('customer_id', auth('customer')->id())
            ->first();

        if (!$address) {
            Log::error("ShippingAddress not found for update. ID: {$id}, Customer: " . auth('customer')->id());

            return response()->json(['success' => false, 'message' => 'Address not found or unauthorized.'], 404);
        }

        Log::info("Updating ShippingAddress ID: {$id} for customer: " . auth('customer')->id());

        $address->update([
            'name'        => $request->name,
            'phone'       => $request->phone,
            'email'       => $request->email ?? '',
            'district_id' => $request->district_id,
            'upazila_id'  => $request->upazila_id,
            'union_id'    => $request->union_id ?? null,
            'postal_code' => $request->postal_code ?? '',
            'address'     => $request->address,
            'note'        => $request->note ?? '',
        ]);

        return redirect()->route('customer.profile', ['tab' => 'address'])->with('success', 'Address updated successfully.');
    }

    public function destroy($id) {
        $address = ShippingAddress::where('customer_id', Auth::id())->find($id);

        if (!$address) {
            return response()->json(['success' => false, 'message' => 'Address not found.'], 404);
        }

        $address->delete();

        return redirect()->route('customer.profile', ['tab' => 'address'])->with('success', 'Address deleted successfully.');
    }

}
