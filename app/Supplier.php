<?php

namespace App;

use Illuminate\Foundation\Auth\User as Authenticatable;

class Supplier extends Authenticatable {
    protected $fillable = [
        'name',
        'phone',
        'email',
        'address',
        'tread_name',
        'tread_no',
        'image',
        'opening_balance',
        'main_balance',
        'due_balance',
        'is_active',
    ];

    protected $casts = [
        'opening_balance' => 'float',
        'main_balance'    => 'float',
        'due_balance'     => 'float',
        'is_active'       => 'boolean',
    ];

    public function purchases() {
        return $this->hasMany(Purchase::class, 'supplier_id');
    }
}
