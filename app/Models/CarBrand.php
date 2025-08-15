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
        'image'
    ];

    /**
     *
     * @return HasOne
     */
    public function localized(): HasOne
    {
        return $this->localizedRelation(CarBrandLocale::class);
    }

    /**
     *
     * @return HasMany
     */
    public function locales(): HasMany
    {
        return $this->hasMany(CarBrandLocale::class);
    }
}
