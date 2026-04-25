<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use App\ProductSubcategory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ProductSubcategoryController extends Controller {

    public function index() {
        $subcategories = ProductSubcategory::with('category')->get();

        return view('backend.subcategories.index', compact('subcategories'));
    }

    public function create() {
        $categories = ProductCategory::all();

        return view('backend.subcategories.create', compact('categories'));
    }

    public function store(Request $request) {
        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name'        => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        ProductSubcategory::create([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'status'      => $request->status ?? 1,
        ]);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory created successfully.');
    }

    public function edit($id) {
        $subcategory = ProductSubcategory::findOrFail($id);
        $categories  = ProductCategory::all();

        return view('backend.subcategories.edit', compact('subcategory', 'categories'));
    }

    public function update(Request $request, $id) {
        $subcategory = ProductSubcategory::findOrFail($id);

        $request->validate([
            'category_id' => 'required|exists:product_categories,id',
            'name'        => 'required|string|max:255',
        ]);

        $slug = Str::slug($request->name);

        $subcategory->update([
            'category_id' => $request->category_id,
            'name'        => $request->name,
            'slug'        => $slug,
            'description' => $request->description,
            'status'      => $request->status ?? $subcategory->status,
        ]);

        return redirect()->route('subcategories.index')->with('success', 'Subcategory updated successfully.');
    }

    public function destroy($id)
    {
        $subcategory = ProductSubcategory::findOrFail($id);
    
        // Delete products directly under this subcategory
        foreach ($subcategory->products as $product) {
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
            $product->delete();
        }
    
        // Delete all childcategories under this subcategory
        foreach ($subcategory->childcategories as $child) {
            // Delete products under each childcategory
            foreach ($child->products as $product) {
                if ($product->image) {
                    Storage::disk('public')->delete('products/' . $product->image);
                }
                $product->delete();
            }
            $child->delete();
        }
    
        // Finally delete the subcategory itself
        $subcategory->delete();
    
        return redirect()->route('subcategories.index')->with('success', 'Subcategory and all related childcategories and products deleted successfully.');
    }


    public function changeStatus($id) {
        $subcategory         = ProductSubcategory::findOrFail($id);
        $subcategory->status = !$subcategory->status;
        $subcategory->save();

        return redirect()->route('subcategories.index')->with('success', 'Status updated.');
    }
}
