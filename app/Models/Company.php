<?php

namespace App\Models;

use App\Filters\Admin\CompanyFilter;
use App\Traits\HasLocalized;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, HasLocalized, SoftDeletes;

    protected $guarded = [];

    /**
     *
     * @return HasOne
     */
    public function localized(): HasOne
    {
        return $this->localizedRelation(CompanyLocale::class);
    }

    /**
     * Get all locales for this company
     *
     * @return HasMany
     */
    public function locales(): HasMany
    {
        return $this->hasMany(CompanyLocale::class);
    }

    /**
     *
     * @return HasMany
     */
    public function stores(): HasMany
    {
        return $this->hasMany(Store::class, 'company_id');
    }

    /**
     *
     * @return HasManyThrough
     */
    public function orders(): HasManyThrough
    {
        return $this->hasManyThrough(Order::class, Store::class, 'company_id', 'id', 'id', 'store_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {/**/
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *
     * @return HasManyThrough
     */
    public function usersPivot(): HasManyThrough
    {
        return $this->hasManyThrough(StoreUser::class, Store::class, 'company_id', 'store_id');
    }

    /**
     *
     * @return HasMany
     */
    public function users(): HasMany
    {
        return $this->hasMany(User::class, 'company_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function country(): BelongsTo
    {
        return $this->belongsTo(Country::class, 'country_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function getCarBrandIdsAttribute()
    {
        return json_decode($this->attributes['car_brand_id'] ?? '[]', true);
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
        return (new CompanyFilter($request))->filter($builder);
    }
}
