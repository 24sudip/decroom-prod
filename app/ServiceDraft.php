<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ServiceDraft extends Model
{
    use HasFactory;
    protected $guarded = [];

    public function vendor()
    {
        return $this->belongsTo(User::class, 'vendor_user_id', 'id');
    }
}

