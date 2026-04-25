<?php

use App\Http\Controllers\AboutController;
use App\Http\Controllers\AccountEntryController;
use App\Http\Controllers\AccountHeadController;
use App\Http\Controllers\AppSettingController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\Auth\VendorAuthController;
use App\Http\Controllers\Frontend\VendorDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Backend\ModuleController;
use App\Http\Controllers\Backend\PermissionController;
use App\Http\Controllers\Backend\RoleController;
use App\Http\Controllers\Backend\{SupplierController, ServiceDraftController, SettingController};
use App\Http\Controllers\CouponController;
use App\Http\Controllers\CustomerController;
use App\Http\Controllers\FaqController;
use App\Http\Controllers\ProductQuestionController;
use App\Http\Controllers\Frontend\CartController;
use App\Http\Controllers\Frontend\CheckoutController;
use App\Http\Controllers\Frontend\FrontendController;
use App\Http\Controllers\Frontend\PrescriptionController;
use App\Http\Controllers\GenericController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\InventoryController;
use App\Http\Controllers\VendormanageController;
use App\Http\Controllers\LandingController;
use App\Http\Controllers\OrderReportController;
use App\Http\Controllers\PageCategoryController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ProductBrandController;
use App\Http\Controllers\PaymentMethodController;
use App\Http\Controllers\ProductCategoryController;
use App\Http\Controllers\OfferBannerController;
use App\Http\Controllers\ProductChildcategoryController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProductSubcategoryController;
use App\Http\Controllers\PurchaseController;
use App\Http\Controllers\PurchaseReturnController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\ShippingAddressController;
use App\Http\Controllers\SliderController;
use App\Http\Controllers\SupplierPaymentController;
use App\Http\Controllers\UniteController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\{ServiceController, ServiceCategoryController};
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Http\Request;

// use Illuminate\Support\Facades\Mail;
// use App\Mail\VerifyEmailMail;
// use App\{Customer, User, Product};
// use App\Notifications\OrderComplete;
// use Illuminate\Support\Facades\Notification;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

//------------------------//
// Cache Clear Route
//------------------------//
Route::get('/cc', function () {
    Artisan::call('cache:clear');
    Artisan::call('config:clear');
    Artisan::call('route:clear');
    Artisan::call('view:clear');
    Artisan::call('config:cache');
    return '<h1>All Config cleared</h1>';
});

// Artisan::call('make:mail PasswordVerifyMail');
// $data = [
//     'verifyToken' => "Bizzare",
//     'heading' => "Your Email is confirmed on this verifyToken"
// ];
// $email = "rishad.quicktech@gmail.com";
// Mail::to($email)->send(new VerifyEmailMail($data));

Route::get('/force-logout', function (Request $request) {
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return '<h1>logout</h1>';
});

// $customer = Customer::where('phone', '01981812616')->first();
// $user = User::where('phone','+1 (469) 488-9148')->first();
// $product = Product::find(13);
// Notification::send($user, new OrderComplete($customer->name, $product->name));
Route::get('/test', function () {
    return '<h1>notify</h1>';
});
// $exitCode = Artisan::call('make:middleware SaveLastUrl');
// $exitCode = Artisan::call('migrate --path=/database/migrations/2026_02_06_170219_create_service_shares_table.php');
// $exitCode = Artisan::call('make:model ServiceShare -m');

Route::get('/debug-auth', function() {
    return response()->json([
        'authenticated' => auth()->check(),
        'user_id' => auth()->id(),
        'user_name' => auth()->user()->name ?? 'No user',
        'session_id' => session()->getId(),
        'csrf_token' => csrf_token()
    ]);
});


Route::get('lang/{lang}', function($lang){
    session(['app_locale' => $lang]);
    return back();
})->name('lang.change');


