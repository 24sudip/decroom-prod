<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Prescription;
use App\PrescriptionImage;
use Auth;
use Illuminate\Http\Request;

class PrescriptionController extends Controller {

    public function create() {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Please, Login First.');
        }

        return view('frontend.pages.prescription.upload_prescription');
    }

    public function upload(Request $request) {
        $request->validate([
            'name'        => 'required|string|max:255',
            'phone'       => 'required|string|max:20',
            'description' => 'nullable|string',
            'files.*'     => 'required|file|mimes:jpg,jpeg,png,pdf|max:2048',
        ]);

        $prescription = Prescription::create([
            'customer_id' => auth('customer')->id(),
            'name'        => $request->name,
            'phone'       => $request->phone,
            'description' => $request->description,
        ]);

        foreach ($request->file('files') as $file) {
            $ext       = $file->getClientOriginalExtension();
            $imageName = time() . '_' . uniqid() . '.' . $ext;
            $file->move(public_path('storage/prescriptions/'), $imageName);

            PrescriptionImage::create([
                'prescription_id' => $prescription->id,
                'file_path'       => 'storage/prescriptions/' . $imageName,
            ]);
        }

        return redirect()->back()->with('success', 'Prescription uploaded successfully.');
    }

    public function download($id) {
        $image = PrescriptionImage::findOrFail($id);

        if ($image->prescription->customer_id != auth('customer')->id()) {
            abort(403, 'Unauthorized');
        }

        $filePath = public_path($image->file_path);

        if (!file_exists($filePath)) {
            return redirect()->back()->with('error', 'File not found.');
        }

        return response()->download($filePath);
    }

}
