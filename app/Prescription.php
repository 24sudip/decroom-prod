<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model {

    protected $fillable = ['customer_id', 'name', 'phone', 'description', 'file_path'];

    public function customer() {
        return $this->belongsTo(Customer::class);
    }

    public function images() {
        return $this->hasMany(PrescriptionImage::class);
    }
}
