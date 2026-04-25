<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Services\CartService;
use Illuminate\Http\Request;
use App\Product;
use App\Wishlist;
use App\ProductVariant;
use Illuminate\Support\Facades\Log;


class CartController extends Controller
{
    protected $cart;

    public function __construct(CartService $cart)
    {
        $this->cart = $cart;
    }

    /**
     * Show cart page
     */
    public function cart()
    {
        $cartItems = $this->cart->all();
        $cartCount = $this->cart->count();
        $subTotal = $this->cart->total();
        $total = $subTotal;

        return view('frontend.pages.cart', compact('cartItems', 'subTotal', 'total', 'cartCount'));
    }

    public function moveToWishlist(Request $request)
    {
        $ids = $request->ids ?? [$request->id];

        foreach ($ids as $cartItemId) {
            $cartItem = $this->cart->get($cartItemId);

            if (!$cartItem) continue;

            $productId = $cartItem['attributes']['product_id'] ?? null;
            $variantId = $cartItem['attributes']['variant_id'] ?? null;

            Wishlist::updateOrCreate(
                [
                    'customer_id' => auth('customer')->id(),
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                ],
                []
            );

            $this->cart->remove($cartItemId);
        }

        return response()->json([
            'success' => true,
            'message' => 'Moved to wishlist!',
            'cart_count' => $this->cart->count(),
            'subtotal' => $this->cart->total(),
        ]);
    }

