<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class PrescriptionImage extends Model {
    protected $fillable = ['prescription_id', 'file_path'];

    public function prescription() {
        return $this->belongsTo(Prescription::class);
    }
}
