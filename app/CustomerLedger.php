<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CustomerLedger extends Model {
    use HasFactory;

    protected $table = 'customer_ledger';

    protected $fillable = [
        'customer_id',
        'transaction_date',
        'type',
        'amount',
        'balance',
        'reference',
        'note',
    ];

    // Relationship: Each ledger entry belongs to one customer
    public function customer() {
        return $this->belongsTo(Customer::class);
    }
}