//------------------------//
// Admin Authentication
//------------------------//
Route::prefix('admin')->group(function () {
    // Route::get('dashboard', [AdminController::class, 'dashboard'])->name('admin.dashboard')->middleware('auth:admin');

    Route::get('login', [AuthController::class, 'showLoginForm'])->name('admin.login');
    Route::post('login', [AuthController::class, 'login'])->name('admin.submitLogin');
    Route::post('logout', [AuthController::class, 'logout'])->name('admin.logout');

    Route::get('register', [AuthController::class, 'showRegistrationForm'])->name('admin.register');
    Route::post('register', [AuthController::class, 'register'])->name('admin.register.submit');

    Route::get('change-password', [AuthController::class, 'showChangePasswordForm'])->name('admin.password.change');
    Route::post('change-password', [AuthController::class, 'changePassword'])->name('admin.password.update');
});

//------------------------//
// Vendor Routes
//------------------------//
Route::prefix('vendor')->name('vendor.')->group(function () {

    Route::get('kyc', [VendorAuthController::class, 'kycForm'])->name('kyc');
    Route::post('kyc', [VendorAuthController::class, 'kycSubmit'])->name('kyc.submit');

    Route::get('password/reset', [VendorAuthController::class, 'passwordReset'])->name('password.reset');
    Route::post('reset/password', [VendorAuthController::class, 'resetpassword'])->name('reset.password');

    Route::get('password/forget', [VendorAuthController::class, 'passwordForget'])->name('password.forget');
    Route::post('forget/password', [VendorAuthController::class, 'forgetpassword'])->name('forget.password');

    Route::get('verify', [VendorAuthController::class, 'verify'])->name('verify');
    Route::post('verify', [VendorAuthController::class, 'verifySubmit'])->name('verify.submit');

    // Guest middleware for vendors (vendor login/registration)
    Route::middleware('guest:vendor')->group(function () {
        // Login
        Route::get('login', [VendorAuthController::class, 'showLoginForm'])->name('login');
        Route::post('login', [VendorAuthController::class, 'loginSubmit'])->name('login.submit');

        // Register
        Route::get('register', [VendorAuthController::class, 'showRegisterForm'])->name('register');
        Route::post('register', [VendorAuthController::class, 'registerSubmit'])->name('register.submit');

        // Google Login
        Route::get('login/google', [VendorAuthController::class, 'redirect'])->name('google.login');
        Route::get('login/google/callback', [VendorAuthController::class, 'callback'])->name('google.callback');
    });

    // Authenticated vendor routes (vendor dashboard and management)
    Route::middleware('auth:vendor')->group(function () {
        // routes/web.php
        Route::get('/unread-messages', [VendorAuthController::class, 'unreadMessages']);

        // Authentication
        Route::post('logout', [VendorAuthController::class, 'logout'])->name('logout');

        //order
        Route::get('orders', [VendorDashboardController::class, 'orderList'])->name('orders.list');
        Route::get('orders/{id}', [VendorDashboardController::class, 'show'])->name('orders.show');
        Route::post('orders/update-status', [VendorDashboardController::class, 'updateOrderStatus'])->name('orders.updateStatus');


        // Dashboard & Profile
        Route::get('dashboard', [VendorDashboardController::class, 'index'])->name('dashboard');
        Route::get('profile', [VendorDashboardController::class, 'profile'])->name('profile');
        Route::post('profile/update-image', [VendorDashboardController::class, 'updateProfileImage'])->name('profile.updateImage');
        Route::get('profile/edit', [VendorDashboardController::class, 'editProfile'])->name('profile.edit');
        Route::post('profile/update', [VendorDashboardController::class, 'updateProfile'])->name('profile.update');

        // Product Management
        Route::prefix('products')->name('products.')->group(function () {
            Route::get('create', [VendorDashboardController::class, 'create'])->name('create');
            Route::post('store', [VendorDashboardController::class, 'store'])->name('store');
            Route::get('manage', [VendorDashboardController::class, 'manage'])->name('manage');
            Route::get('{id}/edit', [VendorDashboardController::class, 'edit'])->name('edit');
            Route::put('{id}', [VendorDashboardController::class, 'update'])->name('update');
            Route::patch('{id}/status', [VendorDashboardController::class, 'updateStatus'])->name('update-status');
            Route::delete('{id}', [VendorDashboardController::class, 'destroy'])->name('destroy');
            Route::patch('{id}/restore', [VendorDashboardController::class, 'restore'])->name('restore');
            Route::delete('{id}/force-delete', [VendorDashboardController::class, 'forceDelete'])->name('force-delete');
        });

        //inventory
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('stock', [VendorDashboardController::class, 'stockManage'])->name('stock.manage');
            Route::post('stock-update/{id}', [VendorDashboardController::class, 'stockUpdate'])->name('stock.update');
            Route::post('warning-limit-update', [VendorDashboardController::class, 'WarningLimitUpdate'])
            ->name('warning-limit.update');
            Route::get('stock-warning', [VendorDashboardController::class, 'stockWarning'])->name('stock.warning');
            Route::get('stock-out', [VendorDashboardController::class, 'stockOut'])->name('stock.out');
        });

        // Seller chat
        Route::get('/me/seller/chat', [VendorDashboardController::class, 'chat'])->name('message');
        Route::get('me/seller/chat-withcustomer/{name}/{id}', [VendorDashboardController::class, 'chatwithcustomer']);
        Route::get('me/seller/chat/content', [VendorDashboardController::class, 'chatcontent']);
        Route::post('/me/seller/to/customer/message', [VendorDashboardController::class, 'sellertocustomersms'])->name('send-message');

        // Wallet and Ledger
        Route::get('/me/seller/wallet-all', [VendorDashboardController::class, 'walletAll'])->name('wallet.all');
        Route::get('/me/seller/wallet', [VendorDashboardController::class, 'wallet'])->name('wallet');
        Route::get('/me/seller/transactions', [VendorDashboardController::class, 'transactions'])->name('transactions');
        Route::post('/me/seller/wallet/send-request', [VendorDashboardController::class, 'widthdrawRequest'])->name('send-request');
    });
});