    /**
     * Add product to cart
     */
    public function addToCart(Request $request)
    {
        try {
            \Log::info('Add to cart request:', $request->all());

            // Validate request with proper numeric handling
            $validated = $request->validate([
                'id' => 'required|string',
                'name' => 'required|string|max:255',
                'price' => 'required|numeric|min:0',
                'qty' => 'required|integer|min:1',
            ]);

            $cartItemId = $request->id;
            $quantity = (int) $request->qty;

            // Ensure price is properly cast to float
            $price = (float) $request->price;

            \Log::info('Processed cart data:', [
                'id' => $cartItemId,
                'name' => $request->name,
                'price' => $price,
                'price_type' => gettype($price),
                'qty' => $quantity
            ]);

            // Extract product ID from the combined ID
            $idParts = explode('_', $cartItemId);
            $productId = $idParts[0];

            // Verify product exists
            $product = Product::find($productId);
            if (!$product) {
                return response()->json([
                    'success' => false,
                    'message' => 'Product not found!',
                ], 404);
            }

            // Handle variant if exists
            $variant = null;
            $variantId = $idParts[1] ?? null;
            if ($variantId) {
                $variant = ProductVariant::find($variantId);
            }

            // Prepare item data
            $itemData = [
                'id' => $cartItemId,
                'name' => $request->name,
                'price' => $price, // Use the properly cast float
                'quantity' => $quantity,
                'attributes' => [
                    'product_id' => $productId,
                    'variant_id' => $variantId,
                    'variant_name' => $variant ? ($variant->color . ' - ' . $variant->size) : null,
                    'image' => $request->image ?? ($product->images->first()->image_path ?? 'default.png'),
                ]
            ];

            \Log::info('Adding item to cart:', $itemData);

            // Add to cart
            $this->cart->add($itemData);

            // Get updated cart data
            $cartCount = $this->cart->count();
            $cartItems = $this->cart->all();

            \Log::info('Cart after add - Count: ' . $cartCount);

            return response()->json([
                'success' => true,
                'message' => 'Product added to cart successfully!',
                'cart_count' => $cartCount,
                'cart_items' => $cartItems,
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation error in add to cart: ' . json_encode($e->errors()));

            return response()->json([
                'success' => false,
                'message' => 'Validation failed: ' . implode(', ', array_flatten($e->errors())),
                'errors' => $e->errors()
            ], 422);

        } catch (\Exception $e) {
            \Log::error('Add to cart error: ' . $e->getMessage());
            \Log::error('Stack trace: ' . $e->getTraceAsString());

            return response()->json([
                'success' => false,
                'message' => 'Unable to add product to cart. Please try again.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update cart item quantity
     */
    public function updateCart(Request $request)
    {
        try {
            $request->validate([
                'id' => 'required',
                'quantity' => 'required|integer|min:1',
            ]);

            $this->cart->update($request->id, [
                'quantity' => [
                    'relative' => false,
                    'value' => $request->quantity,
                ],
            ]);

            $cartItems = $this->cart->all();
            $cartCount = $this->cart->count();
            $subTotal = $this->cart->total();
            $total = $subTotal;

            $updatedItem = $this->cart->get($request->id);

            return response()->json([
                'success' => true,
                'message' => 'Cart updated successfully!',
                'item_total' => ($updatedItem['price'] ?? 0) * ($updatedItem['quantity'] ?? 0),
                'item_quantity' => $updatedItem['quantity'] ?? 0,
                'subtotal' => $subTotal,
                'total' => $total,
                'cart_count' => $cartCount,
            ]);

        } catch (\Exception $e) {
            Log::error('Update cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error updating cart: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Remove single cart item
     */
    public function removeCartItem($id)
    {
        try {
            $item = $this->cart->get($id);
            if (!$item) {
                return response()->json([
                    'success' => false,
                    'message' => 'Item not found in cart!',
                ], 404);
            }

            $this->cart->remove($id);

            $cartCount = $this->cart->count();
            $subTotal = $this->cart->total();
            $total = $subTotal;

            return response()->json([
                'success' => true,
                'message' => 'Item removed successfully!',
                'cart_count' => $cartCount,
                'subtotal' => $subTotal,
                'total' => $total,
            ]);

        } catch (\Exception $e) {
            Log::error('Remove cart item error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error removing item: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clearCart()
    {
        try {
            $this->cart->destroy();

            return response()->json([
                'success' => true,
                'message' => 'Cart cleared successfully!',
                'cart_count' => 0,
                'subtotal' => 0,
                'total' => 0,
            ]);

        } catch (\Exception $e) {
            Log::error('Clear cart error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error clearing cart: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function getCartCount()
    {
        try {
            $count = $this->cart->count();
            return response()->json(['count' => $count]);
        } catch (\Exception $e) {
            Log::error('Get cart count error: ' . $e->getMessage());
            return response()->json(['count' => 0]);
        }
    }

    public function applyCoupon(Request $request, CartService $cart)
    {
        try {
            // Use input() to properly get JSON or form data
            $code = trim($request->input('coupon_code'));

            if (!$code) {
                return response()->json([
                    'success' => false,
                    'message' => 'Please enter a coupon code.'
                ]);
            }

            $coupon = \App\Coupon::active()->where('code', $code)->first();

            if (!$coupon) {
                return response()->json([
                    'success' => false,
                    'message' => 'Invalid or expired coupon.'
                ]);
            }

            $cartTotal = $cart->total();

            // Optional: check min purchase
            if ($coupon->min_purchase && $cartTotal < $coupon->min_purchase) {
                return response()->json([
                    'success' => false,
                    'message' => "Minimum purchase of ৳ {$coupon->min_purchase} required."
                ]);
            }

            // Calculate discount
            $discount = $coupon->type === 'percentage'
                ? ($cartTotal * $coupon->amount) / 100
                : $coupon->amount;

            $discount = min($discount, $cartTotal);

            // Save in session
            session([
                'coupon_code' => $code,
                'coupon_discount' => $discount
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Coupon applied successfully!',
                'discount' => $discount,
                'total' => $cartTotal - $discount,
                'subtotal' => $cartTotal,
                'cart_count' => $cart->count()
            ]);
        } catch (\Throwable $e) {
            \Log::error('Apply coupon error: '.$e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Something went wrong while applying coupon.',
                'error' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

   public function removeCoupon(Request $request)
    {
        try {
            session()->forget(['coupon_code', 'coupon_discount']);

            $subtotal = $this->cart->total();
            $total = $subtotal;

            if ($request->ajax()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Coupon removed successfully!',
                    'subtotal' => $subtotal,
                    'total' => $total,
                    'cart_count' => $this->cart->count(),
                ]);
            }
            return redirect()->back()->with('success', 'Coupon removed successfully!');

        } catch (\Exception $e) {
            \Log::error('Remove coupon error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Unable to remove coupon. Please try again.',
            ]);
        }
    }
}
