<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'vendor_id',
        'name',
        'description',
        'highlight',
        'category_id',
        'brand_id',
        'price',
        'special_price',
        'stock',
        'sku',
        'free_items',
        'promotion_image',
        'video_path',
        'youtube_url',
        'weight',
        'length',
        'width',
        'height',
        'dangerous_goods',
        'availability',
        'qc_status',
        'reject_status',
        'admin_commission',
        'status',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'special_price' => 'decimal:2',
        'admin_commission' => 'decimal:2',
        'weight' => 'decimal:3',
        'length' => 'decimal:2',
        'width' => 'decimal:2',
        'height' => 'decimal:2',
    ];

    /** --------------------------
     *  Relationships
     *  -------------------------- */
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function category()
    {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }

    public function brand()
    {
        return $this->belongsTo(ProductBrand::class, 'brand_id');
    }

    public function variants()
    {
        return $this->hasMany(ProductVariant::class, 'product_id');
    }

    public function images()
    {
        return $this->hasMany(ProductImage::class, 'product_id')->orderBy('is_primary', 'desc');
    }

    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'product_id');
    }

    // Add the reviews relationship
    public function reviews()
    {
        return $this->hasMany(Review::class, 'product_id');
    }

    public function questions()
    {
        return $this->hasMany(ProductQuestion::class)->where('status', 1);
    }

    public function ratings()
    {
        return $this->hasMany(ProductRating::class);
    }

    public function averageRating()
    {
        return $this->ratings()->avg('rating') ?? 0;
    }

    public function ratingCount()
    {
        return $this->ratings()->count();
    }

    /** --------------------------
     *  Accessors
     *  -------------------------- */
    public function getTotalStockAttribute()
    {
        if ($this->variants()->exists()) {
            return $this->variants()->sum('stock');
        }
        return $this->stock;
    }

    /** --------------------------
     *  Helper Methods
     *  -------------------------- */
    public function primaryImage()
    {
        return $this->images()->where('is_primary', true)->first();
    }

    /** --------------------------
     *  Status Helpers
     *  -------------------------- */
    public function getStatusTextAttribute()
    {
        return match($this->status) {
            0 => 'Inactive',
            1 => 'Active',
            2 => 'Pending QC',
            3 => 'Rejected',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute()
    {
        return match($this->status) {
            1 => 'green',
            0, 3 => 'red',
            2 => 'orange',
            default => 'gray'
        };
    }
}
