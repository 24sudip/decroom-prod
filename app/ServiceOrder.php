<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceOrder extends Model
{
    use HasFactory;
    protected $guarded = [];
    /**
     * Get the service_order_item associated with the ServiceOrder
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOne
     */
    public function service_order_item()
    {
        return $this->hasOne(ServiceOrderItem::class, 'service_order_id', 'id');
    }
}

