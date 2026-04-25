<?php
namespace App;

use Illuminate\Database\Eloquent\Model;

class ProductRating extends Model
{
    protected $fillable = ['product_id', 'customer_id', 'rating', 'review','status'];

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }

    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
