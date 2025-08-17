<?php

namespace App\Models;

use App\Traits\HasLocalized;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class CarBrand extends Model
{
    use HasFactory, HasLocalized;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'external_id',
        'is_active',
        'logo'
    ];

    /**
     * Get the logo URL with full path
     */
    public function getLogoUrlAttribute()
    {
        if ($this->logo) {
            return asset('storage/' . $this->logo);
        }
        return null;
    }

    /**
     *
     * @return HasOne
     */
    public function localized(): HasOne
    {
        return $this->localizedRelation(CarBrandLocale::class);
    }

    /**
     * Get all locales for this car brand
     */
    public function locales(): HasMany
    {
        return $this->hasMany(CarBrandLocale::class);
    }

    /**
     * Get English name
     */
    public function getEnglishNameAttribute()
    {
        $englishLocale = $this->locales()->whereHas('locale', function ($query) {
            $query->where('locale', 'en');
        })->first();
        
        return $englishLocale ? $englishLocale->name : null;
    }

    /**
     * Get Arabic name
     */
    public function getArabicNameAttribute()
    {
        $arabicLocale = $this->locales()->whereHas('locale', function ($query) {
            $query->where('locale', 'ar');
        })->first();
        
        return $arabicLocale ? $arabicLocale->name : null;
    }

    /**
     *
     * @return HasMany
     */
    public function carModels(): HasMany
    {
        return $this->hasMany(CarModel::class);
    }
}
