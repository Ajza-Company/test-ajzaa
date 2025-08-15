<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Relations\HasOne;

trait HasLocalized
{
    /**
     * Define a localized relationship with dynamic model class
     *
     * @param string $relatedModel
     * @return HasOne
     */
    public function localizedRelation(string $relatedModel): HasOne
    {
        return $this->hasOne($relatedModel)->whereHas('Locale', function ($query) {
            $query->where('locale', app()->getLocale());
        });
    }
}
