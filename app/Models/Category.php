<?php

namespace App\Models;

use App\Filters\Frontend\CategoryFilter;
use App\Traits\HasLocalized;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
    use HasFactory, HasLocalized;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'parent_id',
        'image',
        'is_active',
        'sort_order'
    ];

    /**
     *
     * @return HasOne
     */
    public function localized(): HasOne
    {
        return $this->localizedRelation(CategoryLocale::class);
    }

    /**
     *
     * @return HasMany
     */
    public function variants(): HasMany
    {
        return $this->hasMany(VariantCategory::class, 'category_id');
    }

    /**
     *
     * @return HasManyThrough
     */
    public function stores(): HasManyThrough
    {
        return $this->hasManyThrough(Store::class, StoreCategory::class,
            'category_id', 'id', 'id', 'store_id');
    }

    public function translations(): HasMany
    {
        return $this->hasMany(CategoryLocale::class);
    }

    public function parent()
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    /**
     * Scope for ordering categories by sort_order
     *
     * @param Builder $query
     * @return Builder
     */
    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('sort_order', 'asc')->orderBy('created_at', 'asc');
    }

    /**
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new CategoryFilter($request))->filter($builder);
    }
}
