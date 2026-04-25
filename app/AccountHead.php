<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AccountHead extends Model {
    use HasFactory;

    protected $fillable = ['name', 'type', 'status'];
}
