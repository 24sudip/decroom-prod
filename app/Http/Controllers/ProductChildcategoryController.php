<?php

namespace App\Http\Controllers;

use App\ProductChildcategory;
use App\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductChildcategoryController extends Controller {

    public function index() {
        $childcategories = ProductChildcategory::with('subcategory')->get();

        return view('backend.childcategories.index', compact('childcategories'));
    }

    public function create() {
        $subcategories = ProductSubcategory::all();

        return view('backend.childcategories.create', compact('subcategories'));
    }

    public function store(Request $request) {
        $request->validate([
            'subcategory_id' => 'required|exists:product_subcategories,id',
            'name'           => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        ProductChildcategory::create([
            'subcategory_id' => $request->subcategory_id,
            'name'           => $request->name,
            'slug'           => $slug,
            'description'    => $request->description,
            'status'         => $request->status ?? 1,
        ]);

        return redirect()->route('childcategories.index')->with('success', 'Child category created successfully.');
    }

    public function edit($id) {
        $childcategory = ProductChildcategory::findOrFail($id);
        $subcategories = ProductSubcategory::all();

        return view('backend.childcategories.edit', compact('childcategory', 'subcategories'));
    }

    public function update(Request $request, $id) {
        $childcategory = ProductChildcategory::findOrFail($id);

        $request->validate([
            'subcategory_id' => 'required|exists:product_subcategories,id',
            'name'           => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        $childcategory->update([
            'subcategory_id' => $request->subcategory_id,
            'name'           => $request->name,
            'slug'           => $slug,
            'description'    => $request->description,
            'status'         => $request->status ?? $childcategory->status,
        ]);

        return redirect()->route('childcategories.index')->with('success', 'Child category updated successfully.');
    }

    public function destroy($id)
    {
        $childcategory = ProductChildcategory::findOrFail($id);
    
        // Delete all products under this childcategory
        foreach ($childcategory->products as $product) {
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
            $product->delete();
        }
    
        // Delete the childcategory itself
        $childcategory->delete();
    
        return redirect()->route('childcategories.index')
            ->with('success', 'Child category and all related products deleted successfully.');
    }


    public function changeStatus($id) {
        $childcategory         = ProductChildcategory::findOrFail($id);
        $childcategory->status = !$childcategory->status;
        $childcategory->save();

        return redirect()->route('childcategories.index')->with('success', 'Status updated.');
    }
}
