<?php

namespace App\Models;

use App\Traits\HasLocalized;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarModel extends Model
{
    use HasFactory, HasLocalized;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_brand_id'
    ];

    public function carBrand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class);
    }

    /**
     *
     * @return HasOne
     */
    public function localized(): HasOne
    {
        return $this->localizedRelation(CarModelLocale::class);
    }

    /**
     *
     * @return HasMany
     */
    public function locales(): HasMany
    {
        return $this->hasMany(CarModelLocale::class);
    }
}
