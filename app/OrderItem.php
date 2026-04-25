<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OrderItem extends Model {
    use HasFactory;

    protected $fillable = [
        'order_id',
        'product_id',
        'service_id',    
        'variant_id',
        'product_name',
        'variant_name',
        'service_name',   
        'vendor_earning',   
        'admin_commission',   
        'quantity',
        'unit_price',
        'total_price',
        'type',           
        'vendor_id',
    ];

    public function order() {
        return $this->belongsTo(Order::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }
    
    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id');
    }

    public function variant() {
        return $this->belongsTo(ProductVariant::class, 'variant_id');
    }

    public function service() {
        return $this->belongsTo(Service::class);
    }

    public function vendor() {
        return $this->belongsTo(Vendor::class);
    }
}
