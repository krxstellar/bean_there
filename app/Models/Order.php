<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Support\Facades\DB;

class Order extends Model
{
    use HasFactory;

    public const STATUSES = ['pending', 'processing', 'completed', 'cancelled'];
    public const DISCOUNT_STATUSES = ['none', 'pending', 'approved', 'rejected'];

    protected $fillable = [
        'order_number',
        'customer_order_number',
        'user_id',
        'status',
        'total',
        'instructions',
        'placed_at',
        'discount_proof',
        'discount_amount',
        'discount_type',
        'discount_status',
        'discount_approved_by',
        'discount_approved_at',
        'discount_approval_note',
    ];

    protected $casts = [
        'total' => 'decimal:2',
        'discount_amount' => 'decimal:2',
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
        $percent = $this->discount_amount ? (float) $this->discount_amount : 20.0;
        return round($itemsTotal * (1 - ($percent / 100)), 2);
    }

    public function approveDiscount($byUser, $note = null)
    {
        if (!$this->discount_proof || $this->discount_status === 'approved') {
            return false;
        }

        if (!$this->relationLoaded('items')) {
            $this->load('items');
        }

        // Ensure there is a discount percentage recorded. Default to 20% if none provided.
        if (empty($this->discount_amount)) {
            $this->discount_amount = 20.0;
        }

        $this->discount_status = 'approved';
        $this->discount_approved_by = $byUser->id ?? null;
        $this->discount_approved_at = now();
        $this->discount_approval_note = $note;
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
        $this->save();

        return true;
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function payments(): HasMany
    {
        return $this->hasMany(Payment::class);
    }

    public function latestPayment(): HasOne
    {
        return $this->hasOne(Payment::class)->latestOfMany();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function shippingAddress(): HasOne
    {
        return $this->hasOne(Address::class)->where('type', 'shipping');
    }

    protected static function booted()
    {
        static::creating(function ($order) {
            DB::transaction(function () use ($order) {
                if (empty($order->order_number)) {
                    $max = DB::table('orders')->lockForUpdate()->max('order_number');
                    $order->order_number = ($max ?? 0) + 1;
                }

                if (empty($order->customer_order_number)) {
                    $maxCustomer = DB::table('orders')
                        ->where('user_id', $order->user_id)
                        ->lockForUpdate()
                        ->max('customer_order_number');
                    $order->customer_order_number = ($maxCustomer ?? 0) + 1;
                }
            });
        });
    }
}
