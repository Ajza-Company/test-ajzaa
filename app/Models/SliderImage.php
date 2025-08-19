<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class SliderImage extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'image',
        'order',
        'is_active',
        'locale_id'
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'is_active' => 'boolean',
        'order' => 'integer'
    ];

    /**
     * Get the image URL
     */
    public function getImageUrlAttribute(): ?string
    {
        if ($this->image) {
            // Try to get URL from storage
            $url = Storage::url($this->image);
            
            // If storage URL doesn't work, construct direct URL
            if (str_contains($url, '/storage/')) {
                return 'https://dev.ajza.net/storage/' . str_replace('/storage/', '', $this->image);
            }
            
            return $url;
        }
        return null;
    }

    /**
     * Get the image path
     */
    public function getImagePathAttribute(): ?string
    {
        return $this->image;
    }

    /**
     * Scope for active sliders
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for ordered sliders
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('order');
    }

    /**
     * Get the default locale ID
     */
    public static function getDefaultLocaleId(): int
    {
        return Locale::where('is_default', true)->first()->id ?? 1;
    }

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($slider) {
            if (!$slider->locale_id) {
                $slider->locale_id = self::getDefaultLocaleId();
            }
        });
    }
}
