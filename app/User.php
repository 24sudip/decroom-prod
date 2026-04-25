<?php
namespace App;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable {
    use HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'welcome_description',
        'google_id',
        'name',
        'email',
        'phone',
        'image',
        'password',
        'is_active',
        'role_id',
        'verifyToken',
        'passresetToken'
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
    ];

    public function services()
    {
        return $this->hasMany(Service::class, 'vendor_id');
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function hasPermission($permission_slug) {
        return $this->role->permissions()->where('permission_slug', $permission_slug)->first() ? true : false;
    }

    public function vendorDetails()
    {
        return $this->hasOne(Vendor::class, 'user_id');
    }

    public function products()
    {
        return $this->hasMany(Product::class, 'vendor_id');
    }

    public function activeProducts()
    {
        return $this->products()->where('status', true);
    }

    // Removed follow-related methods since they're now in Customer model
}
