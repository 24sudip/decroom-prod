<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchaseReturn extends Model {
    use HasFactory;

    protected $fillable = [
        'purchase_id',
        'supplier_id',
        'return_date',
        'note',
        'total_return_amt',
        'created_by',
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }

    public function purchase() {
        return $this->belongsTo(Purchase::class);
    }

    public function details() {
        return $this->hasMany(PurchaseReturnDetail::class, 'return_id');
    }

}
