<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Permission extends Model
{
    use HasFactory, SoftDeletes;
    protected $guarded = ['id'];

    public function role()
    {
        return $this->belongsToMany(Role::class)->withPivot('role_id');
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }
}
