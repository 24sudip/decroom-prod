<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model {
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'amount',
        'min_purchase',
        'start_date',
        'end_date',
        'status',
    ];

    protected $casts = [
        'start_date'   => 'date',
        'end_date'     => 'date',
        'amount'       => 'decimal:2',
        'min_purchase' => 'decimal:2',
        'status'       => 'boolean',
    ];

    // Scope for active coupons
    public function scopeActive($query) {
        return $query->where('status', 1)
            ->whereDate('start_date', '<=', now())
            ->whereDate('end_date', '>=', now());
    }
}
