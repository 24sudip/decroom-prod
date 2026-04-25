<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductChildcategory extends Model {
    use HasFactory;

    protected $fillable = [
        'subcategory_id', 'name', 'slug', 'image', 'description', 'status',
    ];

    public function subcategory() {
        return $this->belongsTo(ProductSubcategory::class, 'subcategory_id');
    }
    
    public function products() {
        return $this->hasMany(Product::class, 'childcategory_id');
    }
}
