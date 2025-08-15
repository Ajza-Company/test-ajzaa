<?php

namespace App\Models;

use App\Traits\HasLocale;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StoreLocale extends Model
{
    use HasFactory, HasLocale;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'locale_id',
        'store_id'
    ];
}
