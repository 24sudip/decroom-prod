<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class About extends Model {
    protected $fillable = [
        'name',
        'phone',
        'hot_line',
        'whats_app',
        'address',
        'description_top',
        'description',
        'image',
    ];
}
