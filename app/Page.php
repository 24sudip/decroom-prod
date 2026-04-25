<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Page extends Model {
    use HasFactory;

    protected $fillable = ['title', 'slug', 'content', 'category_id', 'status'];

    public function category() {
        return $this->belongsTo(PageCategory::class, 'category_id');
    }
}
