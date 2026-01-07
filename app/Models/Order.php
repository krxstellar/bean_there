<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'processing', 'completed', 'cancelled'];
    public const DISCOUNT_STATUSES = ['none', 'pending', 'approved', 'rejected'];

    protected $fillable = [
        'user_id',
        'status',
        'total',
        'currency',
        'instructions',
        'placed_at',
        'discount_proof',
        'discount_amount',
        'discount_type',
        'discount_status',
        'discount_approved_by',
        'discount_approved_at',
        'discount_approval_note',
        'paid_total',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'paid_total' => 'decimal:2',
        'placed_at' => 'datetime',
        'discount_approved_at' => 'datetime',
    ];

    public function approvedBy()
    {
        return $this->belongsTo(User::class, 'discount_approved_by');
    }

    public function getTotalAfterDiscountAttribute()
    {
        $itemsTotal = $this->items->sum(fn($i) => $i->unit_price * $i->quantity);
        if (!$this->discount_amount) {
            return round($itemsTotal, 2);
        }
        if ($this->discount_type === 'percent') {
            return round($itemsTotal * (1 - ($this->discount_amount / 100)), 2);
        }
        return round(max(0, $itemsTotal - $this->discount_amount), 2);
    }

    public function approveDiscount($byUser, $note = null)
    {
        if (!$this->discount_proof || $this->discount_status === 'approved') {
            return false;
        }

        if (!$this->relationLoaded('items')) {
            $this->load('items');
        }

        $this->discount_status = 'approved';
        $this->discount_approved_by = $byUser->id ?? null;
        $this->discount_approved_at = now();
        $this->discount_approval_note = $note;
        $this->paid_total = $this->total_after_discount;
        $this->save();

        return true;
    }

    public function rejectDiscount($byUser, $note = null)
    {
        if ($this->discount_status === 'rejected') {
            return false;
        }

        $this->discount_status = 'rejected';
        $this->discount_approved_by = $byUser->id ?? null;
        $this->discount_approved_at = now();
        $this->discount_approval_note = $note;
        $this->paid_total = null;
        $this->save();

        return true;
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('type', 'shipping');
    }
}
