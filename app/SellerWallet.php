<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellerWallet extends Model
{
    use HasFactory;

    protected $table = 'sellerwallets';

    protected $fillable = [
        'title',
        'vendor_id',
        'approved_by',
        'amount',
        'receipt',
        'current',
        'note',
        'credit',
        'status',
    ];

    // Relationships
    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }

    public function approver()
    {
        return $this->belongsTo(User::class, 'approved_by');
    }
}
