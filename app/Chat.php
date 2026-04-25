<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Chat extends Model
{
    protected $table = 'chats';
    
    protected $fillable = [
        'message',
        'customerId', 
        'sellerId',
        'isSeller',
        'status',
        'file',
        'file_size'
    ];
    
    protected $casts = [
        'isSeller' => 'boolean',
        'status' => 'boolean'
    ];
    
    // Relationship with customer
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customerId');
    }
    
    // Relationship with vendor
    public function vendor()
    {
        return $this->belongsTo(Vendor::class, 'sellerId');
    }
}