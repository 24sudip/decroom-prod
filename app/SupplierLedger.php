<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SupplierLedger extends Model {
    use HasFactory;

    protected $fillable = [
        'supplier_id', 'date', 'type', 'ref_id', 'debit', 'credit', 'note',
    ];

    public function supplier() {
        return $this->belongsTo(Supplier::class);
    }
}
