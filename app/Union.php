<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Union extends Model {
    use HasFactory;

    protected $fillable = ['upazilla_id', 'name', 'bn_name', 'url'];

    public function upazila() {
        return $this->belongsTo(Upazila::class, 'upazilla_id');
    }
}
