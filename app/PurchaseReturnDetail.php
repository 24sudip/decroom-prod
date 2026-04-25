<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturnDetail extends Model {
    use HasFactory;

    protected $fillable = [
        'return_id',
        'product_id',
        'quantity',
        'rate',
        'tax_amt',
        'total',
        'reason',
    ];

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function purchaseReturn() {
        return $this->belongsTo(PurchaseReturn::class, 'return_id');
    }
}
