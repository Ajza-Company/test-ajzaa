<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class ProductFavorite extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_product_id',
        'user_id'
    ];

    /**
     *
     * @return BelongsTo
     */
    public function storeProduct(): BelongsTo
    {
        return $this->belongsTo(StoreProduct::class, 'store_product_id');
    }

    /**
     *
     * @return HasOneThrough
     */
    public function product(): HasOneThrough
    {
        return $this->hasOneThrough(Product::class, StoreProduct::class, 'product_id', 'id');
    }
}
