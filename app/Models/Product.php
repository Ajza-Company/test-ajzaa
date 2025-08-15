<?php

namespace App\Models;

use App\Filters\General\ProductFilter;
use App\Traits\HasLocalized;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory, HasLocalized, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'category_id',
        'image',
        'price',
        'part_number',
        'is_active'
    ];

    /**
     *
     * @return HasOne
     */
    public function localized(): HasOne
    {
        return $this->localizedRelation(ProductLocale::class);
    }

    /**
     *
     * @return HasOne
     */
    public function offer(): HasOne
    {
        return $this->hasOne(StoreProductOffer::class, 'product_id');
    }

    /**
     *
     * @return HasMany
     */
    public function variant(): HasMany
    {
        return $this->hasMany(VariantValue::class, 'product_id');
    }

    /**
     *
     * @return HasMany
     */
    public function locales(): HasMany
    {
        return $this->hasMany(ProductLocale::class, 'product_id');
    }

    /**
     *
     * @return HasOne
     */
    public function storeProduct(): HasOne
    {
        return $this->hasOne(StoreProduct::class, 'product_id');
    }

    /**
     *
     * @return HasMany
     */
    public function favorites(): HasMany
    {
        return $this->hasMany(ProductFavorite::class, 'product_id');
    }

    /**
     *
     * @return HasOne
     */
    public function favorite(): HasOne
    {
        return $this->hasOne(ProductFavorite::class, 'product_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     *
     * @return HasMany
     */
    public function carAttributes(): HasMany
    {
        return $this->hasMany(ProductCarAttribute::class, 'product_id');
    }

        /**
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new ProductFilter($request))->filter($builder);
    }

}
