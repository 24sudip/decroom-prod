<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Socialmedia extends Model
{
    protected $table = 'socialmedia'; 

    protected $fillable = [
        'name',
        'icon',
        'link',
        'status',
    ];

    // Automatically convert timestamps to Carbon instances
    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'status'     => 'boolean',
    ];

    // Scope for published items
    public function scopePublished($query)
    {
        return $query->where('status', 1);
    }

    // Scope for unpublished items
    public function scopeUnpublished($query)
    {
        return $query->where('status', 0);
    }
}
