<?php

namespace App\Models;

// USE ILLUMINATE\CONTRACTS\AUTH\MUSTVERIFYEMAIL;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use App\Models\Staff;

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

    public function cartItems(): HasMany
    {
        return $this->hasMany(CartItem::class);
    }

    public function staff(): HasOne
    {
        return $this->hasOne(Staff::class);
    }
}
