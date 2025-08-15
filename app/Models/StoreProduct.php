<?php

namespace App\Models;

use App\Filters\Frontend\ProductFilter;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class StoreProduct extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'product_id',
        'price',
        'store_id',
        'quantity',
    ];

    /**
     *
     * @return BelongsTo
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
    }

    /**
     *
     * @return HasOne
     */
    public function favorite(): HasOne
    {
        return $this->hasOne(ProductFavorite::class, 'store_product_id');
    }

    public function offer(): HasOne
    {
        return $this->hasOne(StoreProductOffer::class, 'store_product_id')->where('expires_at', '>=', now());
    }

    /**
     * Filter Scope
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new ProductFilter($request))->filter($builder);
    }

    protected $appends = ['price_after_discount'];

    public function getPriceAfterDiscountAttribute()
    {
        if (!$this->offer) {
            return $this->price;
        }

        if ($this->offer->type === 'fixed') {
            return (double)number_format(max(0, $this->price - $this->offer->discount), 2, '.', '');
        }

        // For percentage discount
        $discountAmount = ($this->price * $this->offer->discount) / 100;
        return (double)number_format(max(0, $this->price - $discountAmount), 2, '.', '');
    }
}
