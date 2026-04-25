<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CustomerOnly
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!auth()->check() || !auth()->user()->isCustomer()) {
            return response()->json([
                'success' => false,
                'message' => 'Only customers can perform this action'
            ], 403);
        }

        return $next($request);
    }
}