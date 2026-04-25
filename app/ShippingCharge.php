<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ShippingCharge extends Model
{
    use HasFactory;
     
    protected $fillable = [
        'location',
        'charge',
        'type',
        'status',
    ];
}
