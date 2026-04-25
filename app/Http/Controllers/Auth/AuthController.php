<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\User;

class AuthController extends Controller
{
    /**
     * Show login form
     */
    public function showLoginForm(Request $request)
    {
        $user = Auth::guard('admin')->user();

        if ($user) {
            return redirect()->route('dashboard');
        }
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        
        return view('backend.auth.login');
    }

    /**
     * Handle login for Admin / Vendor / Customer
     */
    public function login(Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required|min:6',
            // 'type'     => 'required|in:admin,vendor,customer',
        ]);

        $email = $request->email;
        $password = $request->password;
        // $type = $request->type;
        
        $adminRoleId = 1;
        
        // adjust according to roles table
        $user = User::where('email', $email)
        ->where('role_id', $adminRoleId)
        ->where('is_active', 1)
        ->first();

        if ($user && Hash::check($password, $user->password)) {
            Auth::guard('admin')->login($user); // ✅ KEY FIX
            session(['user_type' => 'admin', 'admin_id' => $user->id]);
            Toastr::success('Welcome back, ' . $user->name);
            return redirect()->intended('/dashboard');
        }
        // switch ($type) {
        //     case 'vendor':
        //         // role_id for vendor (adjust according to your roles table)
        //         $vendorRoleId = 2; 

        //         $user = User::where('email', $email)
        //                     ->where('role_id', $vendorRoleId)
        //                     ->where('is_active', 1)
        //                     ->first();

        //         if ($user && Hash::check($password, $user->password)) {
        //             Auth::login($user);
        //             session(['user_type' => 'vendor', 'vendor_id' => $user->id]);
        //             Toastr::success('Welcome back, ' . $user->name);
        //             return redirect()->intended('/dashboard');
        //         }
        //         break;

        //     case 'customer':
        //         $customerRoleId = 3; // adjust according to roles table
        //         $user = User::where('email', $email)
        //                     ->where('role_id', $customerRoleId)
        //                     ->where('is_active', 1)
        //                     ->first();

        //         if ($user && Hash::check($password, $user->password)) {
        //             Auth::login($user);
        //             session(['user_type' => 'customer', 'customer_id' => $user->id]);
        //             Toastr::success('Welcome back, ' . $user->name);
        //             return redirect()->intended('/dashboard');
        //         }
        //         break;

        //     case 'admin':
        //     default:
        //         $adminRoleId = 1; // adjust according to roles table
        //         $user = User::where('email', $email)
        //                     ->where('role_id', $adminRoleId)
        //                     ->where('is_active', 1)
        //                     ->first();

        //         if ($user && Hash::check($password, $user->password)) {
        //             Auth::login($user);
        //             session(['user_type' => 'admin', 'admin_id' => $user->id]);
        //             Toastr::success('Welcome back, ' . $user->name);
        //             return redirect()->intended('/dashboard');
        //         }
        //         break;
        // }

        return back()->withErrors(['email' => 'Invalid credentials or account not found.'])
                     ->withInput($request->only('email', 'type'));
    }

    /**
     * Logout user based on session
     */
    public function logout(Request $request)
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        Toastr::success('Logged out successfully!');
        
        return redirect()->route('admin.login')->with('success', 'Logged out successfully!');
        
        // $guard = session('user_type', 'web');

        // switch ($guard) {
        //     case 'vendor':
        //         return redirect()->route('admin.login');
        //     case 'customer':
        //         return redirect()->route('customer.login');
        //     default:
        //         return redirect()->route('admin.login');
        // }
    }

    /**
     * Show password change form
     */
    public function showChangePasswordForm()
    {
        return view('backend.auth.passwords.changePassword');
    }

    /**
     * Change password for logged-in user
     */
    public function changePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'password'         => 'required|confirmed|min:6',
        ]);

        $user = Auth::user();

        if (!$user || !Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Current password does not match.']);
        }

        $user->update([
            'password' => Hash::make($request->password),
        ]);

        Toastr::success('Password updated successfully!');
        return back();
    }
}
