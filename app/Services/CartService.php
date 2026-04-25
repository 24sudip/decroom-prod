<?php

namespace App\Services;

use Illuminate\Support\Facades\Session;
use Illuminate\Support\Collection;

class CartService
{
    protected $sessionKey = 'shopping_cart';

    /**
     * Get all cart items
     */
    public function all()
    {
        $cart = Session::get($this->sessionKey, []);
        return new Collection($cart);
    }

    /**
     * Add item to cart
     */
    public function add($item)
    {
        $cart = $this->all()->toArray(); // Convert to array for modification

        $itemId = $item['id'];

        if (isset($cart[$itemId])) {
            // Update quantity if item exists
            $cart[$itemId]['quantity'] += $item['quantity'];
        } else {
            // Add new item
            $cart[$itemId] = [
                'id' => $item['id'],
                'name' => $item['name'],
                'price' => (float) $item['price'],
                'quantity' => (int) $item['quantity'],
                'attributes' => $item['attributes'] ?? []
            ];
        }

        Session::put($this->sessionKey, $cart);
        return new Collection($cart);
    }

    /**
     * Update cart item
     */
    public function update($id, $data)
    {
        $cart = $this->all()->toArray(); // Convert to array for modification

        if (isset($cart[$id])) {
            if (isset($data['quantity']) && isset($data['quantity']['value'])) {
                $cart[$id]['quantity'] = (int) $data['quantity']['value'];
            }
            Session::put($this->sessionKey, $cart);
        }

        return new Collection($cart);
    }

    /**
     * Remove item from cart
     */
    public function remove($id)
    {
        $cart = $this->all()->toArray(); // Convert to array for modification

        if (isset($cart[$id])) {
            unset($cart[$id]);
            Session::put($this->sessionKey, $cart);
        }

        return new Collection($cart);
    }

    /**
     * Get specific cart item
     */
    public function get($id)
    {
        $cart = $this->all();
        return $cart[$id] ?? null;
    }

    /**
     * Clear entire cart
     */
    public function destroy()
    {
        Session::forget($this->sessionKey);
        return new Collection();
    }

    /**
     * Get cart items count
     */
    public function count()
    {
        return $this->all()->sum('quantity');
    }

    /**
     * Get cart total
     */
    public function total()
    {
        return $this->all()->sum(function ($item) {
            return ($item['price'] ?? 0) * ($item['quantity'] ?? 0);
        });
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty()
    {
        return $this->all()->isEmpty();
    }

    /**
     * Get cart subtotal (alias for total)
     */
    public function subtotal()
    {
        return $this->total();
    }

    /**
     * Check if item exists in cart
     */
    public function has($id)
    {
        return $this->all()->has($id);
    }
}
