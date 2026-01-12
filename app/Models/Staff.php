<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Staff extends Model
{
    use HasFactory;

    /**
     * Explicit table name because migration creates `staffs`.
     *
     * @var string
     */
    protected $table = 'staffs';

    protected $fillable = [
        'user_id',
        'position',
        'staff_code',
        'hired_at',
    ];

    protected $dates = [
        'hired_at',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
