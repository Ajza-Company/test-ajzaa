<?php

namespace App\Models;

use App\Traits\HasLocalized;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantCategory extends Model
{
    use HasFactory, HasLocalized;

    protected $guarded = [];

    public function variantValues()
    {
        return $this->hasMany(VariantValue::class, 'variant_category_id');
    }
    
    /**
     * Get the category associated with the variant category.
     *
     * @return BelongsTo
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    /**
     * Get the localized information for the variant category.
     *
     * @return HasOne
     */
    public function localized(): HasOne
    {
        return $this->localizedRelation(VariantCategoryLocale::class);
    }
}
