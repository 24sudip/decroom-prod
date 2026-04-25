<?php

namespace App\Http\Controllers;

use App\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HomeController extends Controller
{
    public function index()
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route('admin.login');
        }

        $role  = optional($user->role)->role_slug;
        $today = Carbon::today();

        // Base query depending on role
        $query = Order::query();

        // 👇 Vendor Dashboard - filter by vendor_id
        if ($role === 'vendor') {
            $query->where('vendor_id', $user->id);
        }

        // 👇 Customer Dashboard - filter by customer_id
        if ($role === 'customer') {
            $query->where('customer_id', $user->id);
        }

        // ✅ Core Statistics
        $data['totalOrder']      = $query->count();
        $data['todayslOrder']    = (clone $query)->whereDate('created_at', $today)->count();
        $data['newOrder']        = (clone $query)->where('created_at', '>=', now()->subDays(7))->count();
        $data['todayslSale']     = (clone $query)->whereDate('created_at', $today)->sum('total_amount');
        $data['totalSale']       = (clone $query)->sum('total_amount');
        $data['deliveredOrder']  = (clone $query)->where('status', 6)->count();
        $data['pendingOrder']    = (clone $query)->where('status', 1)->count();
        $data['processingOrder'] = (clone $query)->where('status', 3)->count();
        $data['cancelledOrder']  = (clone $query)->where('status', 7)->count();

        $data['user']  = $user;
        $data['role']  = $role;
        $data['today'] = $today->format('Y/m/d');
        $data['time']  = now()->format('H:i:s');

        // ✅ Monthly Sales
        $monthlySales = (clone $query)
            ->selectRaw('MONTH(created_at) as month, SUM(total_amount) as total')
            ->whereYear('created_at', now()->year)
            ->groupByRaw('MONTH(created_at)')
            ->pluck('total', 'month');

        $monthlySalesArray = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesArray[] = round($monthlySales[$i] ?? 0, 2);
        }

        $data['monthlySalesData'] = $monthlySalesArray;
        $data['orderStatusData']  = [
            'delivered' => $data['deliveredOrder'],
            'pending'   => $data['pendingOrder'],
            'cancelled' => $data['cancelledOrder'],
        ];

        // ✅ Role-based dashboard view logic
        switch ($user->role_id) {
            case 1: // Admin
                return view('backend.layouts.admin-dashboard', $data);

            case 2: // Vendor
                return view('backend.layouts.vendor-dashboard', $data);

            case 3: // Operator or other admin roles
                return view('backend.layouts.operator-dashboard', $data);

            default:
                if ($role === 'customer') {
                    return view('frontend.customer.dashboard', $data);
                } elseif ($role === 'accountant') {
                    return view('backend.layouts.accountant-dashboard', $data);
                }
        }

        abort(403, 'Unauthorized action.');
    }

    /**
     * Save pagination preference
     */
    public function per_page_item(Request $request)
    {
        if ($request->ajax()) {
            $page_limit = $request->page;
            $request->session()->put('page_limit', $page_limit);

            return response()->json([
                'isSuccess' => true,
                'Message'   => "Successfully set default {$page_limit} items per page!",
            ]);
        }

        return response()->json([
            'isSuccess' => false,
            'Message'   => 'Something went wrong! please try again',
        ], 409);
    }
}
