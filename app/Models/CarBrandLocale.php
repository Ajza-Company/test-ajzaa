<?php

namespace App\Models;

use App\Traits\HasLocale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CarBrandLocale extends Model
{
    use HasFactory, HasLocale;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_brand_id',
        'name',
        'locale_id'
    ];
}
