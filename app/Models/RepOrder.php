<?php

namespace App\Models;

use App\Filters\Supplier\RepOrderFilter;
use App\Filters\Supplier\StatisticsFilter;
use App\Traits\DateRangeFilterScope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class RepOrder extends Model
{
    use HasFactory , DateRangeFilterScope;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['user_id', 'image', 'description', 'state_id', 'title', 'address_id', 'status','rep_id'];

    /**
     *
     * @return HasMany
     */
    public function repChats(): HasMany
    {
        return $this->hasMany(RepChat::class, 'rep_order_id');
    }

    /**
     *
     * @return HasOne
     */
    public function repChat(): HasOne
    {
        return $this->hasOne(RepChat::class, 'rep_order_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function address(): BelongsTo
    {
        return $this->belongsTo(Address::class, 'address_id');
    }

    /**
     *
     * @return HasMany
     */
    public function offers(): HasMany
    {
        return $this->hasMany(RepOffer::class, 'rep_order_id');
    }

    /**
     *
     * @return HasMany
     */
    public function tracking(): HasMany
    {
        return $this->hasMany(RepOrderTrack::class, 'rep_order_id');
    }

    /**
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new RepOrderFilter($request))->filter($builder);
    }
}