Route::get('shop/list', [FrontendController::class, 'vendorShopList'])->name('vendor.shop.list');
Route::get('shop/{id}', [FrontendController::class, 'vendorShop'])->name('vendor.shop.view');

// Follow functionality (requires customer authentication)
Route::post('shop/follow/{id}', [FrontendController::class, 'followVendor'])->middleware('auth:customer')->name('vendor.shop.follow');
Route::post('/product/rate', [FrontendController::class, 'rateProduct'])->name('product.rate')->middleware('auth:customer');

//------------------------//
// Customer Authentication
//------------------------//
Route::prefix('customer')->group(function () {
    Route::get('verify', [CustomerController::class, 'verify'])->name('customer.verify');
    Route::post('verify/submit', [CustomerController::class, 'verifySubmit'])->name('customer.verify.submit');

    // Password Reset
    Route::get('forgot-password', [CustomerController::class, 'showForgotPasswordForm'])->name('customer.password.request');
    Route::post('password-forget', [CustomerController::class, 'passwordForget'])->name('customer.password.forget');

    Route::get('password-reset', [CustomerController::class, 'passwordReset'])->name('customer.password.reset');
    Route::post('reset/password', [CustomerController::class, 'resetpassword'])->name('customer.reset.password');

    // Registration Routes - Only accessible by guests
    Route::middleware('guest:customer')->group(function () {
        Route::get('register', [CustomerController::class, 'register'])->name('customer.register');
        Route::post('register/submit', [CustomerController::class, 'registerSubmit'])->name('customer.register.submit');

        // Login Routes
        Route::get('login', [CustomerController::class, 'login'])->name('customer.login');
        Route::post('login/submit', [CustomerController::class, 'loginSubmit'])->name('customer.login.submit');
    });

    // Logout (accessible to authenticated customers)
    Route::post('logout', [CustomerController::class, 'logout'])->name('customer.logout');

    // Protected routes - Only accessible by authenticated customers
    Route::middleware('auth:customer')->group(function () {
        // Profile Management
        Route::get('/like/service/{service_id}', [CustomerController::class, 'SaveLikeService'])->name('customer.like-service');
        Route::post('/comment/service/{service_id}', [CustomerController::class, 'CommentService'])->name('customer.comment-service');

        Route::get('profile/{tab?}', [CustomerController::class, 'profile'])->name('customer.profile');
        Route::post('profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

        // Shipping Address Management
        Route::prefix('shipping')->name('shipping.')->group(function () {
            Route::get('/', [ShippingAddressController::class, 'index'])->name('index');
            Route::get('/create', [ShippingAddressController::class, 'create'])->name('create');
            Route::post('/store', [ShippingAddressController::class, 'store'])->name('store');
            Route::get('/edit/{id}', [ShippingAddressController::class, 'edit'])->name('edit');
            Route::post('/update/{id}', [ShippingAddressController::class, 'update'])->name('update');
            Route::delete('/delete/{id}', [ShippingAddressController::class, 'destroy'])->name('destroy');
        });

        // customer chat
        Route::get('/customer/chat', [CustomerController::class, 'chat'])->name('customer.message');
        Route::get('/customer/chat-with-seller/{id}', [CustomerController::class, 'chatwithSeller'])->name('customer.chat-with-seller');
        Route::get('customer/chat/content', [CustomerController::class, 'customerchat']);
        Route::post('customer/to/seller/message', [CustomerController::class, 'custosellersms'])->name('customer.send-message');

        Route::post('customer/to/seller/ask-message', [CustomerController::class, 'customerSellerAsk'])->name('customer.ask-message');
    });
});

//------------------------//
// Dashboard
//------------------------//
Route::get('/dashboard', [HomeController::class, 'index'])->name('dashboard');


//Site settings
Route::get('app-setting', [AppSettingController::class, 'index']);
Route::post('update-setting', [AppSettingController::class, 'update'])->name('update-setting');

//------------------------//
// Resource Routes
//------------------------//
Route::resource('user', UserController::class);
Route::resource('category', ProductCategoryController::class)->except(['show']);
Route::resource('offerbanner', OfferBannerController::class)->except(['show']);
Route::resource('brand', ProductBrandController::class);
Route::resource('paymentmethod', PaymentMethodController::class);
Route::resource('unit', UniteController::class);
Route::resource('generic', GenericController::class);
Route::resource('supplier', SupplierController::class);
Route::resource('slider', SliderController::class);
Route::resource('subcategories', ProductSubcategoryController::class);
Route::resource('childcategories', ProductChildcategoryController::class);
Route::resource('page-categories', PageCategoryController::class);
Route::resource('pages', PageController::class);
Route::resource('faqs', FaqController::class);
Route::resource('coupons', CouponController::class);
Route::resource('abouts', AboutController::class);
Route::resource('account-heads', AccountHeadController::class);
Route::resource('account-entries', AccountEntryController::class);
Route::resource('servicecategory', ServiceCategoryController::class);
Route::post('servicecategory/toggle-status', [ServiceCategoryController::class, 'toggleStatus'])->name('servicecategory.toggle-status');
Route::resource('services', ServiceController::class);
Route::resource('service-draft', ServiceDraftController::class);

Route::patch('/slider/{slider}/toggle-status', [SliderController::class, 'toggleStatus'])->name('slider.toggleStatus');
Route::post('/category/toggle-home', [ProductCategoryController::class, 'toggleHome'])->name('category.toggle-home');
Route::post('/offerbanner/toggle-home', [OfferBannerController::class, 'toggleStatus'])->name('offerbanner.toggle-status');

Route::prefix('admin')->group(function () {
    Route::resource('product', ProductController::class);
    Route::post('product/update-status', [ProductController::class, 'updateStatus'])->name('admin.product.updateStatus');
    Route::get('product/view/{id}', [ProductController::class, 'viewDetails'])->name('admin.product.view');

    // Service Routes
    Route::get('get-services', [ServiceController::class, 'getServices'])->name('admin.service');
    Route::post('service-delete/status/{id}', [ServiceController::class, 'DeleteStatusChange'])->name('admin.service-delete.status');

    Route::get('service-draft/all', [SettingController::class, 'ServiceDraftAll'])->name('admin.service-draft.all');
    Route::post('service-draft/update-status', [SettingController::class, 'updateServiceDraftStatus'])
    ->name('admin.service-draft.updateStatus');
    Route::post('service-draft/status/{id}', [SettingController::class, 'changeServiceDraftStatus'])
    ->name('admin.service-draft.status');

    Route::get('services/view/{id}', [ServiceController::class, 'viewDetails'])->name('admin.services.view');
    Route::post('services/update-status', [ServiceController::class, 'updateServiceStatus'])->name('admin.services.updateStatus');

    Route::post('services/reject/{id}', [ServiceController::class, 'RejectService'])->name('admin.services.reject');

    Route::delete('services/{service}', [ServiceController::class, 'destroy'])->name('admin.services.destroy');
});

//------------------------//
// Product Dependent Routes
//------------------------//
Route::get('/get-subcategories/{category_id}', [ProductController::class, 'getSubcategories']);
Route::get('/get-childcategories/{subcategory_id}', [ProductController::class, 'getChildcategories']);
Route::post('/product/import', [ProductController::class, 'import'])->name('product.import');
Route::post('generic/import', [GenericController::class, 'import'])->name('generic.import');
Route::post('brand/import', [ProductBrandController::class, 'import'])->name('brand.import');

//------------------------//
// Vendor manage Routes
//------------------------//
Route::post('admin/vendor-status-change/{id}', [VendormanageController::class, 'vendorStatusChange'])->name('admin.vendor-status-change');
Route::get('admin/vendor-kyc', [VendormanageController::class, 'vendorKyc'])->name('admin.vendor-kyc');

Route::get('admin/vendorlist', [VendormanageController::class, 'vendorList'])->name('admin.vendorList');
Route::post('admin/vendor-destroy/{id}', [VendormanageController::class, 'vendorDestroy'])->name('admin.vendor-destroy');

Route::get('admin/ordercommission', [VendormanageController::class, 'orderCommission'])->name('admin.orderCommission');
Route::post('/vendor/commission-update', [VendormanageController::class, 'updateCommission'])->name('admin.vendor.commission.update');

Route::get('/seller/withdraw-request', [VendormanageController::class, 'withdrawRequest'])->name('seller.withdrawRequest');
Route::post('/seller/withdraw-request/approved', [VendormanageController::class, 'withdrawRequestApproved'])->name('seller.withdrawApproved');
Route::get('/seller/withdraw', [VendormanageController::class, 'withdraws'])->name('seller.withdraw');
Route::post('/seller/withdraw/receipt-upload', [VendormanageController::class, 'receiptUpload'])->name('seller.receiptUpload');
Route::get('/seller/transactions', [VendormanageController::class, 'ledger'])->name('seller.transactions');


//------------------------//
// Inventory Routes
//------------------------//
Route::get('stock', [InventoryController::class, 'stockManage'])->name('product.stock');
Route::get('stock-warning', [InventoryController::class, 'stockWarning'])->name('product.stock_warning');
Route::get('stock-out', [InventoryController::class, 'stockOut'])->name('product.stock_out');
Route::get('/expired-list', [InventoryController::class, 'expiredList'])->name('expired.list');

//------------------------//
// Purchase & Purchase Return Routes
//------------------------//
Route::get('purchase/index', [PurchaseController::class, 'index'])->name('purchase.index');
Route::get('purchase/create', [PurchaseController::class, 'create'])->name('purchase.create');
Route::post('purchase/store', [PurchaseController::class, 'store'])->name('purchase.store');

Route::get('purchase-return/{purchase}', [PurchaseReturnController::class, 'create'])->name('purchase-return.create');
Route::post('purchase-return-store', [PurchaseReturnController::class, 'store'])->name('purchase-return.store');
Route::get('purchase-return-list', [PurchaseReturnController::class, 'index'])->name('purchase-return.index');
Route::get('return-show/{return}', [PurchaseReturnController::class, 'show'])->name('purchase-return.show');
Route::get('return-edit/{return}/edit', [PurchaseReturnController::class, 'edit'])->name('purchase-return.edit');
Route::put('return-update/{return}', [PurchaseReturnController::class, 'update'])->name('purchase-return.update');
Route::delete('purchase-return/{return}', [PurchaseReturnController::class, 'destroy'])->name('purchase-return.destroy');

//prescriptions route
Route::get('/upload-prescription', [PrescriptionController::class, 'create'])->name('frontend.prescriptions.create')
->middleware('auth:customer');
Route::post('/prescriptions/upload', [PrescriptionController::class, 'upload'])->name('frontend.prescriptions.upload')
->middleware('auth:customer');
Route::get('/prescriptions/download/{id}', [PrescriptionController::class, 'download'])
->name('frontend.prescriptions.download')
->middleware('auth:customer');

//------------------------//
// Supplier & Payments Routes
//------------------------//
Route::get('supplier/{id}/ledger', [SupplierController::class, 'ledger'])->name('supplier.ledger');
Route::get('admin/supplier/ledger', [SupplierController::class, 'allLedgers'])->name('supplier.ledger-list');

// Route::get('customer/wholesale', [CustomerController::class, 'wholesaleCustomer'])->name('customer.wholesale_list');
// Route::get('customer/retailer', [CustomerController::class, 'retailerCustomer'])->name('customer.retailer_list');

Route::get('supplier-payment', [SupplierPaymentController::class, 'index'])->name('supplier_payment.index');
Route::get('supplier-payment/create', [SupplierPaymentController::class, 'create'])->name('supplier_payment.create');
Route::post('supplier-payment/store', [SupplierPaymentController::class, 'store'])->name('supplier_payment.store');


Route::get('customer/wholesellregister', [CustomerController::class, 'wholesellRegister'])->name('customer.wholesellregister');
Route::get('customer/wholesale', [CustomerController::class, 'wholesaleCustomer'])->name('customer.wholesale_list');
Route::get('customer/retailer', [CustomerController::class, 'retailerCustomer'])->name('customer.retailer_list');
Route::post('customer/store', [CustomerController::class, 'store'])->name('customer.store');
Route::get('customer/{id}/edit', [CustomerController::class, 'edit'])->name('customer.edit');
// Route::post('customer/{id}/update', [CustomerController::class, 'update'])->name('customer.update');
Route::delete('customer/{id}', [CustomerController::class, 'destroy'])->name('customer.delete');
Route::get('/get-upazilas/{district_id}', [CustomerController::class, 'getUpazilas']);

Route::get('otp/verify/{id}', [CustomerController::class, 'verifyForm'])->name('otp.verify.form');
Route::post('otp/verify/{id}', [CustomerController::class, 'verifyOtp'])->name('otp.verify.submit');
Route::get('otp/resend/{id}', [CustomerController::class, 'resendOtp'])->name('customer.resendOtp');
Route::post('/customer/profile/update', [CustomerController::class, 'updateProfile'])->name('customer.profile.update');

Route::post('/customer/add/payment/{id}', [CheckoutController::class, 'AddPayment'])->name('customer.add.payment');

//------------------------//
// Frontend Routes
//------------------------//
Route::get("/", [FrontendController::class, "index"])->name('home');
Route::get('/search-list', [FrontendController::class, 'ajaxSearchList'])->name('frontend.searchlist');
Route::get('/search', [FrontendController::class, 'ajaxSearch'])->name('frontend.search');
Route::get('/vendor/shop/ajax', [FrontendController::class, 'ajaxShopList'])->name('vendor.shop.ajax');

Route::get('/load-more-products', [FrontendController::class, 'loadMoreProducts'])->name('load.more.products');
Route::get('/load-more-services', [FrontendController::class, 'loadMoreServices'])->name('load.more.services');

Route::get('category/{slug}', [FrontendController::class, 'CategoryWiseProduct'])->name('category_wise_product');
Route::get('category/subcategory/{slug}', [FrontendController::class, 'SubCategoryWiseProduct'])->name('sub_category_wise_product');
Route::get('category/child/{slug}', [FrontendController::class, 'ChildCategoryWiseProduct'])->name('child_category_wise_product');

Route::get('all-product', [FrontendController::class, 'allProducts'])->name('vendorproduct.index');
Route::get('product-details/{id}', [FrontendController::class, 'productDetails'])->name('product.details');
Route::get('product-category/{slug}', [FrontendController::class, 'productsByCategory'])->name('product_category');

Route::get('/services/{service}/share/{platform}',
    [FrontendController::class, 'share']
)->name('services.share');

Route::get('all-services', [FrontendController::class, 'allServices'])->name('service.index');
Route::get('service-details/{id}', [FrontendController::class, 'serviceDetails'])->name('service.details');
Route::get('service-draft/details/{id}', [FrontendController::class, 'serviceDraftDetails'])->name('service-draft.details');
Route::get('service-category/{slug}', [FrontendController::class, 'servicesByCategory'])->name('service_category');

Route::get('brand-product/{id}', [FrontendController::class, 'BrandWiseProduct'])->name('brand_wise_product');

Route::get('privacy/policy', [FrontendController::class, 'privacyPolicy'])->name('privacy_policy');
Route::get('terms-and-condition', [FrontendController::class, 'termsAndCondition'])->name('terms_and_condition');
Route::get('contact-us', [FrontendController::class, 'contact'])->name('contact_us');
Route::get('warranty', [FrontendController::class, 'warranty'])->name('warranty');
Route::get('delivery', [FrontendController::class, 'delivery'])->name('delivery');
Route::get('help-center', [FrontendController::class, 'help_center'])->name('help_center');
Route::get('about-us', [FrontendController::class, 'aboutUs'])->name('about_us');
Route::get('return-policy', [FrontendController::class, 'returnPolicy'])->name('return_policy');
Route::get('faq-data', [FrontendController::class, 'FAQ'])->name('faq_data');


//Order Manage
Route::get('/order/details/{id}', [FrontendController::class, 'orderDetails'])->name('order.details');

// Product questions routes
Route::post('/products/{product}/questions', [ProductQuestionController::class, 'store'])
    ->name('product.question.store')->middleware('auth:customer');

Route::put('/products/questions/{question}/answer', [ProductQuestionController::class, 'answer'])
    ->name('product.question.answer')
    ->middleware('auth:vendor');

Route::delete('/products/questions/{question}', [ProductQuestionController::class, 'destroy'])
    ->name('product.question.destroy')
    ->middleware('auth');

// Optional AJAX route
Route::put('/products/questions/{question}/answer-ajax', [ProductQuestionController::class, 'answerAjax'])
    ->name('product.question.answer.ajax')
    ->middleware('auth:vendor');

//------------------------//
// Cart & Checkout
//------------------------//
Route::prefix('cart')->group(function () {
    Route::get('/', [CartController::class, 'cart'])->name('cart.view');
    Route::post('add', [CartController::class, 'addToCart'])->name('cart.add');
    Route::post('update', [CartController::class, 'updateCart'])->name('cart.update');
    Route::delete('remove/{id}', [CartController::class, 'removeCartItem'])->name('cart.remove');
    Route::delete('clear', [CartController::class, 'clearCart'])->name('cart.clear');
});

Route::post('/cart/move-to-wishlist', [CartController::class, 'moveToWishlist'])->name('cart.moveToWishlist');

Route::get('/cart/count', function (App\Services\CartService $cart) {
    return response()->json(['count' => $cart->count()]);
})->name('cart.count');

//Coupne apply
Route::post('/cart/apply-coupon', [CartController::class, 'applyCoupon'])->name('cart.applyCoupon');
Route::get('/cart/remove-coupon', [CartController::class, 'removeCoupon'])->name('cart.removeCoupon');

Route::get('/checkout/quick/{id}', [CheckoutController::class, 'quickCheckout'])->name('checkout.quick');
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::match(['GET', 'POST'], '/proceed-to-pay', [CheckoutController::class, 'proceedToPay'])->name('proceed-to-pay'); // Changed to match both
Route::post('/order-submit', [CheckoutController::class, 'placeOrder'])->name('checkout.placeOrder');
Route::post('/order/service/{id}', [CheckoutController::class, 'OrderService'])->name('order.service');

//------------------------//
// Landing Page
//------------------------//
Route::get("front-setting", [LandingController::class, 'manage'])->name('landing.manage');
Route::get("section-enable/{id}", [LandingController::class, 'section_enable'])->name('section.enable');
Route::get("section-disable/{id}", [LandingController::class, 'section_disable'])->name('section.disable');

//------------------------//
// Reports
//------------------------//
Route::prefix('reports')->group(function () {
    Route::get('profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profitLoss');
    Route::get('purchase-sale', [ReportController::class, 'purchaseSale'])->name('reports.purchaseSale');
    Route::get('supplier-customer', [ReportController::class, 'supplierCustomer'])->name('reports.supplierCustomer');
    Route::get('stock', [ReportController::class, 'stock'])->name('reports.stock');
    Route::get('supplier-product-stock', [ReportController::class, 'supplierProductStock'])->name('reports.supplierProductStock');
    Route::get('product-sell', [ReportController::class, 'productSell'])->name('reports.productSell');
    Route::get('purchase-payments', [ReportController::class, 'purchasePayments'])->name('reports.purchasePayments');
});

//------------------------//
// Order Reports
//------------------------//
Route::prefix('report/order')->group(function () {
    Route::get('/all', [OrderReportController::class, 'allOrdersView'])->name('report.order.all');
    Route::get('/all/data', [OrderReportController::class, 'allOrders'])->name('report.order.all.data');
    Route::get('/today', [OrderReportController::class, 'todaysOrders'])->name('report.order.today');
    Route::get('/today/data', [OrderReportController::class, 'todaysOrdersData'])->name('report.order.todays.data');
    Route::get('/customerwise', [OrderReportController::class, 'customerwiseOrder'])->name('report.order.customerwise');
    Route::get('/customerwise/data', [OrderReportController::class, 'customerwiseOrderData'])->name('report.order.customerwise.data');
    Route::get('/brandwise', [OrderReportController::class, 'brandwiseOrder'])->name('report.order.brandwise');
    Route::get('/brandwise/data', [OrderReportController::class, 'brandwiseOrderData'])->name('report.order.brandwise.data');
    Route::get('/status/{slug}', [OrderReportController::class, 'byOrdertype'])->name('report.order.bytype');
    Route::get('/status/{slug}/data', [OrderReportController::class, 'byOrdertypeData'])->name('report.order.bytype.data');
});

Route::post('/order/update/status', [OrderReportController::class, 'updateStatus'])->name('order.update.status');
Route::get('/order/view/{id}', [OrderReportController::class, 'orderView'])->name('order.view');
Route::get('/report/order/customer/{customerId}/orders', [OrderReportController::class, 'getCustomerOrders'])
    ->name('report.order.customer.orders');
Route::get('report/order/brandwise/view/{id}', [ReportController::class, 'brandwiseOrderView'])->name('report.order.brandwise.view');

//------------------------//
// Division,District,Upazila,Union
//------------------------//
Route::post('/mark-notification-as-read/{notification}', [FrontendController::class, 'MarkAsRead']);

Route::get('/get-divisions', [FrontendController::class, 'getDivisions']);
Route::get('/get-districts', [FrontendController::class, 'getDistricts']);
Route::get('/get-upazilas', [FrontendController::class, 'getUpazilas']);
Route::get('/get-unions', [FrontendController::class, 'getUnions']);
Route::get('/allbrand', [FrontendController::class, 'allbrand'])->name('allbrand');

// Notification routes
Route::get('notification-list', 'NotificationController@index');
Route::get('/notification/{id}', 'NotificationController@notification');
Route::post('/top-notification', 'NotificationController@notification_top');
Route::get('/notification-count', 'NotificationController@notificationCount');

// Social Media
Route::prefix('editor')->group(function () {
    Route::get('/social-media/add', 'SocialController@index');
    Route::post('/social-media/save', 'SocialController@store');
    Route::get('/social-media/manage', 'SocialController@manage')->name('social.manage');
    Route::get('/social-media/edit/{id}', 'SocialController@edit');
    Route::post('/social-media/update', 'SocialController@update');
    Route::post('/social-media/unpublished', 'SocialController@unpublished');
    Route::post('/social-media/published', 'SocialController@published');
    Route::post('/social-media/delete', 'SocialController@destroy');
});


Route::get('/test-products-json', function() {
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated'], 401);
    }

    $products = App\Product::with(['category', 'brand'])
        ->where('status', 1)
        ->limit(5)
        ->get()
        ->map(function($product) {
            return [
                'id' => $product->id,
                'name' => $product->name,
                'category' => $product->category->name ?? 'N/A',
                'brand' => $product->brand->name ?? 'N/A',
                'price' => $product->price,
                'stock' => $product->stock
            ];
        });

    return response()->json([
        'success' => true,
        'products' => $products
    ]);
});
