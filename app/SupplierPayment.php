<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierPayment extends Model {
    use HasFactory;

    protected $fillable = [
        'supplier_id',
        'payment_date',
        'amount',
        'payment_method',
        'note',
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
}
