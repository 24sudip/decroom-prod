<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceComment extends Model
{
    use HasFactory;
    protected $guarded = [];
    
    public function customer() {
        return $this->belongsTo(Customer::class, 'customer_id', 'id');
    }
    
    public function service() {
        return $this->belongsTo(Service::class, 'service_id', 'id');
    }
}

