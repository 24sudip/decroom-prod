<?php

namespace App\Http\Controllers;

use App\Generic;
use App\Product;
use App\ProductBrand;
use App\ProductCategory;
use App\ProductChildcategory;
use App\ProductSubcategory;
use App\ProductVariant;
use App\Unite;
use App\Vendor;
use App\ServiceCategory;
use App\User;
use App\ProductImage;
use App\Service;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Str;
use Spatie\SimpleExcel\SimpleExcelReader;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller {

    private function user() {
        return Auth::guard('admin')->user();
    }

    public function index(Request $request)
    {
        if (!auth()->guard('admin')->check()) {
            return redirect()->route('admin.login');
        }

        $user = auth()->guard('admin')->user();
        $role = optional($user->role)->slug;

        $categories = ProductCategory::all();
        $brands = ProductBrand::all();
        $vendors = User::where('role_id', 2)->get();

        // Build the query with better filtering
        $query = Product::with(['category', 'brand', 'vendor'])
            ->where('status', 1)
            ->when($request->category_id, function($q) use ($request) {
                return $q->where('category_id', $request->category_id);
            })
            ->when($request->brand_id, function($q) use ($request) {
                return $q->where('brand_id', $request->brand_id);
            })
            ->when($request->vendor_id, function($q) use ($request) {
                return $q->where('vendor_id', $request->vendor_id);
            })
            ->when($request->search, function($q) use ($request) {
                return $q->where('name', 'like', '%' . $request->search . '%')
                ->orWhere('description', 'like', '%' . $request->search . '%');
            })
            ->when($request->qc_status !== null, function($q) use ($request) {
                return $q->where('qc_status', $request->qc_status);
            })
            ->when($request->reject_status !== null, function($q) use ($request) {
                return $q->where('reject_status', $request->reject_status);
            });

        // Get total count for debugging
        $totalProducts = $query->count();

        // Paginate (30 per page)
        $products = $query->latest()->paginate(30);

        // dd($products);

        // Debug output
        \Log::info('Product Index Loaded', [
            'total_products' => $totalProducts,
            'paginated_count' => $products->count(),
            'filters' => $request->all(),
            'results' => $products->total()
        ]);

        return view('backend.product.product', compact(
            'user', 'role', 'categories', 'brands', 'products', 'vendors'
        ));
    }

    public function updateStatus(Request $request)
    {
        \Log::info('Update Status Request:', $request->all());

        $request->validate([
            'id' => 'required|exists:products,id',
            'type' => 'required|in:qc,reject',
            'value' => 'required|in:0,1'
        ]);

        try {
            $product = Product::findOrFail($request->id);

            if ($request->type === 'qc') {
                $product->qc_status = $request->value;
                \Log::info("Updated QC status for product {$product->id} to {$request->value}");
            } elseif ($request->type === 'reject') {
                $product->reject_status = $request->value;
                \Log::info("Updated reject status for product {$product->id} to {$request->value}");
            }

            $product->save();

            return response()->json([
                'success' => true,
                'message' => 'Status updated successfully'
            ]);

        } catch (\Exception $e) {
            \Log::error('Update Status Error:', [
                'error' => $e->getMessage(),
                'request' => $request->all()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Failed to update status: ' . $e->getMessage()
            ], 500);
        }
    }

    public function viewDetails($id) {
        $product = Product::with([
            'category',
            'brand',
            'vendor.vendorDetails',
            'images',
            'variants',
            'orderItems',
            'reviews',
            'questions'
        ])->findOrFail($id);

        return view('backend.product.view', compact('product'));
    }

    public function create() {
        // Gate::authorize('product-create');

        $users      = User::where('role_id', 2)->where('is_active', 1)->get();
        $units      = Unite::all();
        $categories = ProductCategory::where('status', 1)->get();
        $brands = ProductBrand::where('is_deleted', 0)->get();

        return view('backend.product.product-details', compact('users','categories', 'brands', 'units'));
    }

    public function store(Request $request)
    {
        // dd($request->all());
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'vendor_id' => 'required|exists:users,id',
            'brand_id' => 'required|exists:product_brands,id',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:200|unique:products,sku',
            'description' => 'required|string',
            'highlight' => 'nullable|string',
            'images' => 'required|array|min:1',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp',
            'promotion_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'video' => 'nullable|mimes:mp4|max:102400',
            'weight' => 'required|numeric|min:0.001|max:300',
            'length' => 'required|numeric|min:0.01|max:300',
            'width' => 'required|numeric|min:0.01|max:300',
            'height' => 'required|numeric|min:0.01|max:300',
            'dangerous_goods' => 'nullable|in:none,contains',
            'availability' => 'nullable',
            'variants' => 'nullable|array',
            'variants.*.name' => 'nullable|string|max:255',
            'variants.*.value' => 'nullable|string|max:255',
            'variants.*.additional_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Create uploads directory if it doesn't exist
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Handle promotion image
            $promotionImageName = null;
            if ($request->hasFile('promotion_image')) {
                $promotionImage = $request->file('promotion_image');
                $promotionImageName = time() . '_promotion.' . $promotionImage->getClientOriginalExtension();
                $promotionImage->move($uploadPath, $promotionImageName);
            }

            // Handle video
            $videoName = null;
            if ($request->hasFile('video')) {
                $video = $request->file('video');
                $videoName = time() . '_video.' . $video->getClientOriginalExtension();
                $video->move($uploadPath, $videoName);
            }

            $cat = ProductCategory::find($validated['category_id']);
            if($cat != null){
                $adminCommision = $cat->commission;
            }else{
                $adminCommision = 0;
            }

            $product = Product::create([
                'vendor_id' => $validated['vendor_id'],
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'brand_id' => $validated['brand_id'],
                'price' => $validated['price'],
                'special_price' => $validated['special_price'] ?? null,
                'stock' => $validated['stock'],
                'sku' => $validated['sku'],
                'description' => $validated['description'],
                'highlight' => $validated['highlight'] ?? null,
                'promotion_image' => $promotionImageName ? 'uploads/products/' . $promotionImageName : null,
                'video_path' => $videoName ? 'uploads/products/' . $videoName : null,
                'youtube_url' => $request->youtube_url ?? null,
                'free_items' => $request->free_items ?? 0,
                'weight' => $validated['weight'],
                'length' => $validated['length'],
                'width' => $validated['width'],
                'height' => $validated['height'],
                'admin_commission' => $adminCommision,
                'dangerous_goods' => $validated['dangerous_goods'],
                'availability' => $request->has('availability') ? 1 : 0,
                'qc_status' => 0,
                'reject_status' => 0,
                'status' => 1,
                'is_deleted' => 0,
            ]);

            // Handle product images
            if ($request->hasFile('images')) {
                foreach ($request->file('images') as $index => $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $imageName);

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'uploads/products/' . $imageName,
                        'is_primary' => $index === 0,
                    ]);
                }
            }

            // Handle variants - only create if name and value are provided
            if ($request->has('variants')) {
                foreach ($request->variants as $variantData) {
                    // Only create variant if both name and value are provided and not empty
                    if (!empty($variantData['name']) && !empty($variantData['value'])) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $variantData['name'],
                            'value' => $variantData['value'],
                            'additional_price' => $variantData['additional_price'] ?? 0,
                            'stock' => $variantData['stock'] ?? 0,
                        ]);
                    }
                }
            }
            DB::commit();

            return redirect()->back()
            ->with('success', 'Product added successfully and is pending approval!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product store error: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Error adding product: ' . $e->getMessage());
        }
    }

    public function edit(Product $product) {
        // Gate::authorize('product-update');
        $users = User::where('role_id', 2)->where('is_active', 1)->get();

        $categories = ProductCategory::where('status', 1)->get();
        $brands = ProductBrand::where('is_deleted', 0)->get();

        // Eager load relationships to avoid N+1 queries
        $product->load(['images', 'variants']);

        // Add variant index for dynamic variants
        $variantIndex = $product->variants->count();
        // dd($product);
        return view('backend.product.product-details', compact('product', 'users', 'categories', 'brands', 'variantIndex'));
    }

    public function update(Request $request, Product $product) {
        // Validate the request
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'vendor_id' => 'required|exists:users,id',
            'brand_id' => 'required|exists:product_brands,id',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:200|unique:products,sku,' . $product->id,
            'description' => 'required|string',
            'highlight' => 'nullable|string',
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp',
            'promotion_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp',
            'video' => 'nullable|mimes:mp4|max:102400',
            'weight' => 'required|numeric|min:0.001|max:300',
            'length' => 'required|numeric|min:0.01|max:300',
            'width' => 'required|numeric|min:0.01|max:300',
            'height' => 'required|numeric|min:0.01|max:300',
            'dangerous_goods' => 'required|in:none,contains',
            'availability' => 'nullable',
            'existing_images' => 'nullable|array',
            'existing_images.*' => 'integer|exists:product_images,id',
            'primary_image' => 'nullable|integer|exists:product_images,id',
            'variants' => 'nullable|array',
            'variants.*.name' => 'nullable|string|max:255',
            'variants.*.value' => 'nullable|string|max:255',
            'variants.*.additional_price' => 'nullable|numeric|min:0',
            'variants.*.stock' => 'nullable|integer|min:0',
        ]);

        DB::beginTransaction();
        try {
            // Get the product with relationships
            $product = Product::with(['images', 'variants'])->findOrFail($product->id);

            // Create uploads directory if it doesn't exist
            $uploadPath = public_path('uploads/products');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            // Handle promotion image update
            $promotionImageName = $product->promotion_image;
            if ($request->hasFile('promotion_image')) {
                // Delete old promotion image if exists
                if ($promotionImageName && file_exists(public_path($promotionImageName))) {
                    unlink(public_path($promotionImageName));
                }

                $promotionImage = $request->file('promotion_image');
                $promotionImageName = time() . '_promotion.' . $promotionImage->getClientOriginalExtension();
                $promotionImage->move($uploadPath, $promotionImageName);
                $promotionImageName = 'uploads/products/' . $promotionImageName;
            }

            // Handle video update
            $videoName = $product->video_path;
            if ($request->hasFile('video')) {
                // Delete old video if exists
                if ($videoName && file_exists(public_path($videoName))) {
                    unlink(public_path($videoName));
                }

                $video = $request->file('video');
                $videoName = time() . '_video.' . $video->getClientOriginalExtension();
                $video->move($uploadPath, $videoName);
                $videoName = 'uploads/products/' . $videoName;
            }

            $cat = ProductCategory::find($validated['category_id']);
            if($cat != null){
                $adminCommision = $cat->commission;
            }else{
                $adminCommision = 0;
            }

            // Update product basic information
            $product->update([
                'name' => $validated['name'],
                'category_id' => $validated['category_id'],
                'vendor_id' => $validated['vendor_id'],
                'brand_id' => $validated['brand_id'],
                'price' => $validated['price'],
                'admin_commission' => $adminCommision,
                'special_price' => $validated['special_price'] ?? null,
                'stock' => $validated['stock'],
                'sku' => $validated['sku'],
                'description' => $validated['description'],
                'highlight' => $validated['highlight'] ?? null,
                'promotion_image' => $promotionImageName,
                'video_path' => $videoName,
                'youtube_url' => $request->youtube_url ?? null,
                'free_items' => $request->free_items ?? 0,
                'weight' => $validated['weight'],
                'length' => $validated['length'],
                'width' => $validated['width'],
                'height' => $validated['height'],
                'dangerous_goods' => $validated['dangerous_goods'],
                'availability' => $request->has('availability') ? 1 : 0,
            ]);

            // Handle existing images deletion
            $existingImagesCount = $product->images()->count();

            if ($existingImagesCount > 0) {
                if ($request->has('existing_images') && !empty($request->existing_images)) {
                    $imagesToKeep = $request->existing_images;
                    $imagesToDelete = $product->images()->whereNotIn('id', $imagesToKeep)->get();

                    foreach ($imagesToDelete as $image) {
                        // Delete physical file
                        if ($image->image_path && file_exists(public_path($image->image_path))) {
                            unlink(public_path($image->image_path));
                        }
                        $image->delete();
                    }
                } else {
                    // If no existing images selected, delete all images
                    $imagesToDelete = $product->images()->get();
                    foreach ($imagesToDelete as $image) {
                        if ($image->image_path && file_exists(public_path($image->image_path))) {
                            unlink(public_path($image->image_path));
                        }
                        $image->delete();
                    }
                }

                // Update primary image
                if ($request->has('primary_image') && $product->images()->count() > 0) {
                    $product->images()->update(['is_primary' => false]);
                    $product->images()->where('id', $request->primary_image)->update(['is_primary' => true]);
                }
            }

            // Add new images
            if ($request->hasFile('images')) {
                $hasExistingImages = $product->images()->count() > 0;
                $hasPrimaryImage = $product->images()->where('is_primary', true)->exists();

                foreach ($request->file('images') as $index => $image) {
                    $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                    $image->move($uploadPath, $imageName);

                    $isPrimary = !$hasPrimaryImage && $index === 0;

                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => 'storage/products/' . $imageName,
                        'is_primary' => $isPrimary,
                    ]);
                }
            }

            // Handle variants
            if ($request->has('variants')) {
                $product->variants()->delete();

                foreach ($request->variants as $variantData) {
                    if (!empty($variantData['name']) && !empty(trim($variantData['value']))) {
                        ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $variantData['name'],
                            'value' => trim($variantData['value']),
                            'additional_price' => $variantData['additional_price'] ?? 0,
                            'stock' => $variantData['stock'] ?? 0,
                        ]);
                    }
                }
            }

            DB::commit();

            return redirect()->back()->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update error: ' . $e->getMessage());
            \Log::error('Product update error trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function show($id) {
        $product = Product::with(['category', 'subcategory', 'childcategory', 'brand', 'unit', 'generic'])->findOrFail($id);

        return view('backend.product.product-details', compact('product'));
    }

    public function destroy($id)
    {
        // Gate::authorize('product-delete');

        try {
            $product = Product::findOrFail($id);
            $promotionImageName = $product->promotion_image;
            if ($promotionImageName && file_exists(public_path($promotionImageName))) {
                unlink(public_path($promotionImageName));
            }
            $videoName = $product->video_path;
            if ($videoName && file_exists(public_path($videoName))) {
                unlink(public_path($videoName));
            }
            $imagesToDelete = $product->images()->get();
            foreach ($imagesToDelete as $image) {
                if ($image->image_path && file_exists(public_path($image->image_path))) {
                    unlink(public_path($image->image_path));
                }
                $image->delete();
            }
            $product->variants()->delete();
            $product->delete();

            return response()->json([
                'success' => true,
                'message' => 'Product deleted successfully.',
                'data'    => $product,
            ], 200);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Product not found.',
                'data'    => [],
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong: ' . $e->getMessage(),
                'data'    => [],
            ], 500);
        }
    }

    public function import(Request $request)
    {
        $request->validate([
            'products_file' => 'required|file|mimes:xlsx,csv',
            'variants_file' => 'nullable|file|mimes:xlsx,csv',
        ]);

        try {
            DB::beginTransaction();

            // Store and read product file
            $productsPath = $request->file('products_file')->store('temp');
            $productsData = SimpleExcelReader::create(storage_path("app/{$productsPath}"))->getRows()->toArray();

            $variantsData = [];
            $variantsGrouped = collect();

            // Check for optional variants file
            if ($request->hasFile('variants_file')) {
                $variantsPath = $request->file('variants_file')->store('temp');
                $variantsData = SimpleExcelReader::create(storage_path("app/{$variantsPath}"))->getRows()->toArray();
                $variantsGrouped = collect($variantsData)->groupBy('excel_product_id');
            }

            $now = Carbon::now();
            $idMap = [];

            foreach ($productsData as $row) {
                $stock    = isset($row['stock']) ? (int)$row['stock'] : 0;
                $boxSize  = isset($row['box_size']) ? (int)$row['box_size'] : 0;

                // Multiply box_size × stock if box_size is given
                if ($boxSize > 0 && $stock > 0) {
                    $stock = $boxSize * $stock;
                }

                $excelId = $row['excel_id'] ?? null;
                if (!$excelId) {
                    continue;
                }

                $hasVariants    = $variantsGrouped->has($excelId);
                $wholesalePrice = $hasVariants ? null : ($row['wholesale_price'] ?? null);

                // ✅ Validate generic_id existence
                $genericId = null;
                if (!empty($row['generic_id'])) {
                    $exists = \App\Generic::where('id', $row['generic_id'])->exists();
                    if ($exists) {
                        $genericId = $row['generic_id'];
                    }
                }

                $product = Product::create([
                    'name'              => $row['name'] ?? null,
                    'batch_no'          => $row['batch_no'] ?? null,
                    'category_id'       => $row['category_id'] ?? null,
                    'subcategory_id'    => $row['subcategory_id'] ?? null,
                    'childcategory_id'  => $row['childcategory_id'] ?? null,
                    'brand_id'          => $row['brand_id'] ?? null,
                    'unit_id'           => $row['unit_id'] ?? null,
                    'generic_id'        => $genericId,
                    'old_price'         => $row['old_price'] ?? null,
                    'new_price'         => $row['new_price'] ?? null,
                    'wholesale_price'   => $wholesalePrice,
                    'stock'             => $stock,
                    'exp_limit'         => $row['exp_limit'] ?? 0,
                    'trending'          => $row['trending'] ?? 0,
                    'offer'             => $row['offer'] ?? 0,
                    'new_arival'        => $row['new_arival'] ?? 0,
                    'flash'             => $row['flash'] ?? 0,
                    'description'       => $row['description'] ?? null,
                    'menufecturer'      => $row['menufecturer'] ?? null,
                    'slug'              => Str::slug($row['name'] ?? 'product-' . Str::random(6)),
                    'status'            => 1,
                    'is_deleted'        => 0,
                    'out_of_stock'      => ($stock < 1) ? 1 : 0,
                ]);

                $product->update([
                    'product_code' => 'PRD-' . str_pad($product->id, 5, '0', STR_PAD_LEFT),
                ]);

                $idMap[$excelId] = $product->id;
            }

            // Insert variants if file was uploaded
            if (!empty($variantsData)) {
                foreach ($variantsData as $row) {
                    $excelProductId = $row['excel_product_id'] ?? null;
                    $productId = $idMap[$excelProductId] ?? null;

                    if (!$productId || empty($row['name']) || !isset($row['price'])) {
                        continue;
                    }

                    ProductVariant::create([
                        'product_id'              => $productId,
                        'name'                    => $row['name'],
                        'price'                   => $row['price'],
                        'varient_wholesale_price' => $row['varient_wholesale_price'] ?? null,
                        'quantity'                => $row['quantity'] ?? 0,
                        'is_base'                 => $row['is_base'] ?? 0,
                        'conversion_unit'         => $row['conversion_unit'] ?? 1,
                    ]);
                }
            }

            DB::commit();
            return redirect()->back()->with('success', 'Products and variants imported successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Import failed: ' . $e->getMessage());
        }
    }

    public function getSubcategories($category_id)
    {
        $subcategories = ProductSubcategory::where('category_id', $category_id)
        ->where('status', 1)
        ->get();
        return response()->json($subcategories);
    }

    public function getChildcategories($subcategory_id)
    {
        $childcategories = ProductChildcategory::where('subcategory_id', $subcategory_id)
        ->where('status', 1)
        ->get();
        return response()->json($childcategories);
    }
}
