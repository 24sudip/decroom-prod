<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;

class Authenticate extends Middleware
{
    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function redirectTo($request)
    {
        if ($request->expectsJson()) {
            return null;
        }

        // Vendor routes
        if ($request->is('vendor') || $request->is('vendor/*')) {
            return route('vendor.login');
        }

        // Admin routes
        if ($request->is('admin') || $request->is('admin/*')) {
            return route('admin.login');
        }

        // Customer routes
        if ($request->is('customer') || $request->is('customer/*')) {
            return route('customer.login');
        }

        // Default fallback (optional)
        return route('customer.login');
    }
}
