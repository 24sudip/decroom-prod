<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class Customer extends Authenticatable {
    use Notifiable;

    protected $table = 'customers';

    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'address',
        'district_id',
        'upazila_id',
        'verifyToken',
        'device_token',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'verifyToken',
        'device_token',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function ledgers() {
        return $this->hasMany(CustomerLedger::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function upazila() {
        return $this->belongsTo(Upazila::class);
    }

    public function shippingAddresses() {
        return $this->hasMany(ShippingAddress::class);
    }

    public function orders() {
        return $this->hasMany(Order::class);
    }

    // In your Customer model
    public function followedVendors()
    {
        return $this->belongsToMany(Vendor::class, 'vendor_followers', 'customer_id', 'vendor_id')
        ->withTimestamps();
    }

    public function followsVendor($vendorId)
    {
        return $this->followedVendors()->where('vendor_id', $vendorId)->exists();
    }

    // Relationship with chats
    public function chats()
    {
        return $this->hasMany(Chat::class, 'customerId');
    }

    // Get last message for chat preview
    public function getLastMessageAttribute()
    {
        $lastChat = $this->chats()->latest()->first();
        return $lastChat ? $lastChat->message : null;
    }

    // Get last message time for chat preview
    public function getLastMessageTimeAttribute()
    {
        $lastChat = $this->chats()->latest()->first();
        return $lastChat ? $lastChat->created_at : null;
    }
}
