<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Spatie\Permission\Models\Role;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Staff;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'password' => 'hashed',
    ];

    protected $appends = [
        'role_name',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function staffs(): HasOne
    {
        return $this->hasOne(Staff::class);
    }

    public function getRoleAttribute(): ?Role
    {
        return $this->roles->first();
    }

    public function getRoleNameAttribute(): ?string
    {
        return $this->roles->first()?->name;
    }
}
