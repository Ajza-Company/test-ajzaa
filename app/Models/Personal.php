<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Personal extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'car_brand_id',
        'user_id',
        'car_type_id',
        'car_model_id',
        'vin',
        'car_year',
        'latitude',
        'longitude',
    ];
}
