<?php

namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PageCategory extends Model {
    use HasFactory;

    protected $fillable = ['name'];

    public function pages() {
        return $this->hasMany(Page::class, 'category_id');
    }
}
