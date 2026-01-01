<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'category_id',
        'subcategory_id',
        'name',
        'slug',
        'description',
        'price',
        'image_url',
        'is_active',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    // BELONGS TO MAIN CATEGORY (PASTRIES OR DRINKS)
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    // BELONGS TO SUBCATEGORY (OPTIONAL)
    public function subcategory(): BelongsTo
    {
        return $this->belongsTo(Subcategory::class);
    }
}
