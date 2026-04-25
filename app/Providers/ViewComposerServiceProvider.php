<?php

namespace App\Providers;

use App\AppSettings;
use App\District;
use App\ProductCategory;
use App\Upazila;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class ViewComposerServiceProvider extends ServiceProvider {
    /**
     * Register services.
     */
    public function register(): void {
        //
    }

    
    public function boot(): void {
        View::composer('*', function ($view) {
            $view->with([
                'settings'   => AppSettings::first(),
                'categories' => ProductCategory::where('status', 1)
                    ->with('subcategories', 'subcategories.childcategories')
                    ->latest()
                    ->get(),
                'districts'  => District::all(),
                'upazilas'   => Upazila::all(),
            ]);
        });
    }
}
