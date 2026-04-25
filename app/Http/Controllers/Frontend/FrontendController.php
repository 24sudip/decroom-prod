<?php

namespace App\Http\Controllers\Frontend;

use App\AppSettings;
use App\District;
use App\Division;
use App\Faq;
use App\Http\Controllers\Controller;
use App\Order;
use App\Page;
use App\Product;
use App\ProductBrand;
use App\ProductImage;
use App\OfferBanner;
use App\ProductCategory;
use App\ProductSubcategory;
use App\ProductChildcategory;
use App\ShippingAddress;
use App\ProductRating;
use App\Slider;
use App\Union;
use App\Service;
use App\{User, ServiceDraft};
use App\{Vendor, ServiceOrderItem};
use App\{ServiceCategory, ServiceShare};
use App\{Upazila, ServiceComment};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Helpers\Translator;
use Illuminate\Support\Facades\Session;

class FrontendController extends Controller {

    public function MarkAsRead(Request $request, $notificationId) {
        $user = auth('vendor')->user();
        $vendor = Vendor::where('user_id', $user->id)->first();
        $vendorInfo = $user ? ($user->vendorDetails ?? $vendor) : null;
        $notification = $vendorInfo->notifications()->where('id', $notificationId)->first();
        if ($notification) {
            $notification->markAsRead();
        }
        return response()->json(['count' => $vendorInfo->unreadNotifications()->count()]);
    }

    public function index() {
        $data['sliders']          = Slider::latest()->get();
        $data['brands']           = ProductBrand::latest()->get();
        $data['offerbanner']      = OfferBanner::where('status', 1)->first();

        // HOME CATEGORIES + PRODUCTS WITH RATING
        $data['homeCategories'] = ProductCategory::where('is_home', 1)
            ->with([
                'products' => function ($q) {
                    $q->where('status', 1)
                      ->withAvg('ratings', 'rating')      // ⭐ avg_rating
                      ->withCount('ratings')              // ⭐ ratings_count
                      ->with(['images' => function($img){
                            $img->where('is_primary', true);
                      }])
                      ->latest()
                      ->take(10);
                }
            ])
            ->orderByRaw("id = 13 DESC")
            ->orderBy('id', 'ASC')
            ->get();

        // SERVICES
        $data['services'] = Service::with(['category', 'vendor'])
            ->where('admin_reject', 0)
            ->where('admin_approval', 1)
            ->latest()
            ->take(3)
            ->get();

        $data['serviceCategories'] = ServiceCategory::where('status', 1)->get();
        $data['productCategories'] = ProductCategory::where('status', 1)->get();

        // FEATURED PRODUCTS WITH RATING
        $data['products'] = Product::where('qc_status', 1)
            ->withAvg('ratings', 'rating')       // ⭐ avg_rating
            ->withCount('ratings')               // ⭐ ratings_count
            ->with(['images' => function($query) {
                $query->where('is_primary', true);
            }])
            ->latest()
            ->take(4)
            ->get();

        // ALL PRODUCTS WITH RATING
        $data['allProducts'] = Product::where('qc_status', 1)
            ->withAvg('ratings', 'rating')       // ⭐ avg_rating
            ->withCount('ratings')               // ⭐ ratings_count
            ->with(['images' => function($query) {
                $query->where('is_primary', true);
            }])
            ->latest()
            ->get();

        // CUSTOMER ADDRESSES
        $customerId = auth('customer')->id();

        $data['addresses'] = ShippingAddress::with(['district', 'upazila'])
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('frontend.index', $data);
    }


