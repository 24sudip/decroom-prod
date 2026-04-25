<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Purchase extends Model {
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'user_id',
        'purchase_date',
        'bill_no',
        'bill_date',
        'bill_amt',
        'transport_cost',
        'payment_method',
        'tax_rate',
        'total_bill',
        'paid_amt',
        'due_amt',
        'is_paid',
        'status',
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function user() {
        return $this->belongsTo(User::class);
    }

    public function purchaseDetails() {
        return $this->hasMany(PurchaseDetails::class, 'pur_id');
    }

    public function items() {
        return $this->hasMany(PurchaseDetails::class, 'pur_id');
    }

    protected $casts = [
        'manufacturer_date' => 'datetime',
        'expire_date'       => 'datetime',
    ];

}
