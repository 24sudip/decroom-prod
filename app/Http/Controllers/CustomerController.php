<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Customer;
use Illuminate\Support\Facades\{Validator, File};
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;
use App\{Order, LikeService};
use App\{Chat, ServiceComment};
use App\{User, ServiceOrder};
use App\ShippingAddress;
use Illuminate\Support\Facades\Mail;
use App\Mail\{VerifyEmailMail, PasswordVerifyMail};

class CustomerController extends Controller
{
    public function CommentService(Request $request, $service_id) {
        if ($request->hasFile('comment_image')) {
            $image = $request->file('comment_image');
            $imageName = rand().$image->getClientOriginalName();
            $image->move(public_path('/uploads/service-comment'), $imageName);
            $imagePath = "/uploads/service-comment/". $imageName;
        }
        ServiceComment::create(
            [
                'service_id' => $service_id,
                'customer_id' => Auth::guard('customer')->user()->id,
                'message'=>$request->message ?? null,
                'comment_image'=>isset($imagePath) ? $imagePath : null,
            ]
        );
        return redirect()->back()->with('success', 'Comment Added Successfully');
    }

    public function SaveLikeService($service_id) {
        $like_service = LikeService::where(['service_id' => $service_id,'customer_id' => Auth::guard('customer')->user()->id])->first();
        if($like_service) {
            $like_service->delete();
            return redirect()->back()->with('error', 'Like removed from this service!');
        }
        LikeService::create([
            'service_id' => $service_id,
            'customer_id' => Auth::guard('customer')->user()->id
        ]);
        return redirect()->back()->with('success', "Service Liked Successfully!");
    }

    public function passwordReset() {
        return view('frontend.pages.customer.password-reset');
    }

    public function resetpassword(Request $request){
        $email = Session::get('verifyEmail');

        $customer = Customer::where('email', $email)->first();

        if ($customer->passresetToken != $request->passresetToken) {
            return redirect()->back()->with('error', 'Invalid token');
        }

        $customer->password    = Hash::make($request->password);
        $customer->passresetToken = 1;
        $customer->save();

        return redirect()->route('customer.login')->with('success', "Password reset successfull. Please login");
    }

    public function verify() {
        return view('frontend.pages.customer.customer-verify');
    }

    public function login()
    {
        return view('frontend.pages.customer.login');
    }

    public function loginSubmit(Request $request)
    {
        $request->validate([
            'phone' => ['required','min:11','max:15','regex:/^(\+88)?\d{11}$/'],
            'password' => 'required|string',
        ]);

        $credentials = [
            'phone' => $request->phone,
            'password' => $request->password,
            'is_active' => true
        ];
        if (Auth::guard('customer')->attempt($credentials, $request->remember)) {
            $customer = Customer::where('phone', $request->phone)->first();
            if ($customer->verifyToken != 1) {
                Auth::guard('customer')->logout($customer);
                Session::put('verifyEmail', $customer->email);

                $verifyToken          = rand(1111, 9999);
                $customer->verifyToken = $verifyToken;
                $customer->save();

                $data = [
                    'verifyToken' => $verifyToken,
                    'heading' => "Your Email is confirmed on this verifyToken"
                ];
                $email = $customer->email;
                Mail::to($email)->send(new VerifyEmailMail($data));
                return redirect()->route('customer.verify')->with('error', "From Email OTP Please verify your account first");
            }
            $request->session()->regenerate();
            session(['user_type' => 'customer']);
            if (Session::has('last_url')) {
                return redirect(Session::pull('last_url'));
            }
            return redirect()->intended(route('home'))->with('success', 'Welcome back!');
        }

        return back()->withErrors([
            'phone' => 'The provided credentials do not match our records.',
        ])->onlyInput('phone');
    }

    public function logout(Request $request)
    {
        Auth::guard('customer')->logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('customer.login')->with('success', 'You have been logged out successfully.');
    }

    public function showForgotPasswordForm()
    {
        return view('frontend.pages.customer.forgot-password');
    }

    public function passwordForget(Request $request){
        $customer = Customer::where('email', $request->email)->first();

        if(!$customer){
            return redirect()->back()->with('error', 'This email has no account');
        }

        $passresetToken          = rand(1111, 9999);
        $customer->passresetToken = $passresetToken;
        $customer->save();

        $data = [
            'verifyToken' => $passresetToken,
            'heading' => "Your Password will be Confirmed By This Reset Token"
        ];
        $email = $customer->email;
        Mail::to($email)->send(new PasswordVerifyMail($data));

        Session::put('verifyEmail', $customer->email);

        return redirect()->route('customer.password.reset')->with('success', 'Reset your password here..');
    }

    public function register()
    {
        return view('frontend.pages.customer.register');
    }

    public function verifySubmit(Request $request) {
        $email = Session::get('verifyEmail');

        $customer = Customer::where('email', $email)->first();

        if ($customer->verifyToken != $request->otp) {
            return redirect()->back()->with('error', 'Invalid token');
        }

        $customer->verifyToken = 1;
        $customer->save();
        return redirect()->route('customer.login')->with('success', "Accound verified. Please login");
    }

