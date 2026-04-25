<?php

namespace App\Providers;

use App\About;
use App\AppSettings;
use App\District;
use App\Ordertype;
use App\Socialmedia;
use App\ProductCategory;
use App\ServiceCategory;
use App\ShippingAddress;
use App\Upazila;
use App\Services\CartService;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        $this->app->singleton(CartService::class, function ($app) {
            return new CartService();
        });
    }

    public function boot(): void
    {
        View::composer('*', function ($view) {
            $customerId = auth('customer')->id();

            // Use our custom CartService instead of Cartalyst
            $cartService = app(CartService::class);
            $cartItems = $cartService->all();
            
            // Calculate totals using cart service methods
            $cartCount = $cartService->count();
            $cartTotal = $cartService->total();

            $view->with([
                'settings'   => AppSettings::first(),
                'socialmedia' => Socialmedia::where('status', 1)->orderBy('id', 'ASC')->get(),
                'abouts'     => About::first(),
                'categories' => ProductCategory::where('is_home', 1)
                    ->with('subcategories', 'subcategories.childcategories')
                    ->latest()
                    ->orderByRaw("id = 13 DESC")
                    ->orderBy('id', 'ASC') 
                    ->get(),
                'serviceCategories' => ServiceCategory::where('status', 1)->get(),
                'districts'  => District::all(),
                'upazilas'   => Upazila::all(),
                'addresses'  => $customerId ? ShippingAddress::with(['district', 'upazila'])
                    ->where('customer_id', $customerId)
                    ->latest()
                    ->get() : collect(),
                'cartCount'  => $cartCount,
                'cartTotal'  => $cartTotal,
                'cartItems'  => $cartItems,
                'ordertypes' => Ordertype::get(),
            ]);
        });
    }
}