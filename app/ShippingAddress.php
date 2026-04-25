<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingAddress extends Model {
    use HasFactory;

    protected $fillable = [
        'customer_id',
        'name',
        'phone',
        'email',
        'address',
        'division_id',
        'district_id',
        'upazila_id',
        'union_id',
        'postal_code',
        'note',
        'status',
    ];

    public function customer() {
        return $this->belongsTo(\App\Customer::class);
    }

    public function district() {
        return $this->belongsTo(District::class);
    }

    public function upazila() {
        return $this->belongsTo(Upazila::class);
    }

}
