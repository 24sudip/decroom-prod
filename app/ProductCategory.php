<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'commission',
        'image',
        'description',
        'status',
        'is_home',
    ];

    public function subcategories() {
        return $this->hasMany(ProductSubcategory::class, 'category_id');
    }

    public function products() {
        return $this->hasMany(Product::class, 'category_id')->where('status', 1);
    }

}
