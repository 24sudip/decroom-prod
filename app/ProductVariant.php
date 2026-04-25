<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductVariant extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'name',
        'value',
        'additional_price',
        'stock',
    ];

    public function product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
