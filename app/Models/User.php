<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;

class User extends Authenticatable
{
    // @USE HASFACTORY<\DATABASE\FACTORIES\USERFACTORY>
    use HasFactory, Notifiable, HasRoles;

    // THE ATTRIBUTES THAT ARE MASS ASSIGNABLE
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    // THE ATTRIBUTES THAT SHOULD BE HIDDEN FOR SERIALIZATION
    protected $hidden = [
        'password',
        'remember_token',
    ];

    // GET THE ATTRIBUTES THAT SHOULD BE CAST
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
