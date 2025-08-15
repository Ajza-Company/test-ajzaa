<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreProductOffer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_product_id',
        'type',
        'discount',
        'expires_at',
        'store_id'
    ];

    /**
     *
     * @return BelongsTo
     */
    public function storeProduct(): BelongsTo
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id');
    }
}
