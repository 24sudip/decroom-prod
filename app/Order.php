<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model {
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'vendor_id',
        'name',
        'phone',
        'district_id',
        'upazila_id',
        'address',
        'email',
        'order_note',
        'shipping_cost',
        'discount',
        'total_amount',
        'payment_method',
        'status',
        'type',               
    ];

    /** Relationships */
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
    public function user() {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function upazila() {
        return $this->belongsTo(Upazila::class);
    }

    public function items() {
        return $this->hasMany(OrderItem::class);
    }

    public function ordertype() {
        return $this->belongsTo(Ordertype::class, 'status');
    }
    
    public function paymentMethod() {
        return $this->belongsTo(PaymentMethod::class, 'payment_method');
    }

    public function shipping() {
        return $this->hasOne(ShippingAddress::class, 'customer_id', 'customer_id')->latestOfMany();
    }
}
