<?php

namespace App\Http\Controllers\Frontend;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\{Session, File};
use App\Product;
use App\Service;
use App\Customer;
use App\User;
use App\Vendor;
use App\Order;
use App\Chat;
use App\ProductCategory;
use App\ProductBrand;
use App\ProductVariant;
use App\{ProductImage, ServiceComment};
use App\{SellerWallet, LikeService};
use Carbon\Carbon;

class VendorDashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:vendor');
    }

    public function index()
    {
        if (!Auth::guard('vendor')->check()) {
            return redirect()->route('vendor.login');
        }
        $vendor = Auth::guard('vendor')->user();
        $vendorId = $vendor->id;

        // Get order statistics based on your actual status values
        $orderStats = [
            'pending' => Order::where('vendor_id', $vendorId)
            ->where('status', 1) // Pending
            ->count(),
            'accepted' => Order::where('vendor_id', $vendorId)
            ->where('status', 2) // Accepted
            ->count(),
            'in_process' => Order::where('vendor_id', $vendorId)
            ->where('status', 3) // In Process
            ->count(),
            'picked_up' => Order::where('vendor_id', $vendorId)
            ->where('status', 4) // Picked Up
            ->count(),
            'rescheduled' => Order::where('vendor_id', $vendorId)
            ->where('status', 5) // Rescheduled
            ->count(),
            'delivered' => Order::where('vendor_id', $vendorId)
            ->where('status', 6) // Delivered
            ->count(),
            'cancelled' => Order::where('vendor_id', $vendorId)
            ->where('status', 7) // Cancelled
            ->count(),
            'return' => Order::where('vendor_id', $vendorId)
            ->where('status', 8) // Return
            ->count(),
            'all' => Order::where('vendor_id', $vendorId)->count(),
        ];

        // Get top selling products with proper image relationship loading
        $topSellingProducts = Product::where('vendor_id', $vendorId)
            ->with(['images' => function($query) {
                $query->where('is_primary', true);
            }])
            ->withCount(['orderItems as total_sold' => function($query) {
                $query->select(DB::raw('COALESCE(SUM(quantity), 0)'));
            }])
            ->orderBy('total_sold', 'desc')
            ->limit(4)
            ->get();

        // Get top rated services for this vendor
        $topRatedServices = Service::where('vendor_id', $vendorId)
            ->where('status', 1)
            ->where('admin_reject', 0)
            ->where('admin_approval', 1)
            ->orderBy('created_at', 'desc')
            ->limit(4)
            ->get();

        return view('frontend.seller.dashboard', compact(
            'vendor',
            'orderStats',
            'topSellingProducts',
            'topRatedServices'
        ));
    }

    public function orderList(Request $request)
    {
        $vendorId = Auth::guard('vendor')->id();

        $orders = Order::with(['items.product', 'shipping', 'customer', 'ordertype'])
            ->where('vendor_id', $vendorId)
            ->orderBy('created_at', 'desc');

        $status = $request->status !== null ? (int) $request->status : null;

        if ($status) {
            $orders->where('status', $status);
        }
        if ($request->filled('from_date')) {
            $orders->whereDate('created_at', '>=', $request->from_date);
        }
        if ($request->filled('to_date')) {
            $orders->whereDate('created_at', '<=', $request->to_date);
        }

        $orders = $orders->get();

        return view('frontend.seller.order.orderlist', compact('orders', 'status'));
    }


    /**
     * Show single order details
     */
    public function show($id)
    {
        $vendorId = Auth::guard('vendor')->id();

        $order = Order::with(['items.product', 'shipping', 'customer'])
            ->where('vendor_id', $vendorId)
            ->findOrFail($id);
        // dd($order);
        return view('frontend.seller.order.show', compact('order'));
    }

    public function profile()
    {
        // Get the authenticated vendor user
        $vendorUser = auth()->guard('vendor')->user();

        $totalShares = $vendorUser->services()
        ->withCount('shares')
        ->get()
        ->sum('shares_count');

        $totalLikes = LikeService::whereHas('service', function ($q) use ($vendorUser) {
            $q->where('vendor_id', $vendorUser->id);
        })->count();

        $totalComments = ServiceComment::whereHas('service', function ($q) use ($vendorUser) {
            $q->where('vendor_id', $vendorUser->id);
        })->count();

        $vendor = Vendor::with(['user', 'products' => function($query) {
            $query->where('status', 1);
        }])->where('user_id', $vendorUser->id)->first();

        if (!$vendor) {
            $vendor = Vendor::create([
                'user_id' => $vendorUser->id,
                'shop_name' => $vendorUser->name . "'s Shop",
                'type' => 'individual',
                'commission' => 10.0,
                'status' => true,
                'followers_count' => 0,
                'rating' => 0.0,
            ]);
        }

        // Get vendor statistics
        $stats = [
            'total_products' => $vendor->products->count(),
            'active_products' => $vendor->products->where('status', 1)->count(),
            'total_services' => $vendorUser->services()->count(),
            'total_followers' => $vendor->followers_count,
            'total_orders' => \App\OrderItem::whereHas('product', function($query) use ($vendorUser) {
                $query->where('vendor_id', $vendorUser->id);
            })->count(),
        ];

        return view('frontend.seller.auth.sellerprofile', compact('vendorUser','vendor', 'stats', 'totalShares', 'totalLikes', 'totalComments'));
    }

    public function editProfile()
    {
        $user = Auth::guard('vendor')->user();
        $vendor = $user->vendorDetails;

        return view('frontend.seller.auth.edit-profile', compact('user', 'vendor'));
    }

    public function updateProfile(Request $request)
    {
        $user = Auth::guard('vendor')->user();
        $vendor = $user->vendorDetails;

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $user->id,
            'phone' => 'nullable|string|max:20',
            'password' => 'nullable|string|min:6|confirmed',
            'shop_name' => 'required|string|max:255',
            'address' => 'nullable|string|max:500',
            'logo' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'banner_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:4096',
        ]);

        // Update user info
        $user->name = $request->name;
        $user->email = $request->email;
        $user->phone = $request->phone;

        if ($request->filled('welcome_description')) {
            $user->welcome_description = $request->welcome_description;
        }
        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }
        // profile image
        if ($request->hasFile('image')) {
            if (File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }
            $logo = $request->file('image');
            $logoName = time() . '_profile.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/profile/'), $logoName);
            $user->image = 'uploads/profile/' . $logoName;
        }
        $user->save();

        // Update vendor info
        $vendor->shop_name = $request->shop_name;
        $vendor->address = $request->address;

        // Logo upload
        if ($request->hasFile('logo')) {
            if (File::exists(public_path($vendor->logo))) {
                File::delete(public_path($vendor->logo));
            }
            $logo = $request->file('logo');
            $logoName = time() . '_logo.' . $logo->getClientOriginalExtension();
            $logo->move(public_path('uploads/vendor/'), $logoName);
            $vendor->logo = 'uploads/vendor/' . $logoName;
        }

        // Banner upload
        if ($request->hasFile('banner_image')) {
            if (File::exists(public_path($vendor->banner_image))) {
                File::delete(public_path($vendor->banner_image));
            }
            $banner = $request->file('banner_image');
            $bannerName = time() . '_banner.' . $banner->getClientOriginalExtension();
            $banner->move(public_path('uploads/vendor/'), $bannerName);
            $vendor->banner_image = 'uploads/vendor/' . $bannerName;
        }

        $vendor->save();

        return redirect()->route('vendor.profile.edit')->with('success', 'Profile updated successfully!');
    }


    public function updateProfileImage(Request $request)
    {
        $request->validate([
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,webp',
            'image_type' => 'required|in:profile,banner'
        ]);

        try {
            $vendor = Vendor::where('user_id', auth()->guard('vendor')->id())->first();

            // Create upload directory if it doesn't exist
            $uploadPath = public_path('uploads/vendor');
            if (!file_exists($uploadPath)) {
                mkdir($uploadPath, 0755, true);
            }

            if ($request->image_type === 'profile') {
                // Handle profile image upload
                $profileImage = $request->file('image');
                $profileImageName = 'vendor_' . auth()->guard('vendor')->id() . '_profile_' . time() . '.' . $profileImage->getClientOriginalExtension();

                // Delete old profile image if exists
                if ($vendor->user->image && file_exists(public_path('uploads/vendor/' . basename($vendor->user->image)))) {
                    unlink(public_path('uploads/vendor/' . basename($vendor->user->image)));
                }

                // Move new image
                $profileImage->move($uploadPath, $profileImageName);

                // Update user profile image path
                $vendor->user->update(['image' => 'uploads/vendor/' . $profileImageName]);

            } else {
                // Handle banner image upload
                $bannerImage = $request->file('image');
                $bannerImageName = 'vendor_' . auth()->guard('vendor')->id() . '_banner_' . time() . '.' . $bannerImage->getClientOriginalExtension();

                // Delete old banner image if exists
                if ($vendor->banner_image && file_exists(public_path('uploads/vendor/' . basename($vendor->banner_image)))) {
                    unlink(public_path('uploads/vendor/' . basename($vendor->banner_image)));
                }

                // Move new image
                $bannerImage->move($uploadPath, $bannerImageName);

                // Update vendor banner image path
                $vendor->update(['banner_image' => 'uploads/vendor/' . $bannerImageName]);
            }

            return response()->json([
                'success' => true,
                'message' => 'Image updated successfully',
                'image_url' => $request->image_type === 'profile'
                    ? asset('uploads/vendor/' . $profileImageName)
                    : asset('uploads/vendor/' . $bannerImageName)
            ]);

        } catch (\Exception $e) {
            \Log::error('Profile image upload error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating image: ' . $e->getMessage()
            ], 500);
        }
    }


    public function create()
    {
        $vendor = Auth::guard('vendor')->user();
        if($vendor->is_active != 1) {
            $real_vendor = Vendor::where('user_id', $vendor->id)->first();
            if( !empty($real_vendor->email) ) {
                return redirect()->route('vendor.dashboard')->with('error', "Waiting for Admin Approval");
            }
            Session::put('user_id', $vendor->id);
            return redirect()->route('vendor.kyc')->with('error', "Please fill up this kyc form first");
        }
        $categories = ProductCategory::where('status', 1)->get();
        $brands = ProductBrand::where('is_deleted', 0)->get();

        return view('frontend.seller.product.add', compact('categories', 'brands'));
    }

    public function store(Request $request)
    {
        if($request->weight_unit == 'gram') {
            if($request->weight < 1) {
                return redirect()->back()->with('error', 'Weight can not be less than 1 gram.');
            }
            if($request->weight >= 1000) {
                return redirect()->back()->with('error', 'Please use kg unit');
            }
        }
        if($request->weight_unit == 'kg') {
            if($request->weight < 0.001) {
                return redirect()->back()->with('error', 'Weight can not be less than 0.001 kg.');
            }
            if($request->weight > 300) {
                return redirect()->back()->with('error', 'Weight can not be greater than 300 kg.');
            }
        }
        // |min:|max:300
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
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
            'weight' => 'required|numeric',
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
                'warranty_term' => $request->warranty_term ?? null,
                'vendor_id' => Auth::guard('vendor')->id(),
                'name' => $validated['product_name'],
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
                'weight' => $request->weight_unit == 'gram' ? $validated['weight'] / 1000 : $validated['weight'],
                'length' => $validated['length'],
                'width' => $validated['width'],
                'height' => $validated['height'],
                'admin_commission' => $adminCommision,
                'dangerous_goods' => $validated['dangerous_goods'],
                'availability' => $request->has('availability') ? 1 : 0,
                'qc_status' => 0,
                'reject_status' => 0,
                'status' => 1,
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

    public function manage(Request $request)
    {
        $status = $request->get('status', 'all');
        $vendorId = Auth::guard('vendor')->id();

        // Base query
        $query = Product::where('vendor_id', $vendorId)
        ->with(['images' => function($query) {
            $query->where('is_primary', true);
        }]);

        // Apply filters based on status
        switch ($status) {
            case 'active':
                $query->where('status', 1)->where('availability', true);
                break;
            case 'inactive':
                $query->where(function($q) {
                    $q->where('status', 0)->orWhere('availability', false);
                });
                break;
            case 'qc_status':
                $query->where('qc_status', 0); // Pending QC status
                break;
            case 'rejected':
                $query->where('reject_status', 1); // Rejected status
                break;
            case 'deleted':
                $query->onlyTrashed(); // Soft deleted products
                break;
            case 'all':
            default:
                // Show all non-deleted products (default behavior)
                break;
        }

        $products = $query->latest()->paginate(10);

        // Get counts for each status
        $counts = [
            'all' => Product::where('vendor_id', $vendorId)->count(),
            'active' => Product::where('vendor_id', $vendorId)
            ->where('status', 1)
            ->where('availability', true)
            ->count(),
            'inactive' => Product::where('vendor_id', $vendorId)
            ->where(function($q) {
                $q->where('status', 0)->orWhere('availability', false);
            })
            ->count(),
            'pending' => Product::where('vendor_id', $vendorId)
            ->where('qc_status', 0)
            ->count(),
            'rejected' => Product::where('vendor_id', $vendorId)
            ->where('reject_status', 1)
            ->count(),
            'deleted' => Product::where('vendor_id', $vendorId)
            ->onlyTrashed()
            ->count(),
        ];

        return view('frontend.seller.product.manage', compact('products', 'status', 'counts'));
    }

    public function edit($id)
    {
        try {
            $product = Product::where('vendor_id', Auth::guard('vendor')->id())
            ->with(['images', 'variants'])
            ->findOrFail($id);

            $categories = ProductCategory::where('status', 1)->get();
            $brands = ProductBrand::where('is_deleted', 0)->get();

            return view('frontend.seller.product.edit', compact('product', 'categories', 'brands'));

        } catch (\Exception $e) {
            return redirect()->route('vendor.products.manage')
            ->with('error', 'Product not found or you do not have permission to edit it.');
        }
    }

    public function update(Request $request, $id)
    {
        // Validate the request
        $validated = $request->validate([
            'product_name' => 'required|string|max:255',
            'category_id' => 'required|exists:product_categories,id',
            'brand_id' => 'required|exists:product_brands,id',
            'price' => 'required|numeric|min:0',
            'special_price' => 'nullable|numeric|min:0',
            'stock' => 'required|integer|min:0',
            'sku' => 'required|string|max:200|unique:products,sku,' . $id,
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
            $product = Product::where('vendor_id', Auth::guard('vendor')->id())
            ->with(['images', 'variants'])
            ->findOrFail($id);

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
                'warranty_term' => $request->warranty_term ?? null,
                'name' => $validated['product_name'],
                'category_id' => $validated['category_id'],
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
                        'image_path' => 'uploads/products/' . $imageName,
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

            return redirect()->route('vendor.products.manage')
            ->with('success', 'Product updated successfully!');

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Product update error: ' . $e->getMessage());
            \Log::error('Product update error trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $product = Product::where('vendor_id', Auth::guard('vendor')->id())->findOrFail($id);

            $request->validate([
                'status' => 'required|in:0,1',
            ]);

            $product->update([
                'status' => $request->status,
            ]);

            return redirect()->back()->with('success', 'Product status updated successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error updating product: ' . $e->getMessage());
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::where('vendor_id', Auth::guard('vendor')->id())->findOrFail($id);
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

            return redirect()->back()->with('success', 'Product moved to trash!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $product = Product::where('vendor_id', Auth::guard('vendor')->id())
            ->onlyTrashed()
            ->findOrFail($id);
            $product->restore();

            return redirect()->back()->with('success', 'Product restored successfully!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error restoring product: ' . $e->getMessage());
        }
    }

    public function forceDelete($id)
    {
        try {
            $product = Product::where('vendor_id', Auth::guard('vendor')->id())
            ->onlyTrashed()
            ->findOrFail($id);
            $product->forceDelete();

            return redirect()->back()->with('success', 'Product permanently deleted!');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Error deleting product: ' . $e->getMessage());
        }
    }


    public function vendorShopList(Request $request)
    {
        try {
            // Start with a simple query
            $query = Vendor::with([
                'user:id,name,email,phone',
            ])->where('status', true);

            // Get current customer's follow status for each vendor (optional - for guests this will be empty)
            $currentCustomerId = auth('customer')->check() ? auth('customer')->id() : null;

            if ($currentCustomerId) {
                $query->with(['followers' => function($query) use ($currentCustomerId) {
                    $query->where('customer_id', $currentCustomerId)
                          ->select('vendor_id', 'customer_id');
                }]);
            }

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('shop_name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('type', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('address', 'LIKE', "%{$searchTerm}%")
                      ->orWhereHas('user', function($userQuery) use ($searchTerm) {
                          $userQuery->where('name', 'LIKE', "%{$searchTerm}%");
                      });
                });
            }

            // Filter by type
            if ($request->has('type') && !empty($request->type)) {
                $query->where('type', $request->type);
            }

            // Sort options
            $sort = $request->get('sort', 'latest');
            switch ($sort) {
                case 'popular':
                    $query->orderBy('followers_count', 'desc');
                    break;
                case 'rating':
                    $query->orderBy('rating', 'desc');
                    break;
                case 'name':
                    $query->orderBy('shop_name', 'asc');
                    break;
                default:
                    $query->latest();
            }

            $vendors = $query->paginate(12);
            $vendorTypes = Vendor::where('status', true)->distinct()->pluck('type')->filter();

            return view('frontend.pages.shop-list', compact('vendors', 'vendorTypes'));

        } catch (\Exception $e) {
            \Log::error('Vendor shop list error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());
            return redirect()->back()->with('error', 'Unable to load vendor list. Please try again.');
        }
    }

    public function followVendor(Request $request, $id)
    {
        // Check if customer is authenticated
        if (!auth()->guard('customer')->check()) {
            return response()->json([
                'success' => false,
                'message' => 'Please login as a customer to follow vendors',
                'login_required' => true
            ], 401);
        }

        $customer = auth('customer')->user();

        try {
            $vendor = Vendor::findOrFail($id);

            // Use database transaction for data consistency
            \DB::transaction(function () use ($customer, $vendor) {
                // Check if already following
                $alreadyFollowing = $customer->followsVendor($vendor->id);

                if ($alreadyFollowing) {
                    // Unfollow
                    $customer->followedVendors()->detach($vendor->id);
                    $vendor->decrement('followers_count');
                } else {
                    // Follow
                    $customer->followedVendors()->attach($vendor->id);
                    $vendor->increment('followers_count');
                }
            });

            // Refresh vendor data
            $vendor->refresh();

            return response()->json([
                'success' => true,
                'message' => $alreadyFollowing ? 'Unfollowed ' . $vendor->shop_name : 'Successfully followed ' . $vendor->shop_name,
                'following' => !$alreadyFollowing,
                'followers_count' => $vendor->followers_count
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Follow vendor error - Vendor ID: ' . $id . ' - ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.'
            ], 500);
        }
    }

    public function vendorShop(Request $request, $id)
    {
        try {
            $user = User::with(['vendorDetails' => function($query) {
                $query->where('status', true);
            }])->findOrFail($id);

            $vendor = $user->vendorDetails;

            if (!$vendor) {
                abort(404, 'Vendor shop not found or inactive');
            }

            // Since vendor_id in products table is actually user_id
            $query = Product::where('vendor_id', $user->id)
                           ->where('status', true)
                           ->where('qc_status', 1)
                           ->with(['images' => function($query) {
                               $query->where('is_primary', true);
                           }]);

            // Search functionality
            if ($request->has('search') && !empty($request->search)) {
                $searchTerm = $request->search;
                $query->where(function($q) use ($searchTerm) {
                    $q->where('name', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('description', 'LIKE', "%{$searchTerm}%")
                      ->orWhere('tags', 'LIKE', "%{$searchTerm}%");
                });
            }

            // Filter by category if needed
            if ($request->has('category') && !empty($request->category)) {
                $query->where('category_id', $request->category);
            }

            $products = $query->latest()->paginate(12)->withQueryString();

            // Get related vendors for sidebar
            $relatedVendors = Vendor::where('status', true)
                                   ->where('id', '!=', $vendor->id)
                                   ->where('type', $vendor->type)
                                   ->with(['user'])
                                   ->limit(6)
                                   ->get();

            return view('frontend.pages.shop', compact('products', 'vendor', 'user', 'relatedVendors'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Vendor not found');
        } catch (\Exception $e) {
            \Log::error('Vendor shop page error - User ID: ' . $id . ' - ' . $e->getMessage());
            return redirect()->route('vendor.shop.list')->with('error', 'Unable to load vendor shop. Please try again.');
        }
    }

    public function updateOrderStatus(Request $request)
    {
        $request->validate([
            'order_id' => 'required|exists:orders,id',
            'status' => 'required|integer',
        ]);

        $vendorId = Auth::guard('vendor')->id();

        $order = Order::where('vendor_id', $vendorId)->find($request->order_id);

        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Order not found.']);
        }

        // Update order status
        $order->status = $request->status;
        $order->save();

        /** --------------------------------------
         * When vendor marks order as delivered
         * Add money to SellerWallet
         * --------------------------------------
         */
        if ($request->status == 6) {

            foreach ($order->items as $item) {

                // Ensure item belongs to this vendor
                if ($item->vendor_id != $vendorId) {
                    continue;
                }

                $product = Product::find($item->product_id);

                $productTotal = $item->total_price;

                /** ───────────── COMMISSION LOGIC ───────────── **/
                if ($product && $product->admin_commission) {
                    $adminCommission = ($productTotal * $product->admin_commission) / 100;
                } else {
                    $adminCommission = 0;
                }

                // Vendor earning
                $vendorEarn = $productTotal - $adminCommission;

                /** ───────────── WALLET BALANCE UPDATE ───────────── **/
                $lastWallet = SellerWallet::where('vendor_id', $vendorId)
                    ->orderBy('id', 'DESC')
                    ->first();

                $previousBalance = $lastWallet ? $lastWallet->current : 0;
                $newBalance      = $previousBalance + $vendorEarn;

                // Insert into sellerwallets
                SellerWallet::create([
                    'title'       => 'Order Delivered #' . $order->id,
                    'vendor_id'   => $vendorId,
                    'approved_by' => $vendorId,
                    'amount'      => $vendorEarn,
                    'credit'      => 0,
                    'current'     => $newBalance,
                    'note'        => 'Earning from Order #' . $order->id,
                    'status'      => 0,
                ]);

                /** Save earnings inside order_items */
                $item->vendor_earning   = $vendorEarn;
                $item->admin_commission = $adminCommission;
                $item->save();

                /** UPDATE vendor total cash */
                $vendorUpdate = Vendor::where('user_id', $vendorId)->first();
                if ($vendorUpdate) {
                    $vendorUpdate->sellercash = ($vendorUpdate->sellercash ?? 0) + $vendorEarn;
                    $vendorUpdate->save();
                }
            }
        }

        return response()->json(['success' => true]);
    }



    /**
     * Seller Chatting Section
     */
    public function chat() {
        $vendorId = Auth::guard('vendor')->id();

        $customers = DB::table('customers')
            ->join('chats', 'customers.id', '=', 'chats.customerId')
            ->where('chats.sellerId', $vendorId)
            ->select('customers.*')
            ->distinct()
            ->get();

        return view('frontend.seller.chat', compact('customers'));
    }

    public function chatwithcustomer($name, $id) {
        $vendorId = Auth::guard('vendor')->id();

        $customers = DB::table('customers')
            ->join('chats', 'customers.id', '=', 'chats.customerId')
            ->where('chats.sellerId', $vendorId)
            ->select('customers.*')
            ->distinct()
            ->get();

        Session::put('customerchatId', $id);
        $smessages = Chat::where(['customerId' => $id, 'sellerId' => $vendorId])->get();
        $cuschats  = Chat::where(['customerId' => $id, 'sellerId' => $vendorId, 'status' => 0])->get();

        foreach ($cuschats as $chat) {
            $readChat         = Chat::find($chat->id);
            $readChat->status = 1;
            $readChat->save();
        }

        $customerInfo = Customer::find($id);

        return view('frontend.seller.chat', compact('customers', 'customerInfo', 'smessages'));
    }

    public function chatcontent(Request $request) {
        $vendorId = Auth::guard('vendor')->id();

        $customerId = $request->customerId ?: Session::get('customerchatId');

        if (!$customerId) {
            return response()->json(['error' => 'Customer ID not found'], 400);
        }

        $customerInfo = Customer::find($customerId);
        $smessages    = Chat::where(['customerId' => $customerId, 'sellerId' => $vendorId])->get();

        return view('frontend.seller.chatcontent', compact('smessages', 'customerInfo'));
    }

    public function sellertocustomersms(Request $request)
    {
        try {
            \Log::info('Message received', $request->all());

            // Validate inputs
            $request->validate([
                'customerId' => 'required|exists:customers,id',
                'smessage'   => 'nullable|string|max:1000',
                'file'       => 'nullable|file|max:10240', // 10MB max
            ]);

            // Get vendor (seller) ID
            $vendorId = Auth::guard('vendor')->id();

            if (!$vendorId) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Vendor not authenticated'], 401);
                }
                return back()->with('error', 'Vendor not authenticated');
            }

            // Prepare base message data
            $messageData = [
                'message'    => $request->smessage,
                'customerId' => $request->customerId,
                'sellerId'   => $vendorId,
                'isSeller'   => $vendorId,
                'status'     => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ];

            // Handle optional file upload
            if ($request->hasFile('file') && $request->file('file')->isValid()) {
                $file = $request->file('file');

                $fileSize = $file->getSize();
                $fileName = time() . '_' . uniqid() . '_' . preg_replace('/\s+/', '_', $file->getClientOriginalName());
                $destinationPath = public_path('uploads/chat_files/');

                if (!file_exists($destinationPath)) {
                    mkdir($destinationPath, 0777, true);
                }

                $file->move($destinationPath, $fileName);
                $filePath = 'uploads/chat_files/' . $fileName;

                $messageData['file'] = $filePath;
                $messageData['file_size'] = $fileSize;

                \Log::info('File uploaded successfully', ['path' => $filePath, 'size' => $fileSize]);
            }

            // Save message
            $inserted = DB::table('chats')->insert($messageData);

            if ($inserted) {
                if ($request->ajax()) {
                    return response()->json([
                        'success' => true,
                        'message' => 'Message sent successfully',
                        'data' => $messageData,
                    ]);
                }

                // Normal form submission → redirect back
                return redirect()->back()->with('success', 'Message sent successfully');
            }

            if ($request->ajax()) {
                return response()->json(['success' => false, 'message' => 'Failed to save message'], 500);
            }

            return redirect()->back()->with('error', 'Failed to save message');

        } catch (\Exception $e) {
            \Log::error('Message send error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error sending message: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Error sending message: ' . $e->getMessage());
        }
    }

    public function walletAll() {
        $vendorId = Auth::guard('vendor')->id();

        $transactions = SellerWallet::where(['vendor_id' => $vendorId])->orderBy('updated_at', 'DESC')->get();

        return view('frontend.seller.wallet-all', compact('transactions'));
    }

    public function wallet() {
        $vendorId = Auth::guard('vendor')->id();

        $available_balance = Vendor::where('user_id', $vendorId)->first()->sellercash;
        // dd($available_balance);
        $transactions = SellerWallet::where(['vendor_id' => $vendorId, 'status' => 1])->orderBy('updated_at', 'DESC')
        ->limit(6)->get();

        $pendings      = SellerWallet::where(['vendor_id' => $vendorId, 'status' => 0, 'credit' => 0])
        ->orderBy('updated_at', 'DESC')->limit(5)->get();
        $total_pending = SellerWallet::where(['vendor_id' => $vendorId, 'status' => 0, 'credit' => 0])
        ->orderBy('updated_at', 'DESC')->sum('amount');

        return view('frontend.seller.wallet', compact('transactions', 'available_balance', 'pendings', 'total_pending'));
    }

    public function transactions(Request $request) {
        $vendorId = Auth::guard('vendor')->id();

        $available_balance = Vendor::where('user_id', $vendorId)->first()->sellercash;
        $starting_at       = $request->starting_at ?? Carbon::now()->subDays(10)->format('Y-m-d');
        $ending_at         = $request->ending_at ?? Carbon::now()->format('Y-m-d');
        $ending_at         = Carbon::parse($ending_at)->endOfDay();

        $transactions = SellerWallet::where(['vendor_id' => $vendorId, 'status' => 1])->orderBy('updated_at', 'ASC');

        $transactions = $transactions->whereBetween('updated_at', [$starting_at, $ending_at]);
        $transactions = $transactions->get();
        $ending_at    = $request->ending_at ?? Carbon::now()->format('Y-m-d');

        return view('frontend.seller.transactions', compact('transactions', 'available_balance', 'starting_at', 'ending_at'));
    }

    public function widthdrawRequest(Request $request) {

        $vendorId = Auth::guard('vendor')->id();
        $seller   = Vendor::where('user_id', $vendorId)->first();

        if ($request->amount > $seller->sellercash) {
            return redirect()->back()->with('error', 'Insufficient Balance!');
        }

        $seller->sellercash -= $request->amount;
        $seller->save();

        $sellerwallet            = new SellerWallet();
        $sellerwallet->title     = 'Withdraw';
        $sellerwallet->amount    = $request->amount;
        $sellerwallet->current   = $seller->sellercash;
        $sellerwallet->note      = $request->note;
        $sellerwallet->vendor_id = $vendorId;
        $sellerwallet->save();

        return redirect()->back()->with('success', 'Widthdraw Request Sent Successfully!');
    }

    public function stockUpdate(Request $request, $productId)
    {
        $product = Product::where('vendor_id', Auth::guard('vendor')->id())->findOrFail($productId);
        if ($request->has('variants')) {
            $product_stock = 0;
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
                    $product_stock += $variantData['stock'] ?? 0;
                }
            }
            $product->stock = $product_stock;
            $product->save();
        } else {
            $product->stock = $request->stock ?? $product->stock;
            $product->save();
        }
        return back()->with('success', 'Stock updated successfully!');
    }

    public function stockManage(Request $request)
    {
        $vendorId = Auth::guard('vendor')->id();

        $categories = ProductCategory::all();
        $brands     = ProductBrand::all();

        $products = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('vendor_id', $vendorId)
            ->where('status', 1);

        if ($request->filled('category_id')) {
            $products->where('category_id', $request->category_id);
        }

        if ($request->filled('brand_id')) {
            $products->where('brand_id', $request->brand_id);
        }

        if ($request->filled('search')) {
            $products->where('name', 'like', '%' . $request->search . '%');
        }

        $products = $products->orderByDesc('id')->paginate(10);
        // dd($products);
        return view(
            'frontend.seller.inventory.stock',
            compact('products', 'categories', 'brands')
        );
    }

    public function WarningLimitUpdate(Request $request) {
        $vendorId = Auth::guard('vendor')->id();
        $real_vendor = Vendor::where('user_id', $vendorId)->first();
        $real_vendor->update([
            'product_warning_limit' => $request->product_warning_limit
        ]);
        return back()->with('success','Warning Limit Updated Successfully');
    }

    public function stockWarning(Request $request)
    {
        $vendorId = Auth::guard('vendor')->id();
        $real_vendor = Vendor::where('user_id', $vendorId)->first();
        $warningLimit = $real_vendor->product_warning_limit;

        $categories = ProductCategory::all();
        $brands     = ProductBrand::all();

        $products = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('vendor_id', $vendorId)
            ->where('status', 1)
            ->get()
            ->filter(fn ($product) => $product->total_stock <= $warningLimit);

        return view(
            'frontend.seller.inventory.stock_warning',
            compact('products', 'categories', 'brands','real_vendor')
        );
    }

    public function stockOut(Request $request)
    {
        $vendorId = Auth::guard('vendor')->id();

        $categories = ProductCategory::all();
        $brands     = ProductBrand::all();

        $products = Product::with(['category', 'brand', 'images', 'variants'])
            ->where('vendor_id', $vendorId)
            ->where('status', 1)
            ->get()
            ->filter(fn ($product) => $product->total_stock == 0);

        return view(
            'frontend.seller.inventory.stock_out',
            compact('products', 'categories', 'brands')
        );
    }
}
