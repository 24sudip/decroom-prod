<?php

namespace App\Http\Controllers\Frontend;

use App\Coupon;
use App\Customer;
use App\Order;
use App\OrderItem;
use App\Product;
use App\{Vendor, CustomerPayment};
use App\{ProductVariant, SellerWallet};
use App\{PaymentMethod, ServiceOrderItem, User};
use App\{ShippingCharge, ServiceOrder};
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Http\Controllers\Controller;
use App\Notifications\OrderComplete;
use App\Service;
use Illuminate\Support\Facades\Notification;

class CheckoutController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index(Request $request)
    {
        $cartItems = $this->cartService->all();
        $cartTotal = $this->cartService->total();
        $cartCount = $this->cartService->count();
        $customer = Auth::guard('customer')->user();

        $shippingCharges = ShippingCharge::where('status', 1)->get();
        $firstShipping = $shippingCharges->first();
        $shipping_method = $firstShipping ? $firstShipping->charge : 0;

        // Apply coupon logic
        $coupon_code = $request->coupon_code;
        $discount = 0;

        if ($coupon_code) {
            $coupon = Coupon::where('code', $coupon_code)
                ->where('status', 1)
                ->whereDate('start_date', '<=', now())
                ->whereDate('end_date', '>=', now())
                ->first();

            if ($coupon) {
                if ($coupon->min_purchase && $cartTotal < $coupon->min_purchase) {
                    return redirect()->route('checkout')->with('coupon_error', 'Minimum purchase must be ৳' . number_format($coupon->min_purchase, 2));
                }

                if ($coupon->type === 'fixed') {
                    $discount = $coupon->amount;
                } elseif ($coupon->type === 'percent') {
                    $discount = ($cartTotal * $coupon->amount) / 100;
                }

                // Save to session
                session([
                    'coupon_code' => $coupon_code,
                    'coupon_discount' => $discount,
                ]);
            } else {
                return redirect()->route('checkout')->with('coupon_error', 'Invalid or expired coupon.');
            }
        }

        $finalTotal = $cartTotal - session('coupon_discount', 0) + $shipping_method;

        $addresses = $customer ? $customer->shippingAddresses : [];

        return view('frontend.pages.checkout', compact(
            'cartItems',
            'cartTotal',
            'cartCount',
            'finalTotal',
            'customer',
            'shipping_method',
            'addresses',
            'shippingCharges'
        ));
    }
    public function proceedToPay(Request $request)
    {
        if (!auth('customer')->check()) {
            return redirect()->route('customer.login')->with('error', 'Please login to proceed to payment.');
        }

        // Check if cart is empty
        $cartItems = $this->cartService->all();
        $cartCount = $this->cartService->count();

        if ($cartCount === 0) {
            return redirect()->route('checkout')->with('error', 'Your cart is empty. Please add products to checkout.');
        }

        $methods = PaymentMethod::all();

        if ($methods->isEmpty()) {
            return redirect()->route('checkout')->with('error', 'No payment methods available.');
        }

        $shipping_method = floatval($request->shipping_method ?? session('shipping_method', 0));

        // Retrieve cart totals
        $cartTotal = $this->cartService->total();
        $discount = session('coupon_discount', 0);
        $finalTotal = ($cartTotal - $discount) + $shipping_method;

        // Store shipping method in session for placeOrder to use
        session(['shipping_method' => $shipping_method]);

        return view('frontend.pages.proceed-checkout', compact(
            'methods',
            'cartItems',
            'cartCount',
            'cartTotal',
            'finalTotal',
            'shipping_method'
        ));
    }

    public function OrderService(Request $request, $id) {
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Unauthorized User. Please Login!');
        }
        $service = Service::find($id);
        $service_orders = ServiceOrder::where('customer_id', $customer->id)->where('vendor_user_id', $service->vendor_id)
        ->get();
        foreach ($service_orders as $service_order) {
            if ($service_order->service_order_item && $service_order->service_order_item->service_id == $id) {
                if ($service_order->expired_at > now()) {
                    return back()->with('error', 'You have an Unexpired order with this Service. Please complete it before placing a new order.');
                }
            }
        }
        DB::beginTransaction();
        try {
            $service_order = ServiceOrder::create([
                'expired_at' => now()->copy()->addDays($service->expire_duration)->format('Y-m-d'),
                'customer_id' => $customer->id,
                'vendor_user_id' => $service->vendor_id,
                'name' => $customer->name,
                'phone' => $customer->phone,
                'address' => $customer->address,
                'email' => $customer->email,
                'due_amount' => $service->total_cost,
                'paid_amount' => 0,
                'installment_number' => 0,
                'installment_status' => 1
            ]);
            $sellerInfo = Vendor::where('user_id', $service->vendor_id)->first();
            $sellerCommision = 0;
            if ($sellerInfo->commission_type == 1) {
                $sellerCommision = ($sellerInfo->commission * $service->total_cost) / 100;
            } elseif ($sellerInfo->commission_type == 2) {
                $sellerCommision = $sellerInfo->commission;
            }
            ServiceOrderItem::create([
                'service_order_id' => $service_order->id,
                'service_id' => $service->id,
                'vendor_user_id' => $service->vendor_id,
                'total_cost' => $service->total_cost,
                'material_cost' => $service->material_cost,
                'service_charge' => $service->service_charge,
                'discount' => $service->discount,
                'vendor_earning' => $sellerCommision,
                'admin_commission' => $service->admin_commission
            ]);
            Notification::send($sellerInfo, new OrderComplete($customer->name, $service->title));
            Notification::send($customer, new OrderComplete($customer->name, $service->title));

            DB::commit();
            return redirect()->route('customer.profile', 'service-orders')->with('success', 'Service Ordered Successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();

            return back()->with('error', $e->getMessage());
        }
    }

    public function AddPayment(Request $request, $service_order_id) {
        $service_order = ServiceOrder::findOrFail($service_order_id);
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Unauthorized User. Please Login!');
        }
        $vendorId = $service_order->vendor_user_id;
        $seller   = Vendor::where('user_id', $vendorId)->first();

        if ($request->amount > $service_order->due_amount) {
            return redirect()->back()->with('error', 'Inappropriate Balance!');
        }
        DB::beginTransaction();
        try {
            $seller->sellercash += $request->amount;
            $seller->save();

            $sellerwallet            = new SellerWallet();
            $sellerwallet->title     = 'Installment Of Service Payment';
            $sellerwallet->amount    = $request->amount;
            $sellerwallet->current   = $seller->sellercash;
            $sellerwallet->note      = $customer->name . ' Has Given Payment For Service Order';
            $sellerwallet->vendor_id = $vendorId;
            $sellerwallet->credit = 0;
            $sellerwallet->status = 1;
            $sellerwallet->save();

            $service_order->due_amount -= $request->amount;
            $service_order->paid_amount += $request->amount;
            $service_order->installment_number += 1;
            $service_order->installment_status = 0;
            $service_order->save();

            $customer_payment = new CustomerPayment();
            $customer_payment->service_order_id = $service_order_id;
            $customer_payment->sellerwallet_id = $sellerwallet->id;
            $customer_payment->save();

            $user = User::where('email','admin@gmail.com')->first();
            Notification::send($user, new OrderComplete($customer->name, 'Installment'));
            Notification::send($seller, new OrderComplete($customer->name, 'Installment'));
            Notification::send($customer, new OrderComplete($customer->name, 'Installment'));

            DB::commit();
            return redirect()->back()->with('success', 'Installment Requested Successfully!');

        } catch (\Throwable $e) {
            DB::rollBack();

            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function placeOrder(Request $request)
    {
        // Validate input
        $validator = Validator::make($request->all(), [
            'payment_method' => 'required|exists:payment_methods,id',
            'shipping_method' => 'required|numeric|min:0',
        ]);

        if ($validator->fails()) {
            return redirect()->route('proceed-to-pay')->withErrors($validator)->withInput();
        }

        // Authenticated customer
        $customer = Auth::guard('customer')->user();
        if (!$customer) {
            return redirect()->route('customer.login')->with('error', 'Unauthorized User. Please Login!');
        }

        $cartItems = $this->cartService->all();
        $cartItemsArray = is_array($cartItems) ? $cartItems : $cartItems->toArray();

        if (count($cartItemsArray) === 0) {
            return redirect()->route('checkout')->with('error', 'Your cart is empty.');
        }

        // Use shipping method from request or session
        $shippingCost = floatval($request->shipping_method ?? session('shipping_method', 0));
        $couponCode = session('coupon_code', '');
        $discount = floatval(session('coupon_discount', 0));

        // Group cart items by vendor
        $itemsByVendor = [];
        foreach ($cartItemsArray as $item) {
            $productId = $item['attributes']['product_id'] ?? $item['id'];
            $product = Product::find($productId);
            if (!$product) continue;
            $vendorId = $product->vendor_id;

            if (!isset($itemsByVendor[$vendorId])) {
                $itemsByVendor[$vendorId] = [];
            }
            $itemsByVendor[$vendorId][] = $item;
        }

        DB::beginTransaction();
        try {
            foreach ($itemsByVendor as $vendorId => $items) {
                // Calculate subtotal and grand total for this vendor
                $subtotal = 0;
                foreach ($items as $item) {
                    $subtotal += $item['price'] * $item['quantity'];
                }

                // Distribute discount proportionally among vendors
                $vendorDiscount = $discount * ($subtotal / $this->cartService->total());
                $grandTotal = ($subtotal - $vendorDiscount) + $shippingCost;

                // Create vendor-specific order
                $order = Order::create([
                    'customer_id' => $customer->id,
                    'vendor_id' => $vendorId,
                    'name' => $customer->name,
                    'phone' => $customer->phone,
                    'district_id' => $customer->district_id,
                    'upazila_id' => $customer->upazila_id,
                    'address' => $customer->address,
                    'email' => $customer->email,
                    'order_note' => $request->order_note ?? "",
                    'shipping_cost' => $shippingCost,
                    'discount' => $vendorDiscount,
                    'subtotal' => $subtotal,
                    'total_amount' => $grandTotal,
                    'payment_method' => $request->payment_method,
                    'coupon_code' => $couponCode,
                    'status' => 1, // Pending
                ]);

                // Create order items
                foreach ($items as $item) {
                    $productId = $item['attributes']['product_id'] ?? $item['id'];
                    $variantId = $item['attributes']['variant_id'] ?? null;
                    $product = Product::find($productId);
                    if (!$product) continue;

                    $sellerInfo = Vendor::where('user_id', $product->vendor_id)->first();
                    Notification::send($sellerInfo, new OrderComplete($customer->name, $product->name));
                    Notification::send($customer, new OrderComplete($customer->name, $product->name));

                    $sellerCommision = 0;
                    if ($sellerInfo->commission_type == 1) {
                        $sellerCommision = ($sellerInfo->commission * $item['price']) / 100;
                    } elseif ($sellerInfo->commission_type == 2) {
                        $sellerCommision = $sellerInfo->commission;
                    }

                    OrderItem::create([
                        'order_id' => $order->id,
                        'product_id' => $productId,
                        'variant_id' => $variantId,
                        'vendor_id' => $product->vendor_id,
                        'product_name' => $item['name'],
                        'variant_name' => $item['attributes']['variant_name'] ?? null,
                        'vendor_earning' => $sellerCommision,
                        'quantity' => $item['quantity'],
                        'unit_price' => $item['price'],
                        'total_price' => $item['price'] * $item['quantity'],
                        'admin_commission' => $product->admin_commission,
                    ]);

                    // Update product stock
                    $product->decrement('stock', $item['quantity']);

                    // Update variant stock if exists
                    if ($variantId) {
                        $variant = ProductVariant::find($variantId);
                        if ($variant) {
                            $variant->decrement('stock', $item['quantity']);
                        }
                    }
                }
            }

            // Clear cart and coupon session
            $this->cartService->destroy();
            session()->forget(['coupon_code', 'coupon_discount', 'shipping_method']);

            DB::commit();

            return redirect()->route('customer.profile', ['tab' => 'orders'])
                ->with('success', 'Order placed successfully for all vendors!');
        } catch (\Throwable $e) {
            DB::rollBack();
            \Log::error('Order placement failed', [
                'customer_id' => $customer->id ?? null,
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->route('proceed-to-pay')->with('error', 'Something went wrong. Please try again later.');
        }
    }

    public function quickCheckout($id)
    {
        $product = Product::with('variants', 'images')->findOrFail($id);

        // Clear existing cart
        $this->cartService->destroy();

        $variant = $product->variants->first();

        // Add to cart
        $itemData = [
            'id' => $product->id . ($variant ? '_' . $variant->id : ''),
            'name' => $product->name,
            'price' => $variant ? $variant->price : ($product->special_price ?: $product->price),
            'quantity' => 1,
            'attributes' => [
                'product_id' => $product->id,
                'variant_id' => $variant->id ?? null,
                'variant_name' => $variant ? ($variant->color . ' - ' . $variant->size) : null,
                'image' => $product->images->first()->image_path ?? 'default.png',
            ]
        ];

        $this->cartService->add($itemData);

        return redirect()->route('checkout')->with('success', 'Product added to checkout.');
    }
}
