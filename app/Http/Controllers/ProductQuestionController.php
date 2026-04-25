<?php

namespace App\Http\Controllers;

use App\Product;
use App\ProductQuestion;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class ProductQuestionController extends Controller
{
    /**
     * Display a listing of the questions for a product.
     */
    public function index(Product $product)
    {
        $questions = $product->questions()
            ->with(['customer'])
            ->where('status', 1)
            ->latest()
            ->paginate(5);

        return response()->json($questions);
    }

    /**
     * Store a newly created customer question.
     */
    public function store(Request $request, Product $product)
    {
        // Customer must be logged in
        if (!Auth::guard('customer')->check()) {
            return redirect()->back()->with('error', 'Please login to ask a question.');
        }

        $validator = Validator::make($request->all(), [
            'question' => 'required|string|min:5|max:500',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            ProductQuestion::create([
                'product_id'   => $product->id,
                'customer_id'  => Auth::guard('customer')->id(),
                'question'     => $request->question,
                'status'       => 1,
            ]);

            return redirect()->back()->with('success', 'Your question has been submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Question creation error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to submit your question.');
        }
    }

    /**
     * Vendor answers a question.
     */
    public function answer(Request $request, ProductQuestion $question)
    {
        // Vendor login check
        if (!Auth::guard('vendor')->check()) {
            return redirect()->back()->with('error', 'Only vendors can answer questions.');
        }

        $validator = Validator::make($request->all(), [
            'answer' => 'required|string|min:5|max:1000',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput()
                ->with('error', 'Please fix the errors below.');
        }

        try {
            $vendor = Auth::guard('vendor')->user();

            $question->update([
                'answer'       => $request->answer,
                'answered_by'  => $vendor->name,
                'answered_at'  => now(),
            ]);

            return redirect()->back()->with('success', 'Answer submitted successfully!');
        } catch (\Exception $e) {
            \Log::error('Answer submission error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to submit answer.');
        }
    }

    /**
     * Delete a question (customer or admin or vendor).
     */
    public function destroy(ProductQuestion $question)
    {
        $customerId = Auth::guard('customer')->id();
        $vendorId   = Auth::guard('vendor')->id();
        $admin      = Auth::user();

        $isCustomerOwner = ($customerId && $customerId == $question->customer_id);
        $isVendor        = $vendorId !== null;
        $isAdmin         = ($admin && $admin->is_admin);

        if (!$isCustomerOwner && !$isVendor && !$isAdmin) {
            return redirect()->back()->with('error', 'You are not authorized to delete this question.');
        }

        try {
            $question->update(['status' => 0]);

            return redirect()->back()->with('success', 'Question deleted successfully!');
        } catch (\Exception $e) {
            \Log::error('Question deletion error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Failed to delete question.');
        }
    }

    /**
     * AJAX vendor answer version
     */
    public function answerAjax(Request $request, ProductQuestion $question)
    {
        if (!Auth::guard('vendor')->check()) {
            return response()->json(['error' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'answer' => 'required|string|min:5|max:1000',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        try {
            $vendor = Auth::guard('vendor')->user();

            $question->update([
                'answer'       => $request->answer,
                'answered_by'  => $vendor->name,
                'answered_at'  => now(),
            ]);

            return response()->json([
                'success'       => true,
                'message'       => 'Answer submitted successfully!',
                'answer'        => $question->answer,
                'answered_by'   => $vendor->name,
                'answered_at'   => $question->answered_at->diffForHumans(),
            ]);
        } catch (\Exception $e) {
            \Log::error('AJAX Answer submission error: ' . $e->getMessage());
            return response()->json(['error' => 'Failed to submit answer'], 500);
        }
    }
}
