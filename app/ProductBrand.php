<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductBrand extends Model {
    use HasFactory;

    protected $fillable = [
        'name',
        'slug',
        'image',
        'description',
        'is_deleted',
    ];

    public function products() {
        return $this->hasMany(Product::class, 'brand_id');
    }
    
}
