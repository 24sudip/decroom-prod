<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Vendor extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'product_warning_limit',
        'user_id',
        'name',
        'email',
        'father_name',
        'mother_name',
        'nid_front',
        'nid_back',
        'shop_name',
        'type',
        'commission_type',
        'commission',
        'sellercash',
        'address',
        'logo',
        'status',
        'followers_count',
        'rating',
        'banner_image',
    ];

    protected $casts = [
        'status' => 'boolean',
        'commission' => 'float',
        'sellercash' => 'float',
        'followers_count' => 'integer',
        'rating' => 'float',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Direct relationship with products through user
    public function products()
    {
        return $this->hasManyThrough(
            Product::class,
            User::class,
            'id',
            'vendor_id',
            'user_id',
            'id'
        );
    }

    // Get active products count
    public function getActiveProductsCountAttribute()
    {
        return $this->products()->where('status', true)->count();
    }

    public function services()
    {
        return $this->hasMany(Service::class, 'vendor_id');
    }

    /**
     * Followers relationship (customers who follow this vendor)
     */
    public function followers()
    {
        return $this->belongsToMany(Customer::class, 'vendor_followers', 'vendor_id', 'customer_id')
        ->withTimestamps();
    }

    /**
     * Check if a customer follows this vendor
     */
    public function isFollowedBy($customerId = null)
    {
        if (!$customerId && auth()->guard('customer')->check()) {
            $customerId = auth()->guard('customer')->id();
        }

        if (!$customerId) {
            return false;
        }

        return $this->followers()->where('customer_id', $customerId)->exists();
    }

    /**
     * Get followers count (cached version)
     */
    public function getFollowersCountAttribute()
    {
        return $this->attributes['followers_count'] ?? 0;
    }

    public function isActive()
    {
        return $this->status === true;
    }
}
