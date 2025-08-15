<?php

namespace App\Models;

use App\Traits\HasLocale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductLocale extends Model
{
    use HasFactory, HasLocale;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
       'locale_id',
        'product_id',
        'name',
        'description'
    ];

    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class, 'locale_id');
    }
}