    public function CategoryWiseProduct($slug, Request $request) {
        $category = ProductCategory::with('subcategories.childcategories')
            ->where('slug', $slug)
            ->firstOrFail();

        $subcategoryIds   = $category->subcategories->pluck('id')->toArray();
        $childcategoryIds = $category->subcategories->flatMap(function ($sub) {
            return $sub->childcategories->pluck('id');
        })->toArray();

        $query = Product::where('is_deleted', 0)
            ->where(function($q) use ($category, $subcategoryIds, $childcategoryIds) {
                $q->where('category_id', $category->id)
                  ->orWhereIn('subcategory_id', $subcategoryIds)
                  ->orWhereIn('childcategory_id', $childcategoryIds);
            });

        // Apply sorting
        if ($request->sort == 'low_high') {
            $query->orderBy('new_price', 'asc');
        } elseif ($request->sort == 'high_low') {
            $query->orderBy('new_price', 'desc');
        } elseif ($request->sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->appends(['sort' => $request->sort]);

        $customerId = auth('customer')->id();
        $addresses = ShippingAddress::with(['district', 'upazila'])
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('frontend.pages.category.category', [
            'category'  => $category,
            'products'  => $products,
            'addresses' => $addresses,
        ]);
    }


    public function SubCategoryWiseProduct($slug, Request $request) {
        $subCategory = ProductSubcategory::with('category','childcategories')
            ->where('slug', $slug)
            ->firstOrFail();

        $query = Product::where('subcategory_id', $subCategory->id)
            ;

        // ðŸ”¹ Apply sorting based on request
        if ($request->sort == 'low_high') {
            $query->orderBy('new_price', 'asc');
        } elseif ($request->sort == 'high_low') {
            $query->orderBy('new_price', 'desc');
        } elseif ($request->sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->appends(['sort' => $request->sort]);

        $customerId = auth('customer')->id();

        $addresses = ShippingAddress::with(['district', 'upazila'])
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('frontend.pages.category.subcategory', [
            'category'      => $subCategory->category,
            'subcategory'   => $subCategory,
            'childcategory' => $subCategory->childcategories,
            'products'      => $products,
            'addresses'     => $addresses,
        ]);
    }


    public function ChildCategoryWiseProduct($slug, Request $request) {
        $childCategory = ProductChildcategory::with('subcategory.category')
            ->where('slug', $slug)
            ->firstOrFail();

        $query = Product::where('childcategory_id', $childCategory->id)
            ;

        // ðŸ”¹ Apply sorting
        if ($request->sort == 'low_high') {
            $query->orderBy('new_price', 'asc');
        } elseif ($request->sort == 'high_low') {
            $query->orderBy('new_price', 'desc');
        } elseif ($request->sort == 'newest') {
            $query->orderBy('created_at', 'desc');
        } else {
            $query->latest();
        }

        $products = $query->paginate(12)->appends(['sort' => $request->sort]);

        $customerId = auth('customer')->id();

        $addresses = ShippingAddress::with(['district', 'upazila'])
            ->where('customer_id', $customerId)
            ->latest()
            ->get();

        return view('frontend.pages.category.childcategory', [
            'category'      => $childCategory->subcategory->category,
            'subcategory'   => $childCategory->subcategory,
            'childcategory' => $childCategory,
            'products'      => $products,
            'addresses'     => $addresses,
        ]);
    }


    public function BrandWiseProduct($id, Request $request) {

        $brand = ProductBrand::findOrFail($id);

        $products = Product::with(['category', 'brand', 'images', 'vendor'])
            ->where('brand_id', $id)
            ->where('qc_status', 1)
            ->where('availability', 1)
            ->latest()
            ->paginate(12);
    // dd($products);
        return view('frontend.pages.product.brand_product', compact(
            'products','brand'
        ));
    }


    public function allProducts(Request $request)
    {
        // Get products with proper eager loading
        $products = Product::with([
            'category',
            'brand',
            'images' => function($query) {
                $query->orderBy('is_primary', 'desc');
            },
            'vendor',
            'reviews',
            'orderItems'
        ])
        ->where('qc_status', 1)
        ->where('availability', 1)
        ->latest()
        ->get();

        return view('frontend.pages.product.all_products', compact('products'));
    }

    public function productDetails($id)
    {
        // Get product
        $product = Product::with([
            'category',
            'brand',
            'variants',
            'vendor',
            'images',
            'reviews.user'
        ])
        ->where('qc_status', 1)
        ->findOrFail($id);

        // Reviews pagination
        $reviews = $product->reviews()
            ->with('user')
            ->latest()
            ->paginate(5);

        // Questions pagination
        $questions = $product->questions()
            ->with('customer')
            ->where('status', 1)
            ->latest()
            ->paginate(5);

        // Similar products
        $similarProducts = Product::with('images')
            ->where('qc_status', 1)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        // Track product view
        $this->trackProductView($id);

        // Return view
        return view('frontend.pages.product.details', compact(
            'product',
            'reviews',
            'questions',
            'similarProducts'
        ));
    }

    public function productsByCategory($categorySlug)
    {
        $category = ProductCategory::where('slug', $categorySlug)
            ->where('status', 1)
            ->firstOrFail();

        $products = Product::with(['category', 'brand', 'images', 'vendor'])
            ->where('category_id', $category->id)
            ->where('qc_status', 1)
            ->where('availability', 1)
            ->latest()
            ->paginate(12);

        return view('frontend.pages.product.category_product', compact('products', 'category'));
    }

    public function allServices(Request $request)
    {
        // Get services with eager loading
        $services = Service::with([
            'category',
            'vendor'
        ])
        ->where('admin_approval', 1)
        ->latest()
        ->paginate(12);

        return view('frontend.pages.service.all_services', compact('services'));
    }

    public function share(Service $service, $platform)
    {
        if (!in_array($platform, ['whatsapp', 'facebook', 'copy'])) {
            abort(404);
        }

        ServiceShare::create([
            'service_id' => $service->id,
            'platform'   => $platform,
            'ip'         => request()->ip(),
        ]);

        $url = route('service.details', $service->id);

        return match ($platform) {
            'whatsapp' => redirect('https://wa.me/?text=' . urlencode($url)),
            'facebook' => redirect('https://www.facebook.com/sharer/sharer.php?u=' . urlencode($url)),
            'copy'     => redirect()->back(),
        };
    }

    public function serviceDraftDetails($service_order_id) {
        $service_order_item = ServiceOrderItem::where('service_order_id', $service_order_id)->first();
        $data['service'] = Service::with([
            'category',
            'client',
            'vendor'
        ])
        ->where('admin_approval', 1)
        ->findOrFail($service_order_item->service_id);

        // Multiple strategies for related services
        $similarByCategory = Service::with(['category'])
            ->where('category_id', $data['service']->category_id)
            ->where('id', '!=', $service_order_item->service_id)
            ->where('admin_approval', 1)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // If not enough from same category, add from same vendor
        if ($similarByCategory->count() < 4 && $data['service']->vendor_id) {
            $similarByVendor = Service::with(['category'])
                ->where('vendor_id', $data['service']->vendor_id)
                ->where('id', '!=', $service_order_item->service_id)
                ->whereNotIn('id', $similarByCategory->pluck('id'))
                ->where('admin_approval', 1)
                ->inRandomOrder()
                ->take(4 - $similarByCategory->count())
                ->get();

            $data['similarServices'] = $similarByCategory->merge($similarByVendor);
        } else {
            $data['similarServices'] = $similarByCategory;
        }

        // Fallback to recently viewed or popular services
        if ($data['similarServices']->count() < 4) {
            $fallbackServices = Service::with(['category'])
                ->where('admin_approval', 1)
                ->where('id', '!=', $service_order_item->service_id)
                ->whereNotIn('id', $data['similarServices']->pluck('id'))
                ->inRandomOrder()
                ->take(4 - $data['similarServices']->count())
                ->get();

            $data['similarServices'] = $data['similarServices']->merge($fallbackServices);
        }

        // Recently browsed services
        $data['recentlyBrowsed'] = Service::with(['category'])
            ->where('admin_approval', 1)
            ->where('id', '!=', $service_order_item->service_id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        $service_comments = ServiceComment::where(['service_id' => $service_order_item->service_id, 'status' => 1])->get();
        $service_draft = ServiceDraft::where(['service_id' => $service_order_item->service_id, 'status' => 1])->first();
        return view('frontend.pages.service-draft.details', [
            'service' => $data['service'],
            'similarServices' => $data['similarServices'],
            'recentlyBrowsed' => $data['recentlyBrowsed'],
            'service_comments' => $service_comments,
            'service_draft' => $service_draft,
        ]);
    }

    public function serviceDetails($id) {
        $data['service'] = Service::with([
            'category',
            'client',
            'vendor'
        ])
        ->where('admin_approval', 1)
        ->findOrFail($id);

        // Multiple strategies for related services
        $similarByCategory = Service::with(['category'])
            ->where('category_id', $data['service']->category_id)
            ->where('id', '!=', $id)
            ->where('admin_approval', 1)
            ->inRandomOrder()
            ->take(4)
            ->get();

        // If not enough from same category, add from same vendor
        if ($similarByCategory->count() < 4 && $data['service']->vendor_id) {
            $similarByVendor = Service::with(['category'])
                ->where('vendor_id', $data['service']->vendor_id)
                ->where('id', '!=', $id)
                ->whereNotIn('id', $similarByCategory->pluck('id'))
                ->where('admin_approval', 1)
                ->inRandomOrder()
                ->take(4 - $similarByCategory->count())
                ->get();

            $data['similarServices'] = $similarByCategory->merge($similarByVendor);
        } else {
            $data['similarServices'] = $similarByCategory;
        }

        // Fallback to recently viewed or popular services
        if ($data['similarServices']->count() < 4) {
            $fallbackServices = Service::with(['category'])
                ->where('admin_approval', 1)
                ->where('id', '!=', $id)
                ->whereNotIn('id', $data['similarServices']->pluck('id'))
                ->inRandomOrder()
                ->take(4 - $data['similarServices']->count())
                ->get();

            $data['similarServices'] = $data['similarServices']->merge($fallbackServices);
        }

        // Recently browsed services
        $data['recentlyBrowsed'] = Service::with(['category'])
            ->where('admin_approval', 1)
            ->where('id', '!=', $id)
            ->inRandomOrder()
            ->take(6)
            ->get();

        $service_comments = ServiceComment::where(['service_id' => $id, 'status' => 1])->get();
        return view('frontend.pages.service.details', [
            'service' => $data['service'],
            'similarServices' => $data['similarServices'],
            'recentlyBrowsed' => $data['recentlyBrowsed'],
            'service_comments' => $service_comments,
        ]);
    }

    public function servicesByCategory($categorySlug)
    {
        $category = ServiceCategory::where('slug', $categorySlug)
            ->where('status', 1)
            ->firstOrFail();

        $services = Service::with(['category', 'vendor'])
            ->where('category_id', $category->id)
            ->where('admin_approval', 1)
            ->latest()
            ->paginate(12);

        return view('frontend.pages.service.category_services', compact('services', 'category'));
    }

    // Optional: Add method to track product views
    private function trackProductView($productId) {
        // You can implement session-based or database-based view tracking
        $viewedProducts = session()->get('viewed_products', []);

        // Remove the product if it already exists
        $viewedProducts = array_diff($viewedProducts, [$productId]);

        // Add to beginning of array
        array_unshift($viewedProducts, $productId);

        // Keep only last 10 products
        $viewedProducts = array_slice($viewedProducts, 0, 10);

        session()->put('viewed_products', $viewedProducts);
    }

    public function privacyPolicy() {
        $privacyPolicies = Page::with('category')
            ->where('category_id', 1)
            ->latest()
            ->get();

        return view('frontend.pages.privacypolicy', compact('privacyPolicies'));
    }

    public function termsAndCondition() {
        $terms = Page::with('category')
            ->where('category_id', 2)
            ->latest()
            ->get();

        return view('frontend.pages.terms', compact('terms'));
    }

    public function contact() {
        $contact = Page::with('category')
            ->where('category_id', 8)
            ->latest()
            ->get();

        return view('frontend.pages.contact', compact('contact'));
    }

    public function aboutUs() {
        $about = Page::with('category')
            ->where('category_id', 6)
            ->latest()
            ->get();

        return view('frontend.pages.about', compact('about'));
    }
    public function warranty() {
        $warranty = Page::with('category')
            ->where('category_id', 11)
            ->latest()
            ->get();

        return view('frontend.pages.warranty', compact('warranty'));
    }
    public function delivery() {
        $delivery = Page::with('category')
            ->where('category_id', 9)
            ->latest()
            ->get();

        return view('frontend.pages.delivery', compact('delivery'));
    }
    public function help_center() {
        $help_center = Page::with('category')
            ->where('category_id', 12)
            ->latest()
            ->get();

        return view('frontend.pages.help_center', compact('help_center'));
    }

    public function returnPolicy() {
        $terms = Page::with('category')
            ->where('category_id', 3)
            ->latest()
            ->get();

        return view('frontend.pages.return_policy', compact('terms'));
    }

    public function FAQ() {
        $faqs = Faq::where('status', 1)->get();

        return view('frontend.pages.faq', compact('faqs'));
    }

    // Get all divisions
    public function getDivisions() {
        return response()->json(Division::all());
    }

    // Get districts by division_id
    public function getDistricts(Request $request) {
        $request->validate([
            'division_id' => 'required|exists:divisions,id',
        ]);

        return response()->json(
            District::where('division_id', $request->division_id)->get()
        );
    }

    // Get upazilas by district_id
    public function getUpazilas(Request $request) {
        $request->validate([
            'district_id' => 'required|exists:districts,id',
        ]);

        return response()->json(
            Upazila::where('district_id', $request->district_id)->get()
        );
    }

    // Get unions by upazila_id
    public function getUnions(Request $request) {
        $request->validate([
            'upazila_id' => 'required|exists:upazilas,id',
        ]);

        return response()->json(
            Union::where('upazilla_id', $request->upazila_id)->get()
        );
    }

    public function ajaxSearch(Request $request)
    {
        $query = $request->get('q', $request->keyword);
        $categoryId = $request->category_id;

        $products = Product::with([
            'category',
            'brand',
            'images',
            'vendor',
            'reviews',
            'orderItems'
        ])
        ->withCount('images')
        ->where('qc_status', 1)
        ->whereNull('deleted_at');

        if ($query) {
            $products->where(function($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                  ->orWhere('description', 'LIKE', "%{$query}%")
                  ->orWhere('highlight', 'LIKE', "%{$query}%")
                  ->orWhere('sku', 'LIKE', "%{$query}%");
            });
        }

        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }

        return response()->json($products->take(10)->get());
    }

    public function ajaxSearchList(Request $request)
    {
        $query = $request->get('q', $request->keyword);
        $categoryId = $request->category_id;

        $products = Product::with([
            'category',
            'brand',
            'images' => function ($q) {
                $q->orderBy('is_primary', 'desc');
            },
            'vendor',
            'reviews',
            'orderItems'
        ])
        ->where('qc_status', 1);

        if ($query) {
            $products->where(function ($q) use ($query) {
                $q->where('name', 'LIKE', "%{$query}%")
                    ->orWhere('description', 'LIKE', "%{$query}%")
                    ->orWhere('highlight', 'LIKE', "%{$query}%")
                    ->orWhere('sku', 'LIKE', "%{$query}%");
            });
        }

        if ($categoryId) {
            $products->where('category_id', $categoryId);
        }

        $products = $products->take(12)->get();

        return view('frontend.pages.product.search_product', compact('products'));
    }





    public function allbrand (){
          $data['brands'] = ProductBrand::latest()->get();

        return view('frontend.pages.allbrand', $data);
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
            // dd($vendors);
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

        \Log::info('Follow vendor attempt', [
            'customer_id' => $customer->id,
            'vendor_id' => $id,
            'customer_email' => $customer->email
        ]);

        try {
            $vendor = Vendor::findOrFail($id);

            // Check if already following first - FIXED: Added semicolon and use correct method
            $alreadyFollowing = $vendor->isFollowedBy($customer->id);

            \Log::info('Follow status check', [
                'vendor_id' => $vendor->id,
                'already_following' => $alreadyFollowing,
                'current_followers_count' => $vendor->followers_count
            ]);

            // Use database transaction for data consistency
            \DB::transaction(function () use ($customer, $vendor, $alreadyFollowing) {
                if ($alreadyFollowing) {
                    // Unfollow
                    $customer->followedVendors()->detach($vendor->id);
                    $vendor->decrement('followers_count');
                    \Log::info('Unfollowed vendor', ['vendor_id' => $vendor->id]);
                } else {
                    // Follow
                    $customer->followedVendors()->attach($vendor->id);
                    $vendor->increment('followers_count');
                    \Log::info('Followed vendor', ['vendor_id' => $vendor->id]);
                }
            });

            // Refresh vendor data to get updated count
            $vendor->refresh();

            \Log::info('After follow action', [
                'vendor_id' => $vendor->id,
                'new_followers_count' => $vendor->followers_count,
                'following' => !$alreadyFollowing
            ]);

            return response()->json([
                'success' => true,
                'message' => $alreadyFollowing ? 'Unfollowed ' . $vendor->shop_name : 'Successfully followed ' . $vendor->shop_name,
                'following' => !$alreadyFollowing,
                'followers_count' => $vendor->followers_count
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            \Log::error('Vendor not found', ['vendor_id' => $id, 'error' => $e->getMessage()]);
            return response()->json([
                'success' => false,
                'message' => 'Vendor not found'
            ], 404);
        } catch (\Exception $e) {
            \Log::error('Follow vendor error', [
                'vendor_id' => $id,
                'customer_id' => $customer->id ?? 'unknown',
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return response()->json([
                'success' => false,
                'message' => 'Something went wrong. Please try again.',
                'debug' => config('app.debug') ? $e->getMessage() : null
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

            // Check if current customer follows this vendor
            $isFollowed = false;
            if (auth('customer')->check()) {
                $isFollowed = $vendor->followers()->where('customer_id', auth('customer')->id())->exists();
            }

            // Since vendor_id in products table is actually user_id
            $query = Product::where('vendor_id', $user->id)
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

            return view('frontend.pages.shop', compact('products', 'vendor', 'user', 'relatedVendors', 'isFollowed'));

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            abort(404, 'Vendor not found');
        } catch (\Exception $e) {
            \Log::error('Vendor shop page error - User ID: ' . $id . ' - ' . $e->getMessage());
            return redirect()->route('vendor.shop.list')->with('error', 'Unable to load vendor shop. Please try again.');
        }
    }

    public function orderDetails($orderId)
    {
        $order = Order::with([
            'customer',
            'user.vendorDetails',
            'items.product.images',
            'items.variant',
            'paymentMethod'
        ])->findOrFail($orderId);
    // dd($order);
        return view('frontend.pages.order.orderdetails', compact('order'));
    }

    public function ajaxShopList(Request $request)
    {
        $vendorId = $request->vendor_id;
        $search = $request->search;

        $query = Product::where('vendor_id', $vendorId);

        // Search Filter
        if ($search) {
            $query->where('name', 'LIKE', "%$search%");
        }

        $items = $query->with('images')->take(40)->get();

        $html = view('frontend.pages.ajax-shop-items', compact('items'))->render();

        return response()->json([
            'html' => $html,
            'count' => $items->count(),
        ]);
    }

    public function loadMoreProducts(Request $request)
    {
        $skip = $request->skip ?? 0;

        $products = Product::where('qc_status', 1)
            ->with(['images' => function($query) {
                $query->where('is_primary', true);
            }])
            ->latest()
            ->skip($skip)
            ->take(6)
            ->get();

        $html = view('frontend.ajax.products', compact('products'))->render();

        return response()->json([
            'html'  => $html,
            'count' => $products->count()
        ]);
    }

    public function loadMoreServices(Request $request)
    {
        $skip = $request->skip ?? 0;

        $services = Service::with(['category', 'vendor'])
            ->where('admin_approval', 1)
            ->latest()
            ->skip($skip)
            ->take(6)
            ->get();

        $html = view('frontend.ajax.services', compact('services'))->render();

        return response()->json([
            'html'  => $html,
            'count' => $services->count()
        ]);
    }

    public function rateProduct(Request $request)
    {
        $customer_id = auth()->guard('customer')->id();

        // Validate structure
        $request->validate([
            'product_id' => 'required|array',
            'rating'     => 'required|array',
            'review'     => 'nullable|array'
        ]);
        foreach ($request->product_id as $productId) {
            // rating exists?
            if (!isset($request->rating[$productId])) {
                continue;
            }
            ProductRating::updateOrCreate(
                [
                    'product_id' => $productId,
                    'customer_id' => $customer_id,
                ],
                [
                    'rating' => $request->rating[$productId],
                    'review' => $request->review[$productId] ?? null,
                    'status' => 0,
                ]
            );
        }
        return back()->with('success', 'Thank you for rating your products!');
    }
}
