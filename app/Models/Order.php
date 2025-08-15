<?php

namespace App\Models;

use App\Enums\OrderStatusEnum;
use App\Filters\Supplier\OrdersFilter;
use App\Filters\Supplier\StatisticsFilter;
use App\Observers\OrderObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

#[ObservedBy([OrderObserver::class])]
class Order extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'store_id',
        'user_id',
        'address_id',
        'amount',
        'status',
        'cancel_reason',
        'delivery_method',
        'order_id'
    ];

    /**
     * Scope a query to only include pending orders
     *
     */
    public function scopeWherePending($query): Builder
    {
        return $query->where('status', OrderStatusEnum::PENDING);
    }

    /**
     * Scope a query to only include pending orders
     *
     */
    public function scopeWhereToday($query): Builder
    {
        return $query->whereDate('created_at', now()->format('Y-m-d'));
    }

    /**
     *
     * @return HasMany
     */
    public function orderProducts(): HasMany
    {
        return $this->hasMany(OrderProduct::class, 'order_id');
    }

    /**
     *
     * @return BelongsTo
     */
    public function store(): BelongsTo
    {
        return $this->belongsTo(Store::class, 'store_id');
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
     * @return HasOne
     */
    public function review(): HasOne
    {
        return $this->hasOne(StoreReview::class, 'order_id');
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
    public function transactions(): HasMany
    {
        return $this->hasMany(TransactionAttempt::class, 'order_id');
    }

    /**
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeStatisticsFilter(Builder $builder, $request): Builder
    {
        return (new StatisticsFilter($request))->filter($builder);
    }

    /**
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeOrdersFilter(Builder $builder, $request): Builder
    {
        return (new OrdersFilter($request))->filter($builder);
    }

    /**
     *
     * @param Builder $builder
     * @param $request
     * @return Builder
     */
    public function scopeFilter(Builder $builder, $request): Builder
    {
        return (new \App\Filters\Frontend\OrdersFilter($request))->filter($builder);
    }
}
