<?php

namespace App\Models;

use App\Filters\Frontend\StoreFilter;
use App\Traits\HasLocalized;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;

class Store extends Model
{
    use HasFactory, HasLocalized, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'user_id',
        'longitude',
        'latitude',
        'image',
        'parent_id',
        'address',
        'area_id',
        'parent_id',
        'company_id',
        'is_active',
        'can_add_products',
        'address_url',
        'phone_number',
        'sort_order'
    ];

    /**
     *
     * @return BelongsTo
     */
    public function area(): BelongsTo
    {
        return $this->belongsTo(Area::class);
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
        // Use frontend filter by default (works for all users)
        return (new StoreFilter($request))->filter($builder);
    }

    /**
     *
     * @return HasOne
     */
    public function localized(): HasOne
    {
        return $this->localizedRelation(StoreLocale::class);
    }

    /**
     *
     * @return HasManyThrough
     */
    public function categories(): HasManyThrough
    {
        return $this->hasManyThrough(Category::class, StoreCategory::class, 'store_id', 'id', 'id', 'category_id');
    }

     /**
     *
     * @return HasOne
     */
    public function category(): HasOne
    {
        return $this->hasOne(StoreCategory::class, 'store_id');
    }

    /**
     *
     * @return HasManyThrough
     */
    public function products(): HasManyThrough
    {
        return $this->hasManyThrough(Product::class, StoreProduct::class, 'store_id', 'id', 'id', 'product_id');
    }

    /**
     *
     * @return HasMany
     */
    public function storeProducts(): HasMany
    {
        return $this->hasMany(StoreProduct::class, 'store_id');
    }

    /**
     *
     * @return HasMany
     */
    public function offers(): HasMany
    {
        return $this->hasMany(StoreProductOffer::class, 'store_id');
    }

    /**
     *
     * @return HasMany
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'store_id');
    }

    /**
     *
     * @return HasMany
     */
    public function storeUsers(): HasMany
    {
        return $this->hasMany(StoreUser::class, 'store_id');
    }

    /**
     *
     * @return HasManyThrough
     */
    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class, StoreUser::class, 'store_id', 'id', 'id', 'user_id');
    }

    /**
     *
     * @return HasMany
     */
    public function hours(): HasMany
    {
        return $this->hasMany(StoreHour::class, 'store_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class, 'company_id');
    }

    /**
     * Get the query to fetch stores with all required relations.
     */
    public static function getLocalizedStores()
    {
        return static::query()
            ->whereHas('company.localized')
            ->with([
                'area.localized',
                'area.state.localized',
                'company.localized'
            ]);
    }

    /**
     * Scope for ordering stores by sort_order
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
    }
}
