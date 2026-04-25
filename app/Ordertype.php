<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ordertype extends Model {
    use HasFactory;

    protected $fillable = ['name', 'slug'];
}
