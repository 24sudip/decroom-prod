<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductSubcategory extends Model {
    use HasFactory;

    protected $fillable = [
        'category_id', 'name', 'slug', 'image', 'description', 'status',
    ];

    public function category() {
        return $this->belongsTo(ProductCategory::class, 'category_id');
    }
    
    public function childcategories() {
        return $this->hasMany(ProductChildcategory::class, 'subcategory_id');
    }
    
    public function products() {
        return $this->hasMany(Product::class, 'subcategory_id');
    }

}
