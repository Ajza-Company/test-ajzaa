<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PromoCode extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'code', 'description', 'type', 'value',
        'max_uses', 'used_count', 'min_order_value',
        'max_discount', 'is_active', 'starts_at', 'expires_at'
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount' => 'decimal:2'
    ];

    public function isValid(): bool
    {
        return $this->is_active &&
            (!$this->max_uses || $this->used_count < $this->max_uses) &&
            (!$this->starts_at || $this->starts_at->isPast()) &&
            (!$this->expires_at || $this->expires_at->isFuture());
    }

    public function calculateDiscount($orderTotal)
    {
        if (!$this->isValid() ||
            ($this->min_order_value && $orderTotal < $this->min_order_value)) {
            return 0;
        }

        $discount = $this->type === 'percentage'
            ? ($orderTotal * $this->value / 100)
            : $this->value;

        if ($this->max_discount) {
            $discount = min($discount, $this->max_discount);
        }

        return $discount;
    }

    public function markAsUsed()
    {
        $this->increment('used_count');
    }
}
