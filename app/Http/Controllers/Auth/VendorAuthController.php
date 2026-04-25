<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth, File, Validator};
use Illuminate\Support\Facades\{Hash, Session};
use App\{User, Chat};
use App\Vendor;
use App\SellerWallet;
use Brian2694\Toastr\Facades\Toastr;
use Illuminate\Support\Facades\Mail;
use App\Mail\{VerifyEmailMail, PasswordVerifyMail};
use Laravel\Socialite\Facades\Socialite;
use Illuminate\Support\Str;

class VendorAuthController extends Controller
{
    public function unreadMessages()
    {
        $vendor = Auth::guard('vendor')->user();

        $count = Chat::where('sellerId', $vendor->id)
            ->where('status', 1)
            ->count();

        $message = Chat::where('sellerId', $vendor->id)
            ->where('status', 1)
            ->latest()
            ->first();

        return response()->json([
            'message' => $message->message,
            'customer' => $message->customer->name,
            'customer_id' => $message->customer->id,
            'count' => $count
        ]);
    }

    public function kycForm() {
        return view('frontend.seller.auth.vendor-kyc');
    }
    // Show vendor login form
    public function showLoginForm()
    {
        return view('frontend.seller.auth.vendor-login');
    }

    // Show vendor registration form
    public function showRegisterForm()
    {
        return view('frontend.seller.auth.vendor-register');
    }

    // Handle vendor login
    public function kycSubmit(Request $request) {
        $request->validate([
            'name' => 'required|string|max:255',
            'father_name' => 'required|string|max:255',
            'mother_name' => 'required|string|max:255',
            'email'    => 'required|email',
            'image' => 'required|image|mimes:webp,jpeg,png,jpg,svg,gif',
            'nid_front' => 'required|image|mimes:webp,jpeg,png,jpg,svg,gif',
            'nid_back' => 'required|image|mimes:webp,jpeg,png,jpg,svg,gif',
        ]);
        $user = User::find($request->user_id);
        $vendor = Vendor::where('user_id', $request->user_id)->first();
        // image
        if ($request->hasFile('image')) {
            if (File::exists(public_path($user->image))) {
                File::delete(public_path($user->image));
            }
            $image = $request->file('image');
            $imageName = rand().$image->getClientOriginalName();
            $image->move(public_path('/uploads/profile'), $imageName);
            $imagePath = "/uploads/profile/". $imageName;
        }
        // nid_front
        if ($request->hasFile('nid_front')) {
            if (File::exists(public_path($vendor->nid_front))) {
                File::delete(public_path($vendor->nid_front));
            }
            $image = $request->file('nid_front');
            $imageName = rand().$image->getClientOriginalName();
            $image->move(public_path('/uploads/nid-front'), $imageName);
            $nid_front_path = "/uploads/nid-front/". $imageName;
        }
        // nid_back
        if ($request->hasFile('nid_back')) {
            if (File::exists(public_path($vendor->nid_back))) {
                File::delete(public_path($vendor->nid_back));
            }
            $image = $request->file('nid_back');
            $imageName = rand().$image->getClientOriginalName();
            $image->move(public_path('/uploads/nid-back'), $imageName);
            $nid_back_Path = "/uploads/nid-back/". $imageName;
        }
        $user->update(
            [
                'image'=>isset($imagePath) ? $imagePath : $user->image
            ]
        );
        $vendor->update(
            [
                'name'=>$request->name,
                'email'=>$request->email,
                'father_name'=>$request->father_name,
                'mother_name'=>$request->mother_name,
                'nid_front'=>isset($nid_front_path) ? $nid_front_path : $vendor->nid_front,
                'nid_back'=>isset($nid_back_Path) ? $nid_back_Path : $vendor->nid_back
            ]
        );
        return redirect()->route('vendor.dashboard')->with('success', "KYC has been submitted. Waiting For Admin Approval");
    }

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        $googleUser = Socialite::driver('google')->stateless()->user();

