<?php

namespace App\Http\Controllers;

use App\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Storage;

class ProductCategoryController extends Controller {
    private function user() {
        return Auth::guard('admin')->user();
    }

    public function index(Request $request) {
        // Gate::authorize('index-category');

        $user = $this->user();
        $role = optional($user->role)->role_name;

        $productCategories = ProductCategory::orderBy('name', 'desc')->get();

        if ($request->ajax()) {
            return DataTables::of($productCategories)
                ->addIndexColumn()
                ->addColumn('image', function ($row) {
                    $imageUrl = $row->image
                    ? asset('public/storage/categories/' . $row->image)
                    : asset('assets/no-image.png');

                    return '<img src="' . $imageUrl . '" alt="category-image" width="60" height="60" class="img-thumbnail"/>';
                })
                ->addColumn('description', function ($row) {
                    return Str::limit(strip_tags($row->description), 150);
                })
                ->addColumn('is_home', function ($row) {
                    $btnClass = $row->is_home ? 'btn-success' : 'btn-secondary';
                    $btnText  = $row->is_home ? 'Active' : 'Inactive';

                    return '<button class="btn btn-sm toggle-home-btn ' . $btnClass . '" data-id="' . $row->id . '">' . $btnText . '</button>';
                })
                ->addColumn('option', function ($row) use ($role) {
                    $editBtn = '<a href="' . route('category.edit', $row->id) . '">
                                    <button class="btn btn-primary btn-sm btn-rounded" title="Update category">
                                        <i class="mdi mdi-lead-pencil"></i>
                                    </button>
                                </a>';
                
                    $deleteBtn = '<button class="btn btn-danger btn-sm btn-rounded" id="delete-category" data-id="' . $row->id . '" title="Delete category">
                                        <i class="mdi mdi-delete"></i>
                                  </button>';
                
                    return $editBtn . ' ' . $deleteBtn;
                })
                ->rawColumns(['image', 'option', 'is_home'])
                ->make(true);
        }

        return view('backend.category.category', compact('user', 'role'));
    }

    public function create() {
        $category = null;

        return view('backend.category.category-details', compact('category'));
    }

    public function store(Request $request) {
        $request->validate([
            'name'        => 'required|string|max:255|unique:product_categories,name',
            'commission'   => 'nullable|numeric',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->name);

        $category              = new ProductCategory();
        $category->name        = $request->name;
        $category->commission   = $request->commission ?? 0;
        $category->slug        = $slug;
        $category->description = $request->description;
        $category->is_home     = $request->has('is_home') ? 1 : 0;

        if ($request->hasFile('image')) {
            $imageName = Str::slug($request->name) . '-' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/categories/'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return redirect()->route('category.index')->with('success', 'Category created successfully');
    }

    public function edit($id) {
        $category = ProductCategory::findOrFail($id);

        return view('backend.category.category-details', compact('category'));
    }

    public function update(Request $request, $id) {
        $category = ProductCategory::findOrFail($id);

        $request->validate([
            'name'        => 'required|string|max:255|unique:product_categories,name,' . $category->id,
            'commission'   => 'nullable|numeric',
            'description' => 'nullable|string',
            'image'       => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $slug = Str::slug($request->name);

        $category->name        = $request->name;
        $category->commission   = $request->commission;
        $category->slug        = $slug;
        $category->description = $request->description;
        $category->is_home     = $request->has('is_home') ? 1 : 0;

        if ($request->hasFile('image')) {
            // Delete old image if exists
            $oldPath = public_path('storage/categories/' . $category->image);

            if (File::exists($oldPath)) {
                File::delete($oldPath);
            }

            $imageName = Str::slug($request->name) . '-' . time() . '.' . $request->image->extension();
            $request->image->move(public_path('storage/categories/'), $imageName);
            $category->image = $imageName;
        }

        $category->save();

        return redirect()->route('category.index')->with('success', 'Category updated successfully');
    }

    
    public function destroy($id)
    {
        // Gate::authorize('delete-category');
    
        $category = ProductCategory::findOrFail($id);
    
        // Delete all subcategories
        $subcategories = $category->subcategories;
        foreach ($subcategories as $sub) {

            foreach ($sub->childcategories as $child) {

                foreach ($child->products as $product) {
                    if ($product->image) {
                        Storage::disk('public')->delete('products/' . $product->image);
                    }
                    $product->delete();
                }
                $child->delete();
            }
    
            // Delete products directly under subcategory
            foreach ($sub->products as $product) {
                if ($product->image) {
                    Storage::disk('public')->delete('products/' . $product->image);
                }
                $product->delete();
            }
    
            $sub->delete();
        }
    
        // Delete products directly under category
        foreach ($category->products as $product) {
            if ($product->image) {
                Storage::disk('public')->delete('products/' . $product->image);
            }
            $product->delete();
        }
    
        // Delete category image
        if ($category->image) {
            Storage::disk('public')->delete('categories/' . $category->image);
        }
    
        // Finally delete category
        $category->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'Category and all related subcategories, childcategories, and products deleted successfully!'
        ]);
    }


    public function toggleHome(Request $request) {
        $category          = ProductCategory::findOrFail($request->id);
        $category->is_home = !$category->is_home;
        $category->save();

        return response()->json([
            'success' => true,
            'message' => 'Home page visibility updated.',
            'status'  => $category->is_home ? 'Active' : 'Inactive',
        ]);
    }

}
