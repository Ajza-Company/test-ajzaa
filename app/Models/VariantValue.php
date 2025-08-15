<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class VariantValue extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function variantCategory()
    {
        return $this->belongsTo(VariantCategory::class, 'variant_category_id');
    }
}