        $user = User::where('email', $googleUser->email)->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->name,
                'email' => $googleUser->email,
                'password' => bcrypt(Str::random(16)),
                'google_id' => $googleUser->id,
                'role_id'   => 2,
                // 'verifyToken' => $verifyToken,
                'is_active' => 0,
            ]);
        }

        Auth::guard('vendor')->login($user);

        return redirect()->route('vendor.dashboard')->with('success', 'Welcome back, ' . $user->name);
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'phone'    => 'required',
            'password' => 'required|min:8',
        ]);

        // Vendor role_id = 2
        $vendor = User::where('phone', $request->phone)
          ->where('role_id', 2)
        //   ->where('is_active', 1)
          ->first();

        if ($vendor) {
            if(Hash::check($request->password, $vendor->password)) {
                if ($vendor->verifyToken != 1) {
                    Session::put('verifyEmail', $vendor->email);

                    $verifyToken          = rand(1111, 9999);
                    $vendor->verifyToken = $verifyToken;
                    $vendor->save();

                    $data = [
                        'verifyToken' => $verifyToken,
                        'heading' => "Your Email is confirmed on this verifyToken"
                    ];
                    $email = $vendor->email;
                    Mail::to($email)->send(new VerifyEmailMail($data));
                    return redirect()->route('vendor.verify')->with('error', "Please verify your account first");
                }
                if($vendor->is_active != 1) {
                    $real_vendor = Vendor::where('user_id', $vendor->id)->first();
                    if( !empty($real_vendor->email) ) {
                        return redirect()->route('vendor.dashboard')->with('error', "Waiting for Admin Approval");
                    }
                    Session::put('user_id', $vendor->id);
                    return redirect()->route('vendor.kyc')->with('error', "Please fill up this kyc form first");
                }
                Auth::guard('vendor')->login($vendor);
                session(['user_type' => 'vendor', 'vendor_id' => $vendor->id]);
                return redirect()->route('vendor.dashboard')->with('success', 'Welcome back, ' . $vendor->name);
            } else {
                return back()->withErrors(['phone' => 'Invalid credentials.'])->withInput();
            }
        } else {
            return back()->withErrors(['phone' => 'Account not found.'])->withInput();
        }
    }

    // Handle vendor registration
    public function registerSubmit(Request $request)
    {
        if (!preg_match('/@(gmail|yahoo|hotmail|outlook)\./i', $request->email)) {
            return back()
                ->with('error', 'Only Public email providers are allowed.')
                ->withInput();
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'email' => [
                'required',
                'email:rfc,dns',
                'regex:/^(?!.*@(mailinator|tempmail|10minutemail)\.com).*$/i',
                'unique:users'
            ],
            'phone' => ['required','min:11','max:15','unique:users','regex:/^(\+88)?\d{11}$/'],
            'password' => 'required|min:8|confirmed',
        ]);

        $verifyToken    = rand(1111, 9999);

        // Create user
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'phone'     => $request->phone,
            'password'  => Hash::make($request->password),
            'role_id'   => 2,
            'verifyToken' => $verifyToken,
            'is_active' => 0,
        ]);

        // Create vendor record
        Vendor::create([
            'user_id' => $user->id,
            'shop_name' => 'Your Shop Name',
            'status' => 1,
        ]);

        // Seller Wallet record
        SellerWallet::create([
            'title'     => 'Initial Wallet',
            'vendor_id' => $user->id,
            'amount'    => 0,
            'current'   => 0,
            'credit'    => 0,
            'status'    => 1,
        ]);
        $data = [
            'verifyToken' => $verifyToken,
            'heading' => "Your Email is confirmed on this verifyToken"
        ];
        $email = $user->email;
        Mail::to($email)->send(new VerifyEmailMail($data));

        Session::put('verifyEmail', $user->email);
        return redirect()->route('vendor.verify')->with('success', 'Account created! Verify here..');

        // Login vendor
        // Auth::guard('vendor')->login($user);
        // session(['user_type' => 'vendor', 'vendor_id' => $user->id]);

        // Toastr::success('Registration successful! Welcome ' . $user->name);
        // return redirect()->route('vendor.dashboard');
    }

    public function verifySubmit(Request $request) {
        $email = Session::get('verifyEmail');

        $user = User::where('email', $email)->first();

        if ($user->verifyToken != $request->otp) {
            return redirect()->back()->with('error', 'Invalid token');
        }

        $user->verifyToken = 1;
        $user->save();
        return redirect()->route('vendor.login')->with('success', "Accound verified. Please login");
    }

    public function forgetpassword(Request $request){
        $user = User::where('email', $request->email)->first();

        if(!$user){
            return redirect()->back()->with('error', 'This email has no account');
        }

        $passresetToken          = rand(1111, 9999);
        $user->passresetToken = $passresetToken;
        $user->save();

        $data = [
            'verifyToken' => $passresetToken,
            'heading' => "Your Password will be Confirmed By This Reset Token"
        ];
        $email = $user->email;
        Mail::to($email)->send(new PasswordVerifyMail($data));

        Session::put('verifyEmail', $user->email);

        return redirect()->route('vendor.password.reset')->with('success', 'Reset your password here..');
    }

    public function resetpassword(Request $request){
        $email = Session::get('verifyEmail');

        $user = User::where('email', $email)->first();

        if ($user->passresetToken != $request->passresetToken) {
            return redirect()->back()->with('error', 'Invalid token');
        }

        $user->password    = Hash::make($request->password);
        $user->passresetToken = 1;
        $user->save();

        return redirect()->route('vendor.login')->with('success', "Password reset successfull. Please login");
    }

    public function passwordReset() {
        return view('frontend.seller.auth.password-reset');
    }

    public function passwordForget() {
        return view('frontend.seller.auth.password-forget');
    }

    public function verify() {
        return view('frontend.seller.auth.vendor-verify');
    }
    // Logout vendor
    public function logout(Request $request)
    {
        Auth::guard('vendor')->logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('vendor.login')->with('success', 'Logged out successfully!');
    }

    // TODO: Add Google login methods later if needed
}
