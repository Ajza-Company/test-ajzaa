<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductCarAttribute extends Model
{
    use HasFactory;

    protected $table = 'product_car_attributes';

    protected $guarded = [];

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function carBrand(): BelongsTo
    {
        return $this->belongsTo(CarBrand::class);
    }

    public function carModel(): BelongsTo
    {
        return $this->belongsTo(CarModel::class);
    }

    public function carType(): BelongsTo
    {
        return $this->belongsTo(CarType::class);
    }
}
