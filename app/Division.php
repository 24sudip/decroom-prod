<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Division extends Model {
    use HasFactory;

    protected $fillable = ['name', 'bn_name', 'url'];

    public function districts() {
        return $this->hasMany(District::class);
    }
}
