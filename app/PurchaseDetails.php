<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseDetails extends Model {
    use HasFactory;

    protected $fillable = [
        'pur_date',
        'pur_id',
        'product_id',
        'quantity',
        'rate',
        'tax_amt',
        'total',
        'manufacturer_date',
        'expire_date',
        'returned_quantity',
    ];

    /**
     * Relationships
     */

    public function purchase() {
        return $this->belongsTo(Purchase::class, 'pur_id');
    }

    public function product() {
        return $this->belongsTo(Product::class, 'product_id');
    }

    protected $casts = [
        'manufacturer_date' => 'datetime',
        'expire_date'       => 'datetime',
    ];

}
