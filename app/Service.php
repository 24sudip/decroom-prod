<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    use HasFactory;

    protected $fillable = [
        'delete_access',
        'title',
        'category_id',
        'organization',
        'client_id',
        'vendor_id',
        'delivery_duration',
        'time_line',
        'total_cost',
        'material_cost',
        'service_charge',
        'discount',
        'installment',
        'advance',
        'mid',
        'final',
        'catalog',
        'attachment',
        'service_video',
        'note',
        'status',
        'admin_approval',
        'admin_reject',
    ];

    // Relationships
    public function shares()
    {
        return $this->hasMany(ServiceShare::class);
    }

    public function customers()
    {
        return $this->belongsToMany(Customer::class, 'like_services');
    }
    
    public function category()
    {
        return $this->belongsTo(ServiceCategory::class, 'category_id');
    }

    public function client()
    {
        return $this->belongsTo(Customer::class, 'client_id');
    }

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_id');
    }
}