    public function registerSubmit(Request $request)
    {
        if (!preg_match('/@(gmail|yahoo|hotmail|outlook)\./i', $request->email)) {
            return back()
                ->with('error', 'Only Public email providers are allowed.')
                ->withInput();
        }
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => ['nullable','string','email:rfc,dns','max:255','unique:customers','regex:/^(?!.*@(mailinator|tempmail|10minutemail)\.com).*$/i'],
            'phone' => ['required','min:11','max:15','unique:customers','regex:/^(\+88)?\d{11}$/'],
            'password' => 'required|string|min:8|confirmed',
            'address' => 'nullable|string|max:500',
            'district_id' => 'nullable|exists:districts,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
        ]);

        if ($validator->fails()) {
            return redirect()->back()
                ->withErrors($validator)
                ->withInput();
        }

        try {
            $verifyToken    = rand(1111, 9999);
            $customer = Customer::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'district_id' => $request->district_id,
                'upazila_id' => $request->upazila_id,
                'verifyToken' => $verifyToken,
                'is_active' => true,
            ]);

            $data = [
                'verifyToken' => $verifyToken,
                'heading' => "Your Email is confirmed on this verifyToken"
            ];
            $email = $customer->email;
            Mail::to($email)->send(new VerifyEmailMail($data));

            Session::put('verifyEmail', $customer->email);
            return redirect()->route('customer.verify')->with('success', 'Account created! Verify here..');
            // Auto login after registration
            // Auth::guard('customer')->login($customer);

            // Redirect to home instead of profile after registration
            // return redirect()->route('home')
            //     ->with('success', 'Registration successful! Welcome to our platform.');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'Registration failed. Please try again.')
                ->withInput();
        }
    }

    /**
     * Customer Profile Dashboard
     */
    public function profile($tab = 'dashboard')
    {
        $customer = Auth::guard('customer')->user();

        if (!$customer) {
            return redirect()->route('customer.login')
                ->with('error', 'Please login to access your profile.');
        }

        // Initialize data array with customer and active tab
        $data = [
            'customer' => $customer,
            'active_tab' => $tab,
        ];

        // Load data for all tabs to avoid undefined variables
        switch ($tab) {
            case 'orders':
                $data['orders'] = Order::where('customer_id', $customer->id)
                    ->latest()
                    ->paginate(10);
                break;
            case 'service-orders':
                $data['service_orders'] = ServiceOrder::where('customer_id', $customer->id)
                    ->latest()
                    ->paginate(10);
                break;

            case 'addresses':
                $data['shippingAddresses'] = ShippingAddress::where('customer_id', $customer->id)
                    ->latest()
                    ->get();
                break;

            case 'settings':
                // Settings tab doesn't need additional data
                break;

            case 'followed-vendors':
                // Load followed vendors if you have that relationship
                if (method_exists($customer, 'followedVendors')) {
                    $data['followedVendors'] = $customer->followedVendors()
                    ->with(['user.products'])
                    ->paginate(12);
                }
                break;

            default: // dashboard
                // For dashboard, load data for all statistics
                $data['orders'] = Order::where('customer_id', $customer->id)->get();
                $data['shippingAddresses'] = ShippingAddress::where('customer_id', $customer->id)->get();

                // Load followed vendors if the relationship exists
                if (method_exists($customer, 'followedVendors')) {
                    $data['followedVendors'] = $customer->followedVendors()->get();
                }
                break;
        }
        // dd($data);
        return view('frontend.pages.customer.profile', $data);
    }

    /**
     * Update Customer Profile
     */
    public function updateProfile(Request $request)
    {
        $customer = Auth::guard('customer')->user();

        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'nullable|string|email|max:255|unique:customers,email,' . $customer->id,
            'phone' => 'required|string|max:15|unique:customers,phone,' . $customer->id,
            'address' => 'nullable|string|max:500',
            'district_id' => 'nullable|exists:districts,id',
            'upazila_id' => 'nullable|exists:upazilas,id',
            'current_password' => 'nullable|string|min:6',
            'new_password' => 'nullable|string|min:6|confirmed',
        ]);

        if ($validator->fails()) {
            return redirect()->route('customer.profile', ['tab' => 'settings'])
                ->withErrors($validator)
                ->withInput();
        }

        try {
            // Update basic info
            $customer->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'district_id' => $request->district_id,
                'upazila_id' => $request->upazila_id,
            ]);

            // Update password if provided
            if ($request->filled('current_password') && $request->filled('new_password')) {
                if (Hash::check($request->current_password, $customer->password)) {
                    $customer->update([
                        'password' => Hash::make($request->new_password)
                    ]);
                } else {
                    return redirect()->route('customer.profile', ['tab' => 'settings'])
                        ->with('error', 'Current password is incorrect.');
                }
            }

            return redirect()->route('customer.profile', ['tab' => 'settings'])
                ->with('success', 'Profile updated successfully!');

        } catch (\Exception $e) {
            return redirect()->route('customer.profile', ['tab' => 'settings'])
                ->with('error', 'Failed to update profile. Please try again.');
        }
    }

    /**
     * Simple dashboard redirect (fallback)
     */
    public function dashboard()
    {
        return $this->profile('dashboard');
    }

    /**
     * Customer Chatting Section
     */
    public function chat() {
        $customer = Auth::guard('customer')->user();

        $chatlist = DB::table('users')
            ->join('chats', 'users.id', '=', 'chats.sellerId')
            ->join('vendors', 'vendors.user_id', '=', 'users.id')
            ->where('customerId', $customer->id)
            ->select('users.name','users.id','users.email','users.phone','users.image','vendors.shop_name','vendors.commission','vendors.followers_count','vendors.address','vendors.rating','vendors.logo')
            ->distinct()
            ->get();

        $active_tab = "chat";

        return view('frontend.pages.customer.chat', compact('chatlist','customer','active_tab'));
        // return view('frontend.pages.customer.chat-two', compact('chatlist','customer','active_tab'));
    }

    public function chatwithSeller($id) {
        $customer = Auth::guard('customer')->user();

        $chatlist = DB::table('users')
            ->join('chats', 'users.id', '=', 'chats.sellerId')
            ->join('vendors', 'vendors.user_id', '=', 'users.id')
            ->where('customerId', $customer->id)
            ->select('users.name','users.id','users.email','users.phone','users.image','vendors.shop_name','vendors.commission','vendors.followers_count','vendors.address','vendors.rating','vendors.logo')
            ->distinct()
            ->get();

        $sellerInfo = User::with('vendorDetails')->find($id);
        Session::put('sellerchatId', $sellerInfo->id);

        $cmessages   = Chat::where(['sellerId' => $sellerInfo->id, 'customerId' => $customer->id])->get();
        $sellerchats = Chat::where(['sellerId' => $sellerInfo->id, 'customerId' => $customer->id, 'status' => 0])->get();

        foreach ($sellerchats as $chat) {
            $readChat         = Chat::find($chat->id);
            $readChat->status = 1;
            $readChat->save();
        }

        $active_tab = "chat";

        return view('frontend.pages.customer.chat', compact('cmessages', 'chatlist', 'sellerInfo','customer','active_tab'));
        // return view('frontend.pages.customer.chat-two', compact('cmessages', 'chatlist', 'sellerInfo','customer','active_tab'));
    }

    public function customerchat(Request $request) {
        $customer = Auth::guard('customer')->user();

        $sellerId = $request->sellerId ?: Session::get('sellerchatId');

        if (!$sellerId) {
            return response()->json(['error' => 'Seller ID not found'], 400);
        }

        $sellerInfo = User::with('vendorDetails')->find($sellerId);
        $cmessages = Chat::where(['sellerId' => $sellerId, 'customerId' => $customer->id])->get();

        return view('frontend.pages.customer.chatcontent', compact('cmessages', 'sellerInfo', 'customer'));
    }

    public function customerSellerAsk(Request $request) {
        $request->validate([
            'sellerId' => 'required|exists:users,id',
            'message'  => 'nullable|string|max:1000',
            'file'     => 'nullable|file|max:10240', // 10MB max
        ]);

        // Get customer ID
        $customerId = Auth::guard('customer')->id();

        if (!$customerId) {
            return back()->with('error', 'Customer not authenticated');
        }

        // Prepare base message data
        $messageData = [
            'message'    => $request->message,
            'customerId' => $customerId,
            'sellerId'   => $request->sellerId,
            'isCustomer' => $customerId,
            'status'     => 0,
            'created_at' => now(),
            'updated_at' => now(),
        ];
        // Save message
        $inserted = DB::table('chats')->insert($messageData);

        return redirect()->route('customer.chat-with-seller', $request->sellerId)->with('success', 'Message sent successfully');
    }

    public function custosellersms(Request $request)
    {
        try {
            \Log::info('Customer message received', $request->all());

            // Validate inputs
            $request->validate([
                'sellerId' => 'required|exists:users,id',
                'message'  => 'nullable|string|max:1000',
                'file'     => 'nullable|file|max:10240', // 10MB max
            ]);

            // Get customer ID
            $customerId = Auth::guard('customer')->id();

            if (!$customerId) {
                if ($request->ajax()) {
                    return response()->json(['success' => false, 'message' => 'Customer not authenticated'], 401);
                }
                return back()->with('error', 'Customer not authenticated');
            }

            // Prepare base message data
            $messageData = [
                'message'    => $request->message,
                'customerId' => $customerId,
                'sellerId'   => $request->sellerId,
                'isCustomer' => $customerId,
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

                \Log::info('Customer file uploaded successfully', ['path' => $filePath, 'size' => $fileSize]);
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

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Customer message validation error', ['errors' => $e->errors()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validation failed',
                    'errors' => $e->errors()
                ], 422);
            }

            return redirect()->back()->withErrors($e->errors())->withInput();

        } catch (\Exception $e) {
            \Log::error('Customer message send error: ' . $e->getMessage(), ['trace' => $e->getTraceAsString()]);

            if ($request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error sending message: ' . $e->getMessage(),
                ], 500);
            }

            return redirect()->back()->with('error', 'Error sending message: ' . $e->getMessage());
        }
    }
}
