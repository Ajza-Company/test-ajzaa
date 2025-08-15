<?php

namespace App\Traits;

use App\Models\Locale;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

trait HasLocale
{
    /**
     * Define the relationship with Locale model
     *
     * @return BelongsTo
     */
    public function locale(): BelongsTo
    {
        return $this->belongsTo(Locale::class);
    }
}
